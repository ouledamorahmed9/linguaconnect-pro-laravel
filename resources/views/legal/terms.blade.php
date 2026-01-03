<x-public-layout>
    <div class="py-20 bg-gray-50 min-h-screen">
        <div class="container mx-auto px-6 max-w-4xl">
            <div class="bg-white p-12 rounded-2xl shadow-sm border border-gray-100">
                <h1 class="text-3xl font-black text-gray-900 mb-2">{{ __('messages.legal.terms.title') }}</h1>
                <p class="text-sm text-gray-500 mb-8 border-b pb-4">
                    {{ __('messages.legal.terms.last_updated', ['date' => date('d/m/Y')]) }}</p>

                <div class="prose prose-lg text-gray-600 space-y-8">

                    <section>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('messages.legal.terms.intro_title') }}
                        </h3>
                        <p>{!! __('messages.legal.terms.intro_desc', ['app_name' => __('messages.app_name')]) !!}</p>
                    </section>

                    <section>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('messages.legal.terms.services_title') }}
                        </h3>
                        <p>{{ __('messages.legal.terms.services_desc') }}</p>
                    </section>

                    <section>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('messages.legal.terms.payments_title') }}
                        </h3>
                        <p>{!! __('messages.legal.terms.payments_desc') !!}</p>
                    </section>

                    <section>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('messages.legal.terms.ip_title') }}</h3>
                        <p>{{ __('messages.legal.terms.ip_desc') }}</p>
                    </section>

                    <section>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('messages.legal.terms.law_title') }}</h3>
                        <p>{!! __('messages.legal.terms.law_desc') !!}</p>
                    </section>

                    <section>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('messages.legal.terms.contact_title') }}
                        </h3>
                        <div class="text-left bg-gray-50 p-4 rounded-lg" dir="ltr">
                            {!! __('messages.legal.contact.address_details') !!}<br>
                            United States
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>