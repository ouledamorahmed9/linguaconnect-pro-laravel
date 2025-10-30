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
            Schema::table('subscriptions', function (Blueprint $table) {
                // The total number of lessons included in this subscription plan
                $table->unsignedTinyInteger('total_lessons')->after('plan_type');
                // The number of lessons that have been used so far
                $table->unsignedTinyInteger('lessons_used')->after('total_lessons')->default(0);
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn(['total_lessons', 'lessons_used']);
            });
        }
    };