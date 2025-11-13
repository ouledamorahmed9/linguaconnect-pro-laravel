<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('جدولي الأسبوعي') }}
        </h2>
    </x-slot>

    <!-- 
    ==================================================================
    THIS IS THE NEW, FULLY INTERACTIVE ROSTER FOR THE TEACHER
    ==================================================================
    -->
    <div class="py-12" x-data="{ viewMode: 'list' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Display Success or Error Messages -->
            @if (session('status'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                    <strong>خطأ!</strong> {{ $errors->first('message') ?? 'الرجاء مراجعة بيانات الإدخال.' }}
                </div>
            @endif

            <!-- === ** VIEW TOGGLE BUTTONS ** === -->
            <div class="mb-6">
                <span class="isolate inline-flex rounded-md shadow-sm">
                    <button @click="viewMode = 'list'"
                            :class="viewMode === 'list' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                            type="button" class="relative inline-flex items-center rounded-r-md px-3 py-2 text-sm font-semibold ring-1 ring-inset ring-gray-300 focus:z-10">
                        <svg class="h-5 w-5 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10zm0 5.25a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                        </svg>
                        عرض القائمة
                    </button>
                    <button @click="viewMode = 'calendar'"
                            :class="viewMode === 'calendar' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                            type="button" class="relative -ml-px inline-flex items-center rounded-l-md px-3 py-2 text-sm font-semibold ring-1 ring-inset ring-gray-300 focus:z-10">
                        <svg class="h-5 w-5 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H5.5a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                        </svg>
                        عرض الجدول
                    </button>
                </span>
            </div>
            <!-- === ** END OF TOGGLE BUTTONS ** === -->

            @if($weeklySlots->isEmpty())
                <!-- Placeholder if no slots are assigned -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col items-center justify-center h-64 border-2 border-dashed border-gray-300 rounded-lg text-center p-8">
                            <svg class="h-16 w-16 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            <h3 class="mt-4 text-lg font-semibold text-gray-900">جدولك فارغ</h3>
                            <p class="mt-2 text-sm text-gray-500">لم يقم المدير بتعيين أي حصص أسبوعية لك حتى الآن. يمكنك إضافة الحصص الخاصة بك من النموذج.</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- === ** "LIST VIEW" WRAPPER ** === -->
            <div x-show="viewMode === 'list'">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Column 1: Add New Slot Form -->
                    <div class="lg:col-span-1">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 md:p-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">إضافة حصة أسبوعية</h3>
                            <form method="POST" action="{{ route('teacher.schedule.store') }}" class="space-y-4">
                                @csrf
                                <!-- teacher_id is added automatically in the controller -->

                                <!-- Client Dropdown -->
                                <div>
                                    <x-input-label for="client_id" :value="__('العميل (الطالب)')" />
                                    <select id="client_id" name="client_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                        <option value="">-- اختر العميل --</option>
                                        @forelse($clients as $client)
                                            <option value="{{ $client->id }}" @if(old('client_id') == $client->id) selected @endif>{{ $client->name }}</option>
                                        @empty
                                            <option value="" disabled>لا يوجد عملاء معينون لك</option>
                                        @endforelse
                                    </select>
                                    <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                                </div>
                                
                                <!-- Day of Week Dropdown -->
                                <div>
                                    <x-input-label for="day_of_week" :value="__('يوم الحصة')" />
                                    <select id="day_of_week" name="day_of_week" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                        <option value="">-- اختر اليوم --</option>
                                        <option value="1" @if(old('day_of_week') == '1') selected @endif>الاثنين</option>
                                        <option value="2" @if(old('day_of_week') == '2') selected @endif>الثلاثاء</option>
                                        <option value="3" @if(old('day_of_week') == '3') selected @endif>الأربعاء</option>
                                        <option value="4" @if(old('day_of_week') == '4') selected @endif>الخميس</option>
                                        <option value="5" @if(old('day_of_week') == '5') selected @endif>الجمعة</option>
                                        <option value="6" @if(old('day_of_week') == '6') selected @endif>السبت</option>
                                        <option value="0" @if(old('day_of_week') == '0') selected @endif>الأحد</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('day_of_week')" class="mt-2" />
                                </div>

                                <!-- Start Time -->
<div>
    <x-input-label for="start_time" :value="__('وقت البدء (مدة الحصة 1 ساعة)')" />
    <select id="start_time" name="start_time" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
        <option value="">-- اختر الساعة --</option>
        @for ($hour = 0; $hour < 24; $hour++)
            @php
                // Format the hour to be two digits (e.g., 08:00)
                $timeValue = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
            @endphp
            <option value="{{ $timeValue }}" @if(old('start_time') == $timeValue) selected @endif>
                {{ $timeValue }}
            </option>
        @endfor
    </select>
    <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
</div>
                                <div class="flex items-center justify-end pt-4">
                                    <x-primary-button>
                                        {{ __('إضافة حصة') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Column 2: Existing Slots List -->
                    <div class="lg:col-span-2">
                        @if(!$weeklySlots->isEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($daysOfWeek as $dayNumber => $dayName)
                                @if(isset($weeklySlots[$dayNumber]) && $weeklySlots[$dayNumber]->count() > 0)
                                    <div class="space-y-4">
                                        <h3 class="text-xl font-bold text-gray-800 border-b-2 border-indigo-500 pb-2">
                                            {{ $dayName }}
                                        </h3>
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
                                                <div class="bg-gray-50 px-5 py-3 border-t flex justify-between items-center">
                                                    @php
                                                        $lookupKey = "{$slot->client_id}-{$slot->day_of_week}-{$slot->start_time}";
                                                    @endphp
                                                    @if(isset($loggedSlotsLookup[$lookupKey]))
                                                        <button disabled class="w-full text-center px-4 py-2 bg-green-600 text-white font-semibold rounded-lg text-sm cursor-not-allowed">
                                                            <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                            تم التسجيل
                                                        </button>
                                                    @else
                                                        <a href="{{ route('teacher.sessions.log.create', $slot) }}" class="block w-full text-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg text-sm hover:bg-indigo-700 transition-all">
                                                            تسجيل الحصة
                                                        </a>
                                                    @endif
                                                    
                                                    <!-- ** NEW DELETE BUTTON ** -->
                                                    <form method="POST" action="{{ route('teacher.schedule.destroy', $slot) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذه الحصة الأسبوعية؟');" class="mr-2">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-gray-400 hover:text-red-600" title="حذف الحصة">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
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
            </div>
            <!-- === ** END OF "LIST VIEW" WRAPPER ** === -->
            
            <!-- === ** "CALENDAR VIEW" WRAPPER ** === -->
            <div 
                x-show="viewMode === 'calendar'"
                x-init="
                    if (viewMode === 'calendar') {
                        initFullCalendar($el.dataset.events);
                    }
                    $watch('viewMode', (value) => {
                        if (value === 'calendar') {
                            setTimeout(() => initFullCalendar($el.dataset.events), 0);
                        }
                    });
                "
                class="p-6 md:p-8 bg-white rounded-lg shadow-sm"
                style="display: none;"
                x-data
                :data-events="@js($calendarEvents)"
            >
                <div id='calendar' class="h-[75vh]"></div>
            </div>
            <!-- === ** END OF "CALENDAR VIEW" WRAPPER ** === -->

        </div>
    </div>
    
    <!-- 
    ==================================================================
    STEP 5: ADD CALENDAR/TOOLTIP CSS & JS
    ==================================================================
    -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/ar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            moment.locale('ar'); // Set locale to Arabic

            // Make the init function global so Alpine can call it
            window.initFullCalendar = (calendarEventsJson) => {
                const calendarEl = document.getElementById('calendar');
                
                if (calendarEl.classList.contains('fc-loaded')) {
                    return;
                }
                calendarEl.classList.add('fc-loaded'); 

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'timeGridWeek,timeGridDay,listWeek'
                    },
                    initialView: 'timeGridWeek', 
                    locale: 'ar', 
                    dir: 'rtl', 
                    buttonText: {
                        today: 'اليوم', week: 'أسبوع', day: 'يوم', list: 'قائمة'
                    },
                    dayHeaderFormat: { weekday: 'long' },
                    slotLabelFormat: { hour: 'numeric', minute: '2-digit', meridiem: 'short' },
                    events: JSON.parse(calendarEventsJson), 
                    selectable: false, 

                    eventDidMount: function(info) {
                        var tooltip = document.createElement('div');
                        tooltip.className = 'fc-tooltip';
                        
                        const startTime = moment(info.event.start).format('h:mm A');
                        const endTime = moment(info.event.end).format('h:mm A');

                        tooltip.innerHTML = '<h5>' + info.event.extendedProps.clientName + '</h5>' +
                                            '<p class="fc-tooltip-subject">' + info.event.extendedProps.subject + '</p>' +
                                            '<p class="fc-tooltip-time"><strong>الوقت:</strong> ' + 
                                            startTime + ' - ' + endTime +
                                            '</p>';
                        document.body.appendChild(tooltip);

                        info.el.addEventListener('mouseenter', function() {
                            tooltip.style.display = 'block';
                            tooltip.style.top = (info.el.getBoundingClientRect().top + window.scrollY - tooltip.offsetHeight - 5) + 'px';
                            tooltip.style.left = (info.el.getBoundingClientRect().left + window.scrollX + (info.el.offsetWidth / 2) - (tooltip.offsetWidth / 2)) + 'px';
                        });

                        info.el.addEventListener('mouseleave', function() {
                            tooltip.style.display = 'none';
                        });
                    },
                    eventWillUnmount: function(info) {
                        document.querySelectorAll('.fc-tooltip').forEach(tooltip => tooltip.remove());
                    }
                });

                calendar.render();
            }
        });
    </script>
    
    <!-- Tooltip Custom CSS -->
    <style>
        .fc-tooltip {
            position: absolute;
            z-index: 10001;
            background: #333;
            color: #fff;
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 13px;
            line-height: 1.5;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
            display: none;
            max-width: 250px;
        }
        .fc-tooltip h5 {
            font-weight: bold;
            font-size: 14px;
            margin: 0 0 5px;
        }
        .fc-tooltip p {
            margin: 0;
        }
        .fc-tooltip-subject {
            font-size: 12px;
            color: #ccc;
            padding-bottom: 5px;
            border-bottom: 1px solid #555;
            margin-bottom: 5px;
        }
        .fc-tooltip-time {
            font-size: 12px;
        }
    </style>
</x-app-layout>