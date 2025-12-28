<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PricingController extends Controller
{
    public function index(Request $request): View
    {
        // Default to USD
        $currency = '$';
        $countryName = 'Global'; 

        $plans = [
            'normal' => [
                'name' => 'Normal Class',
                'sub_name' => 'مجموعة قياسية',
                'description' => 'بيئة تعليمية تفاعلية مع طلاب آخرين.',
                'price' => 35,
                'period' => 'شهر',
                'lessons_count' => 8, // <--- Added this for calculation
                'group_size' => '8 طلاب كحد أقصى',
                'is_popular' => false,
                'color' => 'blue',
                'features' => [
                    '8 حصص شهرياً',
                    'مجموعة (4 - 8 طلاب)',
                    'منهج دراسي شامل',
                    'تفاعل جماعي ممتع',
                    'أقل تكلفة'
                ]
            ],
            'vip' => [
                'name' => 'VIP Class',
                'sub_name' => 'مجموعة صغيرة',
                'description' => 'تركيز أعلى في مجموعة صغيرة جداً.',
                'price' => 70,
                'period' => 'شهر',
                'lessons_count' => 8,
                'group_size' => '4 طلاب كحد أقصى',
                'is_popular' => false,
                'color' => 'purple',
                'features' => [
                    '8 حصص شهرياً',
                    'مجموعة صغيرة (2 - 4 طلاب)',
                    'فرصة أكبر للمشاركة',
                    'متابعة دقيقة للمستوى',
                    'تقارير أداء دورية'
                ]
            ],
            'duo' => [
                'name' => 'Duo Class',
                'sub_name' => 'ثنائي',
                'description' => 'تعلم أنت وصديقك أو شريكك فقط.',
                'price' => 110,
                'period' => 'شهر',
                'lessons_count' => 8,
                'group_size' => 'طالبين فقط',
                'is_popular' => false,
                'color' => 'teal',
                'features' => [
                    '8 حصص شهرياً',
                    'أنت وطالب آخر فقط',
                    'منافسة إيجابية',
                    'تفاعل ممتاز مع المعلم',
                    'تنسيق مرن للمواعيد'
                ]
            ],
            'private' => [
                'name' => 'One-to-One',
                'sub_name' => 'خصوصي',
                'description' => 'التركيز الكامل عليك وحدك.',
                'price' => 180,
                'period' => 'شهر',
                'lessons_count' => 8,
                'group_size' => 'طالب واحد (1:1)',
                'is_popular' => true,
                'color' => 'indigo',
                'features' => [
                    '8 حصص شهرياً',
                    'درس خاص (100% لك)',
                    'منهج مخصص لاحتياجاتك',
                    'مرونة كاملة في الأوقات',
                    'أسرع وتيرة للتعلم'
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