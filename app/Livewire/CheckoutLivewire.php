<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use App\Enums\Canton;
use App\Mail\OrderPlaced;
use App\Models\Perfume;
use App\Models\Coupon;
use App\Models\AbandonedCheckout;
use App\Models\BlockedIp;
use App\Models\BlockedEmail;
use App\Models\Customer as CustomerModel;
use App\Services\ShippingCalculator;
use App\Services\CartTierDiscount;
use App\Services\CustomerLoyalty;
use App\Services\TelegramNotifier;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckoutLivewire extends Component
{
    #[Layout('layouts.app')] 

    public $step = 1;
    public $email, $full_name, $phone, $address_line_1, $address_line_2, $city, $zipcode, $canton;
    public $items = [];
    public $subtotal = 0, $shipping = 10, $total = 0, $discount = 0, $coupon_code = null;
    public $couponDiscount = 0, $tierDiscount = 0, $loyaltyDiscount = 0;
    public ?string $loyaltyTierName = null;
    public int $loyaltyDiscountPct = 0;
    public $extra_info_field; 
    public $loadTime;

    public function mount()
    {
        $this->loadTime = now()->timestamp;
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->to('/shop');
        }

        // Prefill from logged-in customer if any
        if ($customer = auth('customer')->user()) {
            $this->email          = (string) $customer->email;
            $this->full_name      = (string) $customer->full_name;
            $this->phone          = (string) $customer->phone;
            $this->address_line_1 = (string) $customer->address_line_1;
            $this->address_line_2 = (string) $customer->address_line_2;
            $this->city           = (string) $customer->city;
            $this->zipcode        = (string) $customer->zipcode;
            $this->canton         = (string) $customer->canton;
            if ($this->email) $this->step = 2;
        }

        $perfumes = Perfume::whereIn('id', array_keys($cart))->get();

        $this->items = $perfumes->map(function ($perfume) use ($cart) {
            return [
                'id' => $perfume->id,
                'name' => $perfume->name,
                'price' => $perfume->price,
                'main_image' => $perfume->main_image,
                'quantity' => $cart[$perfume->id],
            ];
        })->toArray();

        // 1. Calculate Subtotal
        $this->subtotal = collect($this->items)->sum(fn($item) => $item['price'] * $item['quantity']);

        // 2. Load Coupon from Session (Applied in CartPage)
        if (session()->has('coupon')) {
            $this->couponDiscount = session('coupon')['discount'];
            $this->coupon_code = session('coupon')['code'];
        }

        // 2b. Automatic tier discount (admin-configurable)
        $this->tierDiscount = CartTierDiscount::discount((float) $this->subtotal);

        // 2c. Loyalty discount (customer's account tier × subtotal)
        if ($customer = auth('customer')->user()) {
            $tierInfo = CustomerLoyalty::forCustomer($customer);
            $this->loyaltyDiscount   = CustomerLoyalty::discountAmountFor($customer, (float) $this->subtotal);
            $this->loyaltyTierName   = $tierInfo['name'];
            $this->loyaltyDiscountPct = (int) $tierInfo['discount'];
        }

        $this->discount = $this->couponDiscount + $this->tierDiscount + $this->loyaltyDiscount;

        // 3. Calculate Shipping and Total (admin-configurable)
        $this->shipping = ShippingCalculator::fee($this->subtotal);

        // Final total calculation
        $this->total = ($this->subtotal - $this->discount) + $this->shipping;
    }

    public function checkEmail()
    {
        $this->validate(['email' => 'required|email']);

        // Capture cart snapshot for abandonment tracking.
        // If the user comes back with the same email, we update the same row instead of duplicating.
        try {
            $snapshot = collect($this->items)->map(fn ($i) => [
                'id'         => (int)   $i['id'],
                'name'       => (string) $i['name'],
                'price'      => (float) $i['price'],
                'quantity'   => (int)   $i['quantity'],
                'main_image' => (string) ($i['main_image'] ?? ''),
            ])->values()->all();

            $existing = AbandonedCheckout::where('email', $this->email)
                ->whereNull('recovered_at')
                ->latest('id')
                ->first();

            if ($existing) {
                $existing->update([
                    'items'      => $snapshot,
                    'subtotal'   => $this->subtotal,
                    'item_count' => collect($snapshot)->sum('quantity'),
                    'ip'         => request()->ip(),
                    'user_agent' => (string) request()->userAgent(),
                ]);
            } else {
                AbandonedCheckout::create([
                    'email'      => $this->email,
                    'items'      => $snapshot,
                    'subtotal'   => $this->subtotal,
                    'item_count' => collect($snapshot)->sum('quantity'),
                    'ip'         => request()->ip(),
                    'user_agent' => (string) request()->userAgent(),
                ]);
            }
        } catch (\Throwable $e) {
            // Never block checkout because of tracking issues
            Log::warning('Abandoned checkout capture failed: ' . $e->getMessage());
        }

        $this->step = 2;
    }

    public function placeOrder()
    {
        // 0. Idempotency lock — swallows double-clicks that race past wire:loading.
        //    Keyed on IP + email + subtotal; 20s TTL is plenty for one checkout.
        $lockKey = 'place-order-lock:' . md5(request()->ip() . '|' . strtolower((string) $this->email) . '|' . $this->subtotal);
        $lock = \Illuminate\Support\Facades\Cache::lock($lockKey, 20);
        if (! $lock->get()) {
            // Another request is already creating this order; drop this one silently.
            return;
        }

        // 1. Honeypot check
        if (!empty($this->extra_info_field)) {
            return redirect()->to('/');
        }

        // 1b. Blocked IP / email / customer — reject
        $ip = request()->ip();
        if (BlockedIp::isBlocked($ip)) {
            $this->addError('email', 'Trenutno nije moguće poslati narudžbu. Molimo kontaktirajte podršku.');
            return;
        }

        if (BlockedEmail::isBlocked($this->email)) {
            $this->addError('email', 'Ova email adresa nije trenutno u mogućnosti da napravi narudžbu. Molimo kontaktirajte podršku.');
            return;
        }

        $existingCustomer = CustomerModel::whereRaw('LOWER(email) = ?', [strtolower((string) $this->email)])->first();
        if ($existingCustomer && $existingCustomer->is_blocked) {
            $this->addError('email', 'Ova email adresa nije trenutno u mogućnosti da napravi narudžbu. Molimo kontaktirajte podršku.');
            return;
        }

        // 2. Bot Time Check
        $secondsOnPage = now()->timestamp - $this->loadTime;
        if ($secondsOnPage < 3) {
            return redirect()->to('/');
        }

        // 3. Rate Limiting
        $key = 'place-order:' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, $maxAttempts = 2)) {
            $seconds = RateLimiter::availableIn($key);
            $this->addError('email', 'Previše pokušaja. Molimo pokušajte ponovo za ' . ceil($seconds / 60) . ' minuta.');
            return;
        }

        // 4. Validation
        $this->validate([
            'full_name' => 'required',
            'phone' => 'required',
            'address_line_1' => 'required',
            'city' => 'required',
            'zipcode' => 'required',
            'canton' => 'required',
            'email' => 'required|email',
        ]);

        RateLimiter::hit($key, 600);

        // 4b. Recompute totals from authoritative sources (cart + settings)
        //     so tampered public props can't change money values.
        $cart = session()->get('cart', []);
        $perfumes = Perfume::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $freshItems = [];
        $subtotal = 0.0;
        foreach ($cart as $pid => $qty) {
            $perfume = $perfumes->get($pid);
            if (!$perfume) continue;
            $qty = (int) $qty;
            $freshItems[] = [
                'id' => $perfume->id,
                'name' => $perfume->name,
                'price' => (float) $perfume->price,
                'main_image' => $perfume->main_image,
                'quantity' => $qty,
            ];
            $subtotal += (float) $perfume->price * $qty;
        }
        $this->items = $freshItems;
        $this->subtotal = $subtotal;

        // Coupon (validate against cart, respect perfume-binding)
        $couponDiscount = 0.0;
        if (session()->has('coupon') && !empty(session('coupon')['code'])) {
            $coupon = Coupon::where('code', session('coupon')['code'])->first();
            $itemsCollection = collect($freshItems)->map(fn ($i) => (object) $i);
            if ($coupon && $coupon->isValidForCart($itemsCollection)) {
                $couponDiscount = $coupon->calculateCartDiscount($itemsCollection);
                $this->coupon_code = $coupon->code;
            } else {
                session()->forget('coupon');
                $this->coupon_code = null;
            }
        }
        $this->couponDiscount = $couponDiscount;

        $this->tierDiscount = CartTierDiscount::discount((float) $this->subtotal);

        // Loyalty (server-side, from authoritative source)
        $loyaltyCustomer = auth('customer')->user();
        if (!$loyaltyCustomer && $this->email) {
            // Fallback: match by email even for guests, so account holders still get
            // their tier when checking out without logging in first.
            $loyaltyCustomer = \App\Models\Customer::whereRaw('LOWER(email) = ?', [strtolower((string) $this->email)])->first();
        }
        if ($loyaltyCustomer) {
            $tierInfo = CustomerLoyalty::forCustomer($loyaltyCustomer);
            $this->loyaltyDiscount    = CustomerLoyalty::discountAmountFor($loyaltyCustomer, (float) $this->subtotal);
            $this->loyaltyTierName    = $tierInfo['name'];
            $this->loyaltyDiscountPct = (int) $tierInfo['discount'];
        } else {
            $this->loyaltyDiscount    = 0;
            $this->loyaltyTierName    = null;
            $this->loyaltyDiscountPct = 0;
        }

        $this->discount     = $this->couponDiscount + $this->tierDiscount + $this->loyaltyDiscount;
        $this->shipping     = ShippingCalculator::fee($this->subtotal);
        $this->total        = max(0, ($this->subtotal - $this->discount) + $this->shipping);

        // 5. Pronalaženje prodavača preko kupona (prije transakcije)
        $sellerId = null;
        if ($this->coupon_code) {
            $coupon = Coupon::where('code', $this->coupon_code)->first();
            if ($coupon) {
                $sellerId = $coupon->user_id;
            }
        }

        // 6. Database Transaction
        $order = DB::transaction(function () use ($sellerId) {
            
            // A. Update/Create Customer & Link to Seller
            Customer::updateOrCreate(
                ['email' => $this->email],
                [
                    'full_name' => $this->full_name,
                    'phone' => $this->phone,
                    'address_line_1' => $this->address_line_1,
                    'city' => $this->city,
                    'zipcode' => $this->zipcode,
                    'canton' => $this->canton,
                    'user_id' => $sellerId, 
                ]
            );

            // B. Create Order sa automatskim statusom
            $newOrder = Order::create([
                'subtotal' => $this->subtotal,
                'discount_amount' => $this->couponDiscount,
                'tier_discount_amount' => $this->tierDiscount,
                'loyalty_discount_amount' => $this->loyaltyDiscount,
                'loyalty_tier' => $this->loyaltyTierName,
                'shipping_fee' => $this->shipping,
                'amount' => max(0, ($this->subtotal - $this->discount) + $this->shipping),
                'coupon_code' => $this->coupon_code,
                'full_name' => $this->full_name,
                'phone' => $this->phone,
                'address_line_1' => $this->address_line_1,
                'city' => $this->city,
                'zipcode' => $this->zipcode,
                'canton' => $this->canton,
                'email' => $this->email,
                'ip' => request()->ip(),
                'user_id' => $sellerId,
                // Ako postoji sellerId, narudžba je odmah 'taken'
                'status' => $sellerId ? 'taken' : 'pending',
            ]);

            // C. Attach Items
            $pivotData = [];
            foreach ($this->items as $item) {
                $pivotData[$item['id']] = [
                    'quantity' => $item['quantity'], 
                    'price' => $item['price']
                ];
            }
            $newOrder->perfumes()->attach($pivotData);

            // D. Increment Coupon usage
            if ($this->coupon_code) {
                $coupon = Coupon::where('code', $this->coupon_code)->first();
                if ($coupon) {
                    $coupon->increment('used_count');
                }
            }

            return $newOrder;
        });

        // 7. Cleanup
        session()->forget(['cart', 'coupon']);
        RateLimiter::clear($key);

        // Mark any pending abandonment rows for this email as recovered
        try {
            AbandonedCheckout::where('email', $this->email)
                ->whereNull('recovered_at')
                ->update([
                    'recovered_at' => now(),
                    'order_id'     => $order->id,
                ]);
        } catch (\Throwable $e) {
            Log::warning('Abandoned checkout recovery mark failed: ' . $e->getMessage());
        }

        // Slack notification disabled — using Telegram integration instead.
        // $this->sendSlackNotification($order);
        $this->sendOrderConfirmationEmail($order);
        $this->sendTelegramNotification($order);

        return redirect()->route('order.success', ['id' => $order->pretty_id]);
    }

    protected function sendTelegramNotification(Order $order): void
    {
        try {
            TelegramNotifier::orderPlaced($order);
        } catch (\Throwable $e) {
            Log::warning('Telegram notification failed for ' . $order->pretty_id . ': ' . $e->getMessage());
        }
    }

    protected function sendOrderConfirmationEmail(Order $order): void
    {
        if (empty($order->email)) return;

        try {
            Mail::to($order->email)->send(new OrderPlaced($order));
        } catch (\Throwable $e) {
            // Never block the order because of email issues; just log.
            Log::warning('Order confirmation email failed for ' . $order->pretty_id . ': ' . $e->getMessage());
        }
    }
    protected function sendSlackNotification($order)
    {
        // Uzimamo URL iz config-a (koji vuče iz .env-a)
        $webhookUrl = config('services.slack.webhook_url');

        // Ako URL uopšte nije postavljen, samo izađi bez greške
        if (!$webhookUrl) {
            return;
        }

        try {
            // Šaljemo zahtjev sa kratkim timeout-om od 3 sekunde
            // Ne želimo da kupac čeka predugo ako Slack ne odgovara
            Http::timeout(3)->post($webhookUrl, [
                'text' => "🛍️ *Nova narudžba na Sorenza Parfumes!* \n" .
                        "--------------------------------------------\n" .
                        "🆔 *Broj:* #{$order->id} \n" .
                        "👤 *Kupac:* {$order->full_name} \n" .
                        "💰 *Iznos:* {$order->amount} KM \n" .
                        "📍 *Grad:* {$order->city} \n" .
                        "--------------------------------------------"
            ]);

        } catch (\Exception $e) {
            // Ako slanje ne uspije, samo zapiši u log i nastavi dalje
            Log::error("Slack notification failed: " . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.checkout-livewire', [
            'cantons' => Canton::cases()
        ]);
    }
}
