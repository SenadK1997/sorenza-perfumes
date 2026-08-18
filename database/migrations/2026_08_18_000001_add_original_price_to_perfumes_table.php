<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perfumes', function (Blueprint $table) {
            $table->decimal('original_price', 8, 2)->nullable()->after('base_price');
        });

        // Backfill: for existing rows with a discount, reconstruct the pre-discount price.
        // Historical bug: `price` was already the discounted value. Best-effort recovery:
        //   original_price = round(price / (1 - discount/100))
        // Rows with no discount keep original_price = price.
        DB::table('perfumes')->orderBy('id')->chunkById(200, function ($rows) {
            foreach ($rows as $row) {
                $discount = (float) ($row->discount_percentage ?? 0);
                $price    = (float) $row->price;

                if ($discount > 0 && $discount < 100 && $price > 0) {
                    $original = round($price / (1 - $discount / 100), 2);
                } else {
                    $original = $price;
                }

                DB::table('perfumes')->where('id', $row->id)->update([
                    'original_price' => $original,
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('perfumes', function (Blueprint $table) {
            $table->dropColumn('original_price');
        });
    }
};
