<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use App\Enums\Canton;
use App\Models\Perfume;
use App\Models\Coupon;
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
    public $extra_info_field; 
    public $loadTime;

    public function mount()
    {
        $this->loadTime = now()->timestamp;
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->to('/shop');
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
            $this->discount = session('coupon')['discount'];
            $this->coupon_code = session('coupon')['code'];
        }

        // 3. Calculate Shipping and Total
        $this->shipping = ($this->subtotal >= 120) ? 0 : 10;
        
        // Final total calculation
        $this->total = ($this->subtotal - $this->discount) + $this->shipping;
    }

    public function checkEmail()
    {
        $this->validate(['email' => 'required|email']);

        // $customer = Customer::where('email', $this->email)->first();

        // if ($customer) {
        //     $this->full_name = $customer->full_name;
        //     $this->phone = $customer->phone;
        //     $this->address_line_1 = $customer->address_line_1;
        //     $this->address_line_2 = $customer->address_line_2;
        //     $this->city = $customer->city;
        //     $this->zipcode = $customer->zipcode;
        //     $this->canton = $customer->canton;
        // }
        $this->step = 2;
    }

    public function placeOrder()
    {
        // 1. Honeypot check
        if (!empty($this->extra_info_field)) {
            return redirect()->to('/');
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
                'discount_amount' => $this->discount,
                'shipping_fee' => $this->shipping,
                'amount' => ($this->subtotal - $this->discount) + $this->shipping,
                'coupon_code' => $this->coupon_code,
                'full_name' => $this->full_name,
                'phone' => $this->phone,
                'address_line_1' => $this->address_line_1,
                'city' => $this->city,
                'zipcode' => $this->zipcode,
                'canton' => $this->canton,
                'email' => $this->email,
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
        $this->sendSlackNotification($order);
        return redirect()->route('order.success', ['id' => $order->pretty_id]);
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
