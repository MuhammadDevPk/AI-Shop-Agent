<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Add a creator_id to track who initiated the request (since your User model expects it)
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();

            // These should be nullable because one party is invited later
            $table->foreignId('buyer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('seller_id')->nullable()->constrained('users')->nullOnDelete();

            // Missing item details from your RAG workflow
            $table->string('title');
            $table->text('description')->nullable();

            // Financials and timeframe
            $table->decimal('amount', 10, 2);
            $table->unsignedInteger('deadline')->nullable(); // Depending on if it's days or a timestamp

            // Expand your enum to match the exact statuses from your RAG instructions
            $table->enum('status', [
                'pending',
                'accepted',
                'admin_pending_approval',
                'approved',
                'shipped',
                'delivered',
                'hold',
                'completed',
                'dispute',
                'canceled'
            ])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
