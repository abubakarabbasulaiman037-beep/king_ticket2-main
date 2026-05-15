<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('bank_code')->nullable()->after('account_number');
            $table->string('bank_name')->nullable()->after('bank_code');
            $table->string('account_name')->nullable()->after('bank_name');
            $table->boolean('auto_payout')->default(false)->after('scan_token');
            $table->decimal('payout_balance', 12, 2)->default(0)->after('auto_payout');
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['bank_code', 'bank_name', 'account_name', 'auto_payout', 'payout_balance']);
        });
    }
};
