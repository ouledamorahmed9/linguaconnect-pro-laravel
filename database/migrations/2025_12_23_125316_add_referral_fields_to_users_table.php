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
        Schema::table('users', function (Blueprint $table) {
            // 1. The unique code the coordinator/user shares (e.g. "AHMED-123")
            // specific placement: 'after' ensures it doesn't get added at the very end if possible
            $table->string('referral_code')->nullable()->unique()->after('email');

            // 2. The ID of the person who invited this user
            // We use foreign key constraints so if the referrer is deleted, this field becomes NULL
            $table->foreignId('referrer_id')
                  ->nullable()
                  ->after('referral_code')
                  ->constrained('users')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // We must drop the foreign key first, then the columns
            $table->dropForeign(['referrer_id']);
            $table->dropColumn(['referral_code', 'referrer_id']);
        });
    }
};