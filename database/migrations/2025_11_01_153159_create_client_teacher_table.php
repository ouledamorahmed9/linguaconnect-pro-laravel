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
        // This is a "pivot table" as per professional Laravel standards.
        // The name 'client_teacher' is alphabetical.
        Schema::create('client_teacher', function (Blueprint $table) {
            $table->id();
            
            // This is the 'teacher_id', but we use 'user_id' as it links to the users table.
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // This is the 'client_id', which also links to the users table.
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();

            // Prevent the same client from being assigned to the same teacher twice.
            $table->unique(['user_id', 'client_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_teacher');
    }
};
