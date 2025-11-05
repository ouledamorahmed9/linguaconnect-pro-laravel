<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('جدول الحصص') }}
            </h2>
            <a href="{{ route('teacher.appointments.create') }}" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('+ حجز حصة جديدة') }}
            </a>
        </div>
    </x-slot>

    <!-- FullCalendar CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <!-- Tooltip/Popover library -->
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css" />

    <style>
        /* Professional styling overrides for FullCalendar */
        :root {
            --fc-border-color: #e5e7eb; /* gray-200 */
            --fc-today-bg-color: rgba(239, 246, 255, 0.8); /* blue-50 */
            --fc-event-bg-color: #4f46e5; /* indigo-600 */
            --fc-event-border-color: #4338ca; /* indigo-700 */
            --fc-event-text-color: #ffffff;
            --fc-button-bg-color: #ffffff;
            --fc-button-text-color: #374151; /* gray-700 */
            --fc-button-border-color: #d1d5db; /* gray-300 */
            --fc-button-hover-bg-color: #f9fafb; /* gray-50 */
            --fc-button-active-bg-color: #f3f4f6; /* gray-100 */
            --fc-button-primary-bg-color: #4f46e5; /* indigo-600 */
            --fc-button-primary-text-color: #ffffff;
            --fc-button-primary-hover-bg-color: #4338ca; /* indigo-700 */
        }
        #calendar-wrapper {
            background-color: #ffffff;
            border-radius: 0.5rem; /* 8px */
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }
        #calendar {
            padding: 1.5rem; /* 24px */
        }
        .fc {
            font-family: 'Inter', sans-serif; /* Match Tailwind's default font */
        }
        .fc .fc-toolbar-title {
            font-size: 1.25rem; /* text-xl */
            font-weight: 600;
            color: #111827; /* gray-900 */
        }
        .fc .fc-button {
            border-radius: 0.375rem; /* rounded-md */
            font-weight: 600;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            transition: all 150ms ease-in-out;
        }
        .fc .fc-daygrid-day-frame {
            border-radius: 0.375rem;
        }
        .fc .fc-day-today {
            background: var(--fc-today-bg-color) !important;
        }
        .fc .fc-daygrid-day-number {
            padding: 0.5rem;
            font-weight: 500;
        }
        .fc .fc-event {
            border: 1px solid var(--fc-event-border-color);
            border-radius: 0.375rem; /* rounded-md */
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            cursor: pointer;
        }

        /* Custom Tippy.js Tooltip Styling */
        .tippy-box[data-theme~='custom-tooltip'] {
            background-color: #1f2937; /* gray-800 */
            color: #ffffff;
            border-radius: 0.375rem;
            font-family: 'Inter', sans-serif;
        }
        .tippy-box[data-theme~='custom-tooltip'][data-placement^='top'] > .tippy-arrow::before {
            border-top-color: #1f2937;
        }
        .tippy-box[data-theme~='custom-tooltip'][data-placement^='bottom'] > .tippy-arrow::before {
            border-bottom-color: #1f2937;
        }
        .tooltip-title {
            font-size: 1rem;
            font-weight: 600;
            padding-bottom: 0.25rem;
            margin-bottom: 0.25rem;
            border-bottom: 1px solid #4b5563; /* gray-600 */
        }
        .tooltip-body p {
            margin: 0.25rem 0;
            font-size: 0.875rem;
        }
        .tooltip-body strong {
            color: #d1d5db; /* gray-300 */
        }
        .tooltip-link {
            display: inline-block;
            margin-top: 0.5rem;
            padding: 0.25rem 0.75rem;
            background-color: #4f46e5;
            color: #ffffff;
            border-radius: 0.375rem;
            text-decoration: none;
            font-weight: 500;
        }
        .tooltip-link:hover {
            background-color: #4338ca;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="calendar-wrapper">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                // === Core Settings ===
                locale: 'ar', // Set to Arabic
                direction: 'rtl', // Right-to-Left
                initialView: 'timeGridWeek', // Default to week view
                nowIndicator: true, // Show current time
                slotMinTime: '08:00:00', // Start day at 8 AM
                slotMaxTime: '22:00:00', // End day at 10 PM
                allDaySlot: false, // We don't need an "all-day" row
                navLinks: true, // Clickable day/week names
                editable: false, // Don't allow drag-and-drop
                businessHours: {
                    // Highlight typical working hours
                    daysOfWeek: [0, 1, 2, 3, 4, 5, 6], // All days
                    startTime: '10:00',
                    endTime: '20:00',
                },

                // === Header Toolbar ===
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'اليوم',
                    month: 'شهر',
                    week: 'أسبوع',
                    day: 'يوم',
                },

                // === Events Data ===
                events: @json($calendarEvents),

                // === Event Click Action ===
                // This function is triggered when an event is clicked
                eventClick: function(info) {
                    info.jsEvent.preventDefault(); // Don't let the browser follow the URL
                    
                    if (info.event.url) {
                        // Open the Google Meet link in a new tab
                        window.open(info.event.url, '_blank');
                    }
                },

                // === Professional Popover/Tooltip ===
                // This creates a custom tooltip when hovering over an event
                eventDidMount: function(info) {
                    const props = info.event.extendedProps;
                    let tooltipContent = `
                        <div class="tooltip-title">${info.event.title}</div>
                        <div class="tooltip-body">
                            <p><strong>المادة:</strong> ${props.subject}</p>
                            <p><strong>الحالة:</strong> ${props.status}</p>
                            ${props.meetLink ? `<a href="${props.meetLink}" target="_blank" class="tooltip-link">🔗 الانضمام للجلسة</a>` : ''}
                            ${props.status !== 'completed' ? `<a href="${props.logUrl}" class="tooltip-link" style="background-color: #16a34a;">📝 تسجيل الحصة</a>` : ''}
                        </div>
                    `;

                    tippy(info.el, {
                        content: tooltipContent,
                        allowHTML: true,
                        theme: 'custom-tooltip',
                        placement: 'top',
                        interactive: true,
                        appendTo: () => document.body, // Ensure tooltip isn't trapped in overflow
                    });
                }
            });

            calendar.render();
        });
    </script>
</x-app-layout>

