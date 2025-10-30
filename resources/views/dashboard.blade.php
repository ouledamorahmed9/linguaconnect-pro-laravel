@inject('nextAppointment', 'App\Services\NextAppointmentService')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة التحكم') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold">مرحباً بك، {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600">هنا يمكنك متابعة كل ما يتعلق برحلة طفلك التعليمية.</p>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Next Class Card -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6 flex flex-col justify-between">
                    <div>
                        <h4 class="text-lg font-bold text-gray-800">حصتك القادمة</h4>
                        @php $next = $nextAppointment->getNext(); @endphp

                        @if($next)
                            <p class="text-sm text-gray-500">استعد للدرس القادم.</p>
                            
                            <div class="mt-6 flex flex-col md:flex-row md:items-center md:justify-between border-t pt-4">
                                <div class="flex items-center mb-4 md:mb-0">
                                    <img class="w-12 h-12 rounded-full object-cover mr-4" src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=1888&auto-format&fit=crop" alt="Teacher Image">
                                    <div>
                                        <p class="font-semibold text-gray-900">مع الأستاذ/ة: {{ $next->teacher->name }}</p>
                                        <p class="text-sm text-gray-600">موضوع الدرس: {{ $next->topic }}</p>
                                    </div>
                                </div>
                                <div class="text-center md:text-left">
                                    <p class="text-lg font-bold text-indigo-600">{{ $next->start_time->diffForHumans() }}</p>
                                    <p class="text-xs text-gray-500">{{ $next->start_time->translatedFormat('l, d M, h:i A') }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 mt-4">لا توجد حصص قادمة مجدولة حالياً.</p>
                        @endif
                    </div>
                    <a href="#" class="mt-6 block w-full {{ $next ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-300 cursor-not-allowed' }} text-white text-center font-semibold py-3 rounded-lg transition-colors">
                        الانضمام للحصة
                    </a>
                </div>

                <!-- Quick Actions Card -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h4 class="text-lg font-bold text-gray-800 mb-4">روابط سريعة</h4>
                    <ul class="space-y-3">
                        <li>
                            {{-- THIS IS THE FIX: Use the full, correct route name --}}
                            <a href="{{ route('client.schedule.index') }}" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-indigo-50 transition-colors">
                                <svg class="w-6 h-6 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="font-semibold text-gray-700">عرض الجدول الكامل</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('client.progress-reports.index') }}" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-indigo-50 transition-colors">
                                <svg class="w-6 h-6 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="font-semibold text-gray-700">عرض تقارير التقدم</span>
                            </a>
                        </li>
                         <li>
            {{-- UPDATE THIS LINK --}}
            <a href="{{ route('client.subscription.index') }}" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-indigo-50 transition-colors">
                <svg class="w-6 h-6 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                <span class="font-semibold text-gray-700">إدارة الاشتراك</span>
            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-indigo-50 transition-colors">
                                <svg class="w-6 h-6 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="font-semibold text-gray-700">الدعم والمساعدة</span>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

