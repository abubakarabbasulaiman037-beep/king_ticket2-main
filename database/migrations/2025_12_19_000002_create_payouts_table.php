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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Organizer
            $table->unsignedBigInteger('payment_id'); // Which payment triggered this payout
            $table->unsignedBigInteger('event_id'); // Which event's ticket
            $table->decimal('amount', 15, 2); // 95% of payment amount
            $table->decimal('platform_fee', 15, 2); // 5% retained by platform
            $table->string('status')->default('pending'); // pending, completed, retrying, failed
            $table->string('reference')->unique(); // Unique reference for this payout
            $table->string('paystack_transfer_code')->nullable(); // Paystack transfer code
            $table->timestamp('transferred_at')->nullable(); // When transfer completed
            $table->text('error_message')->nullable(); // Error details if failed
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
