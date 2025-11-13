<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Log a Completed Session') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <!-- Session Status Messages -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                         <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-300 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif
                    <!-- End Session Status Messages -->

                    <form method="POST" action="{{ route('teacher.sessions.store') }}">
                        @csrf

                        <!-- Header -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Submit Session Proof</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                Select the appointment and paste the session data from the MyPlatform Sync extension to submit it for verification.
                            </p>
                        </div>

                        <div class="mt-6 space-y-6">
                            <!-- Appointment Selection -->
                            <div>
                                <x-input-label for="appointment_id" :value="__('Select Appointment')" />
                                <select id="appointment_id" name="appointment_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="" disabled {{ old('appointment_id') ? '' : 'selected' }}>-- Please choose a session --</option>
                                    @forelse ($appointments as $appointment)
                                        <option value="{{ $appointment->id }}" {{ old('appointment_id') == $appointment->id ? 'selected' : '' }}>
                                            {{ $appointment->start_time->format('M d, Y \a\t g:i A') }} - {{ $appointment->client->name }}
                                        </option>
                                    @empty
                                        <option value="" disabled>You have no pending sessions to log.</option>
                                    @endforelse
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('appointment_id')" />
                            </div>

                            <!-- Session Proof JSON Input -->
                            <div>
                                <x-input-label for="session_proof" :value="__('Automated Session Proof (JSON)')" />
                                <p class="mt-1 mb-2 text-xs text-gray-500">
                                    Open the MyPlatform Sync extension after your call, copy the generated JSON data, and paste it completely in the box below.
                                </p>
                                <textarea id="session_proof" name="session_proof" rows="10"
                                          class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm font-mono text-sm"
                                          placeholder='{
    "title": "...",
    "duration": "...",
    "participants": [
        { "name": "...", "timeInCall": "..." }
    ]
}'
                                          required>{{ old('session_proof') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('session_proof')" />
                            </div>

                            <!-- Optional Notes -->
                            <div>
                                <x-input-label for="notes" :value="__('Session Notes (Optional)')" />
                                 <p class="mt-1 mb-2 text-xs text-gray-500">
                                    Add any private notes about the session. This is for your records and for the admin, it will not be shown to the client.
                                </p>
                                <textarea id="notes" name="notes" rows="4"
                                          class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                          placeholder="e.g., Client did well on pronunciation, but we need to review irregular verbs next week.">{{ old('notes') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200">
                            <x-primary-button>
                                {{ __('Submit for Verification') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>