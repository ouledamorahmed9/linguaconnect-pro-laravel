<x-public-layout>
    <div class="py-20 bg-gray-50 min-h-screen">
        <div class="container mx-auto px-6 max-w-4xl">
            <div class="bg-white p-12 rounded-2xl shadow-sm border border-gray-100">
                <h1 class="text-3xl font-black text-gray-900 mb-2">{{ __('messages.legal.refund.title') }}</h1>
                <p class="text-sm text-gray-500 mb-8 border-b pb-4">
                    {{ __('messages.legal.refund.last_updated', ['date' => date('d/m/Y')]) }}</p>

                <div class="prose prose-lg text-gray-600 space-y-8">

                    <section>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            {{ __('messages.legal.refund.overview_title') }}</h3>
                        <p>{!! __('messages.legal.refund.overview_desc', ['app_name' => __('messages.app_name')]) !!}
                        </p>
                    </section>

                    <section>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            {{ __('messages.legal.refund.guarantee_title') }}</h3>
                        <p>{!! __('messages.legal.refund.guarantee_desc') !!}</p>
                        <ul class="list-disc list-inside bg-gray-50 p-4 rounded-lg border border-gray-200">
                            @foreach(__('messages.legal.refund.guarantee_list') as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </section>

                    <section>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('messages.legal.refund.noshow_title') }}
                        </h3>
                        <p>{{ __('messages.legal.refund.noshow_desc') }}</p>
                    </section>

                    <section>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            {{ __('messages.legal.refund.processing_title') }}</h3>
                        <p>{{ __('messages.legal.refund.processing_desc') }}</p>
                    </section>

                    <section>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('messages.legal.refund.contact_title') }}
                        </h3>
                        <p>{{ __('messages.legal.refund.contact_desc') }}</p>
                        <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100 text-sm font-semibold text-indigo-900 text-left"
                            dir="ltr">
                            {!! __('messages.legal.contact.address_details') !!}<br>
                            support@kamounacademy.com
                        </div>
                    </section>

                </div>
            </div>
        </div>
    </div>
</x-public-layout>