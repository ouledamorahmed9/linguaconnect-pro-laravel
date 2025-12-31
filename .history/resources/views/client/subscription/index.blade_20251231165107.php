<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Subscription') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($subscription)
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Current Plan</h3>
                            <div class="mt-2 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div class="bg-gray-50 p-4 rounded-md">
                                    <span class="block text-sm font-medium text-gray-500">Type</span>
                                    <span class="mt-1 block text-lg font-semibold text-gray-900">{{ ucfirst($subscription->type) }}</span>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-md">
                                    <span class="block text-sm font-medium text-gray-500">Status</span>
                                    <span class="mt-1 block px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-md">
                                    <span class="block text-sm font-medium text-gray-500">Start Date</span>
                                    <span class="mt-1 block text-lg font-semibold text-gray-900">{{ $subscription->start_date->format('M d, Y') }}</span>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-md">
                                    <span class="block text-sm font-medium text-gray-500">End Date</span>
                                    <span class="mt-1 block text-lg font-semibold text-gray-900">{{ $subscription->end_date->format('M d, Y') }}</span>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-md">
                                    <span class="block text-sm font-medium text-gray-500">Remaining Credits</span>
                                    <span class="mt-1 block text-lg font-semibold text-gray-900">{{ $subscription->lesson_credits }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-lg">You don't have an active subscription.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>