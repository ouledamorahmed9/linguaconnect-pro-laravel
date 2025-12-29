<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-black text-gray-800">اشتراكي 💳</h2>
                <a href="{{ route('pricing.index') }}" class="text-sm font-bold text-indigo-600 hover:underline">
                    + اشتراك جديد
                </a>
            </div>

            @if($activeSubscription)
                <div class="bg-gradient-to-br from-slate-900 to-indigo-900 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row justify-between gap-8">
                        
                        <div>
                            <span class="bg-green-500/20 text-green-300 border border-green-500/30 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                                نشط الآن
                            </span>
                            <h3 class="text-4xl font-black mt-4 mb-2">
                                {{ $activeSubscription->plan_details['name'] ?? ucfirst($activeSubscription->plan_type) }}
                            </h3>
                            <p class="text-slate-400 text-sm mb-6">
                                ينتهي في {{ $activeSubscription->end_date ? $activeSubscription->end_date->format('Y-m-d') : 'غير محدد' }}
                            </p>

                            <div class="flex gap-4">
                                <div class="bg-white/10 p-3 rounded-xl border border-white/10">
                                    <p class="text-xs text-slate-400">إجمالي الحصص</p>
                                    <p class="font-bold text-xl">{{ $activeSubscription->total_lessons }}</p>
                                </div>
                                <div class="bg-indigo-600 p-3 rounded-xl border border-indigo-400 shadow-lg shadow-indigo-900/50">
                                    <p class="text-xs text-indigo-200">الرصيد المتبقي</p>
                                    <p class="font-bold text-xl">{{ $activeSubscription->lesson_credits }} حصة</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-center">
                            <div class="relative w-32 h-32">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="64" cy="64" r="60" stroke="currentColor" stroke-width="8" fill="none" class="text-slate-700" />
                                    @php
                                        $percent = ($activeSubscription->total_lessons > 0) 
                                            ? ($activeSubscription->lesson_credits / $activeSubscription->total_lessons) * 100 
                                            : 0;
                                        $dashArray = 2 * 3.14 * 60; // Circumference
                                        $dashOffset = $dashArray - ($dashArray * $percent / 100);
                                    @endphp
                                    <circle cx="64" cy="64" r="60" stroke="currentColor" stroke-width="8" fill="none" class="text-indigo-400 transition-all duration-1000 ease-out"
                                        stroke-dasharray="{{ $dashArray }}"
                                        stroke-dashoffset="{{ $dashOffset }}"
                                        stroke-linecap="round" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center flex-col">
                                    <span class="text-sm text-slate-400">متبقي</span>
                                    <span class="text-2xl font-bold">{{ $percent }}%</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @else
                <div class="bg-white p-8 rounded-3xl text-center border-2 border-dashed border-gray-200">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4 text-gray-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">لا يوجد اشتراك نشط</h3>
                    <p class="text-gray-500 mb-6 mt-2">اشترك الآن للبدء في حجز الحصص مع المعلمين.</p>
                    <a href="{{ route('pricing.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        تصفح الباقات
                    </a>
                </div>
            @endif

            @if($subscriptions->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-bold text-gray-700">سجل الاشتراكات</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-right">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold">
                                <tr>
                                    <th class="px-6 py-3">الباقة</th>
                                    <th class="px-6 py-3">الحالة</th>
                                    <th class="px-6 py-3">تاريخ البدء</th>
                                    <th class="px-6 py-3">تاريخ الانتهاء</th>
                                    <th class="px-6 py-3">السعر</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($subscriptions as $sub)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            {{ $sub->plan_details['name'] ?? $sub->plan_type }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($sub->status == 'active')
                                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-md text-xs font-bold">نشط</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-md text-xs font-bold">منتهي</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $sub->start_date ? $sub->start_date->format('Y/m/d') : '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $sub->end_date ? $sub->end_date->format('Y/m/d') : '-' }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-gray-700">{{ $sub->price }} {{ $sub->currency ?? '$' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>