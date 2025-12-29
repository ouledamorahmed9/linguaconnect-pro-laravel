<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // We check if columns exist first to avoid errors
            if (!Schema::hasColumn('subscriptions', 'price')) {
                $table->decimal('price', 10, 2)->nullable()->after('type'); // Stores 20.80
            }
            if (!Schema::hasColumn('subscriptions', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('price');
            }
        });
    }

    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['price', 'payment_status']);
        });
    }
};