<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    @if(Auth::user()->hasRole('teacher'))
                        <a href="{{ route('teacher.dashboard') }}" class="text-2xl font-bold text-indigo-600">
                            أكاديمية كمـــــون
                        </a>
                    @elseif(Auth::user()->hasRole('admin'))
                         <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-indigo-600">
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
                    @if(Auth::user()->hasRole('teacher'))
                        {{-- ==================== TEACHER LINKS ==================== --}}
                        <x-nav-link :href="route('teacher.dashboard')" :active="request()->routeIs('teacher.dashboard')">
                            {{ __('لوحة التحكم') }}
                        </x-nav-link>
                        <x-nav-link :href="route('teacher.schedule.index')" :active="request()->routeIs('teacher.schedule.index')">
                            {{ __('جدولي') }}
                        </x-nav-link>
                        {{-- NEW BOOKING LINK FOR TEACHER --}}
                        <x-nav-link :href="route('teacher.appointments.create')" :active="request()->routeIs('teacher.appointments.create')">
                            {{ __('حجز حصة') }}
                        </x-nav-link>
                        
                    @elseif(Auth::user()->hasRole('admin'))
                        {{-- ==================== ADMIN LINKS ==================== --}}
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('لوحة التحكم') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.schedule.index')" :active="request()->routeIs('admin.schedule.index')">
                            {{ __('الجدول الرئيسي') }}
                        </x-nav-link>
                        {{-- ADMIN'S SUPER-USER BOOKING LINK --}}
                        <x-nav-link :href="route('admin.appointments.create')" :active="request()->routeIs('admin.appointments.create')">
                            {{ __('حجز موعد') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.clients.index')" :active="request()->routeIs('admin.clients.index')">
                            {{ __('إدارة العملاء') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.teachers.index')" :active="request()->routeIs('admin.teachers.index')">
                            {{ __('إدارة المعلمين') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.disputes.index')" :active="request()->routeIs('admin.disputes.index')">
                            {{ __('مركز النزاعات') }}
                        </x-nav-link>

                    @else
                        {{-- ==================== CLIENT LINKS (Default) ==================== --}}
                        <x-nav-link :href="route('client.dashboard')" :active="request()->routeIs('client.dashboard')">
                            {{ __('لوحة التحكم') }}
                        </x-nav-link>
                        <x-nav-link :href="route('client.schedule.index')" :active="request()->routeIs('client.schedule.index')">
                            {{ __('جدولي الدراسي') }}
                        </x-nav-link>
                        <x-nav-link :href="route('client.progress-reports.index')" :active="request()->routeIs('client.progress-reports.index')">
                            {{ __('تقارير التقدم') }}
                        </x-nav-link>
                        <x-nav-link :href="route('client.subscription.index')" :active="request()->routeIs('client.subscription.index')">
                            {{ __('اشتراكي') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="left" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
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
             @if(Auth::user()->hasRole('teacher'))
                <x-responsive-nav-link :href="route('teacher.dashboard')" :active="request()->routeIs('teacher.dashboard')">
                    {{ __('لوحة التحكم') }}
                </x-responsive-nav-link>
                 <x-responsive-nav-link :href="route('teacher.schedule.index')" :active="request()->routeIs('teacher.schedule.index')">
                    {{ __('جدولي') }}
                </x-responsive-nav-link>
                {{-- NEW BOOKING LINK FOR TEACHER --}}
                <x-responsive-nav-link :href="route('teacher.appointments.create')" :active="request()->routeIs('teacher.appointments.create')">
                    {{ __('حجز حصة') }}
                </x-responsive-nav-link>
            @elseif(Auth::user()->hasRole('admin'))
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('لوحة التحكم') }}
                </x-responsive-nav-link>
                 <x-responsive-nav-link :href="route('admin.schedule.index')" :active="request()->routeIs('admin.schedule.index')">
                    {{ __('الجدول الرئيسي') }}
                </x-responsive-nav-link>
                 <x-responsive-nav-link :href="route('admin.appointments.create')" :active="request()->routeIs('admin.appointments.create')">
                    {{ __('حجز موعد') }}
                </x-responsive-nav-link>
                 <x-responsive-nav-link :href="route('admin.clients.index')" :active="request()->routeIs('admin.clients.index')">
                    {{ __('إدارة العملاء') }}
                </x-responsive-nav-link>
                 <x-responsive-nav-link :href="route('admin.teachers.index')" :active="request()->routeIs('admin.teachers.index')">
                    {{ __('إدارة المعلمين') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.disputes.index')" :active="request()->routeIs('admin.disputes.index')">
                    {{ __('مركز النزاعات') }}
                </x-responsive-nav-link>

            @else
                <x-responsive-nav-link :href="route('client.dashboard')" :active="request()->routeIs('client.dashboard')">
                    {{ __('لوحة التحكم') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('client.schedule.index')" :active="request()->routeIs('client.schedule.index')">
                    {{ __('جدولي الدراسي') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('client.progress-reports.index')" :active="request()->routeIs('client.progress-reports.index')">
                    {{ __('تقارير التقدم') }}
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

