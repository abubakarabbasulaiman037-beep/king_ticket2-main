<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->integer('available_tickets')->default(0)->after('price');
            $table->string('currency', 10)->default('NGN')->after('available_tickets');
            $table->string('account_number')->nullable()->after('currency');
            $table->string('scan_token', 64)->nullable()->unique()->after('account_number');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['available_tickets', 'currency', 'account_number', 'scan_token']);
        });
    }
};
