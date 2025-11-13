<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('تسجيل حصة مكتملة') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Use Alpine.js to manage the form state -->
                <form 
                    method="POST" 
                    action="{{ route('teacher.sessions.log.store', $weeklySlot) }}"
                    x-data="{ fileUrlHasText: false, jsonHasText: false }"
                >
                    @csrf
                    
                    <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold border-b pb-3 mb-6 dark:border-gray-700">تفاصيل الحصة الأسبوعية</h3>

                        <!-- Info Box (unchanged) -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border dark:border-gray-700">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">العميل</dt>
                                <dd class="text-md font-semibold text-gray-900 dark:text-gray-100">{{ $weeklySlot->client->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">اليوم</dt>
                                <dd class="text-md font-semibold text-gray-900 dark:text-gray-100">{{ $weeklySlot->day_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">التوقيت</dt>
                                <dd class="text-md font-semibold text-gray-900 dark:text-gray-100">{{ $weeklySlot->start_time_12 }}</dd>
                            </div>
                        </div>

                        <!-- Form Fields (unchanged) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <x-input-label for="topic" :value="__('عنوان الدرس')" />
                                <x-text-input id="topic" class="block mt-1 w-full" type="text" name="topic" :value="old('topic')" required autofocus />
                                <x-input-error :messages="$errors->get('topic')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="completion_status" :value="__('حالة الحصة')" />
                                <select id="completion_status" name="completion_status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="completed" @selected(old('completion_status') == 'completed')>أكملت بنجاح</option>
                                    <option value="no_show_client" @selected(old('completion_status') == 'no_show_client')>لم يحضر العميل</option>
                                    <option value="no_show_teacher" @selected(old('completion_status') == 'no_show_teacher')>لم يحضر المعلم (مشكلة فنية)</option>
                                </select>
                                <x-input-error :messages="$errors->get('completion_status')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-input-label for="notes" :value="__('ملاحظات الحصة (اختياري)')" />
                            <textarea id="notes" name="notes" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <!-- === START OF NEW FEATURE === -->

                        <div class="mt-6">
                            <x-input-label for="session_proof" :value="__('رابط إثبات الحصة (اختياري)')" />
                            <x-text-input 
                                id="session_proof" 
                                class="block mt-1 w-full" 
                                type="url" 
                                name="session_proof" 
                                :value="old('session_proof')" 
                                placeholder="https://drive.google.com/..." 
                                @input="fileUrlHasText = $event.target.value.length > 0"
                                :disabled="jsonHasText"
                                :class="{ 'opacity-50 cursor-not-allowed': jsonHasText }"
                            />
                            <x-input-error :messages="$errors->get('session_proof')" class="mt-2" />
                        </div>
                        
                        <!-- Divider -->
                        <div class="flex items-center my-6">
                            <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
                            <span class="flex-shrink mx-4 text-gray-500 dark:text-gray-400 font-medium">OR</span>
                            <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
                        </div>

                        <!-- Proof Method 2: Paste JSON -->
                        <div>
                            <x-input-label for="session_json">
                                {{ __('لصق تقرير JSON (اختياري)') }}
                                <span class="text-xs text-gray-500 dark:text-gray-400">(من "Copy JSON" في الإضافة)</span>
                            </x-input-label>
                            <textarea 
                                id="session_json" 
                                name="session_json" 
                                rows="8" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm font-mono text-sm"
                                placeholder='{ "title": "N/A", "date": "N/A", ... }'
                                @input="jsonHasText = $event.target.value.length > 0"
                                :disabled="fileUrlHasText"
                                :class="{ 'opacity-50 cursor-not-allowed': fileUrlHasText }"
                            >{{ old('session_json') }}</textarea>
                            <x-input-error :messages="$errors->get('session_json')" class="mt-2" />
                        </div>

                        <!-- === END OF NEW FEATURE === -->


                        <!-- Submit Button (unchanged) -->
                        <div class="flex items-center justify-end mt-6 border-t pt-6 dark:border-gray-700">
                            <a href="{{ route('teacher.schedule.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">
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