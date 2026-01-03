<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            {{ __('messages.subscription.manage') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Info Banner --}}
            <div class="bg-gradient-to-r from-violet-600 to-indigo-600 rounded-2xl p-6 md:p-8 shadow-xl shadow-indigo-200 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-full h-full opacity-10 pointer-events-none">
                    <svg class="absolute top-[-20%] right-[-10%] w-64 h-64" fill="currentColor" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50"/></svg>
                </div>
                <div class="relative z-10 flex items-start gap-4">
                    <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-1">{{ __('messages.subscription.details_title') }}</h3>
                        <p class="text-indigo-100 font-medium text-sm md:text-base max-w-2xl">
                            {{ __('messages.subscription.details_desc') }}
                        </p>
                    </div>
                </div>
            </div>

            @if($activeSubscriptions->isNotEmpty())
                <div class="space-y-8">
                    @foreach($activeSubscriptions as $activeSubscription)
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            
                            {{-- Active Plan Card --}}
                            <div class="lg:col-span-2">
                                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden relative">
                                    <div class="absolute top-0 ltr:right-0 rtl:right-0 w-32 h-32 bg-indigo-50 rounded-bl-[4rem] -mr-8 -mt-8 z-0"></div>
                                    
                                    <div class="p-8 relative z-10">
                                        <div class="flex justify-between items-start mb-8">
                                            <div>
                                                <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full mb-3 shadow-sm border border-indigo-200">
                                                    {{ $activeSubscription->target_language ?? 'General' }}
                                                </span>
                                                <h3 class="text-3xl font-black text-slate-900 mb-1">{{ __('messages.plans.' . $activeSubscription->plan_type) }}</h3>
                                                <p class="text-slate-500 font-medium">{{ __('messages.subscription.activated_on', ['date' => $activeSubscription->starts_at->translatedFormat('d F Y')]) }}</p>
                                            </div>
                                            <div class="text-center">
                                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-400 to-green-600 shadow-lg shadow-green-200 flex items-center justify-center text-white mb-2 mx-auto">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </div>
                                                <span class="text-green-600 font-bold text-sm block">{{ __('messages.subscription.active') }}</span>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                                                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">{{ __('messages.dashboard.remaining_lessons') }}</span>
                                                <div class="flex items-baseline gap-1">
                                                    <span class="text-4xl font-black text-indigo-600">{{ $activeSubscription->lesson_credits }}</span>
                                                    <span class="text-slate-500 font-medium">/ {{ $activeSubscription->total_lessons }}</span>
                                                </div>
                                            </div>
                                            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                                                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">{{ __('messages.dashboard.renewal_date') }}</span>
                                                <div class="flex items-baseline gap-1 ltr:flex-row rtl:flex-row-reverse">
                                                    <span class="text-2xl font-bold text-slate-800">{{ $activeSubscription->ends_at->translatedFormat('d M') }}</span>
                                                    <span class="text-slate-500 font-medium text-sm">{{ $activeSubscription->ends_at->format('Y') }}</span>
                                                </div>
                                                <span class="text-xs text-orange-500 font-bold mt-1 block">{{ __('messages.subscription.days_remaining', ['days' => $activeSubscription->ends_at->diffInDays(now())]) }}</span>
                                            </div>
                                        </div>

                                        <div>
                                            <div class="flex justify-between items-end mb-2">
                                                <span class="text-sm font-bold text-slate-600">{{ __('messages.subscription.usage_percentage') }}</span>
                                                <span class="text-sm font-bold text-indigo-600">{{ round(($activeSubscription->lessons_used / $activeSubscription->total_lessons) * 100) }}%</span>
                                            </div>
                                            <div class="w-full bg-slate-100 rounded-full h-4 overflow-hidden shadow-inner">
                                                <div class="bg-indigo-600 h-4 rounded-full transition-all duration-1000 ease-out relative" style="width: {{ ($activeSubscription->lessons_used / $activeSubscription->total_lessons) * 100 }}%">
                                                    <div class="absolute inset-0 bg-white/20 w-full h-full animate-[shimmer_2s_infinite]"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions / Upgrade Card (Only show once or customized) --}}
                            <div class="lg:col-span-1">
                                <div class="bg-gradient-to-b from-slate-900 to-slate-800 rounded-3xl shadow-xl shadow-slate-400 p-8 text-white h-full flex flex-col justify-between relative overflow-hidden">
                                    <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                                        <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none"><path vector-effect="non-scaling-stroke" stroke-width="0.5" d="M0 0l100 100M100 0L0 100"/></svg>
                                    </div>
                                    
                                    <div class="relative z-10">
                                        <h3 class="text-2xl font-bold mb-4">{{ __('messages.subscription.upgrade_title') }}</h3>
                                        <p class="text-slate-300 text-sm leading-relaxed mb-6">
                                            {{ __('messages.subscription.upgrade_desc') }}
                                        </p>
                                    </div>

                                    <a href="{{ route('pricing.index') }}" class="block w-full bg-white text-slate-900 py-4 rounded-xl text-center font-bold text-lg hover:bg-slate-200 transition-colors shadow-lg relative z-10">
                                        {{ __('messages.subscription.new_subscription') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- History Section --}}
                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                        <h3 class="text-lg font-bold text-slate-800">{{ __('messages.subscription.history_title') }}</h3>
                    </div>
                    @if($pastSubscriptions && $pastSubscriptions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full ltr:text-left rtl:text-right">
                                <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold">
                                    <tr>
                                        <th class="px-6 py-4">{{ __('messages.dashboard.plan_type') }}</th>
                                        <th class="px-6 py-4">{{ __('messages.subscription.start_date') }}</th>
                                        <th class="px-6 py-4">{{ __('messages.subscription.end_date') }}</th>
                                        <th class="px-6 py-4">{{ __('messages.subscription.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($pastSubscriptions as $sub)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-6 py-4 font-bold text-slate-800">{{ __('messages.plans.' . $sub->plan_type) }}</td>
                                            <td class="px-6 py-4 text-slate-600">{{ $sub->starts_at->format('Y-m-d') }}</td>
                                            <td class="px-6 py-4 text-slate-600">{{ $sub->ends_at->format('Y-m-d') }}</td>
                                            <td class="px-6 py-4">
                                                <span class="inline-block px-2 py-1 rounded text-xs font-bold bg-gray-100 text-gray-600">{{ __('messages.subscription.expired') }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-8 text-center text-slate-400 text-sm">
                            {{ __('messages.subscription.no_history') }}
                        </div>
                    @endif
                </div>

            @else
                {{-- No Active Subscription --}}
                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 p-12 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ __('messages.subscription.no_active_title') }}</h3>
                    <p class="text-slate-500 max-w-md mx-auto mb-8">{{ __('messages.subscription.no_active_desc') }}</p>
                    <a href="{{ route('pricing.index') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 text-lg">
                        <span>{{ __('messages.subscription.view_plans') }}</span>
                        <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>