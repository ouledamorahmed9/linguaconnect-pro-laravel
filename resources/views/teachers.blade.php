<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة تحكم المعلم') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Display success messages --}}
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <h3 class="text-2xl font-bold text-gray-800 mb-6">حصص اليوم</h3>

            <!-- Today's Classes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($todaysAppointments as $appointment)
                    @php
                        // Professional Status Logic
                        $statusColor = 'indigo'; // default for upcoming
                        $statusText = 'قادمة';
                        $actionButton = 'join'; // 'join', 'log', 'pending', 'verified', 'disputed'

                        if ($appointment->status === 'logged') {
                            $statusColor = 'amber';
                            $statusText = 'بانتظار التحقق';
                            $actionButton = 'pending';
                        } elseif ($appointment->status === 'verified') {
                            $statusColor = 'green';
                            $statusText = 'مكتملة ومقبولة';
                            $actionButton = 'verified';
                        } elseif (in_array($appointment->status, ['disputed', 'cancelled', 'no_show'])) {
                            $statusColor = 'red';
                            $statusText = $appointment->status === 'disputed' ? 'نزاع قائم' : 'ملغاة/مرفوضة';
                            $actionButton = 'disputed';
                        } elseif ($appointment->status === 'scheduled' && $appointment->start_time->isPast()) {
                            $statusColor = 'amber';
                            $statusText = 'بانتظار التسجيل';
                            $actionButton = 'log';
                        }
                    @endphp

                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 
                        @if($statusColor === 'indigo') border-indigo-500 @endif
                        @if($statusColor === 'amber') border-amber-400 @endif
                        @if($statusColor === 'green') border-green-500 @endif
                        @if($statusColor === 'red') border-red-500 @endif
                    ">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-bold text-lg text-gray-900">{{ $appointment->topic }}</p>
                                <p class="text-sm text-gray-600">مع الطالب: {{ $appointment->client->name }}</p>
                                <p class="text-sm font-semibold text-gray-800 mt-2">الوقت: {{ $appointment->start_time->format('h:i A') }} - {{ $appointment->end_time->format('h:i A') }}</p>
                            </div>
                            <span class="text-xs font-semibold px-2 py-1 rounded-full
                                @if($statusColor === 'indigo') bg-indigo-100 text-indigo-800 @endif
                                @if($statusColor === 'amber') bg-amber-100 text-amber-800 @endif
                                @if($statusColor === 'green') bg-green-100 text-green-800 @endif
                                @if($statusColor === 'red') bg-red-100 text-red-800 @endif
                            ">
                                {{ $statusText }}
                            </span>
                        </div>
                        <div class="mt-4 pt-4 border-t">
                            @if($actionButton === 'join')
                                <a href="#" class="block w-full text-center bg-indigo-600 text-white font-bold py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                                    الانضمام للحصة
                                </a>
                            @elseif($actionButton === 'log')
                                <a href="{{ route('teacher.sessions.log.create', $appointment) }}" class="block w-full text-center bg-amber-500 text-white font-bold py-2 rounded-lg hover:bg-amber-600 transition-colors">
                                    سجل الحصة الآن
                                </a>
                            @elseif($actionButton === 'pending')
                                <a href="{{ route('teacher.sessions.log.create', $appointment) }}" class="block w-full text-center bg-gray-200 text-gray-700 font-bold py-2 rounded-lg hover:bg-gray-300 transition-colors">
                                    عرض السجل (قيد المراجعة)
                                </a>
                            @elseif($actionButton === 'verified')
                                <p class="text-sm text-center text-green-600 font-semibold">تم التحقق من هذه الحصة بنجاح.</p>
                            @elseif($actionButton === 'disputed')
                                <p class="text-sm text-center text-red-600 font-semibold">تم رفض هذه الحصة أو عليها نزاع.</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-2 bg-white rounded-xl shadow-lg p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">لا توجد حصص مجدولة لهذا اليوم.</h3>
                        <p class="mt-1 text-sm text-gray-500">استمتع بيومك!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

