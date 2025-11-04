<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('الجدول الرئيسي') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                
                <!-- Header with Teacher Selector -->
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('admin.schedule.index') }}" id="teacher-select-form">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 md:mb-0">
                                @if($selectedTeacher)
                                    جدول المعلم: <span class="text-indigo-600">{{ $selectedTeacher->name }}</span>
                                @else
                                    عرض جدول المعلم
                                @endif
                            </h3>
                            
                            <!-- Professional Dropdown Selector -->
                            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                <label for="teacher_id" class="text-sm font-medium text-gray-700">اختر معلمًا:</label>
                                <select name="teacher_id" id="teacher_id" class="block w-full md:w-auto rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="">-- اختر معلمًا لعرض جدوله --</option>
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

                <!-- Calendar Section -->
                <div class="p-6 md:p-8">
                    @if($selectedTeacher)
                        <!-- Calendar -->
                        <div id="calendar" class="h-[75vh]"></div>
                    @else
                        <!-- Placeholder for when no teacher is selected -->
                        <div class="flex flex-col items-center justify-center h-96 border-2 border-dashed border-gray-300 rounded-lg text-center p-8">
                            <svg class="h-16 w-16 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-semibold text-gray-900">لم يتم اختيار أي معلم</h3>
                            <p class="mt-2 text-sm text-gray-500">الرجاء اختيار اسم معلم من القائمة أعلاه لعرض جدوله الزمني.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- FullCalendar CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/ar.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Teacher Selector Script ---
            const teacherSelect = document.getElementById('teacher_id');
            const teacherSelectForm = document.getElementById('teacher-select-form');

            if (teacherSelect && teacherSelectForm) {
                teacherSelect.addEventListener('change', function() {
                    // Automatically submit the form (reload page) when a teacher is selected
                    teacherSelectForm.submit();
                });
            }

            // --- FullCalendar Script ---
            const calendarEl = document.getElementById('calendar');
            
            // Only initialize calendar if the element exists (i.e., a teacher is selected)
            if (calendarEl) {
                const calendarEvents = @json($calendarEvents); // Get events from controller

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    // --- Professional Options ---
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    initialView: 'timeGridWeek', // Default to week view
                    locale: 'ar', // Arabic localization
                    dir: 'rtl', // Right-to-Left
                    buttonText: {
                        today: 'اليوم',
                        month: 'شهر',
                        week: 'أسبوع',
                        day: 'يوم',
                        list: 'قائمة'
                    },
                    
                    // --- Styling ---
                    dayHeaderFormat: { weekday: 'long', month: 'numeric', day: 'numeric' },
                    slotLabelFormat: {
                        hour: 'numeric',
                        minute: '2-digit',
                        omitZeroMinute: false,
                        meridiem: 'short'
                    },
                    
                    // --- Data & Interaction ---
                    events: JSON.parse(calendarEvents), // Load the events
                    editable: false, // Admin cannot drag events
                    selectable: false, // Admin cannot click to create
                    
                    // --- Professional Event Tooltip ---
                    eventDidMount: function(info) {
                        // Create a professional tooltip on hover
                        var tooltip = document.createElement('div');
                        tooltip.className = 'fc-tooltip';
                        tooltip.innerHTML = '<h5>' + info.event.title + '</h5>' +
                                            '<p><strong>الموضوع:</strong> ' + (info.event.extendedProps.description || 'N/A') + '</p>' +
                                            '<p><strong>الحالة:</strong> ' + info.event.extendedProps.status + '</p>';
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
                        // Clean up tooltips
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
            display: none; /* Hidden by default */
            max-width: 250px;
        }
        .fc-tooltip h5 {
            font-weight: bold;
            font-size: 14px;
            margin: 0 0 5px;
            border-bottom: 1px solid #555;
            padding-bottom: 5px;
        }
        .fc-tooltip p {
            margin: 0;
        }
    </style>
</x-app-layout>
