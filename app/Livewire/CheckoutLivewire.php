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
        if (!empty($this->extra_info_field)) {
            // Silently redirect them to home. 
            // Don't show an error, or the bot will try to fix it.
            return redirect()->to('/');
        }

        // 4. BOT CHECK B: The Time Check
        // If they "typed" all their info in less than 3 seconds, it's a bot.
        $secondsOnPage = now()->timestamp - $this->loadTime;
        if ($secondsOnPage < 3) {
            // Log it if you want to see it during testing
            // logger("Bot detected: Only spent $secondsOnPage seconds on page.");
            return redirect()->to('/');
        }
        // --- RATE LIMITING START ---
        // We identify the user by their IP address
        $key = 'place-order:' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, $maxAttempts = 2)) {
            $seconds = RateLimiter::availableIn($key);
            $this->addError('email', 'Previše pokušaja. Molimo pokušajte ponovo za ' . ceil($seconds / 60) . ' minuta.');
            return;
        }
        $this->validate([
            'full_name' => 'required',
            'phone' => 'required',
            'address_line_1' => 'required',
            'city' => 'required',
            'zipcode' => 'required',
            'canton' => 'required',
            'email' => 'required|email', // Added validation for email
        ]);

        RateLimiter::hit($key, 600);
        
        $order = DB::transaction(function () {
            
            // 1. Find the Seller ID from the coupon if it exists
            $sellerId = null;
            if ($this->coupon_code) {
                $coupon = Coupon::where('code', $this->coupon_code)->first();
                if ($coupon) {
                    $sellerId = $coupon->user_id;
                }
            }

            // 2. Update/Create Customer & Link to Seller
            Customer::updateOrCreate(
                ['email' => $this->email],
                [
                    'full_name' => $this->full_name,
                    'phone' => $this->phone,
                    'address_line_1' => $this->address_line_1,
                    'city' => $this->city,
                    'zipcode' => $this->zipcode,
                    'canton' => $this->canton,
                    'user_id' => $sellerId, // Customer now linked to the seller who gave them the coupon
                ]
            );

            // 3. Create Order with Coupon Data & Link to Seller
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
                'user_id' => $sellerId, // The seller who owns the coupon gets the order in their dashboard
            ]);

            // 4. Attach Items
            $pivotData = [];
            foreach ($this->items as $item) {
                $pivotData[$item['id']] = [
                    'quantity' => $item['quantity'], 
                    'price' => $item['price']
                ];
            }
            $newOrder->perfumes()->attach($pivotData);

            // 5. Increment Coupon usage
            if ($this->coupon_code) {
                $coupon = \App\Models\Coupon::where('code', $this->coupon_code)->first();
                if ($coupon) {
                    $coupon->increment('used_count');
                }
            }

            return $newOrder;
        });

        // Clear everything
        session()->forget(['cart', 'coupon']);

        RateLimiter::clear($key);
        
        return redirect()->route('order.success', ['id' => $order->pretty_id]);
    }

    public function render()
    {
        return view('livewire.checkout-livewire', [
            'cantons' => Canton::cases()
        ]);
    }
}
