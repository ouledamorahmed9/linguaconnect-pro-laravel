<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{-- 'تسجيل حصة مكتملة' --}}
            {{ __('Log Completed Session') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{-- معلومات الحصة --}}
                        Session Information
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{-- أنت تسجل الحصة لـ --}}
                        You are logging the session for: <strong>{{ $appointment->client->name }}</strong>
                    </p>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{-- وقت الحصة --}}
                        Session Time: <strong>{{ $appointment->start_time->format('D, M j, Y \a\t g:i A') }}</strong>
                    </p>

                    <!-- This is the Alpine.js component -->
                    <form 
                        method="POST" 
                        action="{{ route('teacher.sessions.log.store', $appointment) }}" 
                        enctype="multipart/form-data"
                        x-data="{ proofType: 'file', jsonHasText: false, fileIsSelected: false }"
                        class="mt-6 space-y-6"
                    >
                        @csrf

                        <!-- Proof Method 1: File Upload -->
                        <div>
                            <x-input-label for="session_proof" :value="__('Upload Proof (Screenshot or Video)')" />
                            <input 
                                id="session_proof" 
                                name="session_proof" 
                                type="file" 
                                class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none dark:placeholder-gray-400"
                                @change="fileIsSelected = $event.target.files.length > 0"
                                :disabled="jsonHasText"
                                :class="{ 'opacity-50 cursor-not-allowed': jsonHasText }"
                            >
                            <x-input-error :messages="$errors->get('session_proof')" class="mt-2" />
                        </div>

                        <!-- Divider -->
                        <div class="flex items-center">
                            <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
                            <span class="flex-shrink mx-4 text-gray-500 dark:text-gray-400 font-medium">OR</span>
                            <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
                        </div>

                        <!-- Proof Method 2: Paste JSON -->
                        <div>
                            <x-input-label for="session_json">
                                {{ __('Paste JSON Report') }}
                                <span class="text-xs text-gray-500 dark:text-gray-400">(From "Copy JSON" in extension)</span>
                            </x-input-label>
                            <textarea 
                                id="session_json" 
                                name="session_json" 
                                rows="8" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm font-mono text-sm"
                                placeholder='{ "title": "N/A", "date": "N/A", ... }'
                                @input="jsonHasText = $event.target.value.length > 0"
                                :disabled="fileIsSelected"
                                :class="{ 'opacity-50 cursor-not-allowed': fileIsSelected }"
                            ></textarea>
                            <x-input-error :messages="$errors->get('session_json')" class="mt-2" />
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center gap-4">
                            <x-primary-button>
                                {{-- 'تسجيل الحصة' --}}
                                {{ __('Log Session') }}
                            </x-primary-button>

                            @if (session('status') === 'session-logged')
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600 dark:text-gray-400"
                                >{{ __('Saved.') }}</p>
                            @endif
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>