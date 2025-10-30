<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('حجز موعد جديد') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    <form method="POST" action="{{ route('admin.appointments.store') }}" class="space-y-6">
                        @csrf

                        <!-- Client Selection -->
                        <div>
                            <x-input-label for="client_id" :value="__('اختر العميل (ولي الأمر)')" />
                            <select id="client_id" name="client_id" required class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">-- يرجى الاختيار --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Teacher Selection -->
                        <div>
                            <x-input-label for="teacher_id" :value="__('اختر المعلم')" />
                            <select id="teacher_id" name="teacher_id" required class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">-- يرجى الاختيار --</option>
                                 @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <hr class="my-6">

                        <!-- Appointment Date & Time -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="date" :value="__('تاريخ الحصة')" />
                                <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" required />
                            </div>
                            <div>
                                <x-input-label for="start_time" :value="__('وقت البدء')" />
                                <x-text-input id="start_time" class="block mt-1 w-full" type="time" name="start_time" required />
                            </div>
                        </div>

                        <!-- Subject & Topic -->
                         <div>
                            <x-input-label for="topic" :value="__('موضوع الدرس')" />
                            <x-text-input id="topic" class="block mt-1 w-full" type="text" name="topic" placeholder="مثال: مراجعة زمن المضارع البسيط" required />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.schedule.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
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
