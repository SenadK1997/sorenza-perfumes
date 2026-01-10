<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Percentage Coupon (e.g., 10% off)
        Coupon::create([
            'user_id' => 1,
            'code' => 'SORENZA10',
            'type' => 'percent',
            'value' => 10.00,
            'min_total' => 50.00, // Only valid for orders over 50 KM
            'usage_limit' => null, // Unlimited usage
            'is_active' => true,
            'expires_at' => Carbon::now()->addMonths(6),
        ]);

        // 2. Fixed Amount Coupon (e.g., 20 KM off)
        Coupon::create([
            'user_id' => 1,
            'code' => 'WELCOME20',
            'type' => 'fixed',
            'value' => 20.00,
            'min_total' => 100.00, // Valid for orders over 100 KM
            'usage_limit' => 100, // First 100 customers only
            'is_active' => true,
            'expires_at' => Carbon::now()->addMonth(),
        ]);

        // 3. Seller Special (Expired for testing logic)
        Coupon::create([
            'user_id' => 1,
            'code' => 'EXPIRED5',
            'type' => 'fixed',
            'value' => 5.00,
            'is_active' => true,
            'expires_at' => Carbon::now()->subDays(1), // Already expired
        ]);

        // 4. Inactive Coupon
        Coupon::create([
            'user_id' => 1,
            'code' => 'OFFLINE',
            'type' => 'percent',
            'value' => 50.00,
            'is_active' => false, // Manually disabled
        ]);
    }
}