<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('جدولي الدراسي') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Upcoming Appointments -->
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">الحصص القادمة</h3>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                       @if($upcomingAppointments->isNotEmpty())
                        <ul class="space-y-4">
                            @foreach($upcomingAppointments as $appt)
                            <li class="p-4 bg-gray-50 rounded-lg flex flex-col md:flex-row md:items-center md:justify-between">
                                <div class="flex items-center mb-4 md:mb-0">
                                    <div class="text-center md:text-right mr-4">
                                        <p class="text-indigo-600 font-bold text-lg">{{ $appt->start_time->format('d') }}</p>
                                        <p class="text-gray-500 text-sm">{{ $appt->start_time->translatedFormat('M') }}</p>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $appt->topic }}</p>
                                        <p class="text-sm text-gray-600">مع الأستاذ/ة: {{ $appt->teacher->name }} في {{ $appt->start_time->format('h:i A') }}</p>
                                    </div>
                                </div>
                                <a href="#" class="bg-indigo-600 text-white text-sm text-center font-semibold py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">
                                    الانضمام للحصة
                                </a>
                            </li>
                            @endforeach
                        </ul>
                       @else
                        <p class="text-gray-500">لا توجد حصص قادمة مجدولة حالياً.</p>
                       @endif
                    </div>
                </div>
            </div>

            <!-- Past Appointments -->
            <div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">الحصص السابقة</h3>
                 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                       @if($pastAppointments->isNotEmpty())
                        <ul class="space-y-4">
                            @foreach($pastAppointments as $appt)
                            <li class="p-4 bg-gray-50 rounded-lg flex flex-col md:flex-row md:items-center md:justify-between opacity-70">
                                <div class="flex items-center mb-4 md:mb-0">
                                    <div class="text-center md:text-right mr-4">
                                        <p class="text-gray-600 font-bold text-lg">{{ $appt->start_time->format('d') }}</p>
                                        <p class="text-gray-500 text-sm">{{ $appt->start_time->translatedFormat('M') }}</p>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $appt->topic }}</p>
                                        <p class="text-sm text-gray-600">مع الأستاذ/ة: {{ $appt->teacher->name }}</p>
                                    </div>
                                </div>
                                <a href="#" class="bg-gray-200 text-gray-700 text-sm text-center font-semibold py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors">
                                    عرض التقرير
                                </a>
                            </li>
                            @endforeach
                        </ul>
                       @else
                        <p class="text-gray-500">لا توجد سجلات للحصص السابقة.</p>
                       @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

