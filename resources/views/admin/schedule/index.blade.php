<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4 md:mb-0">
                {{ __('الجدول الزمني الرئيسي') }}
            </h2>
            <div class="flex items-center space-x-2">
                <button class="px-3 py-1 bg-gray-200 text-gray-700 text-sm font-semibold rounded-md hover:bg-gray-300">&lt; الأسبوع السابق</button>
                {{-- Display the dynamic week range --}}
                <span class="text-lg font-semibold text-gray-800">
                    {{ $startOfWeek->format('d M') }} - {{ $endOfWeek->format('d M, Y') }}
                </span>
                <button class="px-3 py-1 bg-gray-200 text-gray-700 text-sm font-semibold rounded-md hover:bg-gray-300">الأسبوع التالي &gt;</button>
                 <a href="{{ route('admin.appointments.create') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 mr-4">
                    حجز موعد جديد
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            {{-- Display a success message after booking --}}
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                    <strong class="font-bold">نجاح!</strong>
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="grid grid-cols-7 divide-x divide-gray-200">
                    {{-- Loop through the days of the week --}}
                    @foreach (range(0, 6) as $day)
                        @php
                            $currentDay = $startOfWeek->copy()->addDays($day);
                            $dayKey = $currentDay->translatedFormat('l, d');
                            $appointmentsForDay = $calendarData->get($dayKey) ?? collect();
                        @endphp
                        <div class="flex flex-col">
                            <!-- Day Header -->
                            <div class="bg-gray-50 p-3 border-b text-center">
                                <p class="font-semibold text-gray-800">{{ $currentDay->translatedFormat('l') }}</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $currentDay->format('d') }}</p>
                            </div>
                            <!-- Appointments for the day -->
                            <div class="p-2 space-y-2 flex-grow min-h-[60vh]">
                                @forelse($appointmentsForDay as $appt)
                                    <div class="p-2 rounded-lg cursor-pointer hover:shadow-md transition-shadow bg-indigo-100 border border-indigo-200">
                                        <p class="text-sm font-bold text-indigo-800">{{ $appt->start_time->format('h:i A') }} - {{ $appt->teacher->name }}</p>
                                        <p class="text-xs text-gray-600">مع الطالب: {{ $appt->client->name }}</p>
                                        <p class="text-xs text-gray-500 truncate mt-1" title="{{ $appt->topic }}">{{ $appt->topic }}</p>
                                    </div>
                                @empty
                                    <div class="h-full flex items-center justify-center">
                                        <p class="text-xs text-gray-400">لا توجد مواعيد</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

