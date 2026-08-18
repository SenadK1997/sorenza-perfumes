<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sold_perfumes', function (Blueprint $table) {
            $table->decimal('customer_price', 10, 2)->nullable()->after('base_price');
        });

        // Backfill: before this fix, no discounts were in production, so historical
        // customer-paid = full retail = perfumes.original_price (which was itself
        // backfilled from perfumes.price for undiscounted items).
        DB::statement("
            UPDATE sold_perfumes
            JOIN perfumes ON perfumes.id = sold_perfumes.perfume_id
            SET sold_perfumes.customer_price = COALESCE(perfumes.original_price, perfumes.price)
            WHERE sold_perfumes.customer_price IS NULL
        ");
    }

    public function down(): void
    {
        Schema::table('sold_perfumes', function (Blueprint $table) {
            $table->dropColumn('customer_price');
        });
    }
};
