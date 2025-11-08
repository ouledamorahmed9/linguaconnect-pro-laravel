<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إدارة الجدول الأسبوعي') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ viewMode: 'list' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                
                <!-- Header with Teacher Selector (Unchanged) -->
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
                    
                    <!-- Teacher Stats Cards (Unchanged) -->
                    @if($teacherStats)
                    <div class="p-6 md:p-8 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-md font-semibold text-gray-900 mb-4">إحصائيات المعلم</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Verified Hours Card -->
                            <div class="bg-white overflow-hidden shadow-sm rounded-lg border">
                                <div class="p-5 flex items-center">
                                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                        <svg class...></svg>
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
                                        <svg class...></svg>
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
                                        <svg class...></svg>
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
                    
                    <!-- View Toggle Buttons (Unchanged) -->
                    <div class="p-6 md:pb-0 md:pt-4 md:px-8">
                        <span class="isolate inline-flex rounded-md shadow-sm">
                            <button @click="viewMode = 'list'" ...>
                                <svg ...></svg>
                                عرض القائمة
                            </button>
                            <button @click="viewMode = 'calendar'" ...>
                                <svg ...></svg>
                                عرض الجدول
                            </button>
                        </span>
                    </div>

                    <!-- "LIST VIEW" WRAPPER -->
                    <div x-show="viewMode === 'list'" class="grid grid-cols-1 lg:grid-cols-3 gap-8 p-6 md:p-8">
                        
                        <!-- Column 1: Add New Slot Form -->
                        <div class="lg:col-span-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">إضافة حصة أسبوعية</h3>

                            <!-- (Error messages are unchanged) -->
                            @if (session('status'))
                                <div ...>{{ session('status') }}</div>
                            @endif
                            @if ($errors->any())
                                <div ...><strong>خطأ!</strong> {{ $errors->first('message') ?? $errors->first('client_id') ?? 'الرجاء مراجعة بيانات الإدخال.' }}</div>
                            @endif

                            <form method="POST" action="{{ route('admin.roster.store') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="teacher_id" value="{{ $selectedTeacher->id }}">

                                <!-- Client Dropdown -->
                                <div>
                                    <x-input-label for="client_id" :value="__('العميل (الطالب)')" />
                                    <!-- ** MODIFICATION: The $clients list is now pre-filtered ** -->
                                    <select id="client_id" name="client_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                        <option value="">-- اختر العميل --</option>
                                        @forelse($clients as $client)
                                            <option value="{{ $client->id }}" @if(old('client_id') == $client->id) selected @endif>{{ $client->name }}</option>
                                        @empty
                                            <option value="" disabled>لا يوجد عملاء معينون لهذا المعلم باشتراك نشط</option>
                                        @endforelse
                                    </select>
                                    <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                                </div>
                                
                                <!-- (Day of Week Dropdown is unchanged) -->
                                <div>
                                    <x-input-label for="day_of_week" ... />
                                    <select id="day_of_week" ...>
                                        <option value="">-- اختر اليوم --</option>
                                        <option value="1" ...>الاثنين</option>
                                        <option value="2" ...>الثلاثاء</option>
                                        <option value="3" ...>الأربعاء</option>
                                        <option value="4" ...>الخميس</option>
                                        <option value="5" ...>الجمعة</option>
                                        <option value="6" ...>السبت</option>
                                        <option value="0" ...>الأحد</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('day_of_week')" class="mt-2" />
                                </div>

                                <!-- (Start Time is unchanged) -->
                                <div>
                                    <x-input-label for="start_time" ... />
                                    <x-text-input id="start_time" ... />
                                    <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                                </div>

                                <!-- (Submit Button is unchanged) -->
                                <div class="flex items-center justify-end pt-4">
                                    <x-primary-button>
                                        {{ __('إضافة حصة') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>

                        <!-- Column 2: Existing Slots List -->
                        <div class="lg:col-span-2">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">الحصص الأسبوعية المسجلة</h3>
                            
                            @if($weeklySlots->isEmpty())
                                <p class="text-sm text-gray-500">لا يوجد حصص أسبوعية مجدولة لهذا المعلم حتى الآن.</p>
                            @else
                                <div class="space-y-6">
                                    @php
                                        $days = [ 0 => 'الأحد', 1 => 'الاثنين', 2 => 'الثلاثاء', 3 => 'الأربعاء', 4 => 'الخميس', 5 => 'الجمعة', 6 => 'السبت'];
                                    @endphp

                                    @foreach($weeklySlots as $clientName => $slots)
                                        <!-- ** MODIFICATION: Add red border if ANY slot is inactive ** -->
                                        @php
                                            $clientHasActiveSub = $slots->first()->hasActiveSubscription;
                                        @endphp
                                        <div class="p-4 rounded-lg shadow-sm @if($clientHasActiveSub) bg-gray-50 border @else bg-red-50 border border-red-300 @endif">
                                            
                                            <div class="flex justify-between items-center mb-3">
                                                <h4 class="text-md font-semibold @if($clientHasActiveSub) text-indigo-700 @else text-red-700 @endif">
                                                    العميل: {{ $clientName }}
                                                </h4>
                                                @if(!$clientHasActiveSub)
                                                    <span class="text-xs font-semibold text-red-800 bg-red-100 px-2 py-0.5 rounded-full">اشتراك غير نشط</span>
                                                @endif
                                            </div>
                                            
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="@if($clientHasActiveSub) bg-gray-100 @else bg-red-100 @endif">
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


                    <!-- "CALENDAR VIEW" WRAPPER (This is your correct, working code) -->
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
                        class="p-6 md:p-8"
                        style="display: none;"
                        x-data
                        :data-events="@js($calendarEvents)"
                    >
                        <div id='calendar' class="h-[75vh]"></div>
                    </div>
                    <!-- === ** END OF "CALENDAR VIEW" WRAPPER ** === -->

                @else
                    <!-- Placeholder (Unchanged) -->
                    <div class="p-6 md:p-8">
                        <div ...>
                            <svg ...></svg>
                            <h3 ...>الرجاء اختيار معلم</h3>
                            <p ...>اختر معلمًا من القائمة أعلاه لإدارة جدوله الأسبوعي.</p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div> <!-- === ** END OF ALPINE.JS WRAPPER ** === -->


    <!-- Calendar/Tooltip CSS & JS (Unchanged, but we add one new style) -->
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

                        // ** MODIFICATION: Add Inactive Status to Tooltip **
                        let statusHtml = '';
                        if (!info.event.extendedProps.hasActiveSubscription) {
                            statusHtml = '<p class="fc-tooltip-status">اشتراك غير نشط</p>';
                        }

                        tooltip.innerHTML = '<h5>' + info.event.extendedProps.clientName + '</h5>' +
                                            '<p class="fc-tooltip-subject">' + info.event.extendedProps.subject + '</p>' +
                                            '<p class="fc-tooltip-time"><strong>الوقت:</strong> ' + 
                                            startTime + ' - ' + endTime +
                                            '</p>' +
                                            statusHtml; // Add the status HTML
                        document.body.appendChild(tooltip);

                        info.el.addEventListener('mouseenter', function() {
                            tooltip.style.display = 'block';
                            // ... (positioning code is unchanged) ...
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
        /* ** NEW STYLE FOR INACTIVE STATUS ** */
        .fc-tooltip-status {
            font-size: 12px;
            font-weight: bold;
            color: #f87171; /* Red color */
            margin-top: 5px;
        }
    </style>
</x-app-layout>