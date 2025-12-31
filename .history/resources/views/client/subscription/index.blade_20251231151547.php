<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subscription Plans') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">اختر خطتك الدراسية</h1>
                <p class="text-lg text-gray-600">خطط مرنة تناسب جميع الاحتياجات والمستويات</p>
            </div>

            <!-- Dynamic Grid for 4 Plans -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 rtl">
                @foreach($plans as $key => $plan)
                    <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden border-2 {{ $plan['is_popular'] ? 'border-' . $plan['color'] . '-500 transform scale-105 z-10' : 'border-transparent hover:border-gray-200' }} transition-all duration-300 flex flex-col">
                        
                        @if($plan['is_popular'])
                            <div class="absolute top-0 right-0 bg-{{ $plan['color'] }}-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg">
                                الأكثر طلباً
                            </div>
                        @endif

                        <div class="p-6 flex-grow">
                            <h3 class="text-xl font-bold text-gray-900">{{ $plan['name'] }}</h3>
                            <p class="text-sm text-gray-500 mb-4">{{ $plan['sub_name'] }}</p>
                            
                            <div class="flex items-baseline mb-4">
                                <span class="text-3xl font-extrabold text-gray-900">${{ $plan['price'] }}</span>
                                <span class="text-gray-500 mr-1">/ {{ $plan['period'] }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mb-6">
                                ({{ $plan['price_per_session'] }}$ / للحصة)
                            </p>

                            <p class="text-sm text-gray-600 mb-6">{{ $plan['description'] }}</p>

                            <ul class="space-y-3 mb-6">
                                <li class="flex items-center text-sm text-gray-600">
                                    <svg class="h-5 w-5 text-green-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    {{ $plan['lessons_count'] }} حصص (Credits)
                                </li>
                                <li class="flex items-center text-sm text-gray-600">
                                    <svg class="h-5 w-5 text-green-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    حجم المجموعة: {{ $plan['group_size'] }}
                                </li>
                                @foreach($plan['features'] as $feature)
                                    <li class="flex items-center text-sm text-gray-600">
                                        <svg class="h-5 w-5 text-green-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="p-6 bg-gray-50 mt-auto">
                            <form action="{{ route('client.subscription.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="plan" value="{{ $key }}">
                                <button type="submit" class="w-full bg-{{ $plan['color'] }}-600 hover:bg-{{ $plan['color'] }}-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                                    اشترك الآن
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>