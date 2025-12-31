<?php

return [
    'normal' => [
        'name' => 'Normal Class',
        'sub_name' => 'مجموعة قياسية',
        'description' => 'تعلم في مجموعات كبيرة وتفاعلية.',
        'price' => 20.80, // 2.60 * 8
        'price_per_session' => 2.60,
        'period' => 'شهرياً',
        'lessons_count' => 8,
        'group_size' => '8-10 طلاب',
        'features' => [
            '8 حصص شهرياً',
            'مجموعات 8-10 طلاب',
            'منهج دراسي شامل',
            'واجبات منزلية أسبوعية',
            'شهادة إتمام المستوى',
        ],
        'is_popular' => false,
        'color' => 'blue', 
    ],

    'vip' => [
        'name' => 'VIP Class',
        'sub_name' => 'مجموعة مميزة',
        'description' => 'تركيز أعلى مع عدد طلاب أقل.',
        'price' => 28.00, // 3.50 * 8
        'price_per_session' => 3.50,
        'period' => 'شهرياً',
        'lessons_count' => 8,
        'group_size' => '3-5 طلاب',
        'features' => [
            '8 حصص شهرياً',
            'مجموعات صغيرة (3-5)',
            'تفاعل مباشر ومكثف',
            'تصحيح فوري للأخطاء',
            'تسجيلات للحصص',
        ],
        'is_popular' => true, // Highlight this one
        'color' => 'indigo',
    ],

    'duo' => [
        'name' => 'Duo Class',
        'sub_name' => 'شريك تعليمي',
        'description' => 'تعلم مع صديق أو شريك بمستواك.',
        'price' => 41.60, // 5.20 * 8
        'price_per_session' => 5.20,
        'period' => 'شهرياً',
        'lessons_count' => 8,
        'group_size' => 'طالبين فقط',
        'features' => [
            '8 حصص شهرياً',
            'شريك واحد فقط',
            'خطة مخصصة للاثنين',
            'مرونة في المواعيد',
            'دعم شات مباشر',
        ],
        'is_popular' => false,
        'color' => 'purple',
    ],

    'private' => [
        'name' => 'One-to-One',
        'sub_name' => 'خاص ومكثف',
        'description' => 'المعلم لك وحدك لنتائج أسرع.',
        'price' => 68.80, // 8.60 * 8
        'price_per_session' => 8.60,
        'period' => 'شهرياً',
        'lessons_count' => 8,
        'group_size' => 'طالب واحد',
        'features' => [
            '8 حصص خاصة',
            'تركيز 100% عليك',
            'تخصيص كامل للمنهج',
            'اختر الوقت الذي يناسبك',
            'تغيير الموعد مجاناً',
        ],
        'is_popular' => false,
        'color' => 'slate',
    ],
];