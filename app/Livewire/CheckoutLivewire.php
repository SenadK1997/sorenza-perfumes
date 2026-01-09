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

class CheckoutLivewire extends Component
{
    #[Layout('layouts.app')] 

    public $step = 1;
    public $email, $full_name, $phone, $address_line_1, $address_line_2, $city, $zipcode, $canton;
    public $items = [];
    public $subtotal = 0, $shipping = 10, $total = 0, $discount = 0, $coupon_code = null;

    public function mount()
    {
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
        $this->validate([
            'full_name' => 'required',
            'phone' => 'required',
            'address_line_1' => 'required',
            'city' => 'required',
            'zipcode' => 'required',
            'canton' => 'required',
        ]);
        
        $order = DB::transaction(function () {
            // Update Customer
            Customer::updateOrCreate(
                ['email' => $this->email],
                [
                    'full_name' => $this->full_name,
                    'phone' => $this->phone,
                    'address_line_1' => $this->address_line_1,
                    'city' => $this->city,
                    'zipcode' => $this->zipcode,
                    'canton' => $this->canton,
                ]
            );
            // dd($this->total);
            // Create Order with Coupon Data
            $newOrder = Order::create([
                'subtotal' => $this->subtotal, // Price of perfumes
                'discount_amount' => $this->discount, // KM value of discount
                'shipping_fee' => $this->shipping, // 0 or 10
                'amount' => ($this->subtotal - $this->discount) + $this->shipping, // Final Total
                'coupon_code' => $this->coupon_code,
                'full_name' => $this->full_name,
                'phone' => $this->phone,
                'address_line_1' => $this->address_line_1,
                'city' => $this->city,
                'zipcode' => $this->zipcode,
                'canton' => $this->canton,
                'email' => $this->email,
            ]);

            // Attach Items
            $pivotData = [];
            foreach ($this->items as $item) {
                $pivotData[$item['id']] = [
                    'quantity' => $item['quantity'], 
                    'price' => $item['price']
                ];
            }
            $newOrder->perfumes()->attach($pivotData);

            // Handle Coupon Usage Count
            if ($this->coupon_code) {
                $coupon = Coupon::where('code', $this->coupon_code)->first();
                if ($coupon) {
                    $coupon->increment('used_count');
                }
            }

            return $newOrder;
        });

        // Clear everything
        session()->forget(['cart', 'coupon']);
        
        return redirect()->route('order.success', ['id' => $order->pretty_id]);
    }

    public function render()
    {
        return view('livewire.checkout-livewire', [
            'cantons' => Canton::cases()
        ]);
    }
}
