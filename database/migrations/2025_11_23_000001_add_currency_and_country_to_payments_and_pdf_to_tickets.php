<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'currency')) {
                $table->string('currency', 8)->default('NGN')->after('amount');
            }
            if (!Schema::hasColumn('payments', 'country')) {
                $table->string('country', 80)->nullable()->after('currency');
            }
        });

        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'pdf_path')) {
                $table->string('pdf_path')->nullable()->after('qr_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'currency')) {
                $table->dropColumn('currency');
            }
            if (Schema::hasColumn('payments', 'country')) {
                $table->dropColumn('country');
            }
        });

        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'pdf_path')) {
                $table->dropColumn('pdf_path');
            }
        });
    }
};
