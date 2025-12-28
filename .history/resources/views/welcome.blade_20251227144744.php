<x-public-layout>
    {{-- This section is for custom styles specific to this page --}}
    @push('styles')
    <style>
        .hero-bg {
            background-image: linear-gradient(rgba(1, 22, 56, 0.7), rgba(1, 22, 56, 0.7)), url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=2071&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }
        .gradient-text {
            background: linear-gradient(90deg, #a5b4fc, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }
    </style>
    @endpush

    <section class="hero-bg text-white pt-40 pb-20">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-4">
                تعليم لغات يمنحك راحة البال
            </h1>
            <p class="text-lg md:text-xl text-indigo-200 mb-8 max-w-3xl mx-auto">
                نوفر لطفلك أفضل المعلمين ونقوم بكل التنسيق والمتابعة نيابة عنك. ركز على تقدم طفلك، واترك الباقي لنا.
            </p>
            <a 
                href="https://wa.me/21612345678?text=مرحباً%20وصلة%20تعليم،%20أرغب%20في%20حجز%20استشارة%20تعليمية%20مجانية." 
                target="_blank" 
                rel="noopener noreferrer" 
                class="bg-white text-indigo-600 font-bold px-10 py-4 rounded-lg hover:bg-gray-200 transition duration-300 text-lg shadow-xl inline-block"
            >
                احجز استشارتك المجانية
            </a>
        </div>
    </section>
    
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                الرحلة التعليمية في <span class="gradient-text">3 خطوات</span> بسيطة
            </h2>
            <p class="text-gray-600 mb-12 max-w-2xl mx-auto">
                من أول مكالمة وحتى إتقان اللغة، نحن معك في كل خطوة.
            </p>
            <div class="grid md:grid-cols-3 gap-12">
                <div class="bg-gray-50 p-8 rounded-xl shadow-lg transform hover:-translate-y-2 transition-transform duration-300">
                    <div class="bg-indigo-100 text-indigo-600 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">تواصل واستشارة</h3>
                    <p class="text-gray-600">تحدث مع منسقنا التعليمي لفهم احتياجات طفلك وتحديد أهدافه بدقة.</p>
                </div>
                <div class="bg-gray-50 p-8 rounded-xl shadow-lg transform hover:-translate-y-2 transition-transform duration-300">
                    <div class="bg-indigo-100 text-indigo-600 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">خطة وجدولة مخصصة</h3>
                    <p class="text-gray-600">نقوم باختيار المعلم الأنسب ووضع جدول مرن يناسبكم تماماً.</p>
                </div>
                <div class="bg-gray-50 p-8 rounded-xl shadow-lg transform hover:-translate-y-2 transition-transform duration-300">
                    <div class="bg-indigo-100 text-indigo-600 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">ابدأ التعلم والتقدم</h3>
                    <p class="text-gray-600">تنطلق الدروس التفاعلية وتستلم تقارير دورية لمتابعة تطور مستوى طفلك.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800">لماذا يثق بنا أولياء الأمور؟</h2>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto">لأننا نقدم أكثر من مجرد دروس، نحن نقدم نظاماً متكاملاً يضمن راحة بالكم ونجاح أطفالكم.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 text-indigo-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A5.975 5.975 0 0112 13a5.975 5.975 0 013 1.803M15 21a9 9 0 00-3-5.197m-2.25-2.25A5.965 5.965 0 0112 13a5.965 5.965 0 012.25 1.551"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">منسق شخصي</h3>
                    <p class="text-gray-600 text-sm">لكل عائلة منسق خاص بها يتولى كل التفاصيل الإدارية والجدولة.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 text-indigo-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">معلمون معتمدون</h3>
                    <p class="text-gray-600 text-sm">نختار معلمينا بعناية فائقة لضمان أعلى مستوى من الخبرة والكفاءة.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 text-indigo-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">تقارير تقدم دورية</h3>
                    <p class="text-gray-600 text-sm">بعد كل حصة، تتلقى تقريراً مفصلاً بملاحظات المعلم حول أداء طفلك.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 text-indigo-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">بيئة آمنة ومراقبة</h3>
                    <p class="text-gray-600 text-sm">نظامنا يضمن بيئة تعلم آمنة واختبارات موثوقة لقياس التقدم الفعلي.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800">ماذا يقول عملاؤنا عنا</h2>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto">نفخر بثقة عملائنا ونسعد بمشاركة قصص نجاح أبنائهم.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-8 rounded-lg shadow-lg">
                    <p class="text-gray-600 mb-6 italic">"لم أكن أتخيل أن تعلم اللغة الفرنسية يمكن أن يكون بهذه السهولة والمتعة لابني. المتابعة من المنسقة رائعة والمعلمة ممتازة. شكراً لكم!"</p>
                    <div class="flex items-center">
                        <img class="w-12 h-12 rounded-full mr-4 object-cover" src="https://images.pexels.com/photos/1239291/pexels-photo-1239291.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Avatar of Parent">
                        <div>
                            <p class="font-bold text-gray-800">علياء محمد</p>
                            <p class="text-sm text-gray-500">والدة الطالب يوسف</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 p-8 rounded-lg shadow-lg">
                    <p class="text-gray-600 mb-6 italic">"أفضل ما في المنصة هو راحة البال. لا أشغل نفسي أبداً بالجدولة أو التنسيق. كل شيء يتم باحترافية عالية، وأرى نتائج حقيقية في مستوى ابنتي."</p>
                    <div class="flex items-center">
                        <img class="w-12 h-12 rounded-full mr-4 object-cover" src="https://images.pexels.com/photos/91227/pexels-photo-91227.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Avatar of Parent">
                        <div>
                            <p class="font-bold text-gray-800">أحمد منصور</p>
                            <p class="text-sm text-gray-500">والد الطالبة فاطمة</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 p-8 rounded-lg shadow-lg">
                    <p class="text-gray-600 mb-6 italic">"كنت مترددة من التعلم عن بعد، لكن تجربة (أكاديمية كمـــــون) غيرت رأيي تماماً. النظام منظم، والمعلم صبور ومتمكن. أوصي بهم بشدة."</p>
                    <div class="flex items-center">
                        <img class="w-12 h-12 rounded-full mr-4 object-cover" src="https://images.pexels.com/photos/762020/pexels-photo-762020.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Avatar of Parent">
                        <div>
                            <p class="font-bold text-gray-800">هند خالد</p>
                            <p class="text-sm text-gray-500">والدة الطالب عمر</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800">تعرف على نخبة من معلمينا</h2>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto">نفخر بفريقنا من المعلمين الخبراء الذين يتمتعون بالشغف والإلهام.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden text-center transform hover:-translate-y-2 transition-transform duration-300">
                    <div class="h-64 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=1888&auto=format&fit=crop')"></div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">نورة عبدالله</h3>
                        <p class="text-indigo-600 font-semibold">خبيرة لغة إنجليزية للأطفال</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden text-center transform hover:-translate-y-2 transition-transform duration-300">
                    <div class="h-64 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=1887&auto=format&fit=crop')"></div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">كريم محمود</h3>
                        <p class="text-indigo-600 font-semibold">متخصص في اللغة الفرنسية</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden text-center transform hover:-translate-y-2 transition-transform duration-300">
                    <div class="h-64 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1543269865-cbf427effbad?q=80&w=2070&auto=format&fit=crop')"></div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">سارة علي</h3>
                        <p class="text-indigo-600 font-semibold">محترفة التحضير لاختبارات IELTS</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="bg-indigo-600">
        <div class="container mx-auto px-6 py-20 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">هل أنت مستعد لبدء رحلة طفلك التعليمية؟</h2>
            <p class="text-indigo-200 mb-8 max-w-2xl mx-auto">
                تواصل معنا اليوم للحصول على استشارة مجانية واكتشف كيف يمكننا مساعدة طفلك على التحدث بثقة.
            </p>
            <a href="https://wa.me/21612345678?text=مرحباً%20وصلة%20تعليم،%20أرغب%20في%20حجز%20استشارة%20تعليمية%20مجانية." target="_blank" rel="noopener noreferrer" class="bg-white text-indigo-600 font-bold px-10 py-4 rounded-lg hover:bg-gray-200 transition duration-300 text-lg shadow-xl inline-block">
                ابدأ الآن
            </a>
        </div>
    </section>
</x-public-layout>