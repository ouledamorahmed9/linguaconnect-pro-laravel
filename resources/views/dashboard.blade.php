<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            {{ __('messages.nav.dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Welcome Banner --}}
            <div
                class="relative bg-gradient-to-r from-indigo-600 to-violet-700 rounded-3xl p-8 md:p-12 shadow-2xl shadow-indigo-200 overflow-hidden text-white">
                <div class="absolute top-0 right-0 w-full h-full overflow-hidden opacity-10 pointer-events-none">
                    <svg class="absolute top-[-20%] right-[-10%] w-96 h-96 text-white" fill="currentColor"
                        viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="50" />
                    </svg>
                    <svg class="absolute bottom-[-20%] left-[-10%] w-80 h-80 text-white" fill="currentColor"
                        viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="50" />
                    </svg>
                </div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <h3 class="text-3xl md:text-4xl font-black tracking-tight mb-2">
                            {{ __('messages.dashboard.welcome', ['name' => explode(' ', Auth::user()->name)[0]]) }} 👋
                        </h3>
                        <p class="text-indigo-100 text-lg font-medium max-w-xl">
                            {{ __('messages.auth.login_subtitle') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Left Column (Main Content) --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- Next Lesson Card --}}
                    <div
                        class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
                        <div
                            class="p-6 border-b border-slate-50 flex justify-between items-center bg-gradient-to-l from-white to-slate-50/50">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                <span class="bg-indigo-100 text-indigo-600 p-2 rounded-lg"><svg class="w-5 h-5"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg></span>
                                {{ __('messages.dashboard.next_lesson') }}
                            </h3>
                            @if($nextLesson)
                                <span
                                    class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold shadow-sm">{{ __('messages.dashboard.confirmed') }}</span>
                            @endif
                        </div>
                        <div class="p-8">
                            @if($nextLesson)
                                <div
                                    class="flex flex-col md:flex-row gap-8 items-center md:items-start text-center md:ltr:text-left md:rtl:text-right">
                                    <div
                                        class="bg-indigo-50 rounded-2xl p-4 min-w-[120px] flex flex-col items-center justify-center border border-indigo-100">
                                        <span
                                            class="text-indigo-600 font-bold block mb-1">{{ $daysOfWeek[$nextLesson->day_of_week] ?? __('messages.dashboard.day') }}</span>
                                        <span
                                            class="text-3xl font-black text-slate-800 block">{{ \Carbon\Carbon::parse($nextLesson->start_time)->format('h:i') }}</span>
                                        <span
                                            class="text-slate-400 text-sm font-bold uppercase block mt-1">{{ \Carbon\Carbon::parse($nextLesson->start_time)->format('A') }}</span>
                                    </div>
                                    <div class="flex-1 space-y-4">
                                        <div>
                                            <label
                                                class="text-xs uppercase tracking-wide text-slate-400 font-bold mb-1 block">{{ __('messages.dashboard.teacher') }}</label>
                                            <div class="flex items-center justify-center md:justify-start gap-3">
                                                <div
                                                    class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold">
                                                    {{ substr($nextLesson->teacher->name, 0, 1) }}
                                                </div>
                                                <p class="text-lg font-bold text-slate-800">{{ $nextLesson->teacher->name }}
                                                </p>
                                            </div>
                                        </div>
                                        <div>
                                            <label
                                                class="text-xs uppercase tracking-wide text-slate-400 font-bold mb-1 block">{{ __('messages.dashboard.subject') }}</label>
                                            <p
                                                class="text-slate-600 font-medium bg-slate-50 inline-block px-3 py-1.5 rounded-lg border border-slate-100">
                                                {{ $nextLesson->teacher->subject ?? 'General English' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <a href="{{ $nextLesson->meeting_link ?? '#' }}" target="_blank"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-indigo-200 transition-all flex items-center gap-2 group">
                                            <span>{{ __('messages.dashboard.join_link') }}</span>
                                            <svg class="w-4 h-4 rtl:rotate-180 group-hover:translate-x-1 ltr:group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div
                                        class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <p class="text-slate-500 font-medium text-lg mb-2">{{ __('messages.dashboard.no_scheduled_classes') }}</p>
                                    <p class="text-slate-400 text-sm">{{ __('messages.dashboard.contact_admin') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Subscription Card --}}
                    <div
                        class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
                        <div
                            class="p-6 border-b border-slate-50 flex justify-between items-center bg-gradient-to-l from-white to-slate-50/50">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                <span class="bg-violet-100 text-violet-600 p-2 rounded-lg"><svg class="w-5 h-5"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                        </path>
                                    </svg></span>
                                {{ __('messages.dashboard.my_subscriptions') }}
                            </h3>
                            <a href="{{ route('client.subscription.index') }}"
                                class="text-sm font-bold text-violet-600 hover:text-violet-800 transition-colors">{{ __('messages.dashboard.manage_plans') }} &larr;</a>
                        </div>
                        <div class="p-8">
                            @if($activeSubscriptions->isNotEmpty())
                                @foreach($activeSubscriptions as $index => $activeSubscription)
                                    @if($index > 0)
                                    <hr class="my-8 border-slate-100"> @endif

                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-6 gap-4">
                                        <div>
                                            <span
                                                class="inline-block bg-indigo-100 text-indigo-700 text-xs font-bold px-2 py-0.5 rounded mb-2">
                                                {{ $activeSubscription->target_language ?? 'General' }}
                                            </span>
                                            <span
                                                class="text-sm font-bold text-slate-400 uppercase tracking-wide block mb-1">{{ __('messages.dashboard.plan_type') }}</span>
                                            <h4 class="text-2xl font-black text-slate-900 leading-none">
                                                {{ __('messages.plans.' . $activeSubscription->plan_type) }}
                                            </h4>
                                        </div>
                                        <div
                                            class="bg-green-50 text-green-700 px-4 py-2 rounded-xl text-sm font-bold border border-green-100 flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                            {{ __('messages.dashboard.active_until', ['date' => $activeSubscription->ends_at->translatedFormat('d M, Y')]) }}
                                        </div>
                                    </div>

                                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                                        <div class="flex justify-between items-end mb-3">
                                            <span class="text-sm font-bold text-slate-500">{{ __('messages.dashboard.lessons_used') }}</span>
                                            <span class="text-slate-900 font-bold">{{ $activeSubscription->lessons_used }} <span
                                                    class="text-slate-400 font-normal">/
                                                    {{ $activeSubscription->total_lessons }}</span></span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                                            @php
                                                $percentage = ($activeSubscription->total_lessons > 0) ? (($activeSubscription->lessons_used / $activeSubscription->total_lessons) * 100) : 0;
                                                $barColor = $percentage >= 80 ? 'bg-amber-500' : 'bg-violet-600';
                                            @endphp
                                            <div class="{{ $barColor }} h-3 rounded-full transition-all duration-1000 ease-out relative"
                                                style="width: {{ $percentage }}%">
                                                <div
                                                    class="absolute inset-0 bg-white/20 w-full h-full animate-[shimmer_2s_infinite]">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-xs text-slate-400 mt-3 ltr:text-left rtl:text-right">
                                            {{ __('messages.dashboard.completion_msg', ['percentage' => round($percentage), 'language' => $activeSubscription->target_language]) }}
                                        </p>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-6">
                                    <p class="text-slate-500 mb-4">{{ __('messages.dashboard.no_active_subscription') }}</p>
                                    <a href="{{ route('pricing.index') }}"
                                        class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700 transition">{{ __('messages.dashboard.subscribe_now') }}</a>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- Right Column (Reports) --}}
                <div class="lg:col-span-1">
                    <div
                        class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden h-full">
                        <div
                            class="p-6 border-b border-slate-50 flex justify-between items-center bg-gradient-to-l from-white to-slate-50/50">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                <span class="bg-teal-100 text-teal-600 p-2 rounded-lg"><svg class="w-5 h-5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg></span>
                                {{ __('messages.dashboard.progress_report') }}
                            </h3>
                            <a href="{{ route('client.progress-reports.index') }}"
                                class="text-sm font-bold text-teal-600 hover:text-teal-800 transition-colors">{{ __('messages.dashboard.view_all') }}</a>
                        </div>
                        <div class="p-6">
                            @if($latestReports->isEmpty())
                                <div class="text-center py-12">
                                    <div
                                        class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-slate-400 font-medium">{{ __('messages.dashboard.no_reports') }}</p>
                                </div>
                            @else
                                <div class="relative border-r border-slate-100 mr-2 space-y-6">
                                    @foreach($latestReports as $report)
                                        <div class="relative pr-6 group">
                                            <div
                                                class="absolute top-1.5 -right-1.5 w-3 h-3 bg-teal-400 rounded-full ring-4 ring-white">
                                            </div>
                                            <div
                                                class="bg-slate-50 p-4 rounded-2xl border border-slate-100 group-hover:bg-teal-50 group-hover:border-teal-100 transition-colors">
                                                <h5 class="text-sm font-bold text-slate-900 mb-1 group-hover:text-teal-900">
                                                    {{ $report->topic }}</h5>
                                                <div class="flex items-center gap-2 text-xs text-slate-500">
                                                    <span class="font-medium text-slate-600">{{ $report->teacher->name }}</span>
                                                    <span>&bull;</span>
                                                    <span>{{ $report->start_time->translatedFormat('d M') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="p-6 pt-0 mt-auto">
                            <div
                                class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-5 text-white shadow-lg shadow-indigo-200">
                                <h4 class="font-bold text-lg mb-1">{{ __('messages.dashboard.need_help') }}</h4>
                                <p class="text-indigo-100 text-sm mb-3">{{ __('messages.dashboard.support_team') }}</p>
                                <a href="{{ route('contact.index') }}"
                                    class="block w-full text-center bg-white text-indigo-600 py-2.5 rounded-xl font-bold hover:bg-indigo-50 transition">{{ __('messages.dashboard.contact_us') }}</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>