<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        DB::table('site_settings')->insert([
            ['key' => 'free_shipping_enabled', 'value' => '1',   'created_at' => now(), 'updated_at' => now()],
            ['key' => 'shipping_fee',          'value' => '10',  'created_at' => now(), 'updated_at' => now()],
            ['key' => 'free_shipping_threshold','value' => '120','created_at' => now(), 'updated_at' => now()],
            ['key' => 'refund_days',           'value' => '7',   'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
