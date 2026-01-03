<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PricingController extends Controller
{
    public static function getPlans()
    {
        return [
            'normal' => [
                'name' => __('messages.pricing.plans.normal.name'),
                'sub_name' => __('messages.pricing.plans.normal.sub_name'),
                'description' => __('messages.pricing.plans.normal.description'),
                'price' => 35,
                'period' => __('messages.pricing.period'),
                'lessons_count' => 8,
                'group_size' => __('messages.pricing.plans.normal.group_size'),
                'is_popular' => false,
                'color' => 'blue',
                'features' => __('messages.pricing.plans.normal.features')
            ],
            'vip' => [
                'name' => __('messages.pricing.plans.vip.name'),
                'sub_name' => __('messages.pricing.plans.vip.sub_name'),
                'description' => __('messages.pricing.plans.vip.description'),
                'price' => 70,
                'period' => __('messages.pricing.period'),
                'lessons_count' => 8,
                'group_size' => __('messages.pricing.plans.vip.group_size'),
                'is_popular' => false,
                'color' => 'purple',
                'features' => __('messages.pricing.plans.vip.features')
            ],
            'duo' => [
                'name' => __('messages.pricing.plans.duo.name'),
                'sub_name' => __('messages.pricing.plans.duo.sub_name'),
                'description' => __('messages.pricing.plans.duo.description'),
                'price' => 110,
                'period' => __('messages.pricing.period'),
                'lessons_count' => 8,
                'group_size' => __('messages.pricing.plans.duo.group_size'),
                'is_popular' => false,
                'color' => 'teal',
                'features' => __('messages.pricing.plans.duo.features')
            ],
            'private' => [
                'name' => __('messages.pricing.plans.private.name'),
                'sub_name' => __('messages.pricing.plans.private.sub_name'),
                'description' => __('messages.pricing.plans.private.description'),
                'price' => 180,
                'period' => __('messages.pricing.period'),
                'lessons_count' => 8,
                'group_size' => __('messages.pricing.plans.private.group_size'),
                'is_popular' => true,
                'color' => 'indigo',
                'features' => __('messages.pricing.plans.private.features')
            ],
        ];
    }

    public function index(Request $request): View
    {
        // Default Configuration
        $currency = __('messages.pricing.currency');
        $countryName = 'Tunisia';

        $plans = self::getPlans();

        return view('pricing', [
            'plans' => $plans,
            'currency' => $currency,
            'country' => $countryName
        ]);
    }
}