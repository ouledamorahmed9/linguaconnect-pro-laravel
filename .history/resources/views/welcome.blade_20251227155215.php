<x-public-layout>
    {{-- Custom Styles --}}
    @push('styles')
    <style>
        .hero-bg {
            background-image: 
                linear-gradient(to bottom, rgba(15, 23, 42, 0.8), rgba(30, 27, 75, 0.9)),
                url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
        }
        
        .text-gradient {
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .blob {
            position: absolute;
            filter: blur(50px);
            z-index: 0;
            opacity: 0.4;
        }
    </style>
    @endpush

    <section class="hero-bg text-white relative min-h-[700px] flex items-center overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        
        <div class="container mx-auto px-6 py-24 relative z-10 text-center">
            
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 px-4 py-1.5 rounded-full mb-8 shadow-2xl">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-sm font-semibold tracking-wide text-indigo-100">🎁 حصتك الأولى مجانية 100%</span>
            </div>

            <h1 class="text-5xl md:text-7xl font-black leading-tight mb-6 tracking-tight">
                تعلم اللغات <br class="hidden md:block" />
                <span class="text-gradient">بثقة واحترافية</span>
            </h1>
            
            <p class="text-lg md:text-xl text-gray-300 mb-10 max-w-2xl mx-auto leading-relaxed font-light">
                جرب التعليم التفاعلي مع أفضل المعلمين المعتمدين.
                <span class="text-white font-medium">لا يعجبك المعلم؟</span> نغيره لك مجاناً فوراً. راحتك هي أولويتنا.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-5 justify-center">
                <a href="https://wa.me/21612345678" target="_blank" class="relative inline-flex group items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all duration-200 bg-gradient-to-r from-indigo-600 to-violet-600 rounded-2xl hover:from-indigo-700 hover:to-violet-700 focus:outline-none shadow-lg shadow-indigo-500/40 hover:shadow-indigo-500/60 hover:-translate-y-1">
                    احجز حصتك المجانية
                    <svg class="w-5 h-5 mr-2 -ml-1 rtl:ml-2 rtl:-mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </a>
                
                <a href="{{ route('teachers.index') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all duration-200 bg-white/10 border border-white/20 backdrop-blur-md rounded-2xl hover:bg-white hover:text-indigo-900 shadow-lg hover:-translate-y-1">
                    تصفح المعلمين
                </a>
            </div>

            <div class="mt-16 flex flex-wrap justify-center gap-8 md:gap-16 opacity-80">
                <div class="flex flex-col items-center">
                    <span class="text-3xl font-bold">50+</span>
                    <span class="text-sm text-indigo-200">معلم خبير</span>
                </div>
                <div class="w-px h-10 bg-white/20 hidden md:block"></div>
                <div class="flex flex-col items-center">
                    <span class="text-3xl font-bold">100%</span>
                    <span class="text-sm text-indigo-200">ضمان الرضا</span>
                </div>
                <div class="w-px h-10 bg-white/20 hidden md:block"></div>
                <div class="flex flex-col items-center">
                    <span class="text-3xl font-bold">4.9/5</span>
                    <span class="text-sm text-indigo-200">تقييم الطلاب</span>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white relative">
        <div class="container mx-auto px-6">
            <div class="text-center mb-20">
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 mb-4">لماذا نحن الأفضل؟</h2>
                <p class="text-slate-500 text-lg max-w-2xl mx-auto">صممنا نظامنا التعليمي ليحل كل المشاكل التي تواجه الطلاب وأولياء الأمور.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="group p-8 rounded-3xl bg-white border border-gray-100 shadow-2xl shadow-gray-200/50 hover:shadow-indigo-500/10 hover:border-indigo-100 transition-all duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white flex items-center justify-center mb-6 shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">تجربة مجانية حقيقية</h3>
                    <p class="text-slate-500 leading-relaxed">لا تدفع أي مبلغ قبل أن تجرب. احجز حصة كاملة مجاناً وتأكد بنفسك من الجودة.</p>
                </div>

                <div class="group p-8 rounded-3xl bg-white border border-gray-100 shadow-2xl shadow-gray-200/50 hover:shadow-indigo-500/10 hover:border-indigo-100 transition-all duration-300 relative">
                    <div class="absolute top-0 right-0 m-6">
                        <span class="bg-indigo-50 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full">الميزة الأقوى</span>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 text-white flex items-center justify-center mb-6 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">تغيير المعلم بضغطة زر</h3>
                    <p class="text-slate-500 leading-relaxed">لم يحدث توافق مع المعلم؟ لا مشكلة. نغيره لك فوراً مجاناً ودون أي أسئلة.</p>
                </div>

                <div class="group p-8 rounded-3xl bg-white border border-gray-100 shadow-2xl shadow-gray-200/50 hover:shadow-indigo-500/10 hover:border-indigo-100 transition-all duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-500 text-white flex items-center justify-center mb-6 shadow-lg shadow-pink-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">منسق خاص لك</h3>
                    <p class="text-slate-500 leading-relaxed">لن تكون وحدك. لك منسق شخصي يتابع الجدول، التقارير، ويحل أي مشكلة تواجهك.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-slate-50 relative overflow-hidden">
        <div class="blob w-96 h-96 bg-indigo-200 rounded-full top-0 left-0 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="blob w-96 h-96 bg-violet-200 rounded-full bottom-0 right-0 translate-x-1/2 translate-y-1/2"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 mb-4">باقات تناسب الجميع</h2>
                <p class="text-slate-500 text-lg">شفافية كاملة في الأسعار. لا رسوم خفية.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200 border border-slate-100 flex flex-col hover:-translate-y-1 transition-transform duration-300">
                    <h3 class="text-lg font-bold text-slate-800">Normal Class</h3>
                    <p class="text-sm text-blue-500 font-medium mb-4">مجموعة قياسية</p>
                    <span class="text-xs bg-slate-100 text-slate-600 font-bold px-3 py-1 rounded-full w-fit mb-6">👥 8 طلاب</span>
                    <div class="mb-8 text-center">
                        <span class="text-4xl font-black text-slate-900">4.37$</span>
                        <span class="text-slate-400 block text-sm mt-1">للحصة الواحدة</span>
                    </div>
                    <a href="{{ route('pricing.index') }}" class="mt-auto block w-full py-3 rounded-xl border-2 border-slate-100 text-slate-600 font-bold text-center hover:border-blue-500 hover:text-blue-600 transition-colors">التفاصيل</a>
                </div>

                <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200 border border-slate-100 flex flex-col hover:-translate-y-1 transition-transform duration-300">
                    <h3 class="text-lg font-bold text-slate-800">VIP Class</h3>
                    <p class="text-sm text-purple-500 font-medium mb-4">مجموعة صغيرة</p>
                    <span class="text-xs bg-purple-50 text-purple-600 font-bold px-3 py-1 rounded-full w-fit mb-6">👥 4 طلاب</span>
                    <div class="mb-8 text-center">
                        <span class="text-4xl font-black text-slate-900">8.75$</span>
                        <span class="text-slate-400 block text-sm mt-1">للحصة الواحدة</span>
                    </div>
                    <a href="{{ route('pricing.index') }}" class="mt-auto block w-full py-3 rounded-xl border-2 border-slate-100 text-slate-600 font-bold text-center hover:border-purple-500 hover:text-purple-600 transition-colors">التفاصيل</a>
                </div>

                <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200 border border-slate-100 flex flex-col hover:-translate-y-1 transition-transform duration-300">
                    <h3 class="text-lg font-bold text-slate-800">Duo Class</h3>
                    <p class="text-sm text-teal-500 font-medium mb-4">أنت وصديقك</p>
                    <span class="text-xs bg-teal-50 text-teal-600 font-bold px-3 py-1 rounded-full w-fit mb-6">👥 طالبين فقط</span>
                    <div class="mb-8 text-center">
                        <span class="text-4xl font-black text-slate-900">13.75$</span>
                        <span class="text-slate-400 block text-sm mt-1">للحصة الواحدة</span>
                    </div>
                    <a href="{{ route('pricing.index') }}" class="mt-auto block w-full py-3 rounded-xl border-2 border-slate-100 text-slate-600 font-bold text-center hover:border-teal-500 hover:text-teal-600 transition-colors">التفاصيل</a>
                </div>

                <div class="bg-white rounded-3xl p-6 shadow-2xl shadow-indigo-200 ring-4 ring-indigo-50 flex flex-col transform md:scale-105 z-10 relative">
                    <div class="absolute top-0 inset-x-0 -mt-3 flex justify-center">
                        <span class="bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg">الأكثر طلباً 🔥</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mt-2">One-to-One</h3>
                    <p class="text-sm text-indigo-500 font-medium mb-4">خصوصي 100%</p>
                    <span class="text-xs bg-indigo-50 text-indigo-700 font-bold px-3 py-1 rounded-full w-fit mb-6">👤 طالب واحد</span>
                    <div class="mb-8 text-center">
                        <span class="text-4xl font-black text-slate-900">22.50$</span>
                        <span class="text-slate-400 block text-sm mt-1">للحصة الواحدة</span>
                    </div>
                    <a href="{{ route('pricing.index') }}" class="mt-auto block w-full py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold text-center shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all">اشترك الآن</a>
                </div>

            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('pricing.index') }}" class="text-indigo-600 font-bold hover:text-indigo-800 inline-flex items-center gap-2 group">
                    عرض كافة التفاصيل والمميزات
                    <svg class="w-4 h-4 rtl:rotate-180 transform group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>
    </section>

    <section class="py-24 bg-slate-900 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-12 opacity-10">
            <svg class="w-64 h-64 text-indigo-500" fill="currentColor" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50"/></svg>
        </div>
        
        <div class="container mx-auto px-6 text-center relative z-10">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-8">هل أنت مستعد للتجربة؟</h2>
            <p class="text-indigo-200 text-lg mb-12 max-w-2xl mx-auto">
                لا يوجد أي التزام مالي. جرب حصة واحدة مجاناً، ونحن واثقون أنك ستحب التجربة التعليمية معنا.
            </p>
            <a href="https://wa.me/21612345678" target="_blank" class="inline-flex items-center justify-center px-10 py-5 text-lg font-bold text-indigo-900 bg-white rounded-2xl hover:bg-indigo-50 hover:scale-105 transition-all duration-200 shadow-2xl">
                ابدأ حصتك المجانية الآن
                <span class="mr-2">🚀</span>
            </a>
        </div>
    </section>
</x-public-layout>