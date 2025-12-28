<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('تأكيد الاشتراك وبدء التجربة') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                        <div class="p-8 text-gray-900" dir="rtl">
                            <div class="flex items-center justify-between mb-8">
                                <h3 class="text-2xl font-black text-slate-800 flex items-center gap-2">
                                    <span class="bg-indigo-100 p-2 rounded-lg text-indigo-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </span>
                                    ملخص الطلب
                                </h3>
                                <span class="text-xs font-bold text-gray-400">MAJDOUB TUTORING LLC</span>
                            </div>

                            <div class="bg-slate-50 rounded-xl p-6 mb-6 border border-slate-200">
                                <div class="flex justify-between items-center mb-4 pb-4 border-b border-slate-200">
                                    <div>
                                        <span class="block text-sm text-gray-500">الباقة المختارة</span>
                                        <span class="text-lg font-bold text-gray-900">{{ $plan['name'] }}</span>
                                        <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full mr-2">{{ $plan['sub_name'] }}</span>
                                    </div>
                                    <div class="text-left">
                                        <span class="block text-sm text-gray-500">السعر الشهري</span>
                                        <span class="text-lg font-bold text-gray-900">{{ $plan['price'] }}$</span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600 font-medium">عدد الحصص</span>
                                    <span class="font-bold">{{ $plan['lessons_count'] }} حصص</span>
                                </div>
                            </div>

                            <div class="space-y-3 mb-8 border-t border-gray-100 pt-6">
                                <div class="flex justify-between text-gray-600">
                                    <span>سعر الباقة</span>
                                    <span>{{ $plan['price'] }}.00$</span>
                                </div>
                                <div class="flex justify-between text-green-600 font-bold bg-green-50 p-2 rounded-lg">
                                    <span>خصم الفترة التجريبية (7 أيام)</span>
                                    <span>-{{ $plan['price'] }}.00$</span>
                                </div>
                                <div class="flex justify-between items-center pt-4 text-gray-900">
                                    <span class="text-lg font-bold">المبلغ المستحق اليوم:</span>
                                    <span class="text-4xl font-black text-indigo-600">0.00$</span>
                                </div>
                            </div>
                            
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex gap-3 items-start">
                                <svg class="w-6 h-6 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-sm text-blue-700">
                                    لن يتم خصم أي مبلغ اليوم. سيبدأ الاشتراك الفعلي بعد انتهاء الفترة التجريبية (7 أيام). يمكنك الإلغاء في أي وقت.
                                </p>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 h-full flex flex-col justify-between">
                        <div class="p-6">
                            <h3 class="font-bold text-gray-800 mb-4">إتمام التسجيل</h3>
                            
                            <div class="space-y-4 mb-6">
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <div class="w-5 h-5 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold">✓</div>
                                    <span>ضمان استرداد الأموال</span>
                                </div>
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <div class="w-5 h-5 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold">✓</div>
                                    <span>دخول فوري للمنصة</span>
                                </div>
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <div class="w-5 h-5 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold">✓</div>
                                    <span>إلغاء في أي وقت</span>
                                </div>
                            </div>

                            <p class="text-xs text-gray-400 leading-relaxed mb-4 text-center">
                                بالضغط على الزر أدناه، أنت توافق على شروط الخدمة وسياسة الخصوصية لشركة 
                                <strong>MAJDOUB TUTORING LLC</strong>.
                            </p>
                        </div>
                        
                        <div class="p-6 bg-gray-50 border-t">
                            <form method="POST" action="{{ route('client.subscription.store') }}">
                                @csrf
                                <input type="hidden" name="plan" value="{{ $plan['key'] }}">
                                
                                <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg hover:shadow-indigo-500/30 flex justify-center items-center gap-2 group">
                                    ابدأ فترتك المجانية
                                    <svg class="w-5 h-5 rtl:rotate-180 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                                
                                <a href="{{ route('pricing.index') }}" class="block text-center mt-4 text-sm text-gray-500 hover:text-gray-800">
                                    اختيار باقة أخرى
                                </a>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>