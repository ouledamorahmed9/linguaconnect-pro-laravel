<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('جدولي الأسبوعي') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ viewMode: 'list' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

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

            <!-- Toggle -->
            <div class="mb-6">
                <span class="isolate inline-flex rounded-md shadow-sm">
                    <button @click="viewMode = 'list'"
                            :class="viewMode === 'list' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                            type="button" class="relative inline-flex items-center rounded-r-md px-3 py-2 text-sm font-semibold ring-1 ring-inset ring-gray-300 focus:z-10">
                        عرض القائمة
                    </button>
                    <button @click="viewMode = 'calendar'"
                            :class="viewMode === 'calendar' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                            type="button" class="relative -ml-px inline-flex items-center rounded-l-md px-3 py-2 text-sm font-semibold ring-1 ring-inset ring-gray-300 focus:z-10">
                        عرض الجدول
                    </button>
                </span>
            </div>

            @if($weeklySlots->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col items-center justify-center h-64 border-2 border-dashed border-gray-300 rounded-lg text-center p-8">
                            <h3 class="mt-4 text-lg font-semibold text-gray-900">جدولك فارغ</h3>
                            <p class="mt-2 text-sm text-gray-500">يمكنك إضافة الحصص الخاصة بك من النموذج بالأسفل.</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- List View -->
            <div x-show="viewMode === 'list'">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Add Slot Form -->
                    <div class="lg:col-span-1">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 md:p-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">إضافة حصة أسبوعية</h3>
                            <form method="POST" action="{{ route('teacher.schedule.store') }}" class="space-y-4">
                                @csrf

                                <!-- Students Multi-select with checkboxes -->
                                <div>
                                    <x-input-label :value="__('الطلاب (يمكن اختيار أكثر من طالب)')" />
                                    <div class="mt-2 space-y-2 max-h-56 overflow-auto rounded-md border border-gray-200 p-3">
                                        @forelse($clients as $client)
                                            <label class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-indigo-50 cursor-pointer">
                                                <input type="checkbox"
                                                       name="students[]"
                                                       value="{{ $client->id }}"
                                                       class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                                       @checked(collect(old('students', []))->contains($client->id))>
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-medium text-gray-900">{{ $client->name }}</span>
                                                    <span class="text-xs text-gray-500">{{ $client->email }}</span>
                                                </div>
                                            </label>
                                        @empty
                                            <p class="text-sm text-gray-500 px-2">لا يوجد طلاب معينون لك.</p>
                                        @endforelse
                                    </div>
                                    <x-input-error :messages="$errors->get('students')" class="mt-2" />
                                    <x-input-error :messages="$errors->get('students.*')" class="mt-1" />
                                </div>
                                
                                <!-- Day of Week -->
                                <div>
                                    <x-input-label for="day_of_week" :value="__('يوم الحصة')" />
                                    <select id="day_of_week" name="day_of_week" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                        <option value="">-- اختر اليوم --</option>
                                        <option value="1" @selected(old('day_of_week') == '1')>الاثنين</option>
                                        <option value="2" @selected(old('day_of_week') == '2')>الثلاثاء</option>
                                        <option value="3" @selected(old('day_of_week') == '3')>الأربعاء</option>
                                        <option value="4" @selected(old('day_of_week') == '4')>الخميس</option>
                                        <option value="5" @selected(old('day_of_week') == '5')>الجمعة</option>
                                        <option value="6" @selected(old('day_of_week') == '6')>السبت</option>
                                        <option value="0" @selected(old('day_of_week') == '0')>الأحد</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('day_of_week')" class="mt-2" />
                                </div>

                                <!-- Start Time -->
                                <div>
                                    <x-input-label for="start_time" :value="__('وقت البدء (مدة الحصة 1 ساعة)')" />
                                    <select id="start_time" name="start_time" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                        <option value="">-- اختر الساعة --</option>
                                        @for ($hour = 0; $hour < 24; $hour++)
                                            @php $timeValue = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00'; @endphp
                                            <option value="{{ $timeValue }}" @selected(old('start_time') == $timeValue)>{{ $timeValue }}</option>
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

                    <!-- Existing Slots -->
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
                                                    <div class="mt-2 space-y-1">
                                                        @php
                                                            $studentNames = $slot->students->pluck('name');
                                                            $displayNames = $studentNames->isNotEmpty()
                                                                ? $studentNames->implode('، ')
                                                                : ($slot->client ? $slot->client->name : 'غير محدد');
                                                        @endphp
                                                        <p class="text-md font-medium text-gray-900">{{ $displayNames }}</p>
                                                        <p class="text-sm text-gray-600">{{ Auth::user()->subject }}</p>
                                                    </div>
                                                </div>
                                                <div class="bg-gray-50 px-5 py-3 border-t flex justify-between items-center gap-2">
                                                    @php
                                                        $firstStudentId = $slot->students->first()->id ?? $slot->client_id;
                                                        $lookupKey = "{$firstStudentId}-{$slot->day_of_week}-{$slot->start_time}";
                                                    @endphp
                                                    @if(isset($loggedSlotsLookup[$lookupKey]))
                                                        <button disabled class="w-full text-center px-4 py-2 bg-green-600 text-white font-semibold rounded-lg text-sm cursor-not-allowed">
                                                            تم التسجيل
                                                        </button>
                                                    @else
                                                        <a href="{{ route('teacher.sessions.log.create', $slot) }}" class="block w-full text-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg text-sm hover:bg-indigo-700">
                                                            تسجيل الحصة
                                                        </a>
                                                    @endif
                                                    
                                                    <form method="POST" action="{{ route('teacher.schedule.destroy', $slot) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذه الحصة؟');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-gray-400 hover:text-red-600" title="حذف الحصة">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a52.66 52.66 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
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
            <!-- End List View -->
            
            <!-- Calendar View -->
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
            <!-- End Calendar View -->

        </div>
    </div>
    
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/ar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            moment.locale('ar');

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
        .fc-tooltip h5 { font-weight: bold; font-size: 14px; margin: 0 0 5px; }
        .fc-tooltip p { margin: 0; }
        .fc-tooltip-subject { font-size: 12px; color: #ccc; padding-bottom: 5px; border-bottom: 1px solid #555; margin-bottom: 5px; }
        .fc-tooltip-time { font-size: 12px; }
    </style>
</x-app-layout>