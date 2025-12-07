<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إدارة الجدول الأسبوعي') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ viewMode: 'list' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                
                <!-- Teacher selector -->
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
                    
                    @if($teacherStats)
                    <div class="p-6 md:p-8 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-md font-semibold text-gray-900 mb-4">إحصائيات المعلم</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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

                            <div class="bg-white overflow-hidden shadow-sm rounded-lg border">
                                <div class="p-5 flex items-center">
                                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 015.25 21h13.5A2.25 2.25 0 0121 18.75V7.5A2.25 2.25 0 0118.75 5.25H5.25A2.25 2.25 0 013 7.5z" />
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
                    
                    <div class="p-6 md:pb-0 md:pt-4 md:px-8">
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

                    <!-- List view -->
                    <div x-show="viewMode === 'list'" class="grid grid-cols-1 lg:grid-cols-3 gap-8 p-6 md:p-8">
                        
                        <!-- Add slot form -->
                        <div class="lg:col-span-1" x-data="{ search: '' }">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">إضافة حصة أسبوعية</h3>

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

                                <!-- Students multi-select with search -->
                                <div>
                                    <x-input-label :value="__('الطلاب (يمكن اختيار أكثر من طالب)')" />
                                    <div class="mt-2 mb-2">
                                        <div class="relative">
                                            <input
                                                x-model="search"
                                                type="text"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm pr-10"
                                                placeholder="ابحث عن طالب بالاسم"
                                            />
                                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1 0 6.5 6.5a7.5 7.5 0 0 0 10.15 10.15Z"/>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="space-y-2 max-h-56 overflow-auto rounded-md border border-gray-200 p-3 bg-white">
                                        @forelse($clients as $client)
                                            @php $nameLower = \Illuminate\Support\Str::lower($client->name); @endphp
                                            <label
                                                class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-indigo-50 cursor-pointer"
                                                x-show="search.trim() === '' || '{{ $nameLower }}'.includes(search.toLowerCase())"
                                                x-cloak
                                            >
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
                                            <p class="text-sm text-gray-500 px-2">لا يوجد طلاب معينون لهذا المعلم.</p>
                                        @endforelse
                                    </div>
                                    <x-input-error :messages="$errors->get('students')" class="mt-2" />
                                    <x-input-error :messages="$errors->get('students.*')" class="mt-1" />
                                </div>
                                
                                <!-- Day of week -->
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

                                <!-- Start time -->
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

                        <!-- Existing slots -->
                        <div class="lg:col-span-2">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">الحصص الأسبوعية المسجلة</h3>
                            
                            @if($weeklySlots->isEmpty())
                                <p class="text-sm text-gray-500">لا يوجد حصص أسبوعية مجدولة لهذا المعلم حتى الآن.</p>
                            @else
                                @php
                                    $days = [0=>'الأحد',1=>'الاثنين',2=>'الثلاثاء',3=>'الأربعاء',4=>'الخميس',5=>'الجمعة',6=>'السبت'];
                                @endphp
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @foreach($days as $dayNumber => $dayName)
                                        @if(isset($weeklySlots[$dayNumber]) && $weeklySlots[$dayNumber]->count() > 0)
                                            <div class="space-y-4">
                                                <h4 class="text-md font-semibold text-indigo-700 border-b pb-2">{{ $dayName }}</h4>
                                                @foreach($weeklySlots[$dayNumber] as $slot)
                                                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                                                        <div class="p-5">
                                                            <div class="flex justify-between items-center mb-2">
                                                                <span class="text-lg font-semibold text-indigo-700">
                                                                    {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}
                                                                </span>
                                                                <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-800">1 ساعة</span>
                                                            </div>
                                                            <div class="mt-2 space-y-1">
                                                                @php
                                                                    $names = $slot->students->pluck('name');
                                                                    $displayNames = $names->isNotEmpty()
                                                                        ? $names->implode('، ')
                                                                        : ($slot->client ? $slot->client->name : 'غير محدد');
                                                                @endphp
                                                                <p class="text-md font-medium text-gray-900">{{ $displayNames }}</p>
                                                                <p class="text-sm text-gray-600">{{ $slot->teacher->subject ?? '' }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 px-5 py-3 border-t flex justify-end">
                                                            <form method="POST" action="{{ route('admin.roster.destroy', $slot) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذه الحصة؟');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">حذف</button>
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
                    <!-- End list view -->

                    <!-- Calendar view -->
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

                @else
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
                if (calendarEl.classList.contains('fc-loaded')) return;
                calendarEl.classList.add('fc-loaded'); 

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    headerToolbar: { left: 'prev,next today', center: 'title', right: 'timeGridWeek,timeGridDay,listWeek' },
                    initialView: 'timeGridWeek',
                    locale: 'ar',
                    dir: 'rtl',
                    buttonText: { today: 'اليوم', week: 'أسبوع', day: 'يوم', list: 'قائمة' },
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
                                            '<p class="fc-tooltip-time"><strong>الوقت:</strong> ' + startTime + ' - ' + endTime + '</p>';
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
                        document.querySelectorAll('.fc-tooltip').forEach(t => t.remove());
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