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
                                            مدار بواسطة
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
                                                <form method="POST" action="{{ route('admin.sessions.cancel', $session) }}" class="inline-block" onsubmit="return confirm('تحذير! هل أنت متأكد من الإلغاء النهائي لهذه الحصة؟ لا يمكن التراجع عن هذا.');">
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
        <div 
            class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50"
            x-transition
        >
            <div 
                class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-lg relative"
                x-transition.scale
            >
                <button 
                    @click="reportModalOpen = false" 
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-700"
                >
                    ✕
                </button>

                <h2 class="text-2xl font-bold text-center mb-4 text-blue-700">
                    📄 تقرير الجلسة
                </h2>

<div class="mt-6 bg-white shadow-sm rounded-lg border border-gray-200">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">عرض التقرير</h3>
        @if($rawExtensionData)
            <span class="text-xs text-gray-500">تم الإرفاق من الامتداد</span>
        @endif
    </div>

    <div class="px-5 py-4">
        @if(!empty($reportData) && is_array($reportData))
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-800">
                <div>
                    <dt class="font-semibold text-gray-600">العنوان</dt>
                    <dd class="mt-1">{{ $reportData['title'] ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-gray-600">التاريخ</dt>
                    <dd class="mt-1">{{ $reportData['date'] ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-gray-600">المدة</dt>
                    <dd class="mt-1">{{ $reportData['duration'] ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-gray-600">عدد المشاركين</dt>
                    <dd class="mt-1">{{ $reportData['participantsCount'] ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-gray-600">متوسط الوقت</dt>
                    <dd class="mt-1">{{ $reportData['avgTimeInCall'] ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-gray-600">وقت البدء</dt>
                    <dd class="mt-1">{{ $reportData['startTime'] ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-gray-600">وقت الانتهاء</dt>
                    <dd class="mt-1">{{ $reportData['endTime'] ?? 'N/A' }}</dd>
                </div>
            </dl>

            @if(!empty($reportData['participants']) && is_array($reportData['participants']))
                <div class="mt-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">المشاركون</h4>
                    <div class="space-y-2">
                        @foreach($reportData['participants'] as $p)
                            <div class="rounded-md border border-gray-100 bg-gray-50 px-3 py-2 text-sm text-gray-800">
                                <div><span class="font-semibold">الاسم:</span> {{ $p['name'] ?? 'N/A' }}</div>
                                <div><span class="font-semibold">أول ظهور:</span> {{ $p['firstSeen'] ?? 'N/A' }}</div>
                                <div><span class="font-semibold">الوقت في المكالمة:</span> {{ $p['timeInCall'] ?? 'N/A' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @elseif($rawExtensionData)
            <div class="text-sm text-gray-600">
                <p class="font-semibold mb-1">لم يتم فك التشفير أو البيانات ليست JSON صالحة.</p>
                <pre class="text-xs bg-gray-100 rounded-md p-3 overflow-x-auto">{{ $rawExtensionData }}</pre>
            </div>
        @else
            <p class="text-sm text-gray-500">لا توجد بيانات تقرير.</p>
        @endif
    </div>
</div>

                <hr class="my-4">

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