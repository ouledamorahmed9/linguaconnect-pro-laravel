<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.messages.index') }}" class="ml-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
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
                <div class="p-6 text-gray-900 dark: text-gray-100">
                    <form method="POST" action="{{ route('admin.messages.store') }}" x-data="messageForm()">
                        @csrf

                        <!-- Recipient Type -->
                        <div class="mb-6">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-3">
                                إرسال إلى <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <label class="relative flex cursor-pointer rounded-lg border p-4 focus:outline-none" : class="recipientType === 'teacher' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-300 dark:border-gray-600'">
                                    <input type="radio" name="recipient_type" value="teacher" x-model="recipientType" class="sr-only">
                                    <span class="flex flex-1 flex-col text-center">
                                        <svg class="mx-auto h-6 w-6 mb-2" : class="recipientType === 'teacher' ? 'text-indigo-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span class="text-sm font-medium" : class="recipientType === 'teacher' ? 'text-indigo-900 dark:text-indigo-100' : 'text-gray-900 dark:text-gray-100'">معلم محدد</span>
                                    </span>
                                </label>

                                <label class="relative flex cursor-pointer rounded-lg border p-4 focus:outline-none" :class="recipientType === 'coordinator' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-300 dark:border-gray-600'">
                                    <input type="radio" name="recipient_type" value="coordinator" x-model="recipientType" class="sr-only">
                                    <span class="flex flex-1 flex-col text-center">
                                        <svg class="mx-auto h-6 w-6 mb-2" :class="recipientType === 'coordinator' ? 'text-indigo-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-. 126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5. 002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span class="text-sm font-medium" :class="recipientType === 'coordinator' ? 'text-indigo-900 dark:text-indigo-100' : 'text-gray-900 dark:text-gray-100'">منسق محدد</span>
                                    </span>
                                </label>

                                <label class="relative flex cursor-pointer rounded-lg border p-4 focus:outline-none" :class="recipientType === 'all_teachers' ? 'border-indigo-500 bg-indigo-50 dark: bg-indigo-900/20' :  'border-gray-300 dark: border-gray-600'">
                                    <input type="radio" name="recipient_type" value="all_teachers" x-model="recipientType" class="sr-only">
                                    <span class="flex flex-1 flex-col text-center">
                                        <svg class="mx-auto h-6 w-6 mb-2" :class="recipientType === 'all_teachers' ?  'text-indigo-600' :  'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <span class="text-sm font-medium" :class="recipientType === 'all_teachers' ? 'text-indigo-900 dark:text-indigo-100' : 'text-gray-900 dark:text-gray-100'">كل المعلمين</span>
                                    </span>
                                </label>

                                <label class="relative flex cursor-pointer rounded-lg border p-4 focus:outline-none" :class="recipientType === 'all_coordinators' ?  'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-300 dark:border-gray-600'">
                                    <input type="radio" name="recipient_type" value="all_coordinators" x-model="recipientType" class="sr-only">
                                    <span class="flex flex-1 flex-col text-center">
                                        <svg class="mx-auto h-6 w-6 mb-2" :class="recipientType === 'all_coordinators' ?  'text-indigo-600' :  'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span class="text-sm font-medium" :class="recipientType === 'all_coordinators' ? 'text-indigo-900 dark:text-indigo-100' : 'text-gray-900 dark:text-gray-100'">كل المنسقين</span>
                                    </span>
                                </label>
                            </div>
                            @error('recipient_type')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Select Specific Teacher -->
                        <div class="mb-6" x-show="recipientType === 'teacher'" x-transition>
                            <label for="recipient_id_teacher" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                اختر المعلم <span class="text-red-500">*</span>
                            </label>
                            <select id="recipient_id_teacher" name="recipient_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark: focus:ring-indigo-600 rounded-md shadow-sm" x-bind: disabled="recipientType !== 'teacher'">
                                <option value="">-- اختر معلم --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Select Specific Coordinator -->
                        <div class="mb-6" x-show="recipientType === 'coordinator'" x-transition>
                            <label for="recipient_id_coordinator" class="block font-medium text-sm text-gray-700 dark: text-gray-300">
                                اختر المنسق <span class="text-red-500">*</span>
                            </label>
                            <select id="recipient_id_coordinator" name="recipient_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" x-bind:disabled="recipientType !== 'coordinator'">
                                <option value="">-- اختر منسق --</option>
                                @foreach($coordinators as $coordinator)
                                    <option value="{{ $coordinator->id }}">{{ $coordinator->name }} ({{ $coordinator->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Category -->
                        <div class="mb-6">
                            <label for="category" class="block font-medium text-sm text-gray-700 dark: text-gray-300">
                                تصنيف الرسالة <span class="text-red-500">*</span>
                            </label>
                            <select id="category" name="category" required class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="general">عام</option>
                                <option value="announcement">إعلان</option>
                                <option value="notice">تنبيه</option>
                                <option value="urgent">عاجل</option>
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
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover: bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
        function messageForm() {
            return {
                recipientType: '{{ old('recipient_type', 'teacher') }}'
            }
        }
    </script>
</x-app-layout>