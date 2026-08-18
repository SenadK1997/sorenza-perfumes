<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained('customer_message_threads')->cascadeOnDelete();
            $table->enum('direction', ['admin', 'customer']);
            // For admin messages: which of the admins actually wrote it (internal only).
            // Customers only ever see "Sorenza".
            $table->foreignId('author_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('body');
            $table->timestamp('read_at')->nullable();
            // Marks messages that were part of a broadcast (so admin can filter/purge them).
            $table->boolean('is_broadcast')->default(false);
            $table->timestamps();

            $table->index(['thread_id', 'created_at']);
            $table->index('direction');
            $table->index('is_broadcast');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_messages');
    }
};
