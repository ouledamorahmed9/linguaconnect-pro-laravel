<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Session Verification & History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <h3 class="text-lg font-medium mb-4">Sessions for Review</h3>

                    <!-- This is the container for the responsive table -->
                    <div class="flex flex-col">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="shadow overflow-hidden border-b border-gray-200 dark:border-gray-700 sm:rounded-lg">
                                    
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Client
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Teacher
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Session Time
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Status
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Proof
                                                </th>
                                                <th scope="col" class="relative px-6 py-3">
                                                    <span class="sr-only">Actions</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            
                                            <!-- Use @forelse to handle empty state -->
                                            @forelse ($appointments as $appointment)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $appointment->client->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                    {{ $appointment->teacher->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('D, M j, Y \a\t g:i A') }}
                                                </td>
                                                
                                                <!-- PROFESSIONAL STATUS BADGES -->
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    @switch($appointment->status)
                                                        @case('verified')
                                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                                Verified
                                                            </span>
                                                            @break
                                                        @case('cancelled')
                                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                                                Cancelled
                                                            </span>
                                                            @break
                                                        @case('conflict')
                                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                                                Review Needed
                                                            </span>
                                                            @break
                                                        @case('pending_verification')
                                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                                                Pending Review
                                                            </span>
                                                            @break
                                                        @default
                                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100">
                                                                {{ $appointment->status }}
                                                            </span>
                                                    @endswitch
                                                </td>
                                                
                                                <!-- MEET REPORT BUTTON / MODAL -->
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                    @if ($appointment->meetReport)
                                                        <!-- x-data for the modal -->
                                                        <div x-data="{ open: false }">
                                                            <button @click="open = true" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-medium">
                                                                View Report
                                                            </button>
                                                            
                                                            <!-- Modal Background -->
                                                            <div x-show="open" @click.away="open = false" class="fixed z-10 inset-0 overflow-y-auto" style="display: none;">
                                                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                                                        <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                                                                    </div>
                                                                    <!-- Modal Panel -->
                                                                    <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-3">
                                                                                Meet Report: {{ $appointment->meetReport->meeting_code }}
                                                                            </h3>
                                                                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                                                                <p><strong>Date:</strong> {{ $appointment->meetReport->report_data['date'] ?? 'N/A' }}</p>
                                                                                <p><strong>Duration:</strong> {{ $appointment->meetReport->report_data['duration'] ?? 'N/A' }}</p>
                                                                                <p><strong>Participants ({{ $appointment->meetReport->report_data['participantsCount'] ?? 'N/A' }}):</strong></p>
                                                                                <ul class="list-disc list-inside mt-2 max-h-60 overflow-y-auto">
                                                                                    @if(isset($appointment->meetReport->report_data['participants']) && is_array($appointment->meetReport->report_data['participants']))
                                                                                        @foreach($appointment->meetReport->report_data['participants'] as $participant)
                                                                                            <li>{{ $participant['name'] }} ({{ $participant['timeInCall'] }})</li>
                                                                                        @endforeach
                                                                                    @endif
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                                            <button @click="open = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-500 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                                                Close
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-gray-400 dark:text-gray-500 italic">No Report</span>
                                                    @endif
                                                </td>
                                                
                                                <!-- ACTION BUTTONS -->
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    @if($appointment->status == 'pending_verification' || $appointment->status == 'conflict')
                                                        <form method="POST" action="{{ route('admin.sessions.verify', $appointment) }}" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">
                                                                Verify
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                            
                                            @empty
                                            <!-- EMPTY STATE for @forelse -->
                                            <tr>
                                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                                    No sessions found for review.
                                                </td>
                                            </tr>
                                            @endforelse <!-- <-- THIS WAS THE TYPO. IT'S NOW FIXED. -->
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PAGINATION LINKS -->
                    <div class="mt-6">
                        {{ $appointments->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>