<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PricingController extends Controller
{
    /**
     * Display the pricing page with 4 packages in USD.
     */
    public function index(Request $request): View
    {
        // Hardcoded to USD for now as requested
        $currency = '$';
        $countryName = 'Global'; 

        // Define 4 Packages
        $plans = [
            'starter' => [
                'name' => 'الباقة التمهيدية',
                'price' => 30,
                'period' => 'شهر',
                'lessons' => 4,
                'features' => [
                    '4 حصص شهرياً',
                    'مدة الحصة 45 دقيقة',
                    'اختيار المدرس',
                ]
            ],
            'basic' => [
                'name' => 'الباقة الأساسية',
                'price' => 55,
                'period' => 'شهر',
                'lessons' => 8,
                'features' => [
                    '8 حصص شهرياً',
                    'مدة الحصة 45 دقيقة',
                    'منهج مخصص',
                    'تقييم مستوى مجاني'
                ]
            ],
            'advanced' => [
                'name' => 'الباقة المتقدمة',
                'price' => 80,
                'period' => 'شهر',
                'lessons' => 12,
                'features' => [
                    '12 حصة شهرياً',
                    'مدة الحصة 60 دقيقة',
                    'أوقات مرنة',
                    'دعم واتساب للمعلم',
                    'شهادة إكمال مستوى'
                ]
            ],
            'intensive' => [
                'name' => 'الباقة المكثفة',
                'price' => 120,
                'period' => 'شهر',
                'lessons' => 20,
                'features' => [
                    '20 حصة شهرياً',
                    'مدة الحصة 60 دقيقة',
                    'أولوية الحجز القصوى',
                    'تسجيل الحصص للمراجعة',
                    'خطة دراسية مكثفة للامتحانات'
                ]
            ],
        ];

        return view('pricing', [
            'plans' => $plans,
            'currency' => $currency,
            'country' => $countryName
        ]);
    }
}