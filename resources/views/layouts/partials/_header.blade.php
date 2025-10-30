<header class="fixed top-0 left-0 right-0 z-50 bg-white shadow-md">
    <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="/" class="text-2xl font-bold text-indigo-600">وصلة تعليم</a>
        <div class="hidden md:flex space-x-8 items-center">
            <a href="/" class="ml-8 {{ request()->is('/') ? 'text-indigo-600 font-bold' : 'text-gray-600 hover:text-indigo-600' }} transition-colors">الرئيسية</a>
            <a href="{{ route('teachers.index') }}" class="{{ request()->routeIs('teachers.index') ? 'text-indigo-600 font-bold' : 'text-gray-600 hover:text-indigo-600' }} transition-colors">معلمونا</a>
            <a href="{{ route('pricing.index') }}" class="{{ request()->routeIs('pricing.index') ? 'text-indigo-600 font-bold' : 'text-gray-600 hover:text-indigo-600' }} transition-colors">الأسعار</a>
            <a href="{{ route('contact.index') }}" class="{{ request()->routeIs('contact.index') ? 'text-indigo-600 font-bold' : 'text-gray-600 hover:text-indigo-600' }} transition-colors">اتصل بنا</a>
        </div>
        <div>
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/client.dashboard') }}" class="text-gray-600 hover:text-indigo-600 transition-colors">لوحة التحكم</a>
            @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 transition-colors">تسجيل الدخول</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ms-4 bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition duration-300">اشتراك</a>
                @endif
            @endauth
        @endif
        </div>
    </nav>
</header>
