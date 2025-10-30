    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('لوحة تحكم المعلم') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                {{-- ADD THIS BLOCK to display the success message --}}
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                    <strong class="font-bold">نجاح!</strong>
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

                <h3 class="text-2xl font-bold text-gray-800 mb-6">حصص اليوم</h3>

                <!-- Today's Classes -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($todaysAppointments as $appointment)
                        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 
                            @if($appointment->status === 'scheduled' && $appointment->start_time->isFuture()) border-indigo-500 @endif
                            @if($appointment->status === 'scheduled' && $appointment->start_time->isPast()) border-amber-400 @endif
                            @if($appointment->status === 'completed') border-green-500 opacity-70 @endif
                        ">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-lg text-gray-900">{{ $appointment->topic }}</p>
                                    <p class="text-sm text-gray-600">مع الطالب: {{ $appointment->client->name }}</p>
                                    <p class="text-sm font-semibold text-gray-800 mt-2">الوقت: {{ $appointment->start_time->format('h:i A') }} - {{ $appointment->end_time->format('h:i A') }}</p>
                                </div>
                                @if($appointment->status === 'scheduled' && $appointment->start_time->isFuture())
                                    <span class="text-xs font-semibold bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full">قادمة</span>
                                @elseif($appointment->status === 'scheduled' && $appointment->start_time->isPast())
                                     <span class="text-xs font-semibold bg-amber-100 text-amber-800 px-2 py-1 rounded-full">بانتظار التسجيل</span>
                                @else
                                     <span class="text-xs font-semibold bg-green-100 text-green-800 px-2 py-1 rounded-full">مكتملة</span>
                                @endif
                            </div>
                            <div class="mt-4 pt-4 border-t">
                               @if($appointment->status === 'scheduled' && $appointment->start_time->isFuture())
                                    <a href="#" class="block w-full text-center bg-indigo-600 text-white font-bold py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                                        الانضمام للحصة
                                    </a>
                               @elseif($appointment->status === 'scheduled' && $appointment->start_time->isPast())
                                    <a href="{{ route('teacher.sessions.log.create', ['appointment' => $appointment->id]) }}" class="block w-full text-center bg-amber-500 text-white font-bold py-2 rounded-lg hover:bg-amber-600 transition-colors">
                                        سجل الحصة الآن
                                    </a>
                               @else
                                    <p class="text-sm text-center text-gray-500">تم تسجيل هذه الحصة بنجاح.</p>
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
    

