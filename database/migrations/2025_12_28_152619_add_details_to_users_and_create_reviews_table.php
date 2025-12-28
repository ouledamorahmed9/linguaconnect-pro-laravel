<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Add Bio & Banner to Users
        Schema::table('users', function (Blueprint $table) {
            $table->text('bio')->nullable(); // Long description
            $table->string('banner_photo_path', 2048)->nullable(); // Cover Image
        });

        // 2. Create Reviews Table
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating'); // 1 to 5
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bio', 'banner_photo_path']);
        });
    }
};