<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('حجز حصة جديدة') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    <h3 class="text-lg font-semibold border-b pb-3 mb-6">تفاصيل الحصة</h3>
                    
                    <form method="POST" action="{{ route('admin.appointments.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Teacher Dropdown -->
                            <div>
                                <x-input-label for="teacher_id" :value="__('المعلم')" />
                                <select id="teacher_id" name="teacher_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                    <option value="">-- اختر المعلم --</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" @if(old('teacher_id') == $teacher->id) selected @endif>{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('teacher_id')" class="mt-2" />
                            </div>

                            <!-- Client Dropdown -->
                            <div>
                                <x-input-label for="client_id" :value="__('العميل (الطالب)')" />
                                <select id="client_id" name="client_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                    <option value="">-- اختر العميل --</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" @if(old('client_id') == $client->id) selected @endif>{{ $client->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                            </div>


                            <!-- Topic -->
                            <div class="md:col-span-2">
                                <x-input-label for="topic" :value="__('موضوع الحصة')" />
                                <x-text-input id="topic" class="block mt-1 w-full" type="text" name="topic" :value="old('topic')" required />
                                <x-input-error :messages="$errors->get('topic')" class="mt-2" />
                            </div>

                            <!-- 
                              --- THIS IS THE FIX ---
                              1. Make Start Time span full width
                              2. Remove End Time field
                            -->
                            <div class="md:col-span-2">
                                <x-input-label for="start_time" :value="__('وقت البدء (مدة الحصة 1 ساعة)')" />
                                <x-text-input id="start_time" class="block mt-1 w-full" type="datetime-local" name="start_time" :value="old('start_time')" required />
                                <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                            </div>
                            
                            <!-- End Time (REMOVED) -->
                            
                            <!-- --- END OF FIX --- -->

                            <!-- Google Meet Link -->
                            <div class="md:col-span-2">
                                <x-input-label for="google_meet_link" :value="__('رابط جوجل ميت (اختياري)')" />
                                <x-text-input id="google_meet_link" class="block mt-1 w-full" type="url" name="google_meet_link" :value="old('google_meet_link')" placeholder="https://meet.google.com/..." />
                                <x-input-error :messages="$errors->get('google_meet_link')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end mt-6 border-t pt-6">
                            <a href="{{ route('admin.schedule.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                إلغاء
                            </a>
                            <x-primary-button>
                                {{ __('تأكيد الحجز') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

