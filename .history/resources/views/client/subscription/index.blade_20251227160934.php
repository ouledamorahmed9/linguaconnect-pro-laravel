<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('اشتراكي الحالي') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            @if($subscription)
                @php
                    // Get details from config based on stored type
                    $details = $plans[$subscription->type] ?? null;
                    $percentUsed = ($subscription->lessons_used / $subscription->total_lessons) * 100;
                @endphp

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                    <div class="p-8">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                            <div>
                                <h3 class="text-2xl font-black text-gray-900 mb-1">{{ $details['name'] ?? ucfirst($subscription->type) }}</h3>
                                <p class="text-sm text-gray-500">تم الاشتراك في: {{ $subscription->starts_at->format('Y-m-d') }}</p>
                            </div>
                            <span class="mt-4 md:mt-0 bg-green-100 text-green-700 font-bold px-4 py-1.5 rounded-full flex items-center gap-2">
                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                نشط
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div class="bg-indigo-50 p-6 rounded-2xl border border-indigo-100">
                                <p class="text-sm text-indigo-600 font-bold mb-1">إجمالي الحصص</p>
                                <p class="text-3xl font-black text-indigo-900">{{ $subscription->total_lessons }}</p>
                            </div>
                            <div class="bg-purple-50 p-6 rounded-2xl border border-purple-100">
                                <p class="text-sm text-purple-600 font-bold mb-1">الحصص المتبقية</p>
                                <p class="text-3xl font-black text-purple-900">{{ $subscription->total_lessons - $subscription->lessons_used }}</p>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200">
                                <p class="text-sm text-gray-600 font-bold mb-1">تاريخ التجديد</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $subscription->ends_at->format('Y-m-d') }}</p>
                                <p class="text-xs text-gray-500">ينتهي خلال {{ $subscription->ends_at->diffInDays(now()) }} يوم</p>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex justify-between text-sm font-bold text-gray-600 mb-2">
                                <span>استهلاك الباقة</span>
                                <span>{{ $subscription->lessons_used }} من {{ $subscription->total_lessons }} حصة</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                                <div class="bg-gradient-to-l from-indigo-500 to-purple-600 h-4 rounded-full transition-all duration-1000" style="width: {{ $percentUsed }}%"></div>
                            </div>
                        </div>

                    </div>
                    <div class="bg-gray-50 px-8 py-4 border-t border-gray-100 flex justify-end">
                        <a href="{{ route('teachers.index') }}" class="text-indigo-600 font-bold hover:text-indigo-800 text-sm">
                            حجز حصة جديدة &larr;
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 text-center py-16">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">لا يوجد اشتراك نشط</h3>
                    <p class="text-gray-500 mb-8">اشترك الآن للبدء في حجز الحصص مع معلمين معتمدين.</p>
                    <a href="{{ route('pricing.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-bold text-white hover:bg-indigo-700 transition shadow-lg">
                        تصفح الباقات والاشتراك
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>