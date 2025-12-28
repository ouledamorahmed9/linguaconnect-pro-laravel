<x-public-layout>
    {{-- Header --}}
    <div class="relative bg-slate-900 py-24 overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-indigo-600/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        </div>
        <div class="container mx-auto px-6 relative z-10 text-center">
            <span class="text-indigo-400 font-bold tracking-wider uppercase text-sm mb-4 block">خطط مرنة وشفافة</span>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6">استثمر في مستقبلك</h1>
            <p class="text-lg text-slate-300 max-w-2xl mx-auto">
                اختر الخطة التي تناسب أهدافك وميزانيتك. جميع الخطط تشمل 8 حصص شهرياً مع معلمين محترفين.
            </p>
        </div>
    </div>

    {{-- Pricing Cards --}}
    <div class="py-20 bg-slate-50 min-h-screen" dir="rtl">
        <div class="container mx-auto px-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-7xl mx-auto">
                @php
                    $plans = config('plans');
                    // Helper to sort: normal, vip, duo, private
                    $orderedKeys = ['normal', 'vip', 'duo', 'private'];
                @endphp

                @foreach($orderedKeys as $key)
                    @php $plan = $plans[$key]; @endphp
                    <div class="relative bg-white rounded-3xl shadow-xl border {{ $plan['is_popular'] ? 'border-indigo-500 ring-4 ring-indigo-500/10' : 'border-gray-100' }} flex flex-col overflow-hidden hover:-translate-y-2 transition-all duration-300">
                        
                        @if($plan['is_popular'])
                            <div class="absolute top-0 inset-x-0 bg-indigo-500 text-white text-xs font-bold text-center py-1">
                                الأكثر طلباً 🔥
                            </div>
                        @endif

                        <div class="p-8 flex-1">
                            {{-- Title --}}
                            <h3 class="text-xl font-black text-slate-900 mb-2">{{ $plan['name'] }}</h3>
                            <p class="text-sm text-indigo-600 font-bold mb-6 bg-indigo-50 inline-block px-3 py-1 rounded-lg">{{ $plan['sub_name'] }}</p>

                            {{-- Price --}}
                            <div class="mb-2 flex items-baseline gap-1">
                                <span class="text-4xl font-black text-slate-900">${{ number_format($plan['price'], 2) }}</span>
                                <span class="text-gray-500 font-medium">/ شهر</span>
                            </div>
                            
                            {{-- Price per session highlight --}}
                            <div class="mb-6 text-xs text-gray-500 font-semibold">
                                (يعادل ${{ number_format($plan['price_per_session'], 2) }} للحصة)
                            </div>

                            <p class="text-gray-500 text-sm mb-8 leading-relaxed border-b border-gray-100 pb-6">
                                {{ $plan['description'] }}
                            </p>

                            {{-- Features --}}
                            <ul class="space-y-4 mb-8">
                                <li class="flex items-center gap-3 text-sm text-slate-700 font-bold">
                                    <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    </div>
                                    {{ $plan['group_size'] }}
                                </li>
                                @foreach($plan['features'] as $feature)
                                    <li class="flex items-start gap-3 text-sm text-gray-600">
                                        <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Action Button --}}
                        <div class="p-8 pt-0 mt-auto">
                            <a href="{{ route('client.subscription.create', $key) }}" class="block w-full py-4 rounded-xl text-center font-bold transition-all duration-300 {{ $plan['is_popular'] ? 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg hover:shadow-indigo-500/30' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 hover:text-slate-900' }}">
                                اشترك الآن
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</x-public-layout>