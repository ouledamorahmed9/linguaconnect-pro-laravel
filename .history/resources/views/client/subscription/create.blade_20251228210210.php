<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen" dir="rtl">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="mb-8 text-center">
                <h2 class="text-3xl font-black text-slate-900 mb-2">تأكيد الاشتراك 🚀</h2>
                <p class="text-gray-500">أنت على بعد خطوة واحدة من بدء رحلتك التعليمية</p>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                <div class="p-8 md:p-10">

                    {{-- Plan Summary Card --}}
                    <div class="bg-gradient-to-br from-indigo-50 to-violet-50 border border-indigo-100 rounded-2xl p-6 mb-8 flex flex-col md:flex-row justify-between items-center gap-6 relative overflow-hidden">
                        
                        {{-- Background Pattern --}}
                        <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
                        
                        <div class="relative z-10 flex items-center gap-5">
                            <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center text-3xl">
                                🎓
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-indigo-900">{{ $plan['name'] }}</h3>
                                <p class="text-indigo-600 font-bold">{{ $plan['sub_name'] }}</p>
                            </div>
                        </div>

                        <div class="relative z-10 text-center md:text-left">
                            <span class="block text-4xl font-black text-slate-900">${{ $plan['price'] }}</span>
                            <span class="text-sm text-gray-500 font-bold">/ شهرياً</span>
                        </div>
                    </div>

                    {{-- Features List --}}
                    <div class="mb-10">
                        <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            مميزات الباقة:
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($plan['features'] as $feature)
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                                    <div class="w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <span class="text-gray-700 font-medium text-sm">{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Action Form --}}
                    <form action="{{ route('client.subscription.store') }}" method="POST">
                        @csrf
                        
                        {{-- 
                            🔴 THIS WAS THE PROBLEM BEFORE 
                            We use $planKey (the variable) instead of $plan['key'] (which doesn't exist)
                        --}}
                        <input type="hidden" name="plan_key" value="{{ $planKey }}">

                        <div class="flex flex-col gap-4">
                            <button type="submit" class="w-full bg-slate-900 text-white font-bold text-lg py-4 rounded-xl shadow-lg hover:bg-indigo-600 hover:shadow-indigo-500/30 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                                <span>تأكيد والبدء فوراً</span>
                                <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </button>
                            
                            <a href="{{ route('pricing.index') }}" class="w-full bg-white border border-gray-200 text-gray-500 font-bold py-4 rounded-xl hover:bg-gray-50 hover:text-gray-700 transition-colors text-center">
                                تراجع واختيار خطة أخرى
                            </a>
                        </div>
                        
                        <p class="mt-4 text-center text-xs text-gray-400">
                            بالنقر على "تأكيد"، أنت توافق على شروط الخدمة وسياسة الخصوصية.
                        </p>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>