<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- === ADD THIS ENTIRE CARD FOR TEACHERS === -->
            @if(Auth::user()->role === 'teacher')
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('API Token') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Generate an API token to link the Chrome Extension. This token is secret, do not share it.') }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('profile.token') }}" class="mt-6">
                            @csrf
                            <x-primary-button>
                                {{ __('Generate New Token') }}
                            </x-primary-button>
                        </form>

                        @if (session('status') === 'token-generated')
                            <div class="mt-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-md">
                                <p class="font-semibold">{{ __('New token generated. Copy this to your extension. It will not be shown again.') }}</p>
                                <textarea
                                    class="w-full mt-2 p-2 font-mono text-sm bg-gray-50 rounded"
                                    rows="3"
                                    readonly
                                    onclick="this.select();"
                                >{{ session('token') }}</textarea>
                            </div>
                        @endif
                    </section>
                </div>
            </div>
            @endif
            <!-- === END OF NEW CARD === -->

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>