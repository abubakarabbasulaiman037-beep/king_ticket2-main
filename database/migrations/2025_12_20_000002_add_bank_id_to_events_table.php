<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Add bank_id foreign key after bank_name
            $table->foreignId('bank_id')
                ->nullable()
                ->after('bank_name')
                ->constrained('banks')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Bank::class);
            $table->dropColumn('bank_id');
        });
    }
};
