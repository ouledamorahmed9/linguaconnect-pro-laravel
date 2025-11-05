<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة التحكم') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">حصتك القادمة</h3>

                    <!-- 
                    ==================================================================
                    THIS IS THE FIX:
                    We now use the new '$nextLesson' variable.
                    Line 25 (the error) is now fixed.
                    ==================================================================
                    -->
                    @if($nextLesson)
                        <div class="border-l-4 border-indigo-500 pl-4">
                            <p class="text-sm text-gray-600">
                                حصتك الأسبوعية القادمة هي:
                            </p>
                            <h4 class="text-2xl font-bold text-indigo-700">
                                <!-- This is the fixed line 25 -->
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
        </div>
    </div>
</x-app-layout>
