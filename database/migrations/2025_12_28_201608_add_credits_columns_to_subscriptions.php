<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            
            // 1. Add 'total_lessons' if it's missing
            if (!Schema::hasColumn('subscriptions', 'total_lessons')) {
                $table->integer('total_lessons')->default(8)->after('end_date'); 
            }

            // 2. Add 'lesson_credits' (The one causing your error)
            if (!Schema::hasColumn('subscriptions', 'lesson_credits')) {
                $table->integer('lesson_credits')->default(8)->after('total_lessons');
            }
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('subscriptions', 'lesson_credits')) {
                $table->dropColumn('lesson_credits');
            }
            if (Schema::hasColumn('subscriptions', 'total_lessons')) {
                $table->dropColumn('total_lessons');
            }
        });
    }
};