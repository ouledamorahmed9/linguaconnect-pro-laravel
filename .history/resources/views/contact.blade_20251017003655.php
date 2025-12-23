@extends('layouts.public')

@section('title', 'اتصل بنا - وصلة تعليم')

@section('content')
    <!-- Page Header Section -->
    <section class="bg-gray-50 pt-32 pb-16">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800">نحن هنا للمساعدة</h1>
            <p class="text-lg text-gray-600 mt-4 max-w-3xl mx-auto">
                هل لديك سؤال؟ هل ترغب في معرفة المزيد؟ لا تتردد في التواصل معنا.
            </p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="lg:flex lg:items-start lg:gap-12">
                <!-- Contact Info -->
                <div class="lg:w-1/3 mb-12 lg:mb-0">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">معلومات التواصل</h2>
                    
                    <div class="space-y-6">
                        <!-- WhatsApp -->
                        <div class="flex items-start">
                            <div class="bg-indigo-100 text-indigo-600 rounded-lg p-3 shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.149-.173.198-.297.297-.495.099-.198.05-.371-.025-.521-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            </div>
                            <div class="ms-4">
                                <h3 class="text-lg font-semibold text-gray-800">تواصل فوري عبر WhatsApp</h3>
                                <p class="text-gray-500 text-sm">أسرع طريقة للتحدث مع منسق تعليمي.</p>
                                <a href="https://wa.me/21612345678" target="_blank" rel="noopener noreferrer" class="text-indigo-600 font-semibold text-sm mt-1 inline-block">ابدأ المحادثة</a>
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="flex items-start">
                            <div class="bg-indigo-100 text-indigo-600 rounded-lg p-3 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="ms-4">
                                <h3 class="text-lg font-semibold text-gray-800">أرسل لنا عبر البريد الإلكتروني</h3>
                                <p class="text-gray-500 text-sm">للاستفسارات العامة والمقترحات.</p>
                                <a href="mailto:contact@linguaconnect.com" class="text-indigo-600 font-semibold text-sm mt-1 inline-block">contact@linguaconnect.com</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="lg:w-2/3 bg-gray-50 p-8 rounded-xl shadow-lg">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">أو اترك رسالة</h2>
                    <form action="#" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">الاسم الكامل</label>
                            <input type="text" name="name" id="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                            <input type="email" name="email" id="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">رسالتك</label>
                            <textarea name="message" id="message" rows="5" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-indigo-700 transition-colors duration-300">
                                إرسال الرسالة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

