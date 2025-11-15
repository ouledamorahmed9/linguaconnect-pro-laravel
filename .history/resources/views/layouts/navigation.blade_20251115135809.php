<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    @if(Auth::user()->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-indigo-600">
                            وصلة تعليم
                        </a>
                    @elseif(Auth::user()->hasRole('teacher'))
                        <a href="{{ route('teacher.dashboard') }}" class="text-2xl font-bold text-indigo-600">
                            وصلة تعليم
                        </a>
                    @else
                        <a href="{{ route('client.dashboard') }}" class="text-2xl font-bold text-indigo-600">
                            وصلة تعليم
                        </a>
                    @endif
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    
                    <!-- Admin Links -->
                    @if(Auth::user()->hasRole('admin'))
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            لوحة التحكم
                        </x-nav-link>
                        <x-nav-link :href="route('admin.clients.index')" :active="request()->routeIs('admin.clients.index')">
                            إدارة العملاء
                        </x-nav-link>
                        <x-nav-link :href="route('admin.teachers.index')" :active="request()->routeIs('admin.teachers.index')">
                            إدارة المعلمين
                        </x-nav-link>
                        <x-nav-link :href="route('admin.coordinators.index')" :active="request()->routeIs('admin.coordinators.index')">
                            إدارة المنسقين
                        </x-nav-link>
                        <x-nav-link :href="route('admin.roster.index')" :active="request()->routeIs('admin.roster.index')">
                            إدارة الجدول الأسبوعي
                        </x-nav-link>
                        <x-nav-link :href="route('admin.sessions.verify.index')" :active="request()->routeIs('admin.sessions.verify.index')">
                            مراجعة الحصص
                        </x-nav-link>
                        <x-nav-link :href="route('admin.disputes.index')" :active="request()->routeIs('admin.disputes.index')">
                            إدارة النزاعات
                        </x-nav-link>

                    <!-- coordinator Links -->
                    @elseif(Auth::user()->hasRole('coordinator'))
                            <x-nav-link :href="route('coordinator.dashboard')" :active="request()->routeIs('coordinator.dashboard')">
                                لوحة التحكم
                            </x-nav-link>
                            
                            <x-nav-link :href="route('coordinator.clients.index')" :active="request()->routeIs('coordinator.clients.index')">
                                إدارة عملائي
                            </x-nav-link>

                            <x-nav-link :href="route('coordinator.teachers.index')" :active="request()->routeIs('coordinator.teachers.index')">
                                إدارة المعلمين
                            </x-nav-link>

                    <!-- Teacher Links -->
                    @elseif(Auth::user()->hasRole('teacher'))
                        <x-nav-link :href="route('teacher.dashboard')" :active="request()->routeIs('teacher.dashboard')">
                            لوحة التحكم
                        </x-nav-link>
                        <x-nav-link :href="route('teacher.schedule.index')" :active="request()->routeIs('teacher.schedule.index')">
                            جدولي الأسبوعي
                        </x-nav-link>
                        
                        <!-- === ** ADD THIS NEW LINK ** === -->
                        <x-nav-link :href="route('teacher.history.index')" :active="request()->routeIs('teacher.history.index')">
                            سجل الحصص
                        </x-nav-link>
                        <!-- === ** END OF NEW LINK ** === -->

                    <!-- Client Links -->
                    @elseif(Auth::user()->hasRole('client'))
                        <x-nav-link :href="route('client.dashboard')" :active="request()->routeIs('client.dashboard')">
                            لوحة التحكم
                        </x-nav-link>
                        <x-nav-link :href="route('client.schedule.index')" :active="request()->routeIs('client.schedule.index')">
                            جدولي
                        </x-nav-link>
                        <x-nav-link :href="route('client.progress-reports.index')" :active="request()->routeIs('client.progress-reports.index')">
                            سجل الحصص
                        </x-nav-link>
                        <x-nav-link :href="route('client.subscription.index')" :active="request()->routeIs('client.subscription.index')">
                            اشتراكي
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="left" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <img class="h-8 w-8 rounded-full object-cover ltr:mr-2 rtl:ml-2" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">    
                        <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('الملف الشخصي') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('تسجيل الخروج') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
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
                <x-responsive-nav-link :href="route('admin.clients.index')" :active="request()->routeIs('admin.clients.index')">
                    إدارة العملاء
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.teachers.index')" :active="request()->routeIs('admin.teachers.index')">
                    إدارة المعلمين
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.coordinators.index')" :active="request()->routeIs('admin.coordinators.index')">
                    إدارة المنسقين
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.roster.index')" :active="request()->routeIs('admin.roster.index')">
                    إدارة الجدول الأسبوعي
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.sessions.verify.index')" :active="request()->routeIs('admin.sessions.verify.index')">
                    مراجعة الحصص
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.disputes.index')" :active="request()->routeIs('admin.disputes.index')">
                    إدارة النزاعات
                </x-responsive-nav-link>
            
            <!-- coordinator Links -->
            @elseif(Auth::user()->hasRole('coordinator'))
                <x-responsive-nav-link :href="route('coordinator.dashboard')" :active="request()->routeIs('coordinator.dashboard')">
                    لوحة التحكم
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('coordinator.clients.index')" :active="request()->routeIs('coordinator.clients.index')">
                    إدارة عملائي
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('coordinator.teachers.index')" :active="request()->routeIs('coordinator.teachers.index')">
                    إدارة المعلمين
                </x-responsive-nav-link>                


            <!-- Teacher Links -->
            @elseif(Auth::user()->hasRole('teacher'))
                <x-responsive-nav-link :href="route('teacher.dashboard')" :active="request()->routeIs('teacher.dashboard')">
                    لوحة التحكم
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('teacher.schedule.index')" :active="request()->routeIs('teacher.schedule.index')">
                    جدولي الأسبوعي
                </x-responsive-nav-link>
                
                <!-- === ** ADD THIS NEW LINK ** === -->
                <x-responsive-nav-link :href="route('teacher.history.index')" :active="request()->routeIs('teacher.history.index')">
                    سجل الحصص
                </x-responsive-nav-link>
                <!-- === ** END OF NEW LINK ** === -->

            <!-- Client Links -->
            @elseif(Auth::user()->hasRole('client'))
                <x-responsive-nav-link :href="route('client.dashboard')" :active="request()->routeIs('client.dashboard')">
                    لوحة التحكم
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('client.schedule.index')" :active="request()->routeIs('client.schedule.index')">
                    جدولي
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('client.progress-reports.index')" :active="request()->routeIs('client.progress-reports.index')">
                    سجل الحصص
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('client.subscription.index')" :active="request()->routeIs('client.subscription.index')">
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

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('تسجيل الخروج') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>