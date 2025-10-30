<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تقارير التقدم') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">سجل الحصص المكتملة</h3>
                    <p class="text-gray-600 mb-6">هنا يمكنك مراجعة ملاحظات المعلمين بعد كل حصة مكتملة لمتابعة تطور مستوى طفلك.</p>

                    <div class="space-y-4" x-data="{ open: 0 }">
                        @forelse($reports as $index => $report)
                            <div class="border rounded-lg">
                                <div @click="open = (open === {{ $index }} ? -1 : {{ $index }})" class="p-4 cursor-pointer flex justify-between items-center bg-gray-50 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center">
                                        <div class="text-center w-12 mr-4">
                                            <p class="text-indigo-600 font-bold text-lg">{{ $report->start_time->format('d') }}</p>
                                            <p class="text-gray-500 text-sm">{{ $report->start_time->translatedFormat('M') }}</p>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $report->topic }}</p>
                                            <p class="text-sm text-gray-600">مع الأستاذ/ة: {{ $report->teacher->name }}</p>
                                        </div>
                                    </div>
                                    {{-- Show status badge --}}
                                    @if($report->status == 'completed')
                                        <span class="hidden sm:inline-flex text-xs font-semibold px-2 py-1 bg-green-100 text-green-800 rounded-full">مكتملة</span>
                                    @else
                                        <span class="hidden sm:inline-flex text-xs font-semibold px-2 py-1 bg-amber-100 text-amber-800 rounded-full">ملاحظة</span>
                                    @endif
                                </div>
                                <div x-show="open === {{ $index }}" x-transition class="p-6 border-t border-gray-200 text-sm text-gray-700 leading-relaxed">
                                    <p class="whitespace-pre-wrap">{{ $report->teacher_notes }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-semibold text-gray-900">لا توجد تقارير بعد</h3>
                                <p class="mt-1 text-sm text-gray-500">سيظهر تقرير الحصة الأولى هنا بعد اكتمالها وتسجيلها من قبل المعلم.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

