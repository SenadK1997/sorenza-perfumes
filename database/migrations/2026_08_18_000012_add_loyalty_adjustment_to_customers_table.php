<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Manual admin adjustment to this-year spend (drives loyalty tier).
            // Positive to bump the tier as a courtesy, negative to reduce it.
            // Applied on top of real orders + direct sales, not stored elsewhere.
            $table->decimal('loyalty_adjustment', 10, 2)->default(0)->after('blocked_at');
            $table->string('loyalty_adjustment_note')->nullable()->after('loyalty_adjustment');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['loyalty_adjustment', 'loyalty_adjustment_note']);
        });
    }
};
