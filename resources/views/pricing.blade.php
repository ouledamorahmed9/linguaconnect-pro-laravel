@extends('layouts.public')

@section('title', 'الأسعار - وصلة تعليم')

@section('content')
    <!-- Page Header Section -->
    <section class="bg-gray-50 pt-32 pb-16">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800">باقات شفافة تناسب الجميع</h1>
            <p class="text-lg text-gray-600 mt-4 max-w-3xl mx-auto">
                اختر الباقة التي تناسب جدول طفلك وأهدافه التعليمية. لا توجد رسوم خفية أو عقود طويلة الأمد.
            </p>
        </div>
    </section>

    <!-- Pricing Grid Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-5xl mx-auto items-start">
                
                        <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-8 flex flex-col h-full">
                            <h3 class="text-2xl font-bold text-gray-800">الباقة الأساسية</h3>
                            <p class="text-gray-500 mt-2">بداية مثالية ومنتظمة.</p>
                            <div class="my-6">
                                <span class="text-5xl font-extrabold text-gray-900">120</span>
                                <span class="text-lg font-medium text-gray-500">د.ت / شهرياً</span>
                            </div>
                            <ul class="space-y-4 text-gray-600 flex-grow">
                                <li class="flex items-center"><svg class="w-5 h-5 text-indigo-500 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>4 حصص فردية (حصتان أسبوعياً)</li>
                                <li class="flex items-center"><svg class="w-5 h-5 text-indigo-500 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>تقارير تقدم بعد كل حصة</li>
                                <li class="flex items-center"><svg class="w-5 h-5 text-indigo-500 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>مرونة في اختيار الأوقات</li>
                            </ul>
                            <a href="#" class="mt-8 block w-full bg-indigo-100 text-indigo-600 text-center font-semibold py-3 rounded-lg hover:bg-indigo-200 transition-colors">اختر الباقة</a>
                        </div>

                        <!-- Plan 2: Popular (Highlighted) -->
                        <div class="bg-indigo-600 text-white rounded-xl shadow-2xl p-8 flex flex-col h-full transform scale-105 relative">
                            <div class="absolute top-0 right-0 -mt-3 mr-3">
                                <span class="bg-indigo-800 text-white text-xs font-bold px-3 py-1 rounded-full">الأكثر شيوعاً</span>
                            </div>
                            <h3 class="text-2xl font-bold">الباقة المتقدمة</h3>
                            <p class="text-indigo-200 mt-2">لتقدم أسرع ونتائج ملحوظة.</p>
                            <div class="my-6">
                                <span class="text-5xl font-extrabold">200</span>
                                <span class="text-lg font-medium text-indigo-200">د.ت / شهرياً</span>
                            </div>
                            <ul class="space-y-4 text-indigo-100 flex-grow">
                                <li class="flex items-center"><svg class="w-5 h-5 text-white mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>8 حصص فردية (حصتان أسبوعياً)</li>
                                <li class="flex items-center"><svg class="w-5 h-5 text-white mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>تقارير تقدم بعد كل حصة</li>
                                <li class="flex items-center"><svg class="w-5 h-5 text-white mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>أولوية في اختيار الأوقات</li>
                                <li class="flex items-center"><svg class="w-5 h-5 text-white mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>اختبار تحديد مستوى مجاني</li>
                            </ul>
                            <a href="#" class="mt-8 block w-full bg-white text-indigo-600 text-center font-semibold py-3 rounded-lg hover:bg-gray-200 transition-colors">اختر الباقة</a>
                        </div>

                        <!-- Plan 3: Premium -->
                        <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-8 flex flex-col h-full">
                            <h3 class="text-2xl font-bold text-gray-800">الباقة المكثفة</h3>
                            <p class="text-gray-500 mt-2">لإتقان اللغة في أسرع وقت.</p>
                            <div class="my-6">
                                <span class="text-5xl font-extrabold text-gray-900">280</span>
                                <span class="text-lg font-medium text-gray-500">د.ت / شهرياً</span>
                            </div>
                            <ul class="space-y-4 text-gray-600 flex-grow">
                                <li class="flex items-center"><svg class="w-5 h-5 text-indigo-500 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>12 حصة فردية (3 حصص أسبوعياً)</li>
                                <li class="flex items-center"><svg class="w-5 h-5 text-indigo-500 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>كافة مزايا الباقة المتقدمة</li>
                                <li class="flex items-center"><svg class="w-5 h-5 text-indigo-500 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>دعم مباشر عبر WhatsApp</li>
                            </ul>
                            <a href="#" class="mt-8 block w-full bg-indigo-100 text-indigo-600 text-center font-semibold py-3 rounded-lg hover:bg-indigo-200 transition-colors">اختر الباقة</a>
                        </div>
                    </div>
                </div>
            </section>
@endsection

