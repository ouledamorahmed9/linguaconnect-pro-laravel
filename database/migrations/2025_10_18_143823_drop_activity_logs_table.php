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
        // This is the instruction to safely drop the unused table if it exists.
        Schema::dropIfExists('activity_logs');
    }

    /**
     * Reverse the migrations.
     * (We can leave this empty as we don't intend to ever recreate this table)
     */
    public function down(): void
    {
        //
    }
};
