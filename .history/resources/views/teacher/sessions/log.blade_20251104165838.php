<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تسجيل حصة مكتملة') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    <div class="border-b pb-4 mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">{{ $appointment->topic }}</h3>
                        <div class="flex items-center text-sm text-gray-600 mt-2">
                            <p class="ml-4"><strong>الطالب:</strong> {{ $appointment->client->name }}</p>
                            <p><strong>الوقت:</strong> {{ $appointment->start_time->format('h:i A') }} - {{ $appointment->end_time->format('h:i A') }}</p>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('teacher.sessions.log.store', $appointment) }}" class="space-y-6">
                        @csrf

                        <!-- Session Status -->
                        <div>
                            <x-input-label for="status" :value="__('حالة الحصة')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="completed">مكتملة</option>
                                <option value="student_no_show">الطالب لم يحضر</option>
                                <option value="technical_issue">مشكلة تقنية</option>
                            </select>
                        </div>

                        <!-- Session Proof ID (Replaces Duration) -->
                        <div>
                            <x-input-label for="session_proof_id" :value="__('معرف إثبات الجلسة (Meetlist.io ID)')" />
                            <x-text-input id="session_proof_id" class="block mt-1 w-full ltr" type="text" name="session_proof_id" :value="old('session_proof_id')" required autocomplete="off" placeholder="e.g., a-b-c-d-e-f-g-h" />
                            <x-input-error :messages="$errors->get('session_proof_id')" class="mt-2" />
                        </div>

                        <!-- Teacher Notes -->
                        <div>
                            <x-input-label for="notes" :value="__('ملاحظات الدرس (ستكون مرئية لولي الأمر)')" />
                            <textarea id="notes" name="notes" rows="6" required class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="مثال: كان أداء الطالب ممتازاً اليوم. ركزنا على مفردات المطار والفنادق. الواجب المنزلي هو كتابة حوار قصير...">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('teacher.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                إلغاء
                            </a>

                            <x-primary-button>
                                {{ __('حفظ وتأكيد الحصة') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

