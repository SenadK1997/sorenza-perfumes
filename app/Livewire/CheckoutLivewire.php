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

class CheckoutLivewire extends Component
{
    #[Layout('layouts.app')] 

    public $step = 1;
    public $email, $full_name, $phone, $address_line_1, $address_line_2, $city, $zipcode, $canton;
    public $items = [];
    public $subtotal = 0, $shipping = 10, $total = 0;

    public function mount()
    {
        // 1. Get the raw cart from session (e.g., [id => quantity])
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->to('/shop');
        }

        // 2. Fetch the Perfume models from the database
        $perfumes = Perfume::whereIn('id', array_keys($cart))->get();

        // 3. Map them into the $items array with the quantities
        $this->items = $perfumes->map(function ($perfume) use ($cart) {
            return [
                'id' => $perfume->id,
                'name' => $perfume->name,
                'price' => $perfume->price,
                'main_image' => $perfume->main_image,
                'quantity' => $cart[$perfume->id],
            ];
        })->toArray();

        // 4. Calculate totals
        $this->subtotal = collect($this->items)->sum(fn($item) => $item['price'] * $item['quantity']);
        $this->shipping = ($this->subtotal >= 120) ? 0 : 10;
        $this->total = $this->subtotal + $this->shipping;
    }

    public function checkEmail()
    {
        $this->validate(['email' => 'required|email']);

        $customer = Customer::where('email', $this->email)->first();

        if ($customer) {
            $this->full_name = $customer->full_name;
            $this->phone = $customer->phone;
            $this->address_line_1 = $customer->address_line_1;
            $this->address_line_2 = $customer->address_line_2;
            $this->city = $customer->city;
            $this->zipcode = $customer->zipcode;
            $this->canton = $customer->canton;
        }
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
        
        // Capture the returned order from the transaction
        $order = DB::transaction(function () {
            // 1. Create/Update Customer
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

            // 2. Create Order
            $newOrder = Order::create([
                'pretty_id' => 'SOR-' . strtoupper(Str::random(8)), // Don't forget this!
                'amount' => $this->total,
                'full_name' => $this->full_name,
                'phone' => $this->phone,
                'address_line_1' => $this->address_line_1,
                'city' => $this->city,
                'zipcode' => $this->zipcode,
                'email' => $this->email,
                'canton' => $this->canton,
                'status' => 'pending',
            ]);

            // 3. Attach Perfumes
            $pivotData = [];
            foreach ($this->items as $item) {
                $pivotData[$item['id']] = [
                    'quantity' => $item['quantity'], 
                    'price' => $item['price']
                ];
            }
            $newOrder->perfumes()->attach($pivotData);

            // Return the order object so it can be used outside the closure
            return $newOrder;
        });

        session()->forget('cart');
        
        // Now $order is defined here!
        return redirect()->route('order.success', ['id' => $order->pretty_id]);
    }

    public function render()
    {
        return view('livewire.checkout-livewire', [
            'cantons' => Canton::cases()
        ]);
    }
}
