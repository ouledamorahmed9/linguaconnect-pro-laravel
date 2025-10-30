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
            Schema::table('appointments', function (Blueprint $table) {
                // Update the 'status' column to include our new, professional statuses
                $table->enum('status', ['scheduled', 'logged', 'verified', 'disputed', 'cancelled', 'no_show'])
                      ->default('scheduled')->change();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('appointments', function (Blueprint $table) {
                // Revert to the old statuses if we need to roll back
                 $table->enum('status', ['scheduled', 'completed', 'cancelled', 'no_show'])
                      ->default('scheduled')->change();
            });
        }
    };