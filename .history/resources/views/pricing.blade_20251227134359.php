<x-layouts.public>
    <div class="bg-gray-50 min-h-screen py-24" dir="rtl">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-base font-semibold text-indigo-600 tracking-wide uppercase">خطط الأسعار</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                باقات تناسب جميع المستويات
            </p>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                اختر الباقة التي تناسب احتياجاتك التعليمية.
                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 mt-2">
                    الأسعار مخصصة لـ: {{ $country }} 🌍
                </span>
            </p>
        </div>

        <div class="mt-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-12 lg:space-y-0 lg:grid lg:grid-cols-3 lg:gap-8">
                
                <div class="relative p-8 bg-white border border-gray-200 rounded-2xl shadow-sm flex flex-col hover:shadow-lg transition-shadow duration-300">
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900">الباقة الأساسية</h3>
                        <p class="mt-4 flex items-baseline text-gray-900">
                            <span class="text-5xl font-extrabold tracking-tight">{{ $plans['basic']['price'] }}</span>
                            <span class="ml-1 text-xl font-semibold text-gray-500">{{ $currency }}</span>
                            <span class="mr-1 text-gray-500 text-sm">/ {{ $plans['basic']['period'] }}</span>
                        </p>
                        <p class="mt-6 text-gray-500">خيار ممتاز للمبتدئين أو للمراجعة الخفيفة.</p>

                        <ul role="list" class="mt-6 space-y-6">
                            <li class="flex">
                                <svg class="flex-shrink-0 w-5 h-5 text-green-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-500">4 حصص شهرياً</span>
                            </li>
                            <li class="flex">
                                <svg class="flex-shrink-0 w-5 h-5 text-green-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-500">مدة الحصة 45 دقيقة</span>
                            </li>
                            <li class="flex">
                                <svg class="flex-shrink-0 w-5 h-5 text-green-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-500">منهج دراسي شامل</span>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}" class="mt-8 block w-full py-3 px-6 border border-indigo-600 rounded-md text-center font-medium text-indigo-600 hover:bg-indigo-50 transition">
                        اشترك الآن
                    </a>
                </div>

                <div class="relative p-8 bg-white border border-gray-200 rounded-2xl shadow-xl flex flex-col ring-2 ring-indigo-600 transform scale-105 z-10">
                    <div class="absolute top-0 inset-x-0 -mt-3 flex justify-center">
                        <span class="inline-flex rounded-full bg-indigo-600 px-4 py-1 text-xs font-bold tracking-wider uppercase text-white shadow-sm">
                            الأكثر طلباً
                        </span>
                    </div>

                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900">الباقة المتقدمة</h3>
                        <p class="mt-4 flex items-baseline text-gray-900">
                            <span class="text-5xl font-extrabold tracking-tight">{{ $plans['advanced']['price'] }}</span>
                            <span class="ml-1 text-xl font-semibold text-gray-500">{{ $currency }}</span>
                            <span class="mr-1 text-gray-500 text-sm">/ {{ $plans['advanced']['period'] }}</span>
                        </p>
                        <p class="mt-6 text-gray-500">للطلاب الجادين في تحسين مستواهم بسرعة.</p>

                        <ul role="list" class="mt-6 space-y-6">
                            <li class="flex">
                                <svg class="flex-shrink-0 w-5 h-5 text-indigo-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-500">8 حصص شهرياً</span>
                            </li>
                            <li class="flex">
                                <svg class="flex-shrink-0 w-5 h-5 text-indigo-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-500">مدة الحصة 60 دقيقة</span>
                            </li>
                            <li class="flex">
                                <svg class="flex-shrink-0 w-5 h-5 text-indigo-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-500">تقارير أداء دورية</span>
                            </li>
                            <li class="flex">
                                <svg class="flex-shrink-0 w-5 h-5 text-indigo-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-500">دعم واتساب خاص</span>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}" class="mt-8 block w-full py-3 px-6 bg-indigo-600 border border-transparent rounded-md text-center font-medium text-white hover:bg-indigo-700 transition">
                        اشترك الآن
                    </a>
                </div>

                <div class="relative p-8 bg-white border border-gray-200 rounded-2xl shadow-sm flex flex-col hover:shadow-lg transition-shadow duration-300">
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900">الباقة المكثفة</h3>
                        <p class="mt-4 flex items-baseline text-gray-900">
                            <span class="text-5xl font-extrabold tracking-tight">{{ $plans['intensive']['price'] }}</span>
                            <span class="ml-1 text-xl font-semibold text-gray-500">{{ $currency }}</span>
                            <span class="mr-1 text-gray-500 text-sm">/ {{ $plans['intensive']['period'] }}</span>
                        </p>
                        <p class="mt-6 text-gray-500">للتجهيز للامتحانات أو النقلة النوعية في المستوى.</p>

                        <ul role="list" class="mt-6 space-y-6">
                            <li class="flex">
                                <svg class="flex-shrink-0 w-5 h-5 text-green-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-500">12 حصة شهرياً</span>
                            </li>
                            <li class="flex">
                                <svg class="flex-shrink-0 w-5 h-5 text-green-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-500">مدة الحصة 60 دقيقة</span>
                            </li>
                            <li class="flex">
                                <svg class="flex-shrink-0 w-5 h-5 text-green-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-500">خطة دراسية مخصصة</span>
                            </li>
                            <li class="flex">
                                <svg class="flex-shrink-0 w-5 h-5 text-green-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-500">أولوية في الحجز</span>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}" class="mt-8 block w-full py-3 px-6 border border-indigo-600 rounded-md text-center font-medium text-indigo-600 hover:bg-indigo-50 transition">
                        اشترك الآن
                    </a>
                </div>

            </div>
        </div>
        
        <div class="max-w-2xl mx-auto px-4 mt-16 sm:px-6 lg:px-8 text-center">
             <div class="flex items-center justify-center space-x-4 rtl:space-x-reverse text-gray-500 text-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>ضمان استرداد الأموال 7 أيام</span>
                </div>
                <span class="text-gray-300">|</span>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <span>دفع آمن 100%</span>
                </div>
             </div>
        </div>
    </div>
</x-layouts.public>