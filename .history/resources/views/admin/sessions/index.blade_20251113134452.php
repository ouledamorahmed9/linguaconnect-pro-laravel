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

<div 
    @endif

    <!-- Modal -->
    <template x-if="reportModalOpen">
        <div 
            class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50"
            x-transition
        >
            <div 
                class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-lg relative"
                x-transition.scale
            >
                <!-- Close button -->
                <button 
                    @click="reportModalOpen = false" 
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-700"
                >
                    ✕
                </button>

                <!-- Header -->
                <h2 class="text-2xl font-bold text-center mb-4 text-blue-700">
                    📄 تقرير الجلسة
                </h2>

                <!-- Session Info -->
                <div x-show="sessionData" class="space-y-2 text-sm">
                    <p><strong>📅 التاريخ:</strong> <span x-text="sessionData.date"></span></p>
                    <p><strong>🕒 المدة:</strong> <span x-text="sessionData.duration"></span></p>
                    <p><strong>👥 عدد المشاركين:</strong> <span x-text="sessionData.participantsCount"></span></p>
                    <p><strong>⏱ متوسط الوقت في المكالمة:</strong> <span x-text="sessionData.avgTimeInCall"></span></p>
                    <p><strong>🕰 وقت البدء:</strong> <span x-text="sessionData.startTime"></span></p>
                    <p><strong>🏁 وقت الانتهاء:</strong> <span x-text="sessionData.endTime"></span></p>
                </div>

                <hr class="my-4">

                <!-- Participants Table -->
                <h3 class="text-lg font-semibold mb-2 text-gray-700">المشاركون</h3>
                <table class="w-full border text-sm">
                    <thead class="bg-blue-100">
                        <tr>
                            <th class="border px-2 py-1">الاسم</th>
                            <th class="border px-2 py-1">وقت الدخول</th>
                            <th class="border px-2 py-1">المدة في المكالمة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="participant in sessionData.participants" :key="participant.name">
                            <tr class="hover:bg-gray-50">
                                <td class="border px-2 py-1" x-text="participant.name"></td>
                                <td class="border px-2 py-1" x-text="participant.firstSeen"></td>
                                <td class="border px-2 py-1" x-text="participant.timeInCall"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <!-- Footer -->
                <div class="text-center mt-6">
                    <button 
                        @click="reportModalOpen = false"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                    >
                        إغلاق
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

    </div>
</x-app-layout>