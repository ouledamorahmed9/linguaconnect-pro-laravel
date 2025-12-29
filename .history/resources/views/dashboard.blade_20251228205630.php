<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="bg-gradient-to-r from-indigo-600 to-violet-600 rounded-3xl p-8 text-white shadow-xl shadow-indigo-200 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <h2 class="text-3xl font-black mb-2">مرحباً بك، {{ Auth::user()->name }}! 👋</h2>
                        <p class="text-indigo-100 text-lg">جاهز لمواصلة رحلة تعلم اللغات اليوم؟</p>
                    </div>
                    @if(!$activeSubscription)
                        <a href="{{ route('pricing.index') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-xl font-bold hover:bg-indigo-50 transition shadow-lg">
                            اشترك الآن
                        </a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm font-bold">الخطة الحالية</p>
                            <h3 class="text-xl font-black text-gray-800">
                                @if($activeSubscription)
                                    {{ $activeSubscription->plan_details['name'] ?? ucfirst($activeSubscription->name ?? $activeSubscription->plan_type) }}
                                @else
                                    لا يوجد اشتراك
                                @endif
                            </h3>
                        </div>
                    </div>
                    @if($activeSubscription)
                        <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                            {{-- Visual progress based on credits if available --}}
                            @php
                                $total = 8; // Default
                                $current = $activeSubscription->lesson_credits ?? 0;
                                $percent = ($current / $total) * 100;
                            @endphp
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                        <p class="text-xs text-gray-400">ينتهي في {{ $activeSubscription->end_date ? $activeSubscription->end_date->format('Y-m-d') : '---' }}</p>
                    @endif
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm font-bold">رصيد الحصص</p>
                            <h3 class="text-3xl font-black text-gray-800">
                                @if($activeSubscription)
                                    {{ $activeSubscription->lesson_credits ?? 0 }}
                                    <span class="text-sm text-gray-400 font-medium">/ حصة</span>
                                @else
                                    0
                                @endif
                            </h3>
                        </div>
                    </div>
                    @if($activeSubscription && ($activeSubscription->lesson_credits ?? 0) > 0)
                        <a href="{{ route('teachers.index') }}" class="text-sm text-green-600 font-bold hover:underline">
                            حجز حصة الآن &larr;
                        </a>
                    @else
                         <span class="text-sm text-red-400">الرصيد نفذ</span>
                    @endif
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm font-bold">الحصة القادمة</p>
                            <h3 class="text-xl font-black text-gray-800">
                                @if(isset($nextLesson) && $nextLesson)
                                    {{ $nextLesson->start_time->format('D, h:i A') }}
                                @else
                                    --
                                @endif
                            </h3>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400">توقيت محلي</p>
                </div>

            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">إجراءات سريعة</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('teachers.index') }}" class="flex flex-col items-center justify-center p-6 rounded-xl bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-100">
                            <span class="text-3xl mb-2">👨‍🏫</span>
                            <span class="font-bold text-sm">تصفح المعلمين</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center p-6 rounded-xl bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-100">
                            <span class="text-3xl mb-2">⚙️</span>
                            <span class="font-bold text-sm">إعدادات الحساب</span>
                        </a>
                        <a href="{{ route('legal.contact') }}" class="flex flex-col items-center justify-center p-6 rounded-xl bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-100">
                            <span class="text-3xl mb-2">💬</span>
                            <span class="font-bold text-sm">الدعم الفني</span>
                        </a>
                        <div class="flex flex-col items-center justify-center p-6 rounded-xl bg-gray-50 opacity-50 cursor-not-allowed border border-gray-100">
                            <span class="text-3xl mb-2">📜</span>
                            <span class="font-bold text-sm">الشهادات (قريباً)</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>