<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('مراجعة الحصص المعلقة') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ reportModalOpen: false, sessionData: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                    <strong>خطأ!</strong> {{ $errors->first('message') }}
                </div>
            @endif
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    <h3 class="text-lg font-semibold mb-6 border-b pb-3">الحصص بانتظار المراجعة</h3>

                    @if($sessions->isEmpty())
                        <p class="text-center text-gray-500 py-10">
                            لا يوجد أي حصص بانتظار المراجعة حاليًا.
                        </p>
                    @else
                        <div class="overflow-x-auto border border-gray-200 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">العميل</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المعلم</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($sessions as $session)
                                        <tr @if($session->client->coordinator) class="bg-red-50" @endif>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $session->client->name }}
                                                <div class="text-xs text-gray-500">{{ $session->topic }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $session->teacher->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $session->start_time->format('Y-m-d H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-2 rtl:space-x-reverse">
                                                    
                                                    <form method="POST" action="{{ route('admin.sessions.verify', $session) }}" class="inline-block" onsubmit="return confirm('تأكيد الاعتماد؟');">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="text-indigo-600 hover:text-indigo-900 font-bold">اعتماد</button>
                                                    </form>

                                                    @if($session->extension_data)
                                                        <button 
                                                            type="button"
                                                            class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-800 py-1 px-2 rounded border"
                                                            x-on:click="
                                                                sessionData = {{ \Illuminate\Support\Js::from($session->decrypted_meet_report) }};
                                                                reportModalOpen = true;
                                                            "
                                                        >
                                                            📄 التقرير
                                                        </button>
                                                    @endif

                                                    <span class="text-gray-300">|</span>

                                                    <form method="POST" action="{{ route('admin.sessions.cancel', $session) }}" class="inline-block" onsubmit="return confirm('إلغاء نهائي؟');">
                                                        @csrf
                                                        <button type="submit" class="text-red-600 hover:text-red-900">إلغاء</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6">{{ $sessions->links() }}</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- MODAL --}}
        <template x-if="reportModalOpen">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50" x-transition.opacity>
                <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg mx-4 relative" @click.away="reportModalOpen = false" x-transition.scale>
                    
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-lg font-bold text-gray-800">تفاصيل الجلسة (Google Meet)</h3>
                        <button @click="reportModalOpen = false" class="text-gray-500 hover:text-gray-700">✕</button>
                    </div>

                    {{-- Error State --}}
                    <div x-show="sessionData && sessionData.error" class="bg-red-50 text-red-700 p-3 rounded mb-4 text-sm">
                        <strong>Error:</strong> <span x-text="sessionData.error"></span>
                    </div>

                    {{-- Success State --}}
                    <div x-show="sessionData && !sessionData.error">
                        <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                            <div><span class="text-gray-500">التاريخ:</span> <br><span class="font-semibold" x-text="sessionData.date || 'N/A'"></span></div>
                            <div><span class="text-gray-500">المدة:</span> <br><span class="font-semibold text-green-600" x-text="sessionData.duration || 'N/A'"></span></div>
                            <div><span class="text-gray-500">وقت البدء:</span> <br><span x-text="sessionData.startTime || 'N/A'"></span></div>
                            <div><span class="text-gray-500">وقت الانتهاء:</span> <br><span x-text="sessionData.endTime || 'N/A'"></span></div>
                        </div>

                        <h4 class="font-bold text-sm text-gray-700 mb-2">المشاركون:</h4>
                        <div class="bg-gray-50 rounded border max-h-40 overflow-y-auto">
                            <table class="w-full text-xs text-right">
                                <thead class="bg-gray-100 text-gray-600">
                                    <tr>
                                        <th class="p-2">الاسم</th>
                                        <th class="p-2">المدة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="p in sessionData.participants" :key="p.name">
                                        <tr class="border-t">
                                            <td class="p-2" x-text="p.name"></td>
                                            <td class="p-2 font-mono" x-text="p.timeInCall"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-6 text-left">
                        <button @click="reportModalOpen = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm">إغلاق</button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>