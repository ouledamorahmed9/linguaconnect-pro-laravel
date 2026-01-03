<x-public-layout>
    <div class="py-20 bg-gray-50 min-h-screen">
        <div class="container mx-auto px-6 max-w-4xl">
            <div class="text-center mb-16">
                <h1 class="text-4xl font-black text-slate-900 mb-4">{{ __('messages.legal.faq.title') }}</h1>
                <p class="text-lg text-slate-500 max-w-2xl mx-auto">{{ __('messages.legal.faq.subtitle') }}</p>
            </div>

            <div class="space-y-6">

                {{-- Question 1 --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8" x-data="{ open: true }">
                    <button @click="open = !open"
                        class="flex justify-between items-center w-full rtl:text-right ltr:text-left group">
                        <h3 class="text-xl font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">
                            {{ __('messages.legal.faq.q1') }}</h3>
                        <span
                            class="bg-indigo-50 text-indigo-600 rounded-full w-8 h-8 flex items-center justify-center transition-transform duration-300"
                            :class="{ 'rotate-180': open }">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </span>
                    </button>
                    <div x-show="open" x-collapse style="display: none;">
                        <p class="text-slate-600 mt-4 leading-relaxed">
                            {{ __('messages.legal.faq.a1') }}
                        </p>
                    </div>
                </div>

                {{-- Question 2 --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex justify-between items-center w-full rtl:text-right ltr:text-left group">
                        <h3 class="text-xl font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">
                            {{ __('messages.legal.faq.q2') }}</h3>
                        <span
                            class="bg-indigo-50 text-indigo-600 rounded-full w-8 h-8 flex items-center justify-center transition-transform duration-300"
                            :class="{ 'rotate-180': open }">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </span>
                    </button>
                    <div x-show="open" x-collapse style="display: none;">
                        <p class="text-slate-600 mt-4 leading-relaxed">
                            {{ __('messages.legal.faq.a2') }}
                        </p>
                    </div>
                </div>

                {{-- Question 3 --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex justify-between items-center w-full rtl:text-right ltr:text-left group">
                        <h3 class="text-xl font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">
                            {{ __('messages.legal.faq.q3') }}</h3>
                        <span
                            class="bg-indigo-50 text-indigo-600 rounded-full w-8 h-8 flex items-center justify-center transition-transform duration-300"
                            :class="{ 'rotate-180': open }">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </span>
                    </button>
                    <div x-show="open" x-collapse style="display: none;">
                        <p class="text-slate-600 mt-4 leading-relaxed">
                            {!! __('messages.legal.faq.a3', ['link' => route('legal.refund')]) !!}
                        </p>
                    </div>
                </div>

                {{-- Question 4 --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex justify-between items-center w-full rtl:text-right ltr:text-left group">
                        <h3 class="text-xl font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">
                            {{ __('messages.legal.faq.q4') }}</h3>
                        <span
                            class="bg-indigo-50 text-indigo-600 rounded-full w-8 h-8 flex items-center justify-center transition-transform duration-300"
                            :class="{ 'rotate-180': open }">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </span>
                    </button>
                    <div x-show="open" x-collapse style="display: none;">
                        <p class="text-slate-600 mt-4 leading-relaxed">
                            {{ __('messages.legal.faq.a4') }}
                        </p>
                    </div>
                </div>

                {{-- Question 5 --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex justify-between items-center w-full rtl:text-right ltr:text-left group">
                        <h3 class="text-xl font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">
                            {{ __('messages.legal.faq.q5') }}</h3>
                        <span
                            class="bg-indigo-50 text-indigo-600 rounded-full w-8 h-8 flex items-center justify-center transition-transform duration-300"
                            :class="{ 'rotate-180': open }">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </span>
                    </button>
                    <div x-show="open" x-collapse style="display: none;">
                        <p class="text-slate-600 mt-4 leading-relaxed">
                            {{ __('messages.legal.faq.a5') }}
                        </p>
                    </div>
                </div>

            </div>

            <div class="mt-16 text-center">
                <p class="text-slate-500">{{ __('messages.legal.faq.footer_text') }}</p>
                <a href="{{ route('legal.contact') }}"
                    class="inline-block mt-4 px-8 py-3 bg-white border border-indigo-200 text-indigo-700 font-bold rounded-xl hover:bg-indigo-50 transition-colors">
                    {{ __('messages.legal.faq.footer_btn') }}
                </a>
            </div>
        </div>
    </div>
</x-public-layout>