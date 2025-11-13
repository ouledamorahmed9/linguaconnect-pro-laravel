<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('مراجعة الحصص المعلقة') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ reportModalOpen: false, sessionData: null, sessionJson: '' }">
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
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العميل</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المعلم</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الحصة</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة (من المعلم)</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الموضوع</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($sessions as $session)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $session->client->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $session->teacher->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($session->start_time)->translatedFormat('l, d F Y') }}</td>
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $session->topic }}</td>
                                            
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-2 rtl:space-x-reverse">
                                                    <form method="POST" action="{{ route('admin.sessions.verify', $session) }}" class="inline-block" onsubmit="return confirm('هل أنت متأكد من اعتماد هذه الحصة؟');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="font-medium text-indigo-600 hover:text-indigo-900">اعتماد</button>
                                                    </form>

                                                    @if($session->extension_data)
                                                        <x-secondary-button
                                                            type="button"
                                                            class="text-xs"
                                                            x-on:click="
                                                                sessionData = JSON.parse(@json($session->extension_data));
                                                                sessionJson = JSON.stringify(sessionData, null, 2);
                                                                reportModalOpen = true;
                                                            "
                                                        >
                                                            عرض التقرير
                                                        </x-secondary-button>
                                                    @endif

                                                    <form method="POST" action="{{ route('admin.sessions.dispute', $session) }}" class="inline-block" onsubmit="return confirm('هل أنت متأكد من رفض هذه الحصة وإرسالها للنزاعات؟');">
                                                        @csrf
                                                        <button type="submit" class="font-medium text-red-600 hover:text-red-900">رفض (نزاع)</button>
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

        <x-modal name="report-modal" x-show="reportModalOpen" @close="reportModalOpen = false" max-width="2xl">
            <div class="p-6" x-if="sessionData">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    تقرير بيانات الحصة
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">ملخص الجلسة</h3>
                        <dl class="space-y-4">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">المدة الإجمالية</dt>
                                <dd class="text-sm font-semibold text-gray-900" x-text="sessionData.duration || '---'"></dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">وقت البدء</dt>
                                <dd class="text-sm font-semibold text-gray-900" x-text="sessionData.startTime || '---'"></dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">وقت الانتهاء</dt>
                                <dd class="text-sm font-semibold text-gray-900" x-text="sessionData.endTime || '---'"></dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">متوسط وقت البقاء</dt>
                                <dd class="text-sm font-semibold text-gray-900" x-text="sessionData.avgTimeInCall || '---'"></dd>
                            </div>
                             <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">التاريخ</dt>
                                <dd class="text-sm font-semibold text-gray-900" x-text="sessionData.date || '---'"></dd>
                            </div>
                        </dl>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">
                            الحضور
                            <span class="text-base font-normal text-gray-500" x-text="'(' + (sessionData.participantsCount || 0) + ')'"></span>
                        </h3>
                        <div class="max-h-48 overflow-y-auto pr-2 space-y-3">
                            <template x-if="sessionData.participants && sessionData.participants.length > 0">
                                <template x-for="participant in sessionData.participants" :key="participant.name">
                                    <div class="p-3 bg-gray-50 rounded-lg border">
                                        <div class="font-semibold text-gray-800" x-text="participant.name"></div>
                                        <div class="text-xs text-gray-600 mt-1 flex justify-between">
                                            <span>وقت الحضور: <span class="font-medium text-gray-700" x-text="participant.firstSeen"></span></span>
                                            <span>مدة البقاء: <span class="font-medium text-gray-700" x-text="participant.timeInCall"></span></span>
                                        </div>
                                    </div>
                                </template>
                            </template>
                            <template x-if="!sessionData.participants || sessionData.participants.length === 0">
                                <p class="py-2 text-sm text-gray-500">لا توجد بيانات حضور مسجلة.</p>
                            </template>
                        </div>
                    </div>

                </div>

                <div class="mt-6 pt-4 border-t">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">البيانات المصدرية (JSON)</h3>
                    <pre class="bg-gray-900 text-white text-xs p-4 rounded-lg overflow-x-auto max-h-60" x-text="sessionJson"></pre>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button @click="reportModalOpen = false">
                        إغلاق
                    </x-secondary-button>
                </div>
            </div>
        </x-modal>

    </div>
</x-app-layout>