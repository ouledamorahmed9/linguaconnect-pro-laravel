@extends('layouts.public')

@section('title', 'الأسعار - أكاديمية القلم')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                باقات تناسب احتياجاتك
            </h2>
            <p class="mt-4 text-xl text-gray-500">
                اختر الباقة التي تناسب أهدافك التعليمية وميزانيتك
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                <div class="px-6 py-8">
                    <h3 class="text-2xl font-bold text-center text-gray-900">الباقة الأساسية</h3>
                    <div class="mt-4 text-center">
                        <span class="text-4xl font-extrabold text-primary-600">$49</span>
                        <span class="text-gray-500">/ شهرياً</span>
                    </div>
                    <ul class="mt-8 space-y-4">
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>4 حصص شهرياً</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>مدة الحصة 45 دقيقة</span>
                        </li>
                    </ul>
                </div>
                <div class="px-6 py-8 bg-gray-50">
                    <a href="{{ route('register') }}" class="block w-full text-center bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-4 rounded-md transition duration-300">
                        ابدأ الآن
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300 border-2 border-primary-500 relative">
                <div class="absolute top-0 right-0 bg-primary-500 text-white px-4 py-1 rounded-bl-lg text-sm font-bold">
                    الأكثر طلباً
                </div>
                <div class="px-6 py-8">
                    <h3 class="text-2xl font-bold text-center text-gray-900">الباقة القياسية</h3>
                    <div class="mt-4 text-center">
                        <span class="text-4xl font-extrabold text-primary-600">$89</span>
                        <span class="text-gray-500">/ شهرياً</span>
                    </div>
                    <ul class="mt-8 space-y-4">
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>8 حصص شهرياً</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>مدة الحصة 60 دقيقة</span>
                        </li>
                    </ul>
                </div>
                <div class="px-6 py-8 bg-gray-50">
                    <a href="{{ route('register') }}" class="block w-full text-center bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-4 rounded-md transition duration-300">
                        ابدأ الآن
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                <div class="px-6 py-8">
                    <h3 class="text-2xl font-bold text-center text-gray-900">الباقة المتميزة</h3>
                    <div class="mt-4 text-center">
                        <span class="text-4xl font-extrabold text-primary-600">$149</span>
                        <span class="text-gray-500">/ شهرياً</span>
                    </div>
                    <ul class="mt-8 space-y-4">
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>12 حصة شهرياً</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>دعم على مدار الساعة</span>
                        </li>
                    </ul>
                </div>
                <div class="px-6 py-8 bg-gray-50">
                    <a href="{{ route('register') }}" class="block w-full text-center bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-4 rounded-md transition duration-300">
                        تواصل معنا
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection