// database/migrations/xxxx_xx_xx_xxxxxx_add_referral_fields_to_users_table.php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        // The code the coordinator shares (e.g., "COORD-592")
        $table->string('referral_code')->nullable()->unique()->index();
        
        // The ID of the person who referred this user
        $table->unsignedBigInteger('referrer_id')->nullable();
        $table->foreign('referrer_id')->references('id')->on('users')->nullOnDelete();
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['referral_code', 'referrer_id']);
    });
}