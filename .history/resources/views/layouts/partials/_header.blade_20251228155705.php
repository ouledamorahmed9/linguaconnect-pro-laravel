<header class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-xl border-b border-gray-100/50 transition-all duration-300">
    <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
        
        <a href="/" class="flex items-center gap-3 group">
            <div class="relative flex items-center justify-center w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-lg shadow-indigo-500/20 ring-4 ring-indigo-50 group-hover:scale-105 group-hover:shadow-indigo-500/40 transition-all duration-300 ease-out">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="transform group-hover:-rotate-6 transition-transform duration-300">
                    <path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"></path>
                    <path d="M22 10v6"></path>
                    <path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"></path>
                </svg>
            </div>
            
            <div class="flex flex-col">
                <span class="text-2xl font-black text-slate-800 leading-none tracking-tight group-hover:text-indigo-900 transition-colors">
                    أكاديمية
                </span>
                <span class="text-sm font-bold bg-clip-text text-transparent bg-gradient-to-l from-indigo-600 to-violet-600 tracking-wide mt-0.5">
                    كمـــــون
                </span>
            </div>
        </a>

        <div class="hidden md:flex items-center gap-1 bg-gray-50/50 p-1.5 rounded-full border border-gray-100">
            <a href="/" class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-200 {{ request()->routeIs('home') ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-indigo-600 hover:bg-white/60' }}">
                الرئيسية
            </a>
            <a href="{{ route('teachers.index') }}" class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-200 {{ request()->routeIs('teachers.*') ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-indigo-600 hover:bg-white/60' }}">
                معلمونا
            </a>
            <a href="{{ route('pricing.index') }}" class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-200 {{ request()->routeIs('pricing.*') ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-indigo-600 hover:bg-white/60' }}">
                الأسعار
            </a>
            
            <a href="{{ route('legal.contact') }}" class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-200 {{ request()->routeIs('legal.contact') ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-indigo-600 hover:bg-white/60' }}">
                اتصل بنا
            </a>
        </div>

        <div class="flex items-center gap-4">
            @auth
                <a href="{{ url('/dashboard') }}" class="flex items-center gap-2 text-slate-700 font-bold hover:text-indigo-600 transition-colors bg-gray-50 px-4 py-2 rounded-xl border border-gray-200 hover:border-indigo-200">
                    <span>لوحة التحكم</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="hidden md:block text-slate-600 font-bold hover:text-indigo-600 transition-colors">
                    تسجيل دخول
                </a>
                <a href="{{ route('register') }}" class="relative inline-flex group items-center justify-center px-6 py-2.5 text-sm font-bold text-white transition-all duration-200 bg-gradient-to-r from-indigo-600 to-violet-600 rounded-xl hover:from-indigo-700 hover:to-violet-700 focus:outline-none shadow-lg hover:shadow-indigo-500/50 hover:-translate-y-0.5">
                    ابدأ الآن
                </a>
            @endauth
            
            <button class="md:hidden p-2 text-gray-600 rounded-lg hover:bg-gray-100 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
        </div>
    </nav>
</header>