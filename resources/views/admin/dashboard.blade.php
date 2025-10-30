<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة تحكم المساعد (Admin)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1: Total Clients -->
                <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">إجمالي العملاء</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $clientCount }}</p>
                        {{-- <p class="text-xs text-green-500">+5 هذا الأسبوع</p> --}}
                    </div>
                    <div class="bg-indigo-100 text-indigo-600 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <!-- Card 2: Active Teachers -->
                <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">المعلمون النشطون</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $teacherCount }}</p>
                        {{-- <p class="text-xs text-gray-500">2 بانتظار الموافقة</p> --}}
                    </div>
                    <div class="bg-green-100 text-green-600 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                </div>
                 <!-- Card 3: Pending Sessions (NEW & DYNAMIC) -->
                <a href="{{ route('admin.sessions.verify.index') }}" class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between hover:shadow-xl transition-shadow">
                    <div>
                        <p class="text-sm font-medium text-gray-500">حصص بانتظار التحقق</p>
                        <p class="text-3xl font-bold text-amber-500">{{ $pendingSessionsCount }}</p>
                    </div>
                    <div class="bg-amber-100 text-amber-600 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </a>
                 <!-- Card 4: Today's Lessons (DYNAMIC) -->
                <div class="bg-white rounded-xl shadow-lg p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">حصص اليوم</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $todaysLessons }}</p>
                        {{-- <p class="text-xs text-gray-500">3 مكتملة</p> --}}
                    </div>
                    <div class="bg-blue-100 text-blue-600 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Activity -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">أحدث الأنشطة</h3>
                    <ul class="space-y-4">
                        <li class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4 shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            </div>
                            <p class="text-sm text-gray-700">عميل جديد <span class="font-semibold">"أحمد منصور"</span> قام بالاشتراك في الباقة المتقدمة.</p>
                            <p class="text-xs text-gray-500 ms-auto shrink-0">منذ 5 دقائق</p>
                        </li>
                         <li class="flex items-center">
                            <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center mr-4 shrink-0">
                               <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <p class="text-sm text-gray-700">المعلمة <span class="font-semibold">"نورة عبدالله"</span> قامت بتسجيل حصة مع الطالب "يوسف خالد".</p>
                            <p class="text-xs text-gray-500 ms-auto shrink-0">منذ 30 دقيقة</p>
                        </li>
                         <li class="flex items-center">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-4 shrink-0">
                               <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                            </div>
                            <p class="text-sm text-gray-700">الحصة المجدولة للطالب <span class="font-semibold">"عمر حسن"</span> تم إلغاؤها.</p>
                            <p class="text-xs text-gray-500 ms-auto shrink-0">منذ ساعة</p>
                        </li>
                    </ul>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                     <h3 class="text-lg font-bold text-gray-800 mb-4">إدارة المنصة</h3>
                     <ul class="space-y-3">
                        <li><a href="{{ route('admin.clients.index') }}" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-indigo-50 transition-colors font-semibold text-gray-700">إدارة العملاء</a></li>
                        <li><a href="{{ route('admin.teachers.index') }}" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-indigo-50 transition-colors font-semibold text-gray-700">إدارة المعلمين</a></li>
                        <li><a href="{{ route('admin.schedule.index') }}" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-indigo-50 transition-colors font-semibold text-gray-700">الجدول الزمني الرئيسي</a></li>
                        <li><a href="#" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-indigo-50 transition-colors font-semibold text-gray-700">التقارير المالية</a></li>
                     </ul>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

