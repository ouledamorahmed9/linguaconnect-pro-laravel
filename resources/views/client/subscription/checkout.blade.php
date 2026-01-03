<x-public-layout>
    <div class="bg-slate-50 min-h-screen py-24" dir="rtl">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-12">
                <h1 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">إتمام الاشتراك</h1>
                <p class="mt-4 text-lg text-slate-600">أنت على بعد خطوة واحدة من بدء رحلتك التعليمية.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- Order Summary (Left/Main in RTL) -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Plan Details Card -->
                    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden relative">
                        <div class="bg-indigo-600 px-6 py-4 border-b border-indigo-500">
                            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                تفاصيل الباقة المختارة
                            </h2>
                        </div>

                        <div class="p-8">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h3 class="text-2xl font-bold text-slate-900">{{ $plan['name'] }}</h3>
                                    <p class="text-indigo-600 font-medium">{{ $plan['sub_name'] }}</p>
                                </div>
                                <span
                                    class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-sm font-bold border border-indigo-100">
                                    {{ $plan['lessons_count'] }} حصص / شهر
                                </span>
                            </div>

                            <div class="space-y-4 mb-8">
                                <p class="text-slate-600 leading-relaxed">{{ $plan['description'] }}</p>
                                <ul class="space-y-3">
                                    @foreach($plan['features'] as $feature)
                                        <li class="flex items-center text-slate-600">
                                            <svg class="w-5 h-5 text-green-500 ml-3 flex-shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="border-t border-slate-100 pt-6">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-slate-600">سعر الباقة الشهري</span>
                                    <span class="text-slate-900 font-bold text-lg">{{ $plan['price'] }}
                                        {{ $currency }}</span>
                                </div>
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-green-600 font-medium flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                            </path>
                                        </svg>
                                        خصم التجربة المجانية (7 أيام)
                                    </span>
                                    <span class="text-green-600 font-bold text-lg">-{{ $plan['price'] }}
                                        {{ $currency }}</span>
                                </div>
                                <div class="border-t border-slate-100 pt-4 flex justify-between items-center">
                                    <span class="text-xl font-black text-slate-900">المجموع المطلوب اليوم</span>
                                    <span class="text-3xl font-black text-indigo-600">0.00 {{ $currency }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Signals -->
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100">
                            <div
                                class="mx-auto w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600 mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-xs font-bold text-slate-700">دفع آمن ومحمي</p>
                        </div>
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100">
                            <div
                                class="mx-auto w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-xs font-bold text-slate-700">لا خصم اليوم</p>
                        </div>
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100">
                            <div
                                class="mx-auto w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-xs font-bold text-slate-700">دعم فني 24/7</p>
                        </div>
                    </div>
                </div>

                <!-- Action Column -->
                <div class="lg:col-span-5">
                    <div class="bg-white rounded-2xl shadow-xl border-2 border-indigo-600 p-8 sticky top-24">
                        <div class="text-center mb-8">
                            <div
                                class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-full mb-4">
                                <span class="text-3xl">🚀</span>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900">ابدأ فترتك التجريبية الآن</h3>
                            <p class="text-slate-500 mt-2 text-sm">لن يتم خصم أي مبلغ منك اليوم. استمتع بتجربة كاملة
                                لمدة 7 أيام مجاناً.</p>
                        </div>

                        <form action="{{ route('client.subscription.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="plan" value="{{ $planKey }}">

                            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                                <p class="text-sm text-slate-600 font-medium mb-2">تفاصيل الحساب:</p>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-lg">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="target_language" class="block text-sm font-bold text-slate-700">اللغة التي
                                    تريد دراستها</label>
                                <select name="target_language" id="target_language" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-medium text-slate-700">
                                    <option value="" disabled selected>اختر اللغة...</option>
                                    @if(isset($studySubjects) && $studySubjects->count() > 0)
                                        @foreach($studySubjects as $subject)
                                            <option value="{{ $subject->name }}">{{ $subject->name }}</option>
                                        @endforeach
                                    @else
                                        <!-- Fallback if no subjects found -->
                                        <option value="English">English</option>
                                        <option value="French">Français</option>
                                        <option value="German">Deutsch</option>
                                    @endif
                                </select>
                            </div>

                            <button type="submit"
                                class="w-full bg-indigo-600 text-white rounded-xl py-4 font-bold text-lg shadow-lg hover:bg-indigo-700 hover:shadow-xl transition-all duration-300 flex items-center justify-center gap-2 group">
                                <span>تأكيد الاشتراك مجاناً</span>
                                <svg class="w-5 h-5 rtl:rotate-180 transform group-hover:translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </button>

                            <p class="text-xs text-center text-slate-400">
                                بالضغط على "تأكيد"، أنت توافق على شروط الخدمة وسياسة الخصوصية.
                            </p>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-public-layout>