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
        //
        // Uses a correlated subquery instead of UPDATE...JOIN so it runs on both
        // MySQL (production) and SQLite (test suite).
        DB::statement("
            UPDATE sold_perfumes
            SET customer_price = (
                SELECT COALESCE(perfumes.original_price, perfumes.price)
                FROM perfumes
                WHERE perfumes.id = sold_perfumes.perfume_id
            )
            WHERE customer_price IS NULL
        ");
    }

    public function down(): void
    {
        Schema::table('sold_perfumes', function (Blueprint $table) {
            $table->dropColumn('customer_price');
        });
    }
};
