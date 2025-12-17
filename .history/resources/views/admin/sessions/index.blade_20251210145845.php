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
                                                    
                                                    {{-- Verify --}}
                                                    <form method="POST" action="{{ route('admin.sessions.verify', $session) }}" class="inline-block" onsubmit="return confirm('تأكيد الاعتماد؟');">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="text-indigo-600 hover:text-indigo-900 font-bold">اعتماد</button>
                                                    </form>

                                                    {{-- ** VIEW REPORT BUTTON (PROFESSIONAL) ** --}}
                                                    @if($session->extension_data)
                                                        <button 
                                                            type="button"
                                                            class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                            x-on:click="
                                                                sessionData = {{ \Illuminate\Support\Js::from($session->decrypted_meet_report) }};
                                                                reportModalOpen = true;
                                                            "
                                                        >
                                                            <svg class="-ml-0.5 mr-2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.708V19a2 2 0 01-2 2z"/></svg>
                                                            عرض التقرير
                                                        </button>
                                                    @endif

                                                    <span class="text-gray-300">|</span>

                                                    {{-- Cancel --}}
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

        {{-- ** PROFESSIONAL MODAL ** --}}
        <template x-if="reportModalOpen">
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                {{-- Backdrop --}}
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="reportModalOpen = false" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    {{-- Modal Panel --}}
                    <div class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        
                        {{-- Header --}}
                        <div class="bg-indigo-600 px-4 py-3 sm:px-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                                    تقرير جلسة Google Meet
                                </h3>
                                <button @click="reportModalOpen = false" class="text-indigo-200 hover:text-white">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                        </div>

                        <div class="px-4 pt-5 pb-4 sm:p-6 bg-gray-50">
                            {{-- Error State --}}
                            <div x-show="sessionData && sessionData.error" class="rounded-md bg-red-50 p-4 mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">خطأ في فك التشفير</h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <p x-text="sessionData.error"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Success State --}}
                            <div x-show="sessionData && !sessionData.error">
                                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                                    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                        <h3 class="text-sm leading-6 font-medium text-gray-900">ملخص الاجتماع</h3>
                                    </div>
                                    <div class="border-t border-gray-200 px-4 py-3 sm:p-0">
                                        <dl class="sm:divide-y sm:divide-gray-200">
                                            <div class="py-2 sm:py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                <dt class="text-sm font-medium text-gray-500">التاريخ</dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" x-text="sessionData.date || 'N/A'"></dd>
                                            </div>
                                            <div class="py-2 sm:py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                <dt class="text-sm font-medium text-gray-500">المدة الكلية</dt>
                                                <dd class="mt-1 text-sm font-bold text-green-600 sm:mt-0 sm:col-span-2" x-text="sessionData.duration || 'N/A'"></dd>
                                            </div>
                                            <div class="py-2 sm:py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                <dt class="text-sm font-medium text-gray-500">الوقت</dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                    <span x-text="sessionData.startTime"></span> - <span x-text="sessionData.endTime"></span>
                                                </dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>

                                <h4 class="font-bold text-sm text-gray-900 mb-3">سجل الحضور</h4>
                                <div class="flex flex-col">
                                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">وقت الدخول</th>
                                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">مدة البقاء</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200">
                                                        <template x-for="p in sessionData.participants" :key="p.name">
                                                            <tr>
                                                                <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900" x-text="p.name"></td>
                                                                <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500" x-text="p.firstSeen"></td>
                                                                <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
                                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800" x-text="p.timeInCall"></span>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="reportModalOpen = false">
                                إغلاق
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>