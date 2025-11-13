use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meet_reports', function (Blueprint $table) {
            $table->id();
            // This is the most important link in the new system
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->string('meeting_code')->unique();
            $table->string('meeting_url', 512);
            $table->json('report_data'); // Stores the full JSON scrape
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meet_reports');
    }
};