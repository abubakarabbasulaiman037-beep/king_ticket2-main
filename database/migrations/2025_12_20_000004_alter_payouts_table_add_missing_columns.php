<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payouts', function (Blueprint $table) {
            // Check if columns already exist before adding them
            if (!Schema::hasColumn('payouts', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('id'); // Organizer
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index('user_id');
            }
            
            if (!Schema::hasColumn('payouts', 'payment_id')) {
                $table->unsignedBigInteger('payment_id')->after('user_id'); // Which payment triggered this payout
                $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('payouts', 'platform_fee')) {
                $table->decimal('platform_fee', 15, 2)->after('amount')->default(0); // 5% retained by platform
            }
            
            if (!Schema::hasColumn('payouts', 'reference')) {
                $table->string('reference')->unique()->after('status'); // Unique reference for this payout
            }
            
            if (!Schema::hasColumn('payouts', 'paystack_transfer_code')) {
                $table->string('paystack_transfer_code')->nullable()->after('reference'); // Paystack transfer code
            }
            
            if (!Schema::hasColumn('payouts', 'transferred_at')) {
                $table->timestamp('transferred_at')->nullable()->after('paystack_transfer_code'); // When transfer completed
            }
            
            if (!Schema::hasColumn('payouts', 'error_message')) {
                $table->text('error_message')->nullable()->after('transferred_at'); // Error details if failed
            }
            
            // Add indexes if not exists
            try {
                $table->index('status');
            } catch (\Exception $e) {
                // Index might already exist
            }
            try {
                $table->index('created_at');
            } catch (\Exception $e) {
                // Index might already exist
            }
        });
    }

    public function down(): void
    {
        Schema::table('payouts', function (Blueprint $table) {
            if (Schema::hasColumn('payouts', 'user_id')) {
                $table->dropForeignIdFor(\App\Models\User::class);
                $table->dropIndex(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('payouts', 'payment_id')) {
                $table->dropForeignIdFor(\App\Models\Payment::class);
                $table->dropColumn('payment_id');
            }
            if (Schema::hasColumn('payouts', 'platform_fee')) {
                $table->dropColumn('platform_fee');
            }
            if (Schema::hasColumn('payouts', 'reference')) {
                $table->dropIndex(['reference']);
                $table->dropColumn('reference');
            }
            if (Schema::hasColumn('payouts', 'paystack_transfer_code')) {
                $table->dropColumn('paystack_transfer_code');
            }
            if (Schema::hasColumn('payouts', 'transferred_at')) {
                $table->dropColumn('transferred_at');
            }
            if (Schema::hasColumn('payouts', 'error_message')) {
                $table->dropColumn('error_message');
            }
        });
    }
};
