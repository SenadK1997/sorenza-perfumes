<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('pretty_id')->unique()->index();
            
            // THE MONEY COLUMNS (Broken down for accuracy)
            $table->decimal('subtotal', 10, 2); // Price of perfumes BEFORE discount and shipping
            $table->decimal('discount_amount', 10, 2)->default(0); // Money saved by coupon
            $table->decimal('shipping_fee', 10, 2)->default(0); // Exactly 0.00 or 10.00
            $table->decimal('amount', 10, 2); // Final amount paid (Subtotal - Discount + Shipping)
            
            $table->string('coupon_code')->nullable();
            $table->string('full_name');
            $table->string('phone');
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city');
            $table->string('zipcode');
            $table->string('email')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('pending');
            $table->text('cancellation_reason')->nullable();
            $table->string('canton');
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
