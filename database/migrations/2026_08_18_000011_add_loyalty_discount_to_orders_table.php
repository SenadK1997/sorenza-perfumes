<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('loyalty_discount_amount', 10, 2)->default(0)->after('tier_discount_amount');
            $table->string('loyalty_tier', 32)->nullable()->after('loyalty_discount_amount');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['loyalty_discount_amount', 'loyalty_tier']);
        });
    }
};
