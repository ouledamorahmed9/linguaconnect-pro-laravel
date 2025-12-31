<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('تعيين باقة جديدة للعميل: ') }} <span class="text-indigo-600">{{ $client->name }}</span>
            </h2>
            <a href="{{ route('admin.clients.edit', $client) }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium transition">
                &larr; رجوع للملف الشخصي
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-8 text-gray-900" dir="rtl">
                    
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r" role="alert">
                            <p class="font-bold">يرجى تصحيح الأخطاء التالية:</p>
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.clients.subscriptions.store', $client) }}">
                        @csrf
                        
                        <div class="mb-10 bg-indigo-50 p-6 rounded-2xl border border-indigo-100 max-w-lg">
                            <label for="starts_at" class="block font-bold text-indigo-900 mb-2 text-lg">تاريخ بدء الاشتراك</label>
                            <input type="date" 
                                   id="starts_at" 
                                   name="starts_at" 
                                   value="{{ date('Y-m-d') }}" 
                                   class="w-full border-indigo-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-lg py-2 px-4 shadow-sm"
                                   required>
                            <p class="text-sm text-indigo-500 mt-2 font-medium">ℹ️ سيتم حساب تاريخ الانتهاء تلقائياً (بعد 30 يوم).</p>
                        </div>

                        <h3 class="text-xl font-bold mb-6 text-gray-800 flex items-center gap-2">
                            <span class="bg-gray-200 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center text-sm">2</span>
                            اختر الباقة المناسبة:
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                            @foreach($plans as $key => $plan)
                                <label class="relative cursor-pointer group h-full">
                                    <input type="radio" name="plan_type" value="{{ $key }}" class="peer sr-only" required>
                                    
                                    <div class="h-full flex flex-col bg-white border-2 border-gray-200 rounded-2xl p-5 hover:border-indigo-300 hover:shadow-md transition-all duration-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:shadow-xl peer-checked:scale-[1.02]">
                                        <div class="absolute top-4 left-4 text-indigo-600 opacity-0 peer-checked:opacity-100 transition-opacity transform scale-0 peer-checked:scale-100 duration-200">
                                            <div class="bg-white rounded-full p-1 shadow-sm">
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <h4 class="font-bold text-lg text-gray-900">{{ $plan['name'] }}</h4>
                                            <span class="inline-block mt-1 px-2 py-0.5 text-xs font-bold rounded bg-{{ $plan['color'] }}-100 text-{{ $plan['color'] }}-700">
                                                {{ $plan['sub_name'] }}
                                            </span>
                                        </div>
                                        
                                        <div class="mb-4 text-center py-2 border-t border-b border-gray-100 border-dashed">
                                            <div class="text-3xl font-black text-gray-800">
                                                {{ $plan['price'] }}<span class="text-lg font-medium text-gray-500">$</span>
                                            </div>
                                            <div class="text-xs text-gray-500 font-semibold">
                                                {{ $plan['lessons_count'] }} حصص / شهر
                                            </div>
                                        </div>

                                        <ul class="text-xs text-gray-500 space-y-2 mt-auto">
                                            @foreach(array_slice($plan['features'], 0, 3) as $feature)
                                                <li class="flex items-start">
                                                    <svg class="w-4 h-4 text-green-500 ml-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    {{ $feature }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-end border-t pt-6 gap-4">
                            <a href="{{ route('admin.clients.edit', $client) }}" class="px-6 py-3 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-900 font-bold transition">
                                إلغاء
                            </a>
                            <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg hover:shadow-indigo-500/30 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                حفظ وتفعيل الاشتراك
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>