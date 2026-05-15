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
        Schema::table('users', function (Blueprint $table) {
            // Add organizer bank account fields
            $table->string('account_number')->nullable()->after('email');
            $table->string('account_name')->nullable()->after('account_number');
            $table->string('bank_name')->nullable()->after('account_name');
            $table->string('bank_code')->nullable()->after('bank_name');
            $table->string('paystack_recipient_code')->nullable()->after('bank_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'account_number',
                'account_name',
                'bank_name',
                'bank_code',
                'paystack_recipient_code',
            ]);
        });
    }
};
