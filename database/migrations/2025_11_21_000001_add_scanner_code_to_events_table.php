<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('scanner_code')->nullable()->unique()->after('id');
            $table->boolean('scanner_enabled')->default(true)->after('scanner_code');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('scanner_code');
            $table->dropColumn('scanner_enabled');
        });
    }
};
