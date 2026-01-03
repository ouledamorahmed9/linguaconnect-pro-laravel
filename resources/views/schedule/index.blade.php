<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            {{ __('جدولي الأسبوعي') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Info Banner --}}
            <div
                class="bg-indigo-600 rounded-2xl p-6 md:p-8 shadow-xl shadow-indigo-200 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-full h-full opacity-10 pointer-events-none">
                    <svg class="absolute top-[-50%] right-[-10%] w-64 h-64" fill="currentColor" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="50" />
                    </svg>
                </div>
                <div class="relative z-10 flex items-start gap-4">
                    <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-1">جدول الحصص الأسبوعي</h3>
                        <p class="text-indigo-100 font-medium text-sm md:text-base max-w-2xl">
                            هذه هي مواعيد حصصك الثابتة التي تتكرر أسبوعياً. يرجى الالتزام بالحضور في الموعد المحدد لضمان
                            الاستفادة القصوى.
                        </p>
                    </div>
                </div>
            </div>

            @if($weeklySlots->isEmpty())
                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 p-12 text-center">
                    <div
                        class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">جدولك فارغ حالياً</h3>
                    <p class="text-slate-500 max-w-md mx-auto mb-8">لم يتم تعيين أي حصص أسبوعية لك بعد. سيظهر جدولك هنا
                        بمجرد أن يقوم المدير بتحديد المواعيد.</p>
                    <a href="{{ route('contact.index') }}"
                        class="inline-flex items-center gap-2 bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                        <span>تواصل مع الإدارة</span>
                        <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    @foreach($daysOfWeek as $dayNumber => $dayName)

                        @if(isset($weeklySlots[$dayNumber]) && $weeklySlots[$dayNumber]->count() > 0)

                            <div class="flex flex-col gap-4">
                                <div class="flex items-center gap-3 pb-2 border-b border-slate-200">
                                    <span class="text-indigo-600 bg-indigo-50 p-2 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </span>
                                    <h3 class="text-xl font-black text-slate-800">{{ $dayName }}</h3>
                                </div>

                                @foreach($weeklySlots[$dayNumber] as $slot)
                                    <div
                                        class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden hover:shadow-xl hover:border-indigo-200 transition-all duration-300 group">
                                        <div class="p-5">
                                            <div class="flex justify-between items-start mb-4">
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-2xl font-black text-slate-900">{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i') }}</span>
                                                    <span
                                                        class="text-xs font-bold text-slate-400 uppercase">{{ \Carbon\Carbon::parse($slot->start_time)->format('A') }}</span>
                                                </div>
                                                <span class="bg-indigo-50 text-indigo-700 text-xs font-bold px-2 py-1 rounded-md">1
                                                    ساعة</span>
                                            </div>

                                            <div class="space-y-3">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs">
                                                        {{ substr($slot->teacher->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-slate-400 font-bold uppercase mb-0.5">المعلم</p>
                                                        <p class="text-sm font-bold text-slate-800">{{ $slot->teacher->name }}</p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-3 pt-3 border-t border-slate-50">
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-violet-50 flex items-center justify-center text-violet-500">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-slate-400 font-bold uppercase mb-0.5">المادة</p>
                                                        <p class="text-sm font-bold text-slate-800">
                                                            {{ $slot->teacher->subject ?? 'غير محدد' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="bg-slate-50 px-5 py-3 border-t border-slate-100 flex justify-between items-center group-hover:bg-indigo-50/50 transition-colors">
                                            <span class="text-xs text-slate-500 font-medium">حصة أسبوعية</span>
                                            @if(\Carbon\Carbon::now()->dayOfWeek == $dayNumber)
                                                <span class="flex h-2 w-2 relative">
                                                    <span
                                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>