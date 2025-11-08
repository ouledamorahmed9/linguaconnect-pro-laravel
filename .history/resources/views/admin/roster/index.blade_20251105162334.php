<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إدارة الجدول الأسبوعي') }}
        </h2>
    </x-slot>

    <!-- 
    ==================================================================
    STEP 1: ADD ALPINE.JS FOR THE TOGGLE
    ==================================================================
    -->
    <div class="py-12" x-data="{ viewMode: 'list' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                
                <!-- Header with Teacher Selector -->
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('admin.roster.index') }}" id="teacher-select-form">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 md:mb-0">
                                @if($selectedTeacher)
                                    الجدول الأسبوعي للمعلم: <span class="text-indigo-600">{{ $selectedTeacher->name }}</span>
                                @else
                                    إدارة الجدول الأسبوعي
                                @endif
                            </h3>
                            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                <label for="teacher_id" class="text-sm font-medium text-gray-700">اختر معلمًا:</label>
                                <select name="teacher_id" id="teacher_id" onchange="this.form.submit()" class="block w-full md:w-auto rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="">-- اختر معلمًا --</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" @if($selectedTeacherId == $teacher->id) selected @endif>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                @if($selectedTeacher)
                    
                    <!-- Teacher Stats Cards (This is your existing, correct code) -->
                    @if($teacherStats)
                    <div class="p-6 md:p-8 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-md font-semibold text-gray-900 mb-4">إحصائيات المعلم</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <!-- Verified Hours Card -->
                            <div class="bg-white overflow-hidden shadow-sm rounded-lg border">
                                <div class="p-5 flex items-center">
                                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="mr-4 text-right">
                                        <dt class="text-sm font-medium text-gray-500 truncate">إجمالي الساعات المعتمدة</dt>
                                        <dd class="text-3xl font-bold text-gray-900">{{ $teacherStats['verified'] }}</dd>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Hours Card -->
                            <div class="bg-white overflow-hidden shadow-sm rounded-lg border">
                                <div class="p-5 flex items-center">
                                    <div class="flex-shrink-0 bg-amber-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="mr-4 text-right">
                                        <dt class="text-sm font-medium text-gray-500 truncate">ساعات بانتظار المراجعة</dt>
                                        <dd class="text-3xl font-bold text-gray-900">{{ $teacherStats['pending'] }}</dd>
                                    </div>
                                </div>
                            </div>

                            <!-- Weekly Hours Card -->
                            <div class="bg-white overflow-hidden shadow-sm rounded-lg border">
                                <div class="p-5 flex items-center">
                                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                        </svg>
                                    </div>
                                    <div class="mr-4 text-right">
                                        <dt class="text-sm font-medium text-gray-500 truncate">ساعات هذا الأسبوع</dt>
                                        <dd class="text-3xl font-bold text-gray-900">{{ $teacherStats['this_week'] }}</dd>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endif
                    <!-- === END OF STATS CARDS === -->
                    
                    <!-- === ** STEP 2: VIEW TOGGLE BUTTONS ** === -->
                    <div class="p-6 md:pb-0 md:pt-4 md:px-8">
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


                    <!-- === ** STEP 3: "LIST VIEW" WRAPPER ** === -->
                    <div x-show="viewMode === 'list'" class="grid grid-cols-1 lg:grid-cols-3 gap-8 p-6 md:p-8">
                        
                        <!-- Column 1: Add New Slot Form (Unchanged) -->
                        <div class="lg:col-span-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">إضافة حصة أسبوعية</h3>

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

                            <form method="POST" action="{{ route('admin.roster.store') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="teacher_id" value="{{ $selectedTeacher->id }}">

                                <!-- Client Dropdown -->
                                <div>
                                    <x-input-label for="client_id" :value="__('العميل (الطالب)')" />
                                    <select id="client_id" name="client_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                        <option value="">-- اختر العميل --</option>
                                        @forelse($clients as $client)
                                            <option value="{{ $client->id }}" @if(old('client_id') == $client->id) selected @endif>{{ $client->name }}</option>
                                        @empty
                                            <option value="" disabled>لا يوجد عملاء معينون لهذا المعلم</option>
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
                                    <x-text-input id="start_time" class="block mt-1 w-full" type="time" name="start_time" :value="old('start_time')" required />
                                    <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                                </div>

                                <div class="flex items-center justify-end pt-4">
                                    <x-primary-button>
                                        {{ __('إضافة حصة') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>

                        <!-- Column 2: Existing Slots List (Unchanged) -->
                        <div class="lg:col-span-2">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">الحصص الأسبوعية المسجلة</h3>
                            
                            @if($weeklySlots->isEmpty())
                                <p class="text-sm text-gray-500">لا يوجد حصص أسبوعية مجدولة لهذا المعلم حتى الآن.</p>
                            @else
                                <div class="space-y-6">
                                    @php
                                        $days = [ 0 => 'الأحد', 1 => 'الاثنين', 2 => 'الثلاثاء', 3 => 'الأربعاء', 4 => 'الخميس', 5 => 'الجمعة', 6 => 'السبت'];
                                    @endphp

                                    <!-- Loop through slots grouped by client -->
                                    @foreach($weeklySlots as $clientName => $slots)
                                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm border">
                                            <h4 class="text-md font-semibold text-indigo-700 mb-3">
                                                العميل: {{ $clientName }}
                                            </h4>
                                            
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اليوم</th>
                                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوقت (1 ساعة)</th>
                                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">إجراء</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($slots as $slot)
                                                        <tr>
                                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                {{ $days[$slot->day_of_week] }}
                                                            </td>
                                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                                                {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}
                                                            </td>
                                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                                <form method="POST" action="{{ route('admin.roster.destroy', $slot) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذه الحصة الأسبوعية؟');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium">حذف</button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div> <!-- === ** END OF "LIST VIEW" WRAPPER ** === -->


                    <!-- === ** STEP 4: "CALENDAR VIEW" WRAPPER (THIS IS THE FIX) ** === -->
                    <div x-show="viewMode === 'calendar'" 
                         x-init="
                            if (viewMode === 'calendar') {
                                initFullCalendar(@json($calendarEvents));
                            }
                            $watch('viewMode', (value) => {
                                if (value === 'calendar') {
                                    // Use setTimeout to make sure the div is visible before init
                                    setTimeout(() => initFullCalendar(@json($calendarEvents)), 0);
                                }
                            });
                            "
                         class="p-6 md:p-8"
                         
                         
                         style="display: none;">
                        
                        <div id="calendar" class="h-[75vh]"></div>

                    </div>
                    <!-- === ** END OF "CALENDAR VIEW" WRAPPER ** === -->

                @else
                    <!-- Placeholder if no teacher is selected (Unchanged) -->
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col items-center justify-center h-96 border-2 border-dashed border-gray-300 rounded-lg text-center p-8">
                            <svg class="h-16 w-16 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-semibold text-gray-900">الرجاء اختيار معلم</h3>
                            <p class="mt-2 text-sm text-gray-500">اختر معلمًا من القائمة أعلاه لإدارة جدوله الأسبوعي.</p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div> <!-- === ** END OF ALPINE.JS WRAPPER ** === -->


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
                
                // Check if calendar is already initialized
                if (calendarEl.classList.contains('fc-loaded')) {
                    return; // Don't re-initialize
                }
                calendarEl.classList.add('fc-loaded'); // Set flag

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'timeGridWeek,timeGridDay,listWeek' // Removed 'dayGridMonth'
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
                    
                    // This is a READ-ONLY calendar, so no 'select' or 'eventClick' actions
                    selectable: false,

                    /**
                     * Professional Tooltip on Hover
                     */
                    eventDidMount: function(info) {
                        var tooltip = document.createElement('div');
                        tooltip.className = 'fc-tooltip';
                        
                        // Format times with moment.js for consistency
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