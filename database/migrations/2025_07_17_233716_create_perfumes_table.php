<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('perfumes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('gender', 10);
            $table->string('inspired_by')->nullable();
            $table->decimal('base_price', 8, 2);
            $table->decimal('price', 8, 2);
            $table->unsignedTinyInteger('discount_percentage')->nullable();
            $table->string('main_image')->nullable();
            $table->string('secondary_image')->nullable();
            $table->text('description')->nullable();
            $table->string('tag')->nullable();
            $table->json('accords')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfumes');
    }
};
