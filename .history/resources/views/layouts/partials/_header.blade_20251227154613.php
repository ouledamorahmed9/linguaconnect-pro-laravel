<header class="fixed top-0 left-0 right-0 z-50 bg-white shadow-sm border-b border-gray-100 backdrop-blur-md bg-opacity-90 transition-all duration-300">
    <nav class="container mx-auto px-6 py-3 flex justify-between items-center">
        
        <a href="/" class="flex items-center group">
            <div class="bg-indigo-600 text-white p-2 rounded-xl shadow-lg transform group-hover:scale-110 transition-transform duration-300 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"></path>
                    <path d="M22 10v6"></path>
                    <path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"></path>
                </svg>
            </div>
            
            <div class="flex flex-col mr-3">
                <span class="text-2xl font-black text-gray-900 leading-none tracking-tight group-hover:text-indigo-700 transition-colors">
                    أكاديمية
                </span>
                <span class="text-sm font-bold text-indigo-600 tracking-widest -mt-1">
                    كمـــــون
                </span>
            </div>
        </a>

        <div class="hidden md:flex space-x-8 space-x-reverse items-center">
            <a href="/" class="text-gray-600 font-medium hover:text-indigo-600 transition-colors {{ request()->routeIs('home') ? 'text-indigo-600 font-bold' : '' }}">
                الرئيسية
            </a>
            <a href="{{ route('teachers.index') }}" class="text-gray-600 font-medium hover:text-indigo-600 transition-colors {{ request()->routeIs('teachers.*') ? 'text-indigo-600 font-bold' : '' }}">
                معلمونا
            </a>
            <a href="{{ route('pricing.index') }}" class="text-gray-600 font-medium hover:text-indigo-600 transition-colors {{ request()->routeIs('pricing.*') ? 'text-indigo-600 font-bold' : '' }}">
                الأسعار
            </a>
            <a href="#" class="text-gray-600 font-medium hover:text-indigo-600 transition-colors">
                اتصل بنا
            </a>
        </div>

        <div class="flex items-center space-x-4 space-x-reverse">
            @auth
                <a href="{{ url('/dashboard') }}" class="text-gray-700 font-bold hover:text-indigo-600">
                    لوحة التحكم
                </a>
            @else
                <a href="{{ route('login') }}" class="hidden md:block text-gray-600 font-medium hover:text-indigo-600 transition-colors">
                    دخول
                </a>
                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg font-bold hover:bg-indigo-700 transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    ابدأ الآن
                </a>
            @endauth

            <button class="md:hidden text-gray-600 hover:text-indigo-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
        </div>
    </nav>
</header>