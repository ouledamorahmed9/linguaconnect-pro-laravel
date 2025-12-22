<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.study-subjects.index') }}" class="mr-4 text-gray-500 hover: text-gray-700 dark: text-gray-400 dark: hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Add New Study Subject') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm: px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.study-subjects.store') }}">
                        @csrf

                        <!-- Subject Name (English) -->
                        <div class="mb-6">
                            <label for="name" class="block font-medium text-sm text-gray-700 dark: text-gray-300">
                                Subject Name (English) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="e.g., English, French, Quran" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" />
                            @error('name')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subject Name (Arabic) -->
                        <div class="mb-6">
                            <label for="name_ar" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                Subject Name (Arabic)
                            </label>
                            <input type="text" id="name_ar" name="name_ar" value="{{ old('name_ar') }}" dir="rtl" placeholder="مثال: الإنجليزية، الفرنسية، القرآن" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark: focus:ring-indigo-600 rounded-md shadow-sm" />
                            @error('name_ar')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Optional - Arabic translation of the subject name</p>
                        </div>

                        <!-- Subject Color -->
                        <div class="mb-6">
                            <label for="color" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                Subject Color <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 flex items-center space-x-4">
                                <input type="color" id="color" name="color" value="{{ old('color', '#4F46E5') }}" class="h-10 w-20 rounded border border-gray-300 dark:border-gray-600 cursor-pointer" />
                                <input type="text" id="color_text" value="{{ old('color', '#4F46E5') }}" placeholder="#4F46E5" maxlength="7" class="w-32 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark: focus:ring-indigo-600 rounded-md shadow-sm" />
                            </div>
                            @error('color')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This color will be used to identify the subject in the UI</p>
                        </div>

                        <!-- Sort Order -->
                        <div class="mb-6">
                            <label for="sort_order" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                Sort Order
                            </label>
                            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="block mt-1 w-32 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" />
                            @error('sort_order')
                                <p class="text-sm text-red-600 dark: text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Lower numbers appear first in the dropdown</p>
                        </div>

                        <!-- Is Active -->
                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded dark:bg-gray-900 border-gray-300 dark: border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus: ring-offset-gray-800" />
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Active (visible in registration form)</span>
                            </label>
                        </div>

                        <!-- Preview Card -->
                        <div class="mb-6 p-4 bg-gray-50 dark: bg-gray-700 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Preview</h4>
                            <div class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-md border border-gray-200 dark:border-gray-600">
                                <div id="preview_color" class="w-4 h-4 rounded-full mr-3" style="background-color: #4F46E5"></div>
                                <span id="preview_name" class="text-gray-900 dark: text-gray-100">Subject Name</span>
                                <span id="preview_name_ar" class="text-gray-500 dark:text-gray-400 ml-2" dir="rtl"></span>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end space-x-4">
                            <a action="{{ route('admin.study-subjects. store') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark: bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active: bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Create Subject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const colorPicker = document.getElementById('color');
        const colorText = document.getElementById('color_text');
        const previewColor = document. getElementById('preview_color');
        const previewName = document.getElementById('preview_name');
        const previewNameAr = document. getElementById('preview_name_ar');
        const nameInput = document. getElementById('name');
        const nameArInput = document.getElementById('name_ar');

        colorPicker.addEventListener('input', function() {
            colorText.value = this.value;
            previewColor.style.backgroundColor = this.value;
        });

        colorText.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                colorPicker.value = this.value;
                previewColor.style.backgroundColor = this.value;
            }
        });

        nameInput.addEventListener('input', function() {
            previewName.textContent = this.value || 'Subject Name';
        });

        nameArInput.addEventListener('input', function() {
            previewNameAr.textContent = this. value ? '(' + this.value + ')' : '';
        });
    </script>
</x-app-layout>