<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    @if(Auth::user()->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-indigo-600">
                            أكاديمية كمـــــون
                        </a>
                    @elseif(Auth::user()->hasRole('teacher'))
                        <a href="{{ route('teacher.dashboard') }}" class="text-2xl font-bold text-indigo-600">
                            أكاديمية كمـــــون
                        </a>
                    @else
                        <a href="{{ route('client.dashboard') }}" class="text-2xl font-bold text-indigo-600">
                            أكاديمية كمـــــون
                        </a>
                    @endif
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                    <!-- Admin Links -->
                    @if(Auth::user()->hasRole('admin'))
                        <div class="hidden sm:flex space-x-4 sm:-my-px sm:ms-10" x-data="{ sidebarOpen: false }">
                            <!-- Dashboard Link -->
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                {{ __('لوحة التحكم') }}
                            </x-nav-link>

                            <!-- Hamburger Menu Button -->
                            <button @click="sidebarOpen = true"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-1" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h7" />
                                </svg>
                                {{ __('القائمة الرئيسية') }}
                            </button>

                            <!-- Slide-over Drawer -->
                            <div x-show="sidebarOpen" style="display: none;" class="fixed inset-0 overflow-hidden z-50">
                                <div class="absolute inset-0 overflow-hidden">
                                    <!-- Backdrop -->
                                    <div x-show="sidebarOpen" x-transition:enter="ease-in-out duration-500"
                                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                        x-transition:leave="ease-in-out duration-500" x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                        @click="sidebarOpen = false"></div>

                                    <!-- Panel -->
                                    <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
                                        <div x-show="sidebarOpen"
                                            x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                                            x-transition:enter-start="translate-x-full"
                                            x-transition:enter-end="translate-x-0"
                                            x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                                            x-transition:leave-start="translate-x-0"
                                            x-transition:leave-end="translate-x-full" class="w-screen max-w-md">
                                            <div class="h-full flex flex-col bg-white shadow-xl overflow-y-scroll">
                                                <div class="px-4 py-6 sm:px-6 bg-indigo-600">
                                                    <div class="flex items-start justify-between">
                                                        <h2 class="text-lg font-medium text-white">قائمة الأدمن</h2>
                                                        <div class="ml-3 h-7 flex items-center">
                                                            <button @click="sidebarOpen = false"
                                                                class="bg-indigo-600 rounded-md text-indigo-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                                                                <span class="sr-only">Close panel</span>
                                                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg"
                                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <p class="mt-1 text-sm text-indigo-200">
                                                        إدارة جميع جوانب المنصة من مكان واحد.
                                                    </p>
                                                </div>
                                                <div class="relative flex-1 py-6 px-4 sm:px-6 space-y-8">

                                                    <!-- Section: User Management -->
                                                    <div>
                                                        <h3
                                                            class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                            إدارة المستخدمين</h3>
                                                        <ul class="mt-4 space-y-4">
                                                            <li>
                                                                <a href="{{ route('admin.clients.index') }}"
                                                                    class="group flex items-center text-base font-medium text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-md p-2">
                                                                    <svg class="h-6 w-6 ml-3 text-gray-400 group-hover:text-indigo-500"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                                    </svg>
                                                                    العملاء
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('admin.teachers.index') }}"
                                                                    class="group flex items-center text-base font-medium text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-md p-2">
                                                                    <svg class="h-6 w-6 ml-3 text-gray-400 group-hover:text-indigo-500"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                                    </svg>
                                                                    المعلمين
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('admin.coordinators.index') }}"
                                                                    class="group flex items-center text-base font-medium text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-md p-2">
                                                                    <svg class="h-6 w-6 ml-3 text-gray-400 group-hover:text-indigo-500"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                                    </svg>
                                                                    المنسقين
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <!-- Section: Subscriptions & Requests -->
                                                    <div>
                                                        <h3
                                                            class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                            الاشتراكات والطلبات</h3>
                                                        <ul class="mt-4 space-y-4">
                                                            <li>
                                                                <a href="{{ route('admin.subscriptions.requests') }}"
                                                                    class="group flex items-center text-base font-medium text-gray-900 bg-indigo-50 hover:bg-indigo-100 rounded-md p-2 border border-indigo-200 shadow-sm">
                                                                    <span
                                                                        class="flex-shrink-0 h-6 w-6 ml-3 flex items-center justify-center rounded-full border border-indigo-300 bg-white text-indigo-600">
                                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                                            stroke="currentColor">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                                                        </svg>
                                                                    </span>
                                                                    طلبات اشتراك جديدة
                                                                    <span
                                                                        class="mr-auto bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-xs font-bold">New</span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('admin.sessions.verify.index') }}"
                                                                    class="group flex items-center text-base font-medium text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-md p-2">
                                                                    <svg class="h-6 w-6 ml-3 text-gray-400 group-hover:text-indigo-500"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    مراجعة الحصص
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <!-- Section: Management -->
                                                    <div>
                                                        <h3
                                                            class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                            الإدارة والجدول</h3>
                                                        <ul class="mt-4 space-y-4">
                                                            <li>
                                                                <a href="{{ route('admin.roster.index') }}"
                                                                    class="group flex items-center text-base font-medium text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-md p-2">
                                                                    <svg class="h-6 w-6 ml-3 text-gray-400 group-hover:text-indigo-500"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                    </svg>
                                                                    إدارة الجدول الأسبوعي
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('admin.study-subjects.index') }}"
                                                                    class="group flex items-center text-base font-medium text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-md p-2">
                                                                    <svg class="h-6 w-6 ml-3 text-gray-400 group-hover:text-indigo-500"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                                    </svg>
                                                                    إدارة المواد الدراسية
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('admin.disputes.index') }}"
                                                                    class="group flex items-center text-base font-medium text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-md p-2">
                                                                    <svg class="h-6 w-6 ml-3 text-gray-400 group-hover:text-indigo-500"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                    </svg>
                                                                    إدارة النزاعات
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <!-- Section: System -->
                                                    <div>
                                                        <h3
                                                            class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                            النظام</h3>
                                                        <ul class="mt-4 space-y-4">
                                                            <li>
                                                                <a href="{{ route('admin.activity-log.index') }}"
                                                                    class="group flex items-center text-base font-medium text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-md p-2">
                                                                    <svg class="h-6 w-6 ml-3 text-gray-400 group-hover:text-indigo-500"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                                                    </svg>
                                                                    سجل الأنشطة
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('admin.messages.index') }}"
                                                                    class="group flex items-center text-base font-medium text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-md p-2">
                                                                    <svg class="h-6 w-6 ml-3 text-gray-400 group-hover:text-indigo-500"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                                    </svg>
                                                                    الرسائل
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- coordinator Links -->
                    @elseif(Auth::user()->hasRole('coordinator'))
                        <x-nav-link :href="route('coordinator.dashboard')"
                            :active="request()->routeIs('coordinator.dashboard')">
                            لوحة التحكم
                        </x-nav-link>

                        <x-nav-link :href="route('coordinator.clients.index')"
                            :active="request()->routeIs('coordinator.clients.index')">
                            إدارة عملائي
                        </x-nav-link>

                        <x-nav-link :href="route('coordinator.teachers.index')"
                            :active="request()->routeIs('coordinator.teachers.index')">
                            إدارة المعلمين
                        </x-nav-link>
                        <x-nav-link :href="route('coordinator.roster.index')"
                            :active="request()->routeIs('coordinator.roster.index')">
                            إدارة الجدول الأسبوعي
                        </x-nav-link>

                        <x-nav-link :href="route('coordinator.sessions.verify.index')"
                            :active="request()->routeIs('coordinator.sessions.verify.index')">
                            مراجعة الحصص
                        </x-nav-link>
                        <x-nav-link :href="route('coordinator.disputes.index')"
                            :active="request()->routeIs('coordinator.disputes.index')">
                            إدارة النزاعات
                        </x-nav-link>

                        <x-nav-link :href="route('coordinator.inbox.index')"
                            :active="request()->routeIs('coordinator.inbox.*')">
                            صندوق الوارد
                            @if(auth()->user()->unread_messages_count > 0)
                                <span class="mr-1 px-1.5 py-0.5 text-xs font-semibold rounded-full bg-red-500 text-white">
                                    {{ auth()->user()->unread_messages_count }}
                                </span>
                            @endif
                        </x-nav-link>

                        <!-- Teacher Links -->
                    @elseif(Auth::user()->hasRole('teacher'))
                        <x-nav-link :href="route('teacher.dashboard')" :active="request()->routeIs('teacher.dashboard')">
                            لوحة التحكم
                        </x-nav-link>
                        <x-nav-link :href="route('teacher.schedule.index')"
                            :active="request()->routeIs('teacher.schedule.index')">
                            جدولي الأسبوعي
                        </x-nav-link>

                        <!-- === ** ADD THIS NEW LINK ** === -->
                        <x-nav-link :href="route('teacher.history.index')"
                            :active="request()->routeIs('teacher.history.index')">
                            سجل الحصص
                        </x-nav-link>
                        <!-- === ** END OF NEW LINK ** === -->
                        <x-nav-link :href="route('teacher.inbox.index')" :active="request()->routeIs('teacher. inbox.*')">
                            صندوق الوارد
                            @if(auth()->user()->unread_messages_count > 0)
                                <span class="mr-1 px-1. 5 py-0.5 text-xs font-semibold rounded-full bg-red-500 text-white">
                                    {{ auth()->user()->unread_messages_count }}
                                </span>
                            @endif
                        </x-nav-link>

                        <!-- Client Links -->
                    @elseif(Auth::user()->hasRole('client'))
                        <x-nav-link :href="route('client.dashboard')" :active="request()->routeIs('client.dashboard')">
                            لوحة التحكم
                        </x-nav-link>
                        <x-nav-link :href="route('client.schedule.index')"
                            :active="request()->routeIs('client.schedule.index')">
                            جدولي
                        </x-nav-link>
                        <x-nav-link :href="route('client.progress-reports.index')"
                            :active="request()->routeIs('client.progress-reports.index')">
                            سجل الحصص
                        </x-nav-link>
                        <x-nav-link :href="route('client.subscription.index')"
                            :active="request()->routeIs('client.subscription.index')">
                            اشتراكي
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Language Switcher -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if(app()->getLocale() == 'en')
                    <a href="{{ route('lang.switch', 'ar') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-gray-500 bg-white hover:text-indigo-600 focus:outline-none transition ease-in-out duration-150">
                        <span class="mr-2">🇦🇪</span> العربية
                    </a>
                @else
                    <a href="{{ route('lang.switch', 'en') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-gray-500 bg-white hover:text-indigo-600 focus:outline-none transition ease-in-out duration-150">
                        <span class="mr-2">🇺🇸</span> English
                    </a>
                @endif
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="left" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <img class="h-8 w-8 rounded-full object-cover ltr:mr-2 rtl:ml-2"
                                src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('messages.nav.dashboard') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('messages.nav.logout') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">

            <!-- Admin Links -->
            @if(Auth::user()->hasRole('admin'))
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    لوحة التحكم
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.clients.index')"
                    :active="request()->routeIs('admin.clients.index')">
                    إدارة العملاء
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.teachers.index')"
                    :active="request()->routeIs('admin.teachers.index')">
                    إدارة المعلمين
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.coordinators.index')"
                    :active="request()->routeIs('admin.coordinators.index')">
                    إدارة المنسقين
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.roster.index')"
                    :active="request()->routeIs('admin.roster.index')">
                    إدارة الجدول الأسبوعي
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.sessions.verify.index')"
                    :active="request()->routeIs('admin.sessions.verify.index')">
                    مراجعة الحصص
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.disputes.index')"
                    :active="request()->routeIs('admin.disputes.index')">
                    إدارة النزاعات
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.activity-log.index')"
                    :active="request()->routeIs('admin.activity-log.index')">
                    سجل الأنشطة
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.study-subjects.index')"
                    :active="request()->routeIs('admin.study-subjects.*')">
                    إدارة المواد الدراسية
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.subscriptions.requests')" :active="request()->routeIs('admin.subscriptions.requests')">
                    طلبات اشتراك جديدة
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.messages.index')"
                    :active="request()->routeIs('admin.messages.*')">
                    الرسائل
                </x-responsive-nav-link>

                <!-- coordinator Links -->
            @elseif(Auth::user()->hasRole('coordinator'))
                <x-responsive-nav-link :href="route('coordinator.dashboard')"
                    :active="request()->routeIs('coordinator.dashboard')">
                    لوحة التحكم
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('coordinator.clients.index')"
                    :active="request()->routeIs('coordinator.clients.index')">
                    إدارة عملائي
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('coordinator.teachers.index')"
                    :active="request()->routeIs('coordinator.teachers.index')">
                    إدارة المعلمين
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('coordinator.roster.index')"
                    :active="request()->routeIs('coordinator.roster.index')">
                    إدارة الجدول الأسبوعي
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('coordinator.sessions.verify.index')"
                    :active="request()->routeIs('coordinator.sessions.verify.index')">
                    مراجعة الحصص
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('coordinator.disputes.index')"
                    :active="request()->routeIs('coordinator.disputes.index')">
                    إدارة النزاعات
                </x-responsive-nav-link>
                <x-responsive-nav-link : href="route('coordinator.inbox.index')"
                    :active="request()->routeIs('coordinator.inbox.*')">
                    صندوق الوارد
                    @if(auth()->user()->unread_messages_count > 0)
                        <span class="mr-1 px-1.5 py-0.5 text-xs font-semibold rounded-full bg-red-500 text-white">
                            {{ auth()->user()->unread_messages_count }}
                        </span>
                    @endif
                </x-responsive-nav-link>

                <!-- Teacher Links -->
            @elseif(Auth::user()->hasRole('teacher'))
                <x-responsive-nav-link :href="route('teacher.dashboard')" :active="request()->routeIs('teacher.dashboard')">
                    لوحة التحكم
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('teacher.schedule.index')"
                    :active="request()->routeIs('teacher.schedule.index')">
                    جدولي الأسبوعي
                </x-responsive-nav-link>

                <!-- === ** ADD THIS NEW LINK ** === -->
                <x-responsive-nav-link :href="route('teacher.history.index')"
                    :active="request()->routeIs('teacher.history.index')">
                    سجل الحصص
                </x-responsive-nav-link>
                <!-- === ** END OF NEW LINK ** === -->
                <x-responsive-nav-link :href="route('teacher.inbox.index')" :active="request()->routeIs('teacher.inbox.*')">
                    صندوق الوارد
                    @if(auth()->user()->unread_messages_count > 0)
                        <span class="mr-1 px-1.5 py-0.5 text-xs font-semibold rounded-full bg-red-500 text-white">
                            {{ auth()->user()->unread_messages_count }}
                        </span>
                    @endif
                </x-responsive-nav-link>

                <!-- Client Links -->
            @elseif(Auth::user()->hasRole('client'))
                <x-responsive-nav-link :href="route('client.dashboard')" :active="request()->routeIs('client.dashboard')">
                    لوحة التحكم
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('client.schedule.index')"
                    :active="request()->routeIs('client.schedule.index')">
                    جدولي
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('client.progress-reports.index')"
                    :active="request()->routeIs('client.progress-reports.index')">
                    سجل الحصص
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('client.subscription.index')"
                    :active="request()->routeIs('client.subscription.index')">
                    اشتراكي
                </x-responsive-nav-link>
            @endif

        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('الملف الشخصي') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('تسجيل الخروج') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>