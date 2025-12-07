<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تعديل الحصة الأسبوعية') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ search: '' }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('teacher.schedule.update', $weeklySlot) }}">
                    @csrf
                    @method('PATCH')

                    <div class="p-6 md:p-8 space-y-6 text-gray-900">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">اليوم</p>
                                @php
                                    $daysOfWeek = [1=>'الاثنين',2=>'الثلاثاء',3=>'الأربعاء',4=>'الخميس',5=>'الجمعة',6=>'السبت',0=>'الأحد'];
                                @endphp
                                <p class="text-sm font-semibold text-gray-800">{{ $daysOfWeek[$weeklySlot->day_of_week] ?? 'غير معروف' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">الوقت</p>
                                <p class="text-sm font-semibold text-gray-800">{{ \Carbon\Carbon::parse($weeklySlot->start_time)->format('h:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">المُدرس</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $weeklySlot->teacher->name ?? '' }}</p>
                            </div>
                        </div>

                        <div>
                            <x-input-label :value="__('الطلاب (ألغِ تحديد من تريد حذفه)')" />
                            <div class="mt-2 mb-2">
                                <div class="relative">
                                    <input
                                        x-model="search"
                                        type="text"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm pr-10"
                                        placeholder="ابحث عن طالب بالاسم"
                                    />
                                    <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1 0 6.5 6.5a7.5 7.5 0 0 0 10.15 10.15Z"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="space-y-2 max-h-64 overflow-auto rounded-md border border-gray-200 p-3 bg-white">
                                @forelse($clients as $client)
                                    @php $nameLower = \Illuminate\Support\Str::lower($client->name); @endphp
                                    <label
                                        class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-indigo-50 cursor-pointer"
                                        x-show="search.trim() === '' || '{{ $nameLower }}'.includes(search.toLowerCase())"
                                        x-cloak
                                    >
                                        <input type="checkbox"
                                               name="students[]"
                                               value="{{ $client->id }}"
                                               class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                               @checked($weeklySlot->students->pluck('id')->contains($client->id))>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-900">{{ $client->name }}</span>
                                            <span class="text-xs text-gray-500">{{ $client->email }}</span>
                                        </div>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500 px-2">لا يوجد طلاب متاحون.</p>
                                @endforelse
                            </div>
                            <x-input-error :messages="$errors->get('students')" class="mt-2" />
                            <x-input-error :messages="$errors->get('students.*')" class="mt-1" />
                        </div>

                        <div class="flex items-center justify-end pt-4 gap-3">
                            <a href="{{ route('teacher.schedule.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm font-semibold">
                                إلغاء
                            </a>
                            <x-primary-button>
                                {{ __('حفظ التغييرات') }}
                            </x-primary-button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>