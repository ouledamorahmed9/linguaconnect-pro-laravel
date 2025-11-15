<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة التحكم') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 md:p-8 text-gray-900">
                    <h3 class="text-2xl font-semibold">
                        مرحباً بعودتك، {{ Auth::user()->name }}!
                    </h3>
                    <p class="text-gray-600 mt-2">إليك ملخص سريع لنشاطك التعليمي.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-4 border-b pb-3">حصتك القادمة</h3>
                            @if($nextLesson)
                                <div class="border-r-4 border-indigo-500 pr-4">
                                    <p class="text-sm text-gray-600">
                                        حصتك الأسبوعية القادمة هي:
                                    </p>
                                    <h4 class="text-2xl font-bold text-indigo-700">
                                        {{ $daysOfWeek[$nextLesson->day_of_week] ?? 'يوم' }}
                                        الساعة
                                        {{ \Carbon\Carbon::parse($nextLesson->start_time)->format('h:i A') }}
                                    </h4>
                                    <p class="text-gray-700 mt-2">
                                        مع المعلم: <span class="font-medium">{{ $nextLesson->teacher->name }}</span>
                                    </p>
                                    <p class="text-gray-700">
                                        المادة: <span class="font-medium">{{ $nextLesson->teacher->subject ?? 'غير محدد' }}</span>
                                    </p>
                                </div>
                            @else
                                <p class="text-gray-500">لا يوجد لديك حصص مجدولة في جدولك الأسبوعي.</p>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="flex justify-between items-center mb-4 border-b pb-3">
                                <h3 class="text-lg font-semibold">اشتراكي الحالي</h3>
                                <a href="{{ route('client.subscription.index') }}" class="text-sm text-indigo-600 hover:underline">إدارة الاشتراك</a>
                            </div>
                            
                            @if($activeSubscription)
                                <div>
                                    <div class="flex justify-between items-baseline mb-3">
                                        <div class="text-2xl font-bold text-gray-800">
                                            @if($activeSubscription->plan_type === 'basic') الباقة الأساسية @endif
                                            @if($activeSubscription->plan_type === 'advanced') الباقة المتقدمة @endif
                                            @if($activeSubscription->plan_type === 'intensive') الباقة المكثفة @endif
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            ينتهي في: <span class="font-semibold text-gray-700">{{ $activeSubscription->ends_at->translatedFormat('d M, Y') }}</span>
                                        </div>
                                    </div>

                                    <h4 class="text-sm font-semibold text-gray-800 mb-2">استهلاك الباقة</h4>
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
                                <p class="text-gray-500">لا يوجد لديك اشتراك نشط حالياً.</p>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="flex justify-between items-center mb-4 border-b pb-3">
                                <h3 class="text-lg font-semibold">أحدث التقارير</h3>
                                <a href="{{ route('client.progress-reports.index') }}" class="text-sm text-indigo-600 hover:underline">عرض الكل</a>
                            </div>
                            
                            @if($latestReports->isEmpty())
                                <p class="text-sm text-gray-500">لا توجد تقارير حصص مسجلة حتى الآن.</p>
                            @else
                                <div class="space-y-4">
                                    @foreach($latestReports as $report)
                                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                            <p class="text-sm font-semibold text-gray-800">{{ $report->topic }}</p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $report->teacher->name }} - {{ $report->start_time->translatedFormat('d M Y') }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>