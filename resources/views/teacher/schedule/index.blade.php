<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('جدولي الدراسي') }}
            </h2>
             <a href="#" class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-indigo-700">
                إدارة أوقات الإتاحة
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="space-y-8">
                @forelse($schedule as $day => $appointments)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 pb-2 border-b mb-4">{{ $day }}</h3>
                    <div class="space-y-4">
                        @foreach($appointments as $appt)
                            @php
                                // Professional Status Logic
                                $statusColor = 'indigo'; // default
                                $statusText = 'قادمة';
                                $actionButton = 'join';

                                if ($appt->status === 'logged') {
                                    $statusColor = 'amber';
                                    $statusText = 'بانتظار التحقق';
                                    $actionButton = 'pending';
                                } elseif ($appt->status === 'verified') {
                                    $statusColor = 'green';
                                    $statusText = 'مكتملة';
                                    $actionButton = 'verified';
                                } elseif (in_array($appt->status, ['disputed', 'cancelled'])) {
                                    $statusColor = 'red';
                                    $statusText = 'مرفوضة';
                                    $actionButton = 'disputed';
                                } elseif ($appt->status === 'scheduled' && $appt->start_time->isPast()) {
                                    $statusColor = 'amber';
                                    $statusText = 'بانتظار التسجيل';
                                    $actionButton = 'log';
                                }
                            @endphp

                            <div class="bg-white rounded-lg shadow-sm p-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-2 h-10 rounded-full 
                                        @if($statusColor === 'indigo') bg-indigo-500 @endif
                                        @if($statusColor === 'amber') bg-amber-400 @endif
                                        @if($statusColor === 'green') bg-green-500 @endif
                                        @if($statusColor === 'red') bg-red-500 @endif
                                    "></div>
                                    <div class="ms-4">
                                        <p class="font-bold text-gray-800">{{ $appt->start_time->format('h:i A') }} - {{ $appt->topic }}</p>
                                        <p class="text-sm text-gray-600">حصة مع الطالب: {{ $appt->client->name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full
                                        @if($statusColor === 'indigo') bg-indigo-100 text-indigo-800 @endif
                                        @if($statusColor === 'amber') bg-amber-100 text-amber-800 @endif
                                        @if($statusColor === 'green') bg-green-100 text-green-800 @endif
                                        @if($statusColor === 'red') bg-red-100 text-red-800 @endif
                                    ">
                                        {{ $statusText }}
                                    </span>
                                    @if($actionButton === 'join')
                                        <a href="#" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">الانضمام</a>
                                    @elseif($actionButton === 'log')
                                        <a href="{{ route('teacher.sessions.log.create', $appt) }}" class="text-sm font-semibold text-amber-600 hover:text-amber-800">تسجيل الحصة</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @empty
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">لا توجد حصص مجدولة لهذا الأسبوع.</h3>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>

