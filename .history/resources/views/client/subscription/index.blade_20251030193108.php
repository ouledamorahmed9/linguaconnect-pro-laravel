<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('اشتراكي') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Active Subscription Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 md:p-8 text-gray-900">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-4">الباقة النشطة</h3>
                            
                            @if($activeSubscription)
                                <div>
                                    <div class="flex justify-between items-baseline mb-4">
                                        <div class="text-3xl font-bold text-indigo-600">
                                            @if($activeSubscription->plan_type === 'basic') الباقة الأساسية @endif
                                            @if($activeSubscription->plan_type === 'advanced') الباقة المتقدمة @endif
                                            @if($activeSubscription->plan_type === 'intensive') الباقة المكثفة @endif
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            ينتهي في: <span class="font-semibold text-gray-700">{{ $activeSubscription->ends_at->translatedFormat('d M, Y') }}</span>
                                        </div>
                                    </div>

                                    <h4 class="text-md font-semibold text-gray-800 mb-2">استهلاك الباقة</h4>
                                    <div class="w-full bg-gray-200 rounded-full h-4">
                                        @php
                                            $percentage = ($activeSubscription->total_lessons > 0) ? (($activeSubscription->lessons_used / $activeSubscription->total_lessons) * 100) : 0;
                                        @endphp
                                        <div class="bg-indigo-600 h-4 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-2 text-center">
                                        تم استهلاك <span class="font-bold">{{ $activeSubscription->lessons_used }}</span> من <span class="font-bold">{{ $activeSubscription->total_lessons }}</span> حصص
                                    </p>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-semibold text-gray-900">لا يوجد لديك باقة نشطة</h3>
                                    <p class="mt-1 text-sm text-gray-500">سيقوم المنسق بتعيين باقة لك قريباً.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Subscription History -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">سجل الاشتراكات</h3>
                            <ul class="space-y-3">
                                @forelse($pastSubscriptions as $sub)
                                    <li class="bg-gray-50 p-3 rounded-lg opacity-70">
                                        <p class="font-semibold text-gray-700">
                                            @if($sub->plan_type === 'basic') الباقة الأساسية @endif
                                            @if($sub->plan_type === 'advanced') الباقة المتقدمة @endif
                                            @if($sub->plan_type === 'intensive') الباقة المكثفة @endif
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            انتهت في: {{ $sub->ends_at->translatedFormat('d M, Y') }}
                                        </p>
                                    </li>
                                @empty
                                    <li class="text-sm text-gray-500">لا يوجد سجل اشتراكات سابقة.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
