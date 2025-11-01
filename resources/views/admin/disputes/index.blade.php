<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('مركز إدارة النزاعات (المفتوحة)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Display success/error messages --}}
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                    {{ session('status') }}
                </div>
            @endif
             @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-gray-600 mb-6">
                        يرجى مراجعة هذه الجلسات المتنازع عليها واتخاذ إجراء لحلها.
                    </p>

                    @if($openDisputes->isEmpty())
                        <div class="text-center py-12 bg-gray-50 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900">لا توجد نزاعات مفتوحة</h3>
                            <p class="mt-1 text-sm text-gray-500">كل شيء يبدو جيداً هنا.</p>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach ($openDisputes as $dispute)
                                <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                                    <div class="p-6">
                                        <div class="flex flex-col md:flex-row md:justify-between">
                                            <div>
                                                <h3 class="text-lg font-bold text-gray-900">
                                                    جلسة: {{ $dispute->appointment->topic }}
                                                </h3>
                                                <p class="text-sm text-gray-600">
                                                    <strong>المعلم:</strong> {{ $dispute->appointment->teacher->name }} | 
                                                    <strong>العميل:</strong> {{ $dispute->appointment->client->name }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    {{ $dispute->appointment->start_time->translatedFormat('d M, Y \a\t h:i A') }}
                                                </p>
                                            </div>
                                            <div class="mt-4 md:mt-0 md:text-left">
                                                <p class="text-xs text-red-700">
                                                    تم فتح النزاع بواسطة: <strong>{{ $dispute->admin->name }}</strong>
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    في: {{ $dispute->created_at->translatedFormat('d M, Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                            <p class="text-sm font-semibold text-red-800">سبب النزاع:</p>
                                            <p class="text-sm text-red-700">{{ $dispute->reason }}</p>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                                        <!-- CANCEL SESSION FORM -->
                                        <form action="{{ route('admin.disputes.cancel', $dispute) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من رغبتك في إلغاء هذه الحصة؟ لن يتم احتسابها للعميل أو المعلم.');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded-md hover:bg-red-700">إلغاء الحصة</button>
                                        </form>
                                        
                                        <!-- RESOLVE & VERIFY SESSION FORM -->
                                        <form action="{{ route('admin.disputes.resolve', $dispute) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من رغبتك في التحقق من هذه الحصة واحتسابها؟');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1 bg-green-600 text-white text-xs font-semibold rounded-md hover:bg-green-700">تم الحل (تحقق من الجلسة)</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

