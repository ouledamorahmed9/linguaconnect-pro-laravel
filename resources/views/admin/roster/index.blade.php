<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إدارة الجدول الأسبوعي') }}
        </h2>
    </x-slot>

    <div class="py-12">
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
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 p-6 md:p-8">
                        
                        <!-- Column 1: Add New Slot Form -->
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

                    </div>
                @else
                    <!-- Placeholder if no teacher is selected -->
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
</x-app-layout>
