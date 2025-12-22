<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.messages.index') }}" class="ml-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark: hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                إرسال رسالة جديدة
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin. messages.store') }}" id="messageForm">
                        @csrf

                        <!-- Recipient Type -->
                        <div class="mb-6">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-3">
                                إرسال إلى <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <label class="recipient-type-label relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 p-4 focus:outline-none hover:border-indigo-400 transition-colors" data-type="teacher">
                                    <input type="radio" name="recipient_type" value="teacher" class="sr-only" {{ old('recipient_type', 'teacher') === 'teacher' ? 'checked' : '' }}>
                                    <span class="flex flex-1 flex-col text-center">
                                        <svg class="mx-auto h-6 w-6 mb-2 text-gray-400 icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100 label-text">معلم محدد</span>
                                    </span>
                                </label>

                                <label class="recipient-type-label relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 p-4 focus: outline-none hover: border-indigo-400 transition-colors" data-type="coordinator">
                                    <input type="radio" name="recipient_type" value="coordinator" class="sr-only" {{ old('recipient_type') === 'coordinator' ? 'checked' : '' }}>
                                    <span class="flex flex-1 flex-col text-center">
                                        <svg class="mx-auto h-6 w-6 mb-2 text-gray-400 icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-. 126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5. 002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900 dark: text-gray-100 label-text">منسق محدد</span>
                                    </span>
                                </label>

                                <label class="recipient-type-label relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 p-4 focus: outline-none hover: border-indigo-400 transition-colors" data-type="all_teachers">
                                    <input type="radio" name="recipient_type" value="all_teachers" class="sr-only" {{ old('recipient_type') === 'all_teachers' ?  'checked' :  '' }}>
                                    <span class="flex flex-1 flex-col text-center">
                                        <svg class="mx-auto h-6 w-6 mb-2 text-gray-400 icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900 dark: text-gray-100 label-text">كل المعلمين</span>
                                    </span>
                                </label>

                                <label class="recipient-type-label relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 p-4 focus:outline-none hover:border-indigo-400 transition-colors" data-type="all_coordinators">
                                    <input type="radio" name="recipient_type" value="all_coordinators" class="sr-only" {{ old('recipient_type') === 'all_coordinators' ? 'checked' : '' }}>
                                    <span class="flex flex-1 flex-col text-center">
                                        <svg class="mx-auto h-6 w-6 mb-2 text-gray-400 icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100 label-text">كل المنسقين</span>
                                    </span>
                                </label>
                            </div>
                            @error('recipient_type')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hidden recipient_id field that will be updated by JavaScript -->
                        <input type="hidden" name="recipient_id" id="recipient_id" value="{{ old('recipient_id') }}">

                        <!-- Select Specific Teacher -->
                        <div class="mb-6" id="teacher_select_container">
                            <label for="teacher_select" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                اختر المعلم <span class="text-red-500">*</span>
                            </label>
                            <select id="teacher_select" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark: focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">-- اختر معلم --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('recipient_id') == $teacher->id ?  'selected' :  '' }}>
                                        {{ $teacher->name }} ({{ $teacher->email }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">عدد المعلمين المتاحين: {{ $teachers->count() }}</p>
                        </div>

                        <!-- Select Specific Coordinator -->
                        <div class="mb-6 hidden" id="coordinator_select_container">
                            <label for="coordinator_select" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                اختر المنسق <span class="text-red-500">*</span>
                            </label>
                            <select id="coordinator_select" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark: bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark: focus:border-indigo-600 focus:ring-indigo-500 dark: focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">-- اختر منسق --</option>
                                @foreach($coordinators as $coordinator)
                                    <option value="{{ $coordinator->id }}" {{ old('recipient_id') == $coordinator->id ? 'selected' : '' }}>
                                        {{ $coordinator->name }} ({{ $coordinator->email }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">عدد المنسقين المتاحين: {{ $coordinators->count() }}</p>
                        </div>

                        <!-- Info for bulk send -->
                        <div class="mb-6 hidden" id="all_teachers_info">
                            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm text-blue-700 dark:text-blue-300">
                                        سيتم إرسال هذه الرسالة إلى <strong>{{ $teachers->count() }}</strong> معلم
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6 hidden" id="all_coordinators_info">
                            <div class="p-4 bg-blue-50 dark: bg-blue-900/20 rounded-lg border border-blue-200 dark: border-blue-800">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm text-blue-700 dark:text-blue-300">
                                        سيتم إرسال هذه الرسالة إلى <strong>{{ $coordinators->count() }}</strong> منسق
                                    </span>
                                </div>
                            </div>
                        </div>

                        @error('recipient_id')
                            <div class="mb-6">
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            </div>
                        @enderror

                        <!-- Category -->
                        <div class="mb-6">
                            <label for="category" class="block font-medium text-sm text-gray-700 dark: text-gray-300">
                                تصنيف الرسالة <span class="text-red-500">*</span>
                            </label>
                            <select id="category" name="category" required class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="general" {{ old('category') === 'general' ?  'selected' :  '' }}>عام</option>
                                <option value="announcement" {{ old('category') === 'announcement' ? 'selected' : '' }}>إعلان</option>
                                <option value="notice" {{ old('category') === 'notice' ? 'selected' : '' }}>تنبيه</option>
                                <option value="urgent" {{ old('category') === 'urgent' ? 'selected' : '' }}>عاجل</option>
                            </select>
                            @error('category')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subject -->
                        <div class="mb-6">
                            <label for="subject" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                عنوان الرسالة <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required placeholder="أدخل عنوان الرسالة" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            @error('subject')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Body -->
                        <div class="mb-6">
                            <label for="body" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                نص الرسالة <span class="text-red-500">*</span>
                            </label>
                            <textarea id="body" name="body" rows="8" required placeholder="اكتب نص الرسالة هنا..." class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('body') }}</textarea>
                            @error('body')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end space-x-4 rtl:space-x-reverse">
                            <a href="{{ route('admin.messages.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark: text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover: bg-gray-500 transition ease-in-out duration-150">
                                إلغاء
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                إرسال الرسالة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const labels = document.querySelectorAll('.recipient-type-label');
            const teacherContainer = document.getElementById('teacher_select_container');
            const coordinatorContainer = document. getElementById('coordinator_select_container');
            const allTeachersInfo = document.getElementById('all_teachers_info');
            const allCoordinatorsInfo = document.getElementById('all_coordinators_info');
            const teacherSelect = document.getElementById('teacher_select');
            const coordinatorSelect = document.getElementById('coordinator_select');
            const recipientIdInput = document.getElementById('recipient_id');

            function updateUI(selectedType) {
                // Reset all labels
                labels.forEach(label => {
                    label. classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
                    label. classList.add('border-gray-300', 'dark:border-gray-600');
                    label.querySelector('. icon').classList.remove('text-indigo-600');
                    label. querySelector('.icon').classList.add('text-gray-400');
                });

                // Highlight selected label
                const selectedLabel = document.querySelector(`.recipient-type-label[data-type="${selectedType}"]`);
                if (selectedLabel) {
                    selectedLabel.classList.remove('border-gray-300', 'dark:border-gray-600');
                    selectedLabel.classList.add('border-indigo-500', 'bg-indigo-50', 'dark: bg-indigo-900/20');
                    selectedLabel. querySelector('.icon').classList.remove('text-gray-400');
                    selectedLabel.querySelector('.icon').classList.add('text-indigo-600');
                }

                // Hide all containers
                teacherContainer.classList.add('hidden');
                coordinatorContainer.classList.add('hidden');
                allTeachersInfo. classList.add('hidden');
                allCoordinatorsInfo. classList.add('hidden');

                // Show appropriate container
                if (selectedType === 'teacher') {
                    teacherContainer. classList.remove('hidden');
                    recipientIdInput.value = teacherSelect.value;
                } else if (selectedType === 'coordinator') {
                    coordinatorContainer.classList.remove('hidden');
                    recipientIdInput. value = coordinatorSelect.value;
                } else if (selectedType === 'all_teachers') {
                    allTeachersInfo. classList.remove('hidden');
                    recipientIdInput.value = '';
                } else if (selectedType === 'all_coordinators') {
                    allCoordinatorsInfo.classList.remove('hidden');
                    recipientIdInput.value = '';
                }
            }

            // Handle radio button changes
            labels.forEach(label => {
                label. addEventListener('click', function() {
                    const radio = this.querySelector('input[type="radio"]');
                    radio.checked = true;
                    updateUI(this.dataset. type);
                });
            });

            // Handle teacher select change
            teacherSelect.addEventListener('change', function() {
                recipientIdInput.value = this.value;
            });

            // Handle coordinator select change
            coordinatorSelect.addEventListener('change', function() {
                recipientIdInput.value = this.value;
            });

            // Initialize on page load
            const checkedRadio = document.querySelector('input[name="recipient_type"]:checked');
            if (checkedRadio) {
                updateUI(checkedRadio.value);
            } else {
                // Default to teacher
                document.querySelector('input[name="recipient_type"][value="teacher"]').checked = true;
                updateUI('teacher');
            }
        });
    </script>
</x-app-layout>