<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('سجل الحصص') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Instructions -->
            <div class="p-4 mb-6 bg-indigo-50 border-l-4 border-indigo-400 rounded-r-lg">
                <p class="text-sm text-indigo-700">
                    <span class="font-semibold">سجلك:</span>
                    هنا يمكنك مراجعة جميع الحصص التي قمت بتسجيلها وحالة مراجعتها من قبل المدير.
                </p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    
                    @if($lessons->isEmpty())
                        <!-- Placeholder if no lessons are logged -->
                        <div class="flex flex-col items-center justify-center h-64 border-2 border-dashed border-gray-300 rounded-lg text-center p-8">
                            <svg class="h-16 w-16 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-semibold text-gray-900">لا يوجد سجل حصص</h3>
                            <p class="mt-2 text-sm text-gray-500">لم تقم بتسجيل أي حصص مكتملة حتى الآن.</p>
                        </div>
                    @else
                        <!-- The new Lesson History List -->
                        <div class="space-y-6">
                            
                            @foreach($lessons as $lesson)
                                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                                    
                                    <!-- Lesson Header -->
                                    <div class="p-4 sm:p-5 bg-gray-50 border-b border-gray-200 flex flex-col sm:flex-row sm:justify-between sm:items-center">
                                        <div>
                                            <p class="text-lg font-bold text-gray-900">
                                                {{ $lesson->subject }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                {{ \Carbon\Carbon::parse($lesson->start_time)->translatedFormat('l, d F Y - h:i A') }}
                                            </p>
                                        </div>
                                        <div class="mt-3 sm:mt-0">
                                            <!-- Admin Status -->
                                            @if($lesson->status == 'verified')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    معتمدة
                                                </span>
                                            @elseif($lesson->status == 'pending_verification')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                                                    بانتظار المراجعة
                                                </span>
                                            @elseif($lesson->status == 'disputed')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    مرفوضة (نزاع)
                                                </span>
                                            @elseif($lesson->status == 'cancelled')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    ملغاة
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Lesson Body -->
                                    <div class="p-4 sm:p-5">
                                        <h4 class="text-md font-semibold text-gray-800">موضوع الحصة: {{ $lesson->topic }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">
                                            العميل: <span class="font-medium">{{ $lesson->client->name }}</span>
                                        </p>
                                        
                                        <!-- Teacher Notes -->
                                        @if($lesson->teacher_notes)
                                            <div class="mt-4 border-t pt-4">
                                                <h5 class="text-sm font-semibold text-gray-700">ملاحظاتك:</h5>
                                                <!-- Use prose for nice text formatting -->
                                                <div class="mt-1 text-sm text-gray-600 prose max-w-none">
                                                    {!! nl2br(e($lesson->teacher_notes)) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            @endforeach

                            <!-- Pagination -->
                            <div class="mt-6">
                                {{ $lessons->links() }}
                            </div>
                        </div>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>