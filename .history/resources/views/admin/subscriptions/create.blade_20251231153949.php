<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تعيين اشتراك جديد للطالب: {{ $client->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form action="{{ route('admin.subscriptions.store', $client->id) }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="plan">
                                اختر الباقة
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                @foreach($plans as $key => $plan)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="plan" value="{{ $key }}" class="peer sr-only" required>
                                        <div class="p-4 rounded-lg border-2 border-gray-200 peer-checked:border-{{ $plan['color'] ?? 'blue' }}-500 peer-checked:bg-{{ $plan['color'] ?? 'blue' }}-50 hover:bg-gray-50 transition-all">
                                            <div class="font-bold text-lg mb-1">{{ $plan['name'] }}</div>
                                            <div class="text-sm text-gray-500 mb-2">{{ $plan['sub_name'] }}</div>
                                            <div class="text-xl font-bold text-{{ $plan['color'] ?? 'blue' }}-600">${{ $plan['price'] }}</div>
                                            <div class="text-xs text-gray-500 mt-2">{{ $plan['lessons_count'] }} Credits</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('plan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.clients.index') }}" class="text-gray-600 hover:text-gray-900 mx-4">إلغاء</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                حفظ وتفعيل الاشتراك
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>