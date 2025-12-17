<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تسجيل حصة (فردية أو جماعية)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                <form method="POST" action="{{ route('teacher.sessions.log.store', $weeklySlot) }}">
                    @csrf
                    
                    <div class="p-6 md:p-10 text-gray-900">
                        <!-- Session Info Card -->
                        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-6 mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-indigo-900">تفاصيل الحصة المجدولة</h3>
                                <p class="text-sm text-indigo-700 mt-1">يرجى تأكيد الحضور وتفاصيل الجلسة أدناه.</p>
                            </div>
                            <div class="flex gap-4 text-sm">
                                <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-indigo-100">
                                    <span class="block text-xs text-gray-500">اليوم</span>
                                    <span class="font-bold text-gray-800">
                                        @php $days = [ 0 => 'الأحد', 1 => 'الاثنين', 2 => 'الثلاثاء', 3 => 'الأربعاء', 4 => 'الخميس', 5 => 'الجمعة', 6 => 'السبت']; @endphp
                                        {{ $days[$weeklySlot->day_of_week] }}
                                    </span>
                                </div>
                                <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-indigo-100">
                                    <span class="block text-xs text-gray-500">الوقت</span>
                                    <span class="font-bold text-gray-800">
                                        {{ \Carbon\Carbon::parse($weeklySlot->start_time)->format('h:i A') }}
                                    </span>
                                </div>
                                <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-indigo-100">
                                    <span class="block text-xs text-gray-500">المالك</span>
                                    <span class="font-bold text-gray-800">{{ $weeklySlot->client->name }}</span>
                                </div>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r shadow-sm">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="mr-3">
                                        <p class="font-bold">يرجى تصحيح الأخطاء التالية:</p>
                                        <ul class="list-disc list-inside text-sm mt-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-8">
                            
                            <!-- === PROFESSIONAL STUDENT SELECTOR === -->
                            <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                                <div class="flex justify-between items-end mb-4">
                                    <div>
                                        <label class="block text-base font-bold text-gray-900">قائمة الحضور (الطلاب)</label>
                                        <p class="text-sm text-gray-500 mt-1">قم باختيار جميع الطلاب الذين حضروا هذه الحصة.</p>
                                    </div>
                                    <div class="flex items-center gap-3 text-sm">
                                        <button type="button" id="btn-select-all" class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors">تحديد الكل</button>
                                        <span class="text-gray-300">|</span>
                                        <button type="button" id="btn-deselect-all" class="text-gray-500 hover:text-gray-700 font-medium transition-colors">إلغاء التحديد</button>
                                    </div>
                                </div>

                                <!-- Student Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 max-h-[300px] overflow-y-auto pr-1 custom-scrollbar">
                                    @foreach($availableStudents as $student)
                                        @php
                                            $isChecked = collect(old('student_ids'))->contains($student->id) || (empty(old('student_ids')) && $student->id == $weeklySlot->client_id);
                                        @endphp
                                        <label class="relative flex items-center p-3 rounded-lg border cursor-pointer transition-all duration-200 select-none group 
                                            {{ $isChecked ? 'bg-indigo-50 border-indigo-500 ring-1 ring-indigo-500' : 'bg-white border-gray-200 hover:border-indigo-300' }}">
                                            
                                            <input type="checkbox" 
                                                   name="student_ids[]" 
                                                   value="{{ $student->id }}" 
                                                   class="student-checkbox h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 transition duration-150 ease-in-out"
                                                   {{ $isChecked ? 'checked' : '' }}>
                                            
                                            <div class="mr-3 flex-1">
                                                <div class="font-semibold text-sm text-gray-900 group-hover:text-indigo-700">{{ $student->name }}</div>
                                            </div>
                                            
                                            <!-- Checkmark Icon (Visible when checked) -->
                                            <div class="checkmark-icon {{ $isChecked ? 'block' : 'hidden' }} text-indigo-600">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="mt-3 text-left">
                                    <span class="text-sm font-medium text-gray-600 bg-white px-3 py-1 rounded-full border border-gray-200 shadow-sm">
                                        تم اختيار: <span id="selected-count" class="text-indigo-600 font-bold">0</span> طالب
                                    </span>
                                </div>
                                <x-input-error :messages="$errors->get('student_ids')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="topic" :value="__('موضوع الحصة (ما تم تدريسه)')" />
                                    <x-text-input id="topic" class="block mt-2 w-full" type="text" name="topic" :value="old('topic')" required placeholder="مثال: قواعد اللغة العربية - المبتدأ والخبر" />
                                    <x-input-error :messages="$errors->get('topic')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="completion_status" :value="__('حالة الحصة')" />
                                    <div class="relative mt-2">
                                        <select id="completion_status" name="completion_status" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm" required>
                                            <option value="completed" @if(old('completion_status') == 'completed') selected @endif>✅ مكتملة</option>
                                            <option value="no_show" @if(old('completion_status') == 'no_show') selected @endif>❌ الطالب لم يحضر</option>
                                            <option value="technical_issue" @if(old('completion_status') == 'technical_issue') selected @endif>⚠️ مشكلة تقنية</option>
                                        </select>
                                    </div>
                                    <x-input-error :messages="$errors->get('completion_status')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="teacher_notes" :value="__('ملاحظات (للإدارة وأولياء الأمور)')" />
                                <textarea id="teacher_notes" name="teacher_notes" rows="3" class="block mt-2 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="اكتب ملاحظاتك حول أداء الطلاب أو الواجبات...">{{ old('teacher_notes') }}</textarea>
                                <x-input-error :messages="$errors->get('teacher_notes')" class="mt-2" />
                            </div>

                            <!-- Collapsible Optional Fields -->
                            <div x-data="{ open: false }" class="border border-gray-200 rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open" class="flex justify-between items-center w-full px-4 py-3 text-sm font-medium text-left text-gray-700 hover:bg-gray-100 focus:outline-none rounded-lg">
                                    <span>خيارات إضافية (رابط، بيانات JSON)</span>
                                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-200 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="px-4 pb-4 space-y-4 pt-2 border-t border-gray-200">
                                    <div>
                                        <x-input-label for="extension_data" :value="__('بيانات الإضافة (JSON)')" />
                                        <textarea id="extension_data" name="extension_data" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm font-mono text-xs bg-white" placeholder="Paste JSON here...">{{ old('extension_data') }}</textarea>
                                    </div>

                                    <div>
                                        <x-input-label for="google_meet_link" :value="__('رابط جوجل ميت')" />
                                        <x-text-input id="google_meet_link" class="block mt-1 w-full bg-white" type="url" name="google_meet_link" :value="old('google_meet_link')" placeholder="https://meet.google.com/..." />
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
                            <a href="{{ route('teacher.schedule.index') }}" class="px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ml-3 transition-colors">
                                إلغاء
                            </a>
                            <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md transition-transform active:scale-95">
                                {{ __('حفظ وتسجيل الحصة') }}
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Styles for Custom Scrollbar -->
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #c7c7c7; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #a0a0a0; }
    </style>

    <!-- JavaScript for Interactivity -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            const countSpan = document.getElementById('selected-count');
            const selectAllBtn = document.getElementById('btn-select-all');
            const deselectAllBtn = document.getElementById('btn-deselect-all');

            function updateCount() {
                if (countSpan) {
                    const count = document.querySelectorAll('.student-checkbox:checked').length;
                    countSpan.textContent = count;
                }
            }

            function toggleStyle(checkbox) {
                const label = checkbox.closest('label');
                const icon = label.querySelector('.checkmark-icon');
                
                if (checkbox.checked) {
                    label.classList.remove('bg-white', 'border-gray-200', 'hover:border-indigo-300');
                    label.classList.add('bg-indigo-50', 'border-indigo-500', 'ring-1', 'ring-indigo-500');
                    icon.classList.remove('hidden');
                    icon.classList.add('block');
                } else {
                    label.classList.add('bg-white', 'border-gray-200', 'hover:border-indigo-300');
                    label.classList.remove('bg-indigo-50', 'border-indigo-500', 'ring-1', 'ring-indigo-500');
                    icon.classList.remove('block');
                    icon.classList.add('hidden');
                }
            }

            // Initialize
            checkboxes.forEach(cb => {
                toggleStyle(cb);
                cb.addEventListener('change', () => {
                    toggleStyle(cb);
                    updateCount();
                });
            });
            updateCount();

            // Select All
            if (selectAllBtn) {
                selectAllBtn.addEventListener('click', () => {
                    checkboxes.forEach(cb => {
                        cb.checked = true;
                        toggleStyle(cb);
                    });
                    updateCount();
                });
            }

            // Deselect All
            if (deselectAllBtn) {
                deselectAllBtn.addEventListener('click', () => {
                    checkboxes.forEach(cb => {
                        cb.checked = false;
                        toggleStyle(cb);
                    });
                    updateCount();
                });
            }
        });
    </script>
</x-app-layout>