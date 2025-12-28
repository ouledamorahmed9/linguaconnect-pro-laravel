<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('إتمام الاشتراك') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                        <div class="p-8 text-gray-900" dir="rtl">
                            <h3 class="text-2xl font-black mb-6 text-slate-800 flex items-center gap-2">
                                <span class="bg-indigo-100 p-2 rounded-lg text-indigo-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                </span>
                                تفاصيل الفاتورة
                            </h3>

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
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 font-medium">عدد الحصص</span>
                                    <span class="font-bold">{{ $plan['lessons_count'] }} حصص</span>
                                </div>
                            </div>

                            <div class="space-y-4 mb-8">
                                <h4 class="font-bold text-gray-700">المميزات المشمولة:</h4>
                                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($plan['features'] as $feature)
                                        <li class="flex items-center text-sm text-gray-600">
                                            <svg class="w-5 h-5 text-green-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="flex justify-between items-center bg-gray-900 text-white p-6 rounded-xl shadow-lg">
                                <span class="text-lg font-bold">المجموع الكلي:</span>
                                <span class="text-3xl font-black">{{ $plan['price'] }}$</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 h-full flex flex-col justify-between">
                        <div class="p-6">
                            <h3 class="font-bold text-gray-800 mb-4">طريقة الدفع</h3>
                            
                            <div class="space-y-3 mb-6">
                                <label class="flex items-center justify-between p-3 border rounded-xl cursor-pointer bg-indigo-50 border-indigo-200">
                                    <div class="flex items-center">
                                        <input type="radio" name="payment" checked class="text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                        <span class="mr-3 font-semibold text-indigo-900">بطاقة بنكية</span>
                                    </div>
                                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </label>
                            </div>

                            <p class="text-xs text-gray-500 leading-relaxed mb-4">
                                بالضغط على زر التأكيد، أنت توافق على شروط الخدمة وسياسة الخصوصية الخاصة بأكاديمية كمون.
                            </p>
                        </div>
                        
                        <div class="p-6 bg-gray-50 border-t">
                            <form method="POST" action="{{ route('client.subscription.store') }}">
                                @csrf
                                <input type="hidden" name="plan" value="{{ $plan['key'] }}">
                                
                                <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg hover:shadow-indigo-500/30 flex justify-center items-center gap-2">
                                    تأكيد ودفع
                                    <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                                
                                <a href="{{ route('pricing.index') }}" class="block text-center mt-4 text-sm text-gray-500 hover:text-gray-800">
                                    تغيير الباقة
                                </a>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>