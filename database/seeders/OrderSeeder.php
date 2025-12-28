<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Perfume;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Enums\Canton;
use App\Enums\OrderStatus;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Get all perfumes to pick randomly
        $perfumes = Perfume::all();

        // Create 5 sample orders
        for ($i = 1; $i <= 5; $i++) {
            $order = Order::create([
                'full_name' => 'Customer ' . $i,
                'phone' => '06123456' . $i,
                'address_line_1' => 'Street ' . $i,
                'address_line_2' => 'House ' . $i,
                'city' => 'Sarajevo',
                'zipcode' => '71000',
                'email' => 'customer'.$i.'@example.com',
                'amount' => 0, // we'll calculate below
                'coupon' => null,
                'status' => OrderStatus::PENDING->value,
                'canton' => Canton::SARAJEVO->value, 
                'user_id' => null,
            ]);

            // Attach 1 to 3 random perfumes with random quantities and price
            $selectedPerfumes = $perfumes->random(rand(1, 3));

            $totalAmount = 0;

            foreach ($selectedPerfumes as $perfume) {
                $quantity = rand(1, 5);
                $price = $perfume->price;

                // Attach to pivot with quantity and price
                $order->perfumes()->attach($perfume->id, [
                    'quantity' => $quantity,
                    'price' => $price,
                ]);

                $totalAmount += $price * $quantity;
            }

            // Update total order amount
            $order->update(['amount' => $totalAmount]);
        }
    }
}
