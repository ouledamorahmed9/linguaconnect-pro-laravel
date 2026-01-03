<x-public-layout>
    <div class="py-20 bg-gray-50 min-h-screen">
        <div class="container mx-auto px-6 max-w-4xl">
            <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100">
                <h1 class="text-3xl font-black text-gray-900 mb-8 border-b pb-4">
                    {{ __('messages.legal.privacy.title') }}</h1>

                <div class="prose prose-lg text-gray-600 space-y-6">
                    <p>{!! __('messages.legal.privacy.intro', ['app_name' => __('messages.app_name')]) !!}</p>

                    <h3 class="text-xl font-bold text-gray-800">{{ __('messages.legal.privacy.section_1_title') }}</h3>
                    <p>{{ __('messages.legal.privacy.section_1_desc') }}</p>

                    <h3 class="text-xl font-bold text-gray-800">{{ __('messages.legal.privacy.section_2_title') }}</h3>
                    <ul class="list-disc list-inside">
                        @foreach(__('messages.legal.privacy.section_2_list') as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>

                    <h3 class="text-xl font-bold text-gray-800">{{ __('messages.legal.privacy.section_3_title') }}</h3>
                    <p>{{ __('messages.legal.privacy.section_3_desc') }}</p>

                    <h3 class="text-xl font-bold text-gray-800">{{ __('messages.legal.privacy.section_4_title') }}</h3>
                    <p>{{ __('messages.legal.privacy.section_4_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>