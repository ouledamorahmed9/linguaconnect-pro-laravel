<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('جدولي الأسبوعي') }}
        </h2>
    </x-slot>

    <!-- 
    ==================================================================
    THIS IS THE NEW, PROFESSIONAL ROSTER VIEW.
    It replaces the old FullCalendar.
    ==================================================================
    -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Instructions -->
            <div class="p-4 mb-6 bg-indigo-50 border-l-4 border-indigo-400 rounded-r-lg">
                <p class="text-sm text-indigo-700">
                    <span class="font-semibold">جدولك الأسبوعي:</span>
                    هذه هي حصصك الثابتة التي تتكرر كل أسبوع.
                </p>
            </div>
            
            <!-- Display Success or Error Messages -->
            @if (session('status'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if($weeklySlots->isEmpty())
                <!-- Placeholder if no slots are assigned -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col items-center justify-center h-64 border-2 border-dashed border-gray-300 rounded-lg text-center p-8">
                            <svg class="h-16 w-16 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            <h3 class="mt-4 text-lg font-semibold text-gray-900">جدولك فارغ</h3>
                            <p class="mt-2 text-sm text-gray-500">لم يقم المدير بتعيين أي حصص أسبوعية لك حتى الآن.</p>
                        </div>
                    </div>
                </div>
            @else
                <!-- The new Weekly Roster List -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <!-- Loop through each day of the week -->
                    @foreach($daysOfWeek as $dayNumber => $dayName)
                        
                        <!-- Check if there are any slots for this day -->
                        @if(isset($weeklySlots[$dayNumber]) && $weeklySlots[$dayNumber]->count() > 0)
                            
                            <!-- Day Column -->
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-gray-800 border-b-2 border-indigo-500 pb-2">
                                    {{ $dayName }}
                                </h3>

                                <!-- Loop through slots for this day -->
                                @foreach($weeklySlots[$dayNumber] as $slot)
                                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                                        <div class="p-5">
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="text-lg font-semibold text-indigo-700">
                                                    {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}
                                                </span>
                                                <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-800">
                                                    1 ساعة
                                                </span>
                                            </div>
                                            <div class="mt-2">
                                                <p class="text-md font-medium text-gray-900">{{ $slot->client->name }}</p>
                                                <p class="text-sm text-gray-600">{{ Auth::user()->subject }}</p>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 px-5 py-3 border-t">
                                            
                                            <!-- === THIS IS THE FIX === -->
                                            <!-- This is now an active link -->
                                            <a href="{{ route('teacher.sessions.log.create', $slot) }}" class="block w-full text-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg text-sm hover:bg-indigo-700 transition-all">
                                                تسجيل الحصة
                                            </a>
                                            <!-- === END OF FIX === -->

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
            
        </div>
    </div>
</x-app-layout>

