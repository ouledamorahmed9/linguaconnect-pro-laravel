<x-public-layout>
    <div class="py-20 bg-gray-50 min-h-screen" dir="rtl">
        <div class="container mx-auto px-6 max-w-4xl">
            <div class="text-center mb-16">
                <h1 class="text-4xl font-black text-gray-900 mb-4">اتصل بنا</h1>
                <p class="text-gray-500 text-lg">فريقنا متاح لمساعدتك طوال أيام الأسبوع</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-lg border border-indigo-100 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-2 h-full bg-indigo-600"></div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">بيانات الشركة</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="bg-indigo-50 p-3 rounded-lg text-indigo-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div class="text-left" dir="ltr">
                                <h4 class="font-bold text-gray-900">Registered Address (USA)</h4>
                                <p class="text-gray-500 text-sm mt-1">
                                    <strong>MAJDOUB TUTORING LLC</strong><br>
                                    30 N Gould St Ste N<br>
                                    Sheridan, WY 82801
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="bg-indigo-50 p-3 rounded-lg text-indigo-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">البريد الإلكتروني</h4>
                                <a href="mailto:support@kamounacademy.com" class="text-indigo-600 font-bold hover:underline">support@kamounacademy.com</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">أرسل لنا رسالة</h3>
                    <form>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">الاسم الكامل</label>
                            <input type="text" class="w-full border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">البريد الإلكتروني</label>
                            <input type="email" class="w-full border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">الرسالة</label>
                            <textarea rows="3" class="w-full border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                        <button type="button" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition">إرسال</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>