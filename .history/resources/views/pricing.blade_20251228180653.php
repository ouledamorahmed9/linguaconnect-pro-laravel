<x-public-layout>
    <div class="bg-gray-50 min-h-screen py-24" dir="rtl">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-base font-semibold text-indigo-600 tracking-wide uppercase">خطط الدراسة</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                اختر أسلوب التعلم المفضل لديك
            </p>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                سواء كنت تفضل الدراسة الفردية أو المجموعات التفاعلية، لدينا الحل الأمثل لك.
            </p>
        </div>

        <div class="mt-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-8 lg:space-y-0 lg:grid lg:grid-cols-4 lg:gap-6">
                
                @foreach($plans as $key => $plan)
                    <div class="relative flex flex-col p-6 bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 {{ $plan['is_popular'] ? 'border-2 border-indigo-500 transform scale-105 z-10' : 'border border-gray-200' }}">
                        
                        @if($plan['is_popular'])
                            <div class="absolute top-0 inset-x-0 -mt-3 flex justify-center">
                                <span class="inline-flex rounded-full bg-indigo-600 px-4 py-1 text-xs font-bold tracking-wider uppercase text-white shadow-sm">
                                    الأكثر طلباً
                                </span>
                            </div>
                        @endif

                        <div class="flex-1">
                            <div class="mb-4">
                                <h3 class="text-lg font-bold text-gray-900">{{ $plan['name'] }}</h3>
                                <p class="text-sm font-medium text-{{ $plan['color'] }}-600 mt-1">{{ $plan['sub_name'] }}</p>
                                <span class="inline-block mt-2 px-2 py-1 text-xs font-semibold rounded bg-{{ $plan['color'] }}-50 text-{{ $plan['color'] }}-700">
                                    👥 {{ $plan['group_size'] }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-500 mb-6 min-h-[40px]">
                                {{ $plan['description'] }}
                            </p>

                            <div class="mb-6 bg-gray-50 rounded-lg p-3 text-center border border-gray-100">
                                <div class="flex justify-center items-baseline text-gray-900">
                                    @php
                                        // Calculate per session price dynamically
                                        $perSession = $plan['price'] / $plan['lessons_count'];
                                        // Format numbers to show 2 decimal places (e.g., 2.60)
                                        $formattedSession = number_format($perSession, 2);
                                        $formattedTotal = number_format($plan['price'], 2);
                                    @endphp
                                    <span class="text-4xl font-extrabold tracking-tight">{{ $formattedSession }}</span>
                                    <span class="ml-1 text-xl font-semibold text-gray-500">$</span>
                                    <span class="mr-1 text-gray-500 text-lg">/ للحصة</span>
                                </div>
                                
                                <p class="text-xs text-gray-500 mt-2 font-bold">
                                    تدفع شهرياً: {{ $formattedTotal }}$ ({{ $plan['lessons_count'] }} حصص)
                                </p>
                            </div>

                            <ul role="list" class="space-y-4 border-t border-gray-100 pt-6">
                                @foreach($plan['features'] as $feature)
                                    <li class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <span class="mr-3 text-sm text-gray-600">{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        
                        <a href="{{ route('client.subscription.create', $key) }}" class="mt-8 block w-full py-3 px-4 rounded-lg text-center text-sm font-bold transition-colors duration-200 {{ $plan['is_popular'] ? 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-md' : 'bg-gray-50 text-indigo-700 hover:bg-indigo-50 border border-indigo-200' }}">
                            اشترك الآن
                        </a>
                    </div>
                @endforeach

            </div>
        </div>
        
        <div class="max-w-2xl mx-auto px-4 mt-20 sm:px-6 lg:px-8 text-center">
             <div class="grid grid-cols-3 gap-4 text-center text-gray-500 text-sm">
                <div class="flex flex-col items-center">
                    <div class="p-2 bg-green-100 rounded-full mb-2 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span>ضمان استرداد 7 أيام</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="p-2 bg-blue-100 rounded-full mb-2 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <span>دفع آمن 100%</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="p-2 bg-purple-100 rounded-full mb-2 text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <span>مدرسون معتمدون</span>
                </div>
             </div>
        </div>
    </div>
</x-public-layout>