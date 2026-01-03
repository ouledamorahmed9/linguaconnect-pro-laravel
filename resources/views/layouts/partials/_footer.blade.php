<footer class="bg-slate-900 text-white border-t border-slate-800">
  <div class="container mx-auto px-6 py-12">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

      <div>
        <h3 class="text-xl font-black mb-4 flex items-center gap-2">
          <span class="text-indigo-500">❖</span> {{ __('messages.app_name') }}
        </h3>
        <p class="text-slate-400 text-sm leading-relaxed">
          {{ __('messages.footer.description') }}
        </p>
      </div>

      <div>
        <h3 class="text-lg font-bold mb-4 text-slate-200">{{ __('messages.footer.quick_links') }}</h3>
        <ul class="space-y-2 text-sm text-slate-400">
          <li><a href="/" class="hover:text-indigo-400 transition-colors">{{ __('messages.footer.home') }}</a></li>
          <li><a href="{{ route('teachers.index') }}"
              class="hover:text-indigo-400 transition-colors">{{ __('messages.footer.teachers') }}</a></li>
          <li><a href="{{ route('pricing.index') }}"
              class="hover:text-indigo-400 transition-colors">{{ __('messages.footer.pricing') }}</a></li>
        </ul>
      </div>

      <div>
        <h3 class="text-lg font-bold mb-4 text-slate-200">{{ __('messages.footer.policies') }}</h3>
        <ul class="space-y-2 text-sm text-slate-400">
          <li><a href="{{ route('legal.privacy') }}"
              class="hover:text-indigo-400 transition-colors">{{ __('messages.footer.privacy') }}</a>
          </li>
          <li><a href="{{ route('legal.terms') }}"
              class="hover:text-indigo-400 transition-colors">{{ __('messages.footer.terms') }}</a></li>
          <li><a href="{{ route('legal.refund') }}"
              class="hover:text-indigo-400 transition-colors">{{ __('messages.footer.refund') }}</a>
          </li>
          <li><a href="{{ route('legal.faq') }}"
              class="hover:text-indigo-400 transition-colors">{{ __('messages.footer.faq') }}</a></li>
        </ul>
      </div>

      <div>
        <h3 class="text-lg font-bold mb-4 text-slate-200">{{ __('messages.footer.contact_us') }}</h3>
        <ul class="space-y-2 text-sm text-slate-400">
          <li class="flex items-center gap-2">
            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
              </path>
            </svg>
            <a href="mailto:support@kamounacademy.com" class="hover:text-indigo-400">support@kamounacademy.com</a>
          </li>
          <li class="flex items-start gap-2">
            <svg class="w-4 h-4 text-indigo-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span>{!! __('messages.footer.address') !!}</span>
          </li>
        </ul>

        <div class="mt-6">
          <h4 class="text-xs font-bold text-slate-500 uppercase mb-2">{{ __('messages.footer.payment_methods') }}</h4>
          <div class="flex gap-2">
            {{-- Visa --}}
            <div class="bg-white p-1 rounded h-8 w-12 flex items-center justify-center">
              <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa"
                class="h-full w-full object-contain">
            </div>
            {{-- Mastercard --}}
            <div class="bg-white p-1 rounded h-8 w-12 flex items-center justify-center">
              <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard"
                class="h-full w-full object-contain">
            </div>
          </div>
        </div>
      </div>

    </div>

    <div class="mt-12 border-t border-slate-800 pt-6 text-center text-xs text-slate-500">
      <p>{{ __('messages.footer.copyright', ['year' => date('Y'), 'app_name' => __('messages.app_name')]) }}</p>
    </div>
  </div>
</footer>