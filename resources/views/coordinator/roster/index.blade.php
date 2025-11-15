<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إدارة الجدول الأسبوعي') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ viewMode: 'list' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('coordinator.roster.index') }}" id="teacher-select-form">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 md:mb-0">
                                @if($selectedTeacher)
                                    جدول المعلم: <span class="text-indigo-600">{{ $selectedTeacher->name }}</span>
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

                    <div x-show="viewMode === 'list'" class="grid grid-cols-1 lg:grid-cols-3 gap-8 p-6 md:p-8">
                        
                        <div class="lg:col-span-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">إضافة حصة لعملائك</h3>

                            @if (session('status'))
                                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">{{ session('status') }}</div>
                            @endif
                            @if ($errors->any())
                                <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert"><strong>خطأ!</strong> {{ $errors->first('message') ?? 'راجع البيانات.' }}</div>
                            @endif

                            <form method="POST" action="{{ route('coordinator.roster.store') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="teacher_id" value="{{ $selectedTeacher->id }}">

                                <div>
                                    <x-input-label for="client_id" :value="__('العميل (من قائمتك)')" />
                                    <select id="client_id" name="client_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                        <option value="">-- اختر العميل --</option>
                                        @forelse($clients as $client)
                                            <option value="{{ $client->id }}" @if(old('client_id') == $client->id) selected @endif>{{ $client->name }}</option>
                                        @empty
                                            <option value="" disabled>لا يوجد عملاء متاحين (تأكد من ربطهم بالمعلم واشتراكهم)</option>
                                        @endforelse
                                    </select>
                                    <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="day_of_week" :value="__('يوم الحصة')" />
                                    <select id="day_of_week" name="day_of_week" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                        <option value="">-- اختر اليوم --</option>
                                        <option value="1">الاثنين</option>
                                        <option value="2">الثلاثاء</option>
                                        <option value="3">الأربعاء</option>
                                        <option value="4">الخميس</option>
                                        <option value="5">الجمعة</option>
                                        <option value="6">السبت</option>
                                        <option value="0">الأحد</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('day_of_week')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="start_time" :value="__('وقت البدء (مدة الحصة 1 ساعة)')" />
                                    <select id="start_time" name="start_time" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                        <option value="">-- اختر الساعة --</option>
                                        @for ($hour = 0; $hour < 24; $hour++)
                                            @php $timeValue = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00'; @endphp
                                            <option value="{{ $timeValue }}">{{ $timeValue }}</option>
                                        @endfor
                                    </select>
                                    <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                                </div>

                                <div class="flex items-center justify-end pt-4">
                                    <x-primary-button>{{ __('إضافة حصة') }}</x-primary-button>
                                </div>
                            </form>
                        </div>

                        <div class="lg:col-span-2">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">جدول الحصص الحالي</h3>
                            
                            @if($weeklySlots->isEmpty())
                                <p class="text-sm text-gray-500">لا يوجد حصص لهذا المعلم.</p>
                            @else
                                <div class="space-y-6">
                                    @php $days = [ 0 => 'الأحد', 1 => 'الاثنين', 2 => 'الثلاثاء', 3 => 'الأربعاء', 4 => 'الخميس', 5 => 'الجمعة', 6 => 'السبت']; @endphp

                                    @foreach($weeklySlots as $clientName => $slots)
                                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm border">
                                            <h4 class="text-md font-semibold text-indigo-700 mb-3">العميل: {{ $clientName }}</h4>
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">اليوم</th>
                                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">الوقت</th>
                                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">إجراء</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($slots as $slot)
                                                        <tr>
                                                            <td class="px-4 py-3 text-sm text-gray-900">{{ $days[$slot->day_of_week] }}</td>
                                                            <td class="px-4 py-3 text-sm text-gray-700">{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}</td>
                                                            <td class="px-4 py-3 text-sm">
                                                                {{-- زر الحذف يظهر فقط إذا كان العميل يتبع لهذا المنسق --}}
                                                                @if($slot->client && $slot->client->created_by_user_id === Auth::id())
                                                                    <form method="POST" action="{{ route('coordinator.roster.destroy', $slot) }}" onsubmit="return confirm('هل أنت متأكد؟');">
                                                                        @csrf @method('DELETE')
                                                                        <button type="submit" class="text-red-600 hover:text-red-900 font-medium">حذف</button>
                                                                    </form>
                                                                @else
                                                                    <span class="text-gray-400 text-xs">ليس من عملائك</span>
                                                                @endif
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
                    </div>

                    <div x-show="viewMode === 'calendar'"
                         x-init="if (viewMode === 'calendar') { initFullCalendar($el.dataset.events); }
                                 $watch('viewMode', (value) => { if (value === 'calendar') setTimeout(() => initFullCalendar($el.dataset.events), 0); });"
                         class="p-6 md:p-8" style="display: none;" x-data :data-events="@js($calendarEvents)">
                        <div id='calendar' class="h-[75vh]"></div>
                    </div>

                @else
                    <div class="p-6 md:p-8 text-center">
                        <p class="text-gray-500">الرجاء اختيار معلم لإدارة الجدول.</p>
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
                    initialView: 'timeGridWeek', locale: 'ar', dir: 'rtl',
                    buttonText: { today: 'اليوم', week: 'أسبوع', day: 'يوم', list: 'قائمة' },
                    dayHeaderFormat: { weekday: 'long' },
                    slotLabelFormat: { hour: 'numeric', minute: '2-digit', meridiem: 'short' },
                    events: JSON.parse(calendarEventsJson), selectable: false,
                    eventDidMount: function(info) {
                        // Tooltip code here (same as admin)
                    }
                });
                calendar.render();
            }
        });
    </script>
</x-app-layout>