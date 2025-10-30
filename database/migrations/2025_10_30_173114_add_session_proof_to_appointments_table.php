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
                // This will store the meetlist.io ID or other proof
                $table->string('session_proof_id')->nullable()->after('teacher_notes');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropColumn('session_proof_id');
            });
        }
    };