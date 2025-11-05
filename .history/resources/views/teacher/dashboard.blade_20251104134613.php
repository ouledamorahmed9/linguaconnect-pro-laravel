<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة التحكم') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- === NEW: PROFESSIONAL STATS CARDS === -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                <!-- Verified Hours Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <!-- Heroicon: check-badge -->
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="mr-4 text-right">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                إجمالي الساعات المعتمدة
                            </dt>
                            <dd class="text-3xl font-bold text-gray-900">
                                {{ $totalVerifiedHours }}
                            </dd>
                        </div>
                    </div>
                </div>

                <!-- Pending Hours Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex items-center">
                        <div class="flex-shrink-0 bg-amber-500 rounded-md p-3">
                            <!-- Heroicon: clock -->
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="mr-4 text-right">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                ساعات بانتظار المراجعة
                            </dt>
                            <dd class="text-3xl font-bold text-gray-900">
                                {{ $totalPendingHours }}
                            </dd>
                        </div>
                    </div>
                </div>

            </div>
            <!-- === END OF NEW STATS CARDS === -->

            <!-- Existing "Next Appointment" Panel -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">حصتك القادمة</h3>

                    @if($nextAppointment)
                        <div class="border-l-4 border-indigo-500 pl-4">
                            <p class="text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($nextAppointment->start_time)->translatedFormat('l, d F Y - H:i') }}
                            </p>
                            <h4 class="text-xl font-bold">{{ $nextAppointment->topic }}</h4>
                            <p class="text-gray-700">مع العميل: <span class="font-medium">{{ $nextAppointment->client->name }}</span></p>
                            <p class="text-gray-700">المادة: <span class="font-medium">{{ $nextAppointment->subject }}</span></p>
                            @if($nextAppointment->google_meet_link)
                                <a href="{{ $nextAppointment->google_meet_link }}" target="_blank" class="inline-block mt-3 px-4 py-2 bg-indigo-600 text-white font-semibold text-sm rounded-lg hover:bg-indigo-700">
                                    الانضمام إلى جوجل ميت
                                </a>
                            @endif
                        </div>
                    @else
                        <p class="text-gray-500">لا يوجد لديك حصص مجدولة قادمة.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
