<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            
            // 1. Add start_date if missing
            if (!Schema::hasColumn('subscriptions', 'start_date')) {
                $table->timestamp('start_date')->nullable()->after('status');
            }

            // 2. Add end_date if missing
            if (!Schema::hasColumn('subscriptions', 'end_date')) {
                $table->timestamp('end_date')->nullable()->after('start_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('subscriptions', 'start_date')) {
                $table->dropColumn('start_date');
            }
            if (Schema::hasColumn('subscriptions', 'end_date')) {
                $table->dropColumn('end_date');
            }
        });
    }
};