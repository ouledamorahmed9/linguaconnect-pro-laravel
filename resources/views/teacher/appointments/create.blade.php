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
                    <form method="POST" action="{{ route('teacher.appointments.store') }}" class="space-y-6">
                        @csrf

                        <!-- Client Selection -->
                        <div>
                            <x-input-label for="client_id" :value="__('اختر الطالب (ولي الأمر)')" />
                            <select id="client_id" name="client_id" required class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">-- يرجى اختيار الطالب --</option>
                                {{-- This dropdown ONLY shows clients assigned to this teacher --}}
                                @forelse($clients as $client)
                                    <option value="{{ $client->id }}" @if(old('client_id') == $client->id) selected @endif>
                                        {{ $client->name }}
                                    </option>
                                @empty
                                    <option value="" disabled>لم يقم المدير بتعيين أي طلاب لك حتى الآن.</option>
                                @endforelse
                            </select>
                            <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                        </div>
                        
                        <hr class="my-6">

                        <!-- Appointment Date & Time -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="date" :value="__('تاريخ الحصة')" />
                                <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date')" min="{{ date('Y-m-d') }}" required />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="start_time" :value="__('وقت البدء')" />
                                <x-text-input id="start_time" class="block mt-1 w-full" type="time" name="start_time" :value="old('start_time')" required />
                                <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Subject & Topic -->
                         <div>
                            <x-input-label for="topic" :value="__('موضوع الدرس')" />
                            <x-text-input id="topic" class="block mt-1 w-full" type="text" name="topic" :value="old('topic')" placeholder="مثال: مراجعة زمن المضارع البسيط" required />
                             <x-input-error :messages="$errors->get('topic')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('teacher.schedule.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                إلغاء
                            </a>

                            <x-primary-button>
                                {{ __('تأكيد وحجز الموعد') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

