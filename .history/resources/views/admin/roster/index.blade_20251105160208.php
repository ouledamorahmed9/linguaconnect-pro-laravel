<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إدارة الجدول الأسبوعي') }}
        </h2>
    </x-slot>

    <!-- Alpine state -->
    <div class="py-12" x-data="{ viewMode: 'list' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <!-- Header: Teacher selector -->
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('admin.roster.index') }}" id="teacher-select-form">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 md:mb-0">
                                @if($selectedTeacher)
                                    الجدول الأسبوعي للمعلم:
                                    <span class="text-indigo-600">{{ $selectedTeacher->name }}</span>
                                @else
                                    إدارة الجدول الأسبوعي
                                @endif
                            </h3>
                            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                <label for="teacher_id" class="text-sm font-medium text-gray-700">اختر معلمًا:</label>
                                <select name="teacher_id" id="teacher_id" onchange="this.form.submit()"
                                        class="block w-full md:w-auto rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
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
                    <!-- Teacher stats -->
                    @if($teacherStats)
                    <div class="p-6 md:p-8 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-md font-semibold text-gray-900 mb-4">إحصائيات المعلم</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <x-stat-card color="green" title="إجمالي الساعات المعتمدة" :value="$teacherStats['verified']"/>
                            <x-stat-card color="amber" title="ساعات بانتظار المراجعة" :value="$teacherStats['pending']"/>
                            <x-stat-card color="blue" title="ساعات هذا الأسبوع" :value="$teacherStats['this_week']"/>
                        </div>
                    </div>
                    @endif

                    <!-- Toggle buttons -->
                    <div class="p-6 md:pb-0 md:pt-4 md:px-8">
                        <span class="isolate inline-flex rounded-md shadow-sm">
                            <button @click="viewMode = 'list'"
                                    :class="viewMode === 'list' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                                    type="button"
                                    class="relative inline-flex items-center rounded-r-md px-3 py-2 text-sm font-semibold ring-1 ring-inset ring-gray-300 focus:z-10">
                                عرض القائمة
                            </button>
                            <button @click="viewMode = 'calendar'"
                                    :class="viewMode === 'calendar' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                                    type="button"
                                    class="relative -ml-px inline-flex items-center rounded-l-md px-3 py-2 text-sm font-semibold ring-1 ring-inset ring-gray-300 focus:z-10">
                                عرض الجدول
                            </button>
                        </span>
                    </div>

                    <!-- List view -->
                    <div x-show="viewMode === 'list'" class="grid grid-cols-1 lg:grid-cols-3 gap-8 p-6 md:p-8">
                        <!-- Left column: form -->
                        <div class="lg:col-span-1">
                            @include('admin.roster.partials.add-slot-form', ['selectedTeacher' => $selectedTeacher, 'clients' => $clients])
                        </div>

                        <!-- Right column: slots list -->
                        <div class="lg:col-span-2">
                            @include('admin.roster.partials.weekly-slots', ['weeklySlots' => $weeklySlots])
                        </div>
                    </div>

                    <!-- Calendar view -->
                    <script>
                        const calendarEvents = @json($calendarEvents);
                    </script>

                    <div x-show="viewMode === 'calendar'" 
                         x-init="
                            if (viewMode === 'calendar') initFullCalendar(calendarEvents);
                            $watch('viewMode', value => {
                                if (value === 'calendar') setTimeout(() => initFullCalendar(calendarEvents), 0);
                            });
                         " 
                         class="p-6 md:p-8">
                        <div id="calendar-wrapper" style="display:none;">
                            <div id="calendar" class="h-[75vh]"></div>
                        </div>
                    </div>
                @else
                    <!-- No teacher selected -->
                    <div class="p-6 md:p-8 text-center border-dashed border-2 border-gray-300 rounded-lg h-96 flex flex-col items-center justify-center">
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">الرجاء اختيار معلم</h3>
                        <p class="mt-2 text-sm text-gray-500">اختر معلمًا من القائمة أعلاه لإدارة جدوله الأسبوعي.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- FullCalendar scripts -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/ar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            moment.locale('ar');

            window.initFullCalendar = function(events) {
                const wrapper = document.getElementById('calendar-wrapper');
                const el = document.getElementById('calendar');

                // Show the wrapper
                wrapper.style.display = 'block';

                // Destroy previous instance if exists
                if (el._fullCalendar) {
                    el._fullCalendar.destroy();
                }

                const calendar = new FullCalendar.Calendar(el, {
                    locale: 'ar',
                    dir: 'rtl',
                    initialView: 'timeGridWeek',
                    allDaySlot: false,
                    events: events,
                    slotMinTime: '08:00:00',
                    slotMaxTime: '22:00:00',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'timeGridWeek,timeGridDay,listWeek'
                    },
                    buttonText: {
                        today: 'اليوم',
                        week: 'أسبوع',
                        day: 'يوم',
                        list: 'قائمة'
                    },
                    eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
                    eventDidMount(info) {
                        const tooltip = document.createElement('div');
                        tooltip.className = 'fc-tooltip';
                        const startTime = moment(info.event.start).format('h:mm A');
                        const endTime = moment(info.event.end).format('h:mm A');
                        tooltip.innerHTML = `
                            <h5>${info.event.extendedProps.clientName}</h5>
                            <p class="fc-tooltip-subject">${info.event.extendedProps.subject || ''}</p>
                            <p class="fc-tooltip-time"><strong>الوقت:</strong> ${startTime} - ${endTime}</p>
                        `;
                        document.body.appendChild(tooltip);

                        info.el.addEventListener('mouseenter', () => {
                            tooltip.style.display = 'block';
                            const rect = info.el.getBoundingClientRect();
                            tooltip.style.top = rect.top + window.scrollY - tooltip.offsetHeight - 5 + 'px';
                            tooltip.style.left = rect.left + window.scrollX + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
                        });
                        info.el.addEventListener('mouseleave', () => tooltip.style.display = 'none');
                    },
                    eventWillUnmount() {
                        document.querySelectorAll('.fc-tooltip').forEach(t => t.remove());
                    }
                });

                el._fullCalendar = calendar;
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
        .fc-tooltip h5 {
            font-weight: bold;
            font-size: 14px;
            margin: 0 0 5px;
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
