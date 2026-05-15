<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Add payment_id foreign key after user_id
            $table->foreignId('payment_id')
                ->nullable()
                ->after('user_id')
                ->constrained('payments')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Payment::class);
            $table->dropColumn('payment_id');
        });
    }
};
