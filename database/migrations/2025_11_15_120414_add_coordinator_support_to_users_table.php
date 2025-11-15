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
            // هذا العمود هو "مفتاح" الميزة بأكملها
            // هو يربط العميل (User) بالمنسق (User) الذي قام بإنشائه
            $table->foreignId('created_by_user_id')
                  ->nullable() // نجعله 'nullable' لأن المدير والمعلمين والعملاء القدامى ليس لديهم مُنشئ
                  ->after('subject') // نضعه بعد عمود 'subject' للترتيب
                  ->constrained('users') // نتأكد أنه مرتبط بـ ID موجود في جدول 'users'
                  ->nullOnDelete(); // إذا تم حذف المنسق، لا يتم حذف عملائه، بل يصبحون 'يتامى'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['created_by_user_id']);
            $table->dropColumn('created_by_user_id');
        });
    }
};