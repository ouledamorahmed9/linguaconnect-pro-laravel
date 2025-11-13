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
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            العميل
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            المعلم
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            تاريخ الحصة
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            الحالة (من المعلم)
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            الموضوع
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            الإجراءات
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($sessions as $session)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $session->client->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $session->teacher->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($session->start_time)->translatedFormat('l, d F Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($session->completion_status == 'completed')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        مكتملة
                                                    </span>
                                                @elseif($session->completion_status == 'no_show')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        الطالب لم يحضر
                                                    </span>
                                                @elseif($session->completion_status == 'technical_issue')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        مشكلة تقنية
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        -
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $session->topic }}
                                            </td>
                                            
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2 rtl:space-x-reverse">
                                                
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
            sessionData = JSON.parse(atob('{{ base64_encode($session->extension_data) }}'));
            reportModalOpen = true;
        "
    >
        عرض التقرير
    </x-secondary-button>
@endif


                                                

                                                <form method="POST" action="{{ route('admin.sessions.dispute', $session) }}" class="inline-block" onsubmit="return confirm('هل أنت متأكد من رفض هذه الحصة وإرسالها للنزاعات؟');">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900">رفض (نزاع)</button>
                                                </form>

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
    <div class="p-6 bg-white rounded-xl shadow-lg" x-show="sessionData">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                📋 تقرير الحصة
            </h2>
            <button
                @click="reportModalOpen = false"
                class="text-gray-500 hover:text-gray-800 text-lg font-bold"
            >
                ✕
            </button>
        </div>

        <div class="space-y-5 text-gray-800">
            <!-- General Info -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-500 mb-1">العنوان</div>
                    <div class="font-semibold text-gray-900" x-text="sessionData.title || 'N/A'"></div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-500 mb-1">تاريخ الحصة</div>
                    <div class="font-semibold text-gray-900" x-text="sessionData.date || 'N/A'"></div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-500 mb-1">وقت البدء</div>
                    <div class="font-semibold text-gray-900" x-text="sessionData.startTime || 'N/A'"></div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-500 mb-1">وقت الانتهاء</div>
                    <div class="font-semibold text-gray-900" x-text="sessionData.endTime || 'N/A'"></div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 col-span-2">
                    <div class="text-sm text-gray-500 mb-1">المدة الإجمالية</div>
                    <div class="font-semibold text-gray-900" x-text="sessionData.duration || 'N/A'"></div>
                </div>
            </div>

            <!-- Participants Section -->
            <div class="bg-gray-100 rounded-xl p-5 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    👥 بيانات الحضور 
                    <span class="text-sm text-gray-600 font-normal" x-text="'(' + (sessionData.participantsCount || 0) + ')'"></span>
                </h3>

                <template x-if="sessionData.participants && sessionData.participants.length > 0">
                    <ul class="divide-y divide-gray-200 bg-white rounded-lg border border-gray-200">
                        <template x-for="(participant, index) in sessionData.participants" :key="index">
                            <li class="p-4 flex justify-between items-start hover:bg-gray-50">
                                <div>
                                    <div class="font-semibold text-gray-900" x-text="participant.name"></div>
                                    <div class="text-sm text-gray-600 mt-1">
                                        ⏰ وقت الحضور:
                                        <span x-text="participant.firstSeen"></span>
                                        |
                                        🕓 مدة البقاء:
                                        <span x-text="participant.timeInCall"></span>
                                    </div>
                                </div>
                            </li>
                        </template>
                    </ul>
                </template>

                <template x-if="!sessionData.participants || sessionData.participants.length === 0">
                    <p class="text-center text-gray-500 py-4">لا توجد بيانات حضور.</p>
                </template>
            </div>
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