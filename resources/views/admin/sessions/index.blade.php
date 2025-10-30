<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sessions Pending Verification') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ openDisputeModal: false, disputeActionUrl: '', sessionInfo: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Display success/error messages --}}
            @if(session('status'))
                <div class...</div>
            @endif
             @if(session('error'))
                <div class...</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th ...>التفاصيل (المعلم/العميل)</th>
                                    <th ...>موضوع الدرس</th>
                                    <th ...>إثبات الجلسة (ID)</th>
                                    <th ...><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($pendingSessions as $session)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $session->teacher->name }} &rarr; {{ $session->client->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $session->start_time->translatedFormat('d M, Y \a\t h:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $session->topic }}</td>
                                        <td class="px-6 py-4">
                                            <a href="https://meetlist.io/{{ $session->session_proof_id }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-900 font-mono" title="Click to verify">
                                                {{ $session->session_proof_id }} &rarr;
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-left text-sm font-medium space-x-2">
                                            
                                            <a href="{{ route('teacher.sessions.log.create', $session) }}" target="_blank" class="px-3 py-1 bg-gray-200 text-gray-700 text-xs font-semibold rounded-md hover:bg-gray-300">
                                                مراجعة
                                            </a>
                                            
                                            <form action="{{ route('admin.sessions.verify', $session) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type... class="px-3 py-1 bg-green-600 ...">
                                                    تحقق
                                                </button>
                                            </form>
                                            
                                            <!-- THIS IS THE NEW DISPUTE BUTTON -->
                                            <button @click="openDisputeModal = true; 
                                                              disputeActionUrl = '{{ route('admin.sessions.dispute', $session) }}';
                                                              sessionInfo = '{{ $session->teacher->name }} - {{ $session->client->name }} ({{ $session->start_time->translatedFormat('d M') }})';"
                                                    class="text-red-600 hover:text-red-900 text-xs font-semibold">
                                                نزاع
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                            لا توجد جلسات بانتظار التحقق حالياً.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dispute Confirmation & Reason Modal -->
        <div x-show="openDisputeModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50" style="display: none;">
            <div @click.away="openDisputeModal = false" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg mx-4">
                <form :action="disputeActionUrl" method="POST">
                    @csrf
                    <div class="text-right">
                        <div class="flex items-center">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <div class="mr-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">فتح نزاع لجلسة</h3>
                                <p class="text-sm text-gray-500" x-text="sessionInfo"></p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="reason" :value="__('سبب النزاع (ملاحظة داخلية)')" />
                            <textarea id="reason" name="reason" rows="4" required class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="مثال: أبلغ العميل أن المعلم تأخر 20 دقيقة، جاري المتابعة..."></textarea>
                        </div>
                    </div>
                    <div class="mt-6 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                            تأكيد النزاع
                        </button>
                        <button @click="openDisputeModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

