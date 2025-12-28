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
        Schema::create('order_perfume', function (Blueprint $table) {
            $table->id();

            // Foreign key to orders
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // Foreign key to perfumes
            $table->foreignId('perfume_id')->constrained()->onDelete('cascade');

            // Quantity ordered
            $table->unsignedInteger('quantity')->default(1);

            // Price of the perfume at the time of ordering
            $table->decimal('price', 8, 2);

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_perfume');
    }
};
