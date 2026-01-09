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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            
            // Type of discount: 'fixed' (e.g. 10 KM) or 'percent' (e.g. 10%)
            $table->enum('type', ['fixed', 'percent'])->default('fixed');
            
            $table->decimal('value', 10, 2); // The numerical value of the discount
            
            $table->decimal('min_total', 10, 2)->nullable(); // Optional: e.g. "Only for orders over 50 KM"
            
            $table->integer('usage_limit')->nullable(); 
            $table->integer('used_count')->default(0);
            
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
