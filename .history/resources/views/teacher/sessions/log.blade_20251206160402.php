<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تسجيل حصة مكتملة') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('teacher.sessions.log.store', $weeklySlot) }}">
                    @csrf
                    
                    <div class="p-6 md:p-8 text-gray-900 space-y-8">
                        <h3 class="text-lg font-semibold border-b pb-3 mb-4">تفاصيل الحصة الأسبوعية</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-2 bg-gray-50 p-4 rounded-lg border">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">اليوم</p>
                                <p class="text-sm font-semibold text-gray-800">
                                    @php
                                        $daysOfWeek = [1=>'الاثنين',2=>'الثلاثاء',3=>'الأربعاء',4=>'الخميس',5=>'الجمعة',6=>'السبت',0=>'الأحد'];
                                    @endphp
                                    {{ $daysOfWeek[$weeklySlot->day_of_week] ?? 'غير معروف' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">وقت البدء</p>
                                <p class="text-sm font-semibold text-gray-800">{{ \Carbon\Carbon::parse($weeklySlot->start_time)->format('h:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">المُدرس</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $weeklySlot->teacher->name ?? '' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="topic" :value="__('موضوع الحصة')" />
                                <x-text-input id="topic" name="topic" type="text" class="mt-1 block w-full" required value="{{ old('topic') }}" />
                                <x-input-error :messages="$errors->get('topic')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="completion_status" :value="__('حالة الحصة')" />
                                <select id="completion_status" name="completion_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                    <option value="completed" @selected(old('completion_status')=='completed')>تمت</option>
                                    <option value="no_show" @selected(old('completion_status')=='no_show')>غياب الطالب</option>
                                    <option value="technical_issue" @selected(old('completion_status')=='technical_issue')>مشكلة تقنية</option>
                                </select>
                                <x-input-error :messages="$errors->get('completion_status')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="google_meet_link" :value="__('رابط الجلسة (اختياري)')" />
                                <x-text-input id="google_meet_link" name="google_meet_link" type="url" class="mt-1 block w-full" value="{{ old('google_meet_link') }}" />
                                <x-input-error :messages="$errors->get('google_meet_link')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="extension_data" :value="__('بيانات إضافية (JSON اختياري)')" />
                                <x-text-input id="extension_data" name="extension_data" type="text" class="mt-1 block w-full" value="{{ old('extension_data') }}" />
                                <x-input-error :messages="$errors->get('extension_data')" class="mt-2" />
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold">الطلاب في هذه الحصة</h3>
                                <p class="text-sm text-gray-500">يمكنك إضافة ملاحظة منفصلة لكل طالب</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($students as $student)
                                    <div class="border rounded-lg p-4 bg-gray-50 hover:bg-white transition shadow-sm">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $student->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $student->email }}</p>
                                            </div>
                                            <span class="text-[10px] px-2 py-1 rounded-full bg-indigo-100 text-indigo-700">طالب</span>
                                        </div>
                                        <div>
                                            <x-input-label :value="__('ملاحظة للطالب')" />
                                            <textarea name="student_notes[{{ $student->id }}]" rows="3" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="اكتب ملاحظة قصيرة...">{{ old('student_notes.' . $student->id) }}</textarea>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('student_notes')" class="mt-2" />
                            <x-input-error :messages="$errors->get('student_notes.*')" class="mt-1" />
                        </div>

                        <div class="flex items-center justify-end pt-4">
                            <x-primary-button>
                                {{ __('تسجيل الحصة') }}
                            </x-primary-button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>