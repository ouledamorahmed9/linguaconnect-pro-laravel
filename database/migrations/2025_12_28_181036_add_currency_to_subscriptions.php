<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Check if column exists, if not, create it
            if (!Schema::hasColumn('subscriptions', 'currency')) {
                $table->string('currency')->default('USD')->after('price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('subscriptions', 'currency')) {
                $table->dropColumn('currency');
            }
        });
    }
};