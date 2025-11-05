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
            // This table stores the REPEATING weekly schedule
            Schema::create('weekly_slots', function (Blueprint $table) {
                $table->id();
                
                // Link to the teacher
                $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
                
                // Link to the client
                $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
    
                // Day of the week (0 = Sunday, 1 = Monday, 2 = Tuesday, etc.)
                $table->integer('day_of_week'); 
    
                // Start time of the lesson (e.g., "20:00:00" for 8 PM)
                $table->time('start_time');
    
                // End time of the lesson (e.g., "21:00:00" for 9 PM)
                $table->time('end_time');
    
                $table->timestamps();
            });
        }
    
        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('weekly_slots');
        }
    };
    ```

### **Step 1.B: Create the Model File**

Now, we need a Laravel Model to interact with our new table.

1.  Run this command in your terminal:
    ```bash
    php artisan make:model WeeklySlot
    
