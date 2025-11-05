<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تسجيل حصة مكتملة') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- 
                ==================================================================
                THIS IS THE FIX:
                The form now points to the 'weeklySlot' variable, not 'appointment'.
                ==================================================================
                -->
                <form method="POST" action="{{ route('teacher.sessions.log.store', $weeklySlot) }}">
                    @csrf
                    
                    <div class="p-6 md:p-8 text-gray-900">
                        <h3 class="text-lg font-semibold border-b pb-3 mb-6">تفاصيل الحصة الأسبوعية</h3>

                        <!-- Info Box -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-gray-50 p-4 rounded-lg border">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">العميل</dt>
                                <dd class="text-md font-semibold text-gray-900">{{ $weeklySlot->client->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">اليوم</dt>
                                <dd class="text-md font-semibold text-gray-900">
                                    @php
                                        $days = [ 0 => 'الأحد', 1 => 'الاثنين', 2 => 'الثلاثاء', 3 => 'الأربعاء', 4 => 'الخميس', 5 => 'الجمعة', 6 => 'السبت'];
                                    @endphp
                                    {{ $days[$weeklySlot->day_of_week] }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">الوقت</dt>
                                <dd class="text-md font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($weeklySlot->start_time)->format('h:i A') }}
                                </dd>
                            </div>
                        </div>

                        <!-- Validation Errors -->
                        @if ($errors->any())
                            <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                                <strong>خطأ!</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Form Fields -->
                        <div class="space-y-6">
                            
                            <!-- ** NEW: Session Date ** -->
                            <div>
                                <x-input-label for="session_date" :value="__('تاريخ الحصة التي أكملتها')" />
                                <x-text-input id="session_date" class="block mt-1 w-full" type="date" name="session_date" :value="old('session_date', now()->format('Y-m-d'))" required />
                                <p class="mt-1 text-xs text-gray-500">الرجاء اختيار التاريخ الذي يتطابق مع يوم الحصة (e.g., إذا كانت الحصة يوم الاثنين, اختر تاريخ يوم الاثنين).</p>
                                <x-input-error :messages="$errors->get('session_date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="topic" :value="__('موضوع الحصة (ما تم تدريسه)')" />
                                <x-text-input id="topic" class="block mt-1 w-full" type="text" name="topic" :value="old('topic')" required />
                                <x-input-error :messages="$errors->get('topic')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="notes" :value="__('ملاحظات (للإدارة وأولياء الأمور)')" />
                                <textarea id="notes" name="notes" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="session_proof" :value="__('رابط إثبات الحصة (اختياري)')" />
                                <x-text-input id="session_proof" class="block mt-1 w-full" type="url" name="session_proof" :value="old('session_proof')" placeholder="https://drive.google.com/..." />
                                <x-input-error :messages="$errors->get('session_proof')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Submit Button -->
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

