<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تسجيل حصة مكتملة') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Session Status Messages -->
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-md shadow-sm">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                 <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-300 rounded-md shadow-sm">
                    <p class="font-bold">حدث خطأ:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- End Session Status Messages -->

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('teacher.sessions.log.store', $weeklySlot->id) }}"> <!-- Use weeklySlot->id as route parameter -->
                    @csrf
                    
                    <div class="p-6 md:p-8 text-gray-900">
                        <h3 class="text-lg font-semibold border-b pb-3 mb-6">تفاصيل الحصة الأسبوعية</h3>

                        <!-- Info Box (unchanged) -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-gray-50 p-4 rounded-lg border">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">العميل</dt>
                                <dd class="text-md font-semibold text-gray-900">{{ $weeklySlot->client->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">اليوم</dt>
                                <dd class="text-md font-semibold text-gray-900">{{ __(ucfirst($weeklySlot->day_of_week)) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">الوقت</dt>
                                <dd class="text-md font-semibold text-gray-900">{{ \Carbon\Carbon::parse($weeklySlot->start_time)->format('g:i A') }}</dd>
                            </div>
                        </div>

                        <!-- Form Inputs (unchanged) -->
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="topic" :value="__('موضوع الحصة')" />
                                <x-text-input id="topic" class="block mt-1 w-full" type="text" name="topic" :value="old('topic')" required autofocus />
                                <x-input-error :messages="$errors->get('topic')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="completion_status" :value="__('حالة الحصة')" />
                                <select id="completion_status" name="completion_status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="completed" @if(old('completion_status') == 'completed') selected @endif>مكتملة</option>
                                    <option value="no_show" @if(old('completion_status') == 'no_show') selected @endif>لم يحضر العميل</option>
                                    <option value="cancelled_by_teacher" @if(old('completion_status') == 'cancelled_by_teacher') selected @endif>ملغاة (بواسطة المعلم)</option>
                                    <option value="cancelled_by_client" @if(old('completion_status') == 'cancelled_by_client') selected @endif>ملغاة (بواسطة العميل)</option>
                                </select>
                                <x-input-error :messages="$errors->get('completion_status')" class="mt-2" />
                            </div>

                            <!-- --- **MODIFICATION 1: `notes` changed to `teacher_notes`** --- -->
                            <div>
                                <x-input-label for="teacher_notes" :value="__('ملاحظات المعلم (اختياري)')" />
                                <p class="mt-1 mb-2 text-xs text-gray-500">
                                    أضف أي ملاحظات خاصة بالجلسة. هذه الملاحظات للإدارة ولأرشيفك الخاص، لن تظهر للعميل.
                                </p>
                                <textarea id="teacher_notes" name="teacher_notes" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('teacher_notes') }}</textarea>
                                <x-input-error :messages="$errors->get('teacher_notes')" class="mt-2" />
                            </div>

                            <!-- --- **MODIFICATION 2: Replaced URL input with JSON Textarea** --- -->
                            <div>
                                <x-input-label for="session_proof" :value="__('إثبات الحصة (بيانات JSON)')" />
                                <p class="mt-1 mb-2 text-xs text-gray-500">
                                    من فضلك، انسخ بيانات الجلسة التي تم إنشاؤها بواسطة إضافة المتصفح والصقها بالكامل هنا.
                                </p>
                                <textarea id="session_proof" name="session_proof" rows="10"
                                          class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm font-mono text-sm"
                                          style="direction: ltr; text-align: left;"
                                          placeholder='{
    "title": "N/A",
    "duration": "00:04:23",
    "participants": [
        { "name": "...", "timeInCall": "..." }
    ]
}'
                                          required>{{ old('session_proof') }}</textarea>
                                <x-input-error :messages="$errors->get('session_proof')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Submit Button (unchanged) -->
                        <div class="flex items-center justify-end mt-6 border-t pt-6">
                            <a href="{{ route('teacher.schedule.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                إلغاء
                            </a>
                            <x-primary-button>
                                {{ __('تسجيل الحصة للمراجعة') }}
                            </x-primary-button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>