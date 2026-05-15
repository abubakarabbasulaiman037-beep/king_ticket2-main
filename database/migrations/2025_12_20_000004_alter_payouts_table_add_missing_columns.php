<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // All columns already added in 2025_12_19_000002_create_payouts_table.php
        // This migration is skipped
    }

    public function down(): void
    {
        // Skipping rollback for now
    }
};
