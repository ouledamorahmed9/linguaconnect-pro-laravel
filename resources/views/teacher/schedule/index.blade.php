<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4 md:mb-0">
                {{ __('جدولي الدراسي') }}
            </h2>
            <div class="flex items-center space-x-2">
                <button class="px-3 py-1 bg-gray-200 text-gray-700 text-sm font-semibold rounded-md hover:bg-gray-300">&lt; الأسبوع السابق</button>
                <span class="text-lg font-semibold text-gray-800">الأسبوع الحالي</span>
                <button class="px-3 py-1 bg-gray-200 text-gray-700 text-sm font-semibold rounded-md hover:bg-gray-300">الأسبوع التالي &gt;</button>
                 <a href="{{ route('teacher.appointments.create') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-indigo-700 mr-4">
                    حجز حصة جديدة
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            {{-- Display success messages --}}
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-8 min-w-full" style="direction: ltr;">
                        
                        <!-- Header Row -->
                        <div class="sticky top-0 z-10 bg-gray-50 border-b border-gray-200"></div> <!-- Empty corner -->
                        @foreach ($days as $dayName)
                            <div class="sticky top-0 z-10 bg-gray-50 border-b border-gray-200 p-3 text-center">
                                <span class="font-semibold text-gray-800">{{ $dayName }}</span>
                            </div>
                        @endforeach

                        <!-- Time Slots Rows -->
                        @foreach ($timeSlots as $time)
                            @php $hour = explode(':', $time)[0]; @endphp
                            <div class="grid grid-cols-1 grid-rows-1 col-span-8 grid-flow-col">
                                <!-- Time Cell -->
                                <div class="row-span-1 p-3 border-r border-b border-gray-200 text-right text-sm text-gray-500 font-semibold sticky left-0 bg-gray-50" style="writing-mode: vertical-rl; transform: rotate(180deg);">
                                    {{ \Carbon\Carbon::parse($time)->format('h:i A') }}
                                </div>
                                <!-- Day Cells -->
                                @foreach ($days as $dayNumber => $dayName)
                                    <div class="row-span-1 p-1 border-r border-b border-gray-200 h-24 {{ $loop->parent->index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                        @if (isset($appointmentsMap[$dayNumber][$hour]))
                                            @php 
                                                $appointment = $appointmentsMap[$dayNumber][$hour];
                                                
                                                // Professional Status Logic
                                                $statusColor = 'indigo'; // default
                                                if ($appointment->status === 'logged') $statusColor = 'amber';
                                                if ($appointment->status === 'verified') $statusColor = 'green';
                                                if (in_array($appointment->status, ['disputed', 'cancelled', 'no_show'])) $statusColor = 'red';
                                            @endphp
                                            <div class="p-2 rounded-lg h-full flex flex-col justify-center
                                                @if($statusColor === 'indigo') bg-indigo-100 border border-indigo-200 @endif
                                                @if($statusColor === 'amber') bg-amber-100 border border-amber-200 @endif
                                                @if($statusColor === 'green') bg-green-100 border border-green-200 @endif
                                                @if($statusColor === 'red') bg-red-100 border border-red-200 @endif
                                            ">
                                                <p class="font-bold text-xs {{ 'text-' . $statusColor . '-800' }}">{{ $appointment->topic }}</p>
                                                <p class="text-xs {{ 'text-' . $statusColor . '-700' }}">{{ $appointment->client->name }}</p>
                                                <a href="{{ route('teacher.sessions.log.create', $appointment) }}" class="text-xs font-semibold {{ 'text-' . $statusColor . '-900' }} hover:underline mt-1">
                                                    عرض التفاصيل
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

