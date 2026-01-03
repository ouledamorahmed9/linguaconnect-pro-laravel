<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            {{ __('سجل الحصص والتقارير') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Info Banner --}}
            <div
                class="bg-gradient-to-r from-teal-500 to-teal-600 rounded-2xl p-6 md:p-8 shadow-xl shadow-teal-200 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-full h-full opacity-10 pointer-events-none">
                    <svg class="absolute bottom-[-20%] left-[-10%] w-64 h-64" fill="currentColor" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="50" />
                    </svg>
                </div>
                <div class="relative z-10 flex items-start gap-4">
                    <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-1">التقدم الأكاديمي</h3>
                        <p class="text-teal-50 font-medium text-sm md:text-base max-w-2xl">
                            سجل كامل لجميع حصصك المكتملة وملاحظات المعلمين. راجع أدائك باستمرار لتحسين مستواك.
                        </p>
                    </div>
                </div>
            </div>

            @if($lessons->isEmpty())
                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 p-12 text-center">
                    <div
                        class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">لا يوجد سجل حصص</h3>
                    <p class="text-slate-500 max-w-md mx-auto">لم يقم معلمك بتسجيل أي حصص مكتملة حتى الان. ستظهر تقارير
                        الحصص هنا بعد انتهائها.</p>
                </div>
            @else
                <div class="relative">
                    {{-- Timeline Line --}}
                    <div class="absolute top-0 bottom-0 right-8 md:right-12 w-0.5 bg-slate-200 hidden sm:block"></div>

                    <div class="space-y-8">
                        @foreach($lessons as $lesson)
                            <div class="relative flex flex-col sm:flex-row gap-6 md:gap-10 sm:pr-24">
                                {{-- Timeline Dot --}}
                                <div
                                    class="absolute top-6 right-8 md:right-12 w-4 h-4 bg-white border-4 border-indigo-500 rounded-full transform translate-x-1/2 hidden sm:block z-10">
                                </div>

                                {{-- Date Label (Mobile: Top, Desktop: Left/Side) --}}
                                <div
                                    class="sm:absolute sm:top-6 sm:right-0 sm:translate-x-full sm:pl-4 sm:w-24 sm:text-left flex items-center gap-2 sm:block mb-2 sm:mb-0">
                                    <span
                                        class="text-sm font-bold text-slate-400 font-mono">{{ \Carbon\Carbon::parse($lesson->start_time)->format('Y') }}</span>
                                    <span
                                        class="text-lg font-black text-slate-800 block">{{ \Carbon\Carbon::parse($lesson->start_time)->format('d M') }}</span>
                                </div>

                                {{-- Card content --}}
                                <div
                                    class="flex-1 bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                                    {{-- Header --}}
                                    <div
                                        class="p-5 border-b border-slate-50 bg-slate-50/30 flex flex-wrap justify-between items-start gap-4">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span
                                                    class="bg-indigo-100 text-indigo-700 text-xs font-bold px-2 py-0.5 rounded-md">{{ $lesson->subject }}</span>
                                                <span
                                                    class="text-xs text-slate-400 font-bold">{{ \Carbon\Carbon::parse($lesson->start_time)->format('h:i A') }}</span>
                                            </div>
                                            <h4 class="text-xl font-black text-slate-900">{{ $lesson->topic }}</h4>
                                        </div>

                                        @if($lesson->status == 'verified')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200 shadow-sm">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                معتمدة
                                            </span>
                                        @elseif($lesson->status == 'disputed')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200 shadow-sm">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                قيد المراجعة
                                            </span>
                                        @elseif($lesson->status == 'cancelled')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200 shadow-sm">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                ملغاة
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Body --}}
                                    <div class="p-5">
                                        <div class="flex items-center gap-3 mb-6">
                                            <div
                                                class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold">
                                                {{ substr($lesson->teacher->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-400 font-bold uppercase mb-0.5">بإشراف المعلم</p>
                                                <p class="text-sm font-bold text-slate-800">{{ $lesson->teacher->name }}</p>
                                            </div>
                                        </div>

                                        @if($lesson->teacher_notes)
                                            <div class="bg-amber-50 rounded-xl p-4 border border-amber-100 text-amber-900">
                                                <h5 class="text-sm font-bold flex items-center gap-2 mb-2 text-amber-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                                        </path>
                                                    </svg>
                                                    ملاحظات المعلم
                                                </h5>
                                                <div class="text-sm leading-relaxed opacity-90 prose prose-sm max-w-none">
                                                    {!! nl2br(e($lesson->teacher_notes)) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $lessons->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>