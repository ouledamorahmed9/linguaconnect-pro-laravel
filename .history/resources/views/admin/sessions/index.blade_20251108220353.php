<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Session Verification') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proof</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($pendingSessions as $session)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $session->teacher->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $session->client->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $session->start_time->format('Y-m-d H:i') }}</td>
                                    
                                    <!-- NEW STATUS LOGIC -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($session->meetReport && $session->status === 'completed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Auto-Verified
                                            </span>
                                        @elseif($session->status === 'conflict')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Review Required
                                            </span>
                                        @elseif($session->status === 'verified')
                                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Verified (Manual)
                                            </span>
                                        @elseif($session->status === 'rejected')
                                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Rejected
                                            </span>
                                        @else
                                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ ucfirst($session->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <!-- NEW PROOF LOGIC -->
                                    <td class="px-6 py-4 whitespace-nowrap" x-data="{ open: false }">
                                        @if($session->meetReport)
                                            <x-primary-button @click="open = true">View Report</x-primary-button>
                                            
                                            <!-- Modal -->
                                            <div x-show="open" @click.away="open = false" class="fixed z-10 inset-0 overflow-y-auto" style="display: none;">
                                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                                    </div>
                                                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                            <h3 class="text-lg leading-6 font-medium text-gray-900">Meet Report: {{ $session->meetReport->meeting_code }}</h3>
                                                            <div class="mt-2 text-sm text-gray-600 space-y-2">
                                                                <p><strong>Duration:</strong> {{ $session->meetReport->report_data['duration'] ?? 'N/A' }}</p>
                                                                <p><strong>Participants ({{ $session->meetReport->report_data['participantsCount'] ?? 'N/A' }}):</strong></p>
                                                                <ul class="list-disc list-inside bg-gray-50 p-3 rounded max-h-48 overflow-y-auto">
                                                                    @foreach($session->meetReport->report_data['participants'] ?? [] as $participant)
                                                                        <li>{{ $participant['name'] }} ({{ $participant['timeInCall'] }})</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                            <button @click="open = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 sm:mt-0 sm:w-auto sm:text-sm">
                                                                Close
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($session->session_proof)
                                            <a href="{{ asset('storage/' . $session->session_proof) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">View Old Proof</a>
                                        @else
                                            <span class="text-gray-400">None</span>
                                        @endif
                                    </td>

                                    <!-- NEW ACTIONS LOGIC -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if(!in_array($session->status, ['verified', 'rejected', 'completed']))
                                            <form action="{{ route('admin.sessions.verify', $session->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900">Verify</button>
                                            </form>
                                            <form action="{{ route('admin.sessions.reject', $session->id) }}" method="POST" class="inline ml-4">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                            </form>
                                        @else
                                            <span class="text-gray-500">Actioned</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>