<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Perfume;
use Illuminate\Database\Seeder;
use App\Enums\Canton;
use App\Enums\OrderStatus;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $perfumes = Perfume::all();

        for ($i = 1; $i <= 5; $i++) {
            // 1. Pick random perfumes first to calculate the subtotal
            $selectedPerfumes = $perfumes->random(rand(1, 3));
            $subtotal = 0;
            $itemsData = [];

            foreach ($selectedPerfumes as $perfume) {
                $qty = rand(1, 2);
                $subtotal += ($perfume->price * $qty);
                $itemsData[$perfume->id] = [
                    'quantity' => $qty,
                    'price' => $perfume->price
                ];
            }

            // 2. Calculate shipping based on your 120 KM rule
            $shippingFee = ($subtotal >= 120) ? 0 : 10;
            $discount = 0; // For seeding, we'll keep it simple or add random if you like
            
            // 3. Create the order with all new required fields
            $order = Order::create([
                'full_name' => 'Customer ' . $i,
                'phone' => '06123456' . $i,
                'address_line_1' => 'Street ' . $i,
                'city' => 'Sarajevo',
                'zipcode' => '71000',
                'email' => 'customer'.$i.'@example.com',
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'shipping_fee' => $shippingFee,
                'amount' => ($subtotal - $discount) + $shippingFee, // Final amount
                'status' => OrderStatus::PENDING,
                'canton' => Canton::SARAJEVO,
                'user_id' => null,
            ]);

            // 4. Attach the perfumes to the pivot table
            $order->perfumes()->attach($itemsData);
        }
    }
}