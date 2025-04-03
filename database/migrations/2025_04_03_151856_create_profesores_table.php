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
        // No need to create a separate table since we're using single table inheritance
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No table to drop
    }
};

