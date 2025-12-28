<x-public-layout>
    {{-- Custom Styles for the Hero Section --}}
    @push('styles')
    <style>
        .hero-bg {
            /* Professional Image: Student with laptop/books in a modern library/cafe setting */
            background-image: 
                linear-gradient(to left, rgba(30, 27, 75, 0.9), rgba(49, 46, 129, 0.7)),
                url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed; /* Parallax effect for modern feel */
            position: relative;
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
    @endpush

    <section class="hero-bg text-white relative min-h-[600px] flex items-center">
        <div class="absolute inset-0 bg-opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>

        <div class="container mx-auto px-6 py-20 relative z-10">
            <div class="max-w-3xl">
                <span class="bg-indigo-500 bg-opacity-20 border border-indigo-400 text-indigo-100 text-sm font-semibold px-4 py-1 rounded-full mb-6 inline-block backdrop-blur-sm">
                    🎁 حصتك الأولى مجانية 100%
                </span>
                
                <h1 class="text-4xl md:text-6xl font-black leading-tight mb-6">
                    جرب قبل أن تشترك <br/>
                    <span class="gradient-text">التعليم كما يجب أن يكون</span>
                </h1>
                
                <p class="text-lg md:text-xl text-gray-200 mb-10 leading-relaxed">
                    ابدأ رحلة تعلم اللغات بحصة تجريبية مجانية. إذا أعجبك الأسلوب، أكمل معنا. 
                    وإذا لم تتوافق مع المعلم؟ نغيره لك فوراً وبدون أي تكلفة. راحتك هي أولويتنا.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <a 
                        href="https://wa.me/21612345678?text=مرحباً، أرغب في حجز حصتي المجانية الأولى!" 
                        target="_blank" 
                        class="bg-yellow-500 text-gray-900 font-bold px-8 py-4 rounded-xl hover:bg-yellow-400 transition-all duration-300 shadow-lg transform hover:-translate-y-1 text-center"
                    >
                        احجز حصتك المجانية
                    </a>
                    <a 
                        href="{{ route('teachers.index') }}" 
                        class="bg-white bg-opacity-10 backdrop-blur-md border border-white text-white font-bold px-8 py-4 rounded-xl hover:bg-white hover:text-indigo-900 transition-all duration-300 text-center"
                    >
                        تصفح المعلمين
                    </a>
                </div>

                <div class="mt-12 flex items-center gap-8 border-t border-indigo-400 border-opacity-30 pt-8">
                    <div>
                        <p class="text-3xl font-bold">مجاناً</p>
                        <p class="text-indigo-200 text-sm">أول حصة</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold">100%</p>
                        <p class="text-indigo-200 text-sm">ضمان الرضا</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold">50+</p>
                        <p class="text-indigo-200 text-sm">معلم خبير</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                كيف نبدأ؟
            </h2>
            <p class="text-gray-600 mb-16 max-w-2xl mx-auto">
                رحلة خالية من المخاطر. جرب، ثم قرر.
            </p>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="group bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="bg-indigo-50 text-indigo-600 rounded-2xl w-20 h-20 flex items-center justify-center mx-auto mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">1. جرب مجاناً</h3>
                    <p class="text-gray-500 leading-relaxed">احجز حصة تجريبية كاملة مع المعلم الذي تختاره دون دفع أي مبلغ.</p>
                </div>
                
                <div class="group bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 relative">
                    <div class="bg-indigo-50 text-indigo-600 rounded-2xl w-20 h-20 flex items-center justify-center mx-auto mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">2. قرر بثقة</h3>
                    <p class="text-gray-500 leading-relaxed">أعجبك المعلم؟ اشترك في الباقة. لم يعجبك؟ نغيره لك مجاناً حتى تجد الأنسب.</p>
                </div>
                
                <div class="group bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="bg-indigo-50 text-indigo-600 rounded-2xl w-20 h-20 flex items-center justify-center mx-auto mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">3. انطلق وتعلم</h3>
                    <p class="text-gray-500 leading-relaxed">ابدأ مشوارك التعليمي وأنت مطمئن، مع ضمان كامل لحقك في اختيار الأفضل.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gray-50 border-t border-gray-200">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">لماذا يختارنا الطلاب؟</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="flex flex-col items-center text-center p-4">
                    <div class="h-14 w-14 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">تجربة مجانية</h3>
                    <p class="text-sm text-gray-500 mt-2">لا تدفع شيئاً حتى تتأكد.</p>
                </div>
                <div class="flex flex-col items-center text-center p-4">
                    <div class="h-14 w-14 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">حرية التغيير</h3>
                    <p class="text-sm text-gray-500 mt-2">غيّر المعلم في أي وقت مجاناً.</p>
                </div>
                <div class="flex flex-col items-center text-center p-4">
                    <div class="h-14 w-14 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">منسق خاص</h3>
                    <p class="text-sm text-gray-500 mt-2">شخص يتابع معك كل صغيرة وكبيرة.</p>
                </div>
                <div class="flex flex-col items-center text-center p-4">
                    <div class="h-14 w-14 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">تقارير دورية</h3>
                    <p class="text-sm text-gray-500 mt-2">تابع تقدم طفلك خطوة بخطوة.</p>
                </div>
            </div>
        </div>
    </section>
    
    <section class="bg-indigo-900 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-20 -mt-20">
            <svg class="w-96 h-96 text-indigo-800 opacity-20 transform rotate-45" fill="currentColor" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50"/></svg>
        </div>
        <div class="container mx-auto px-6 py-20 text-center relative z-10">
            <h2 class="text-3xl md:text-5xl font-bold text-white mb-6">هل أنت مستعد للتجربة؟</h2>
            <p class="text-indigo-200 mb-10 max-w-2xl mx-auto text-lg">
                لا يوجد أي التزام. جرب حصة واحدة مجاناً، ونحن واثقون أنك ستحب التجربة.
            </p>
            <a href="https://wa.me/21612345678?text=أريد%20البدء%20بحصتي%20المجانية" target="_blank" class="bg-white text-indigo-900 font-bold px-10 py-4 rounded-xl hover:bg-gray-100 transition duration-300 text-lg shadow-xl inline-block transform hover:scale-105">
                ابدأ حصتك المجانية الآن 🎁
            </a>
        </div>
    </section>
</x-public-layout>