<? php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudySubject;

class StudySubjectSeeder extends Seeder
{
    /**
     * Run the database seeds. 
     */
    public function run(): void
    {
        $subjects = [
            [
                'name' => 'English',
                'name_ar' => 'الإنجليزية',
                'color' => '#3B82F6',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'French',
                'name_ar' => 'الفرنسية',
                'color' => '#EF4444',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Quran',
                'name_ar' => 'القرآن الكريم',
                'color' => '#10B981',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($subjects as $subject) {
            StudySubject::create($subject);
        }
    }
}