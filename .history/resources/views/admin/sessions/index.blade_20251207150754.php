<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('مراجعة الحصص المعلقة') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ reportModalOpen: false, reportData: null, rawExtensionData: null, debugText: '' }">
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

            {{-- Debug area --}}
            <div id="debug-area" class="mb-4 text-sm text-blue-700" x-text="debugText"></div>
            
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
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العميل</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المعلم</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">مدار بواسطة</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الحصة</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة (من المعلم)</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الموضوع</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($sessions as $session)
                                        <tr @if($session->client->coordinator) class="bg-red-50" @endif>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $session->client->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $session->teacher->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if($session->client->coordinator)
                                                    <div class="font-medium text-gray-900">{{ $session->client->coordinator->name }}</div>
                                                    <div class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        منسق
                                                    </div>
                                                @else
                                                    <div class="font-medium text-indigo-600">Admin</div>
                                                    <div class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                                        المنصة
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($session->start_time)->translatedFormat('l, d F Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($session->completion_status == 'completed')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">مكتملة</span>
                                                @elseif($session->completion_status == 'no_show')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">الطالب لم يحضر</span>
                                                @elseif($session->completion_status == 'technical_issue')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">مشكلة تقنية</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $session->topic }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-3 rtl:space-x-reverse">
                                                    <form method="POST" action="{{ route('admin.sessions.verify', $session) }}" class="inline-block" onsubmit="return confirm('هل أنت متأكد من اعتماد هذه الحصة؟');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-indigo-600 hover:text-indigo-900">اعتماد</button>
                                                    </form>

                                                    @if($session->extension_data)
                                                    <x-secondary-button
                                                        type="button"
                                                        class="text-xs"
                                                        x-on:click="
                                                            reportData = @js($session->reportData ?? null);
                                                            rawExtensionData = @js($session->rawExtensionData ?? null);
                                                            debugText = 'clicked';
                                                            console.log('reportData:', reportData);
                                                            reportModalOpen = true;
                                                        "
                                                    >
                                                        عرض التقرير
                                                    </x-secondary-button>
                                                    @endif
                                                    
                                                    <form method="POST" action="{{ route('admin.sessions.dispute', $session) }}" class="inline-block" onsubmit="return confirm('هل أنت متأكد من رفض هذه الحصة وإرسالها للنزاع؟');">
                                                        @csrf
                                                        <button type="submit" class="text-red-600 hover:text-red-900">رفض (نزاع)</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.sessions.cancel', $session) }}" class="inline-block" onsubmit="return confirm('تحذير! هل أنت متأكد من الإلغاء النهائي؟ هذا الإجراء لا يمكن التراجع عنه.');">
                                                        @csrf
                                                        <button type="submit" class="font-medium text-red-600 hover:text-red-900">إلغاء نهائي</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $sessions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <template x-if="reportModalOpen">
            <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50" x-transition>
                <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-lg relative" x-transition.scale>
                    <button @click="reportModalOpen = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>

                    <h2 class="text-2xl font-bold text-center mb-4 text-blue-700">📄 تقرير الجلسة</h2>

                    <template x-if="reportData && Object.keys(reportData).length">
                        <div>
                            <div class="space-y-2 text-sm">
                                <p><strong>📅 التاريخ:</strong> <span x-text="reportData.date ?? 'N/A'"></span></p>
                                <p><strong>🕒 المدة:</strong> <span x-text="reportData.duration ?? 'N/A'"></span></p>
                                <p><strong>👥 عدد المشاركين:</strong> <span x-text="reportData.participantsCount ?? 'N/A'"></span></p>
                                <p><strong>⏱ متوسط الوقت في المكالمة:</strong> <span x-text="reportData.avgTimeInCall ?? 'N/A'"></span></p>
                                <p><strong>🕰 وقت البدء:</strong> <span x-text="reportData.startTime ?? 'N/A'"></span></p>
                                <p><strong>🏁 وقت الانتهاء:</strong> <span x-text="reportData.endTime ?? 'N/A'"></span></p>
                            </div>

                            <hr class="my-4">

                            <h3 class="text-lg font-semibold mb-2 text-gray-700">المشاركون</h3>
                            <table class="w-full border text-sm">
                                <thead class="bg-blue-100">
                                    <tr>
                                        <th class="border px-2 py-1">الاسم</th>
                                   