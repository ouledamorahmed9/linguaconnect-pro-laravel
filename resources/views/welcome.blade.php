<x-public-layout>
    {{-- Custom Styles --}}
    @push('styles')
    <style>
        .hero-bg {
            background-image: 
                linear-gradient(to bottom, rgba(15, 23, 42, 0.8), rgba(30, 27, 75, 0.9)),
                url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
        }
        
        .text-gradient {
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .blob {
            position: absolute;
            filter: blur(50px);
            z-index: 0;
            opacity: 0.4;
        }
    </style>
    @endpush

    <section class="hero-bg text-white relative min-h-[700px] flex items-center overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        
        <div class="container mx-auto px-6 py-24 relative z-10 text-center">
            
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 px-4 py-1.5 rounded-full mb-8 shadow-2xl">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-sm font-semibold tracking-wide text-indigo-100">{{ __('messages.welcome.hero_cta_primary') }}</span>
            </div>

            <h1 class="text-5xl md:text-7xl font-black leading-tight mb-6 tracking-tight">
                {{ __('messages.welcome.hero_title') }} <br class="hidden md:block" />
                <span class="text-gradient">{{ __('messages.welcome.hero_highlight') }}</span>
            </h1>
            
            <p class="text-lg md:text-xl text-gray-300 mb-10 max-w-2xl mx-auto leading-relaxed font-light">
                {{ __('messages.welcome.hero_subtitle') }}
            </p>
            
            <div class="flex flex-col sm:flex-row gap-5 justify-center">
                <a href="https://wa.me/21612345678" target="_blank" class="relative inline-flex group items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all duration-200 bg-gradient-to-r from-indigo-600 to-violet-600 rounded-2xl hover:from-indigo-700 hover:to-violet-700 focus:outline-none shadow-lg shadow-indigo-500/40 hover:shadow-indigo-500/60 hover:-translate-y-1">
                    {{ __('messages.welcome.hero_cta_primary') }}
                    <svg class="w-5 h-5 mr-2 -ml-1 rtl:ml-2 rtl:-mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </a>
                
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all duration-200 bg-white/10 border border-white/20 backdrop-blur-md rounded-2xl hover:bg-white hover:text-indigo-900 shadow-lg hover:-translate-y-1">
                    {{ __('messages.welcome.hero_cta_secondary') }}
                </a>
            </div>

            <div class="mt-16 flex flex-wrap justify-center gap-8 md:gap-16 opacity-80">
                <div class="flex flex-col items-center">
                    <span class="text-3xl font-bold">50+</span>
                    <span class="text-sm text-indigo-200">{{ __('messages.welcome.stats.teachers') }}</span>
                </div>
                <div class="w-px h-10 bg-white/20 hidden md:block"></div>
                <div class="flex flex-col items-center">
                    <span class="text-3xl font-bold">100%</span>
                    <span class="text-sm text-indigo-200">{{ __('messages.welcome.stats.rating') }}</span>
                </div>
                <div class="w-px h-10 bg-white/20 hidden md:block"></div>
                <div class="flex flex-col items-center">
                    <span class="text-3xl font-bold">4.9/5</span>
                    <span class="text-sm text-indigo-200">{{ __('messages.welcome.stats.students') }}</span>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white relative">
        <div class="container mx-auto px-6">
            <div class="text-center mb-20">
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 mb-4">{{ __('messages.welcome.features_title') }}</h2>
                <p class="text-slate-500 text-lg max-w-2xl mx-auto">{{ __('messages.welcome.hero_subtitle') }}</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="group p-8 rounded-3xl bg-white border border-gray-100 shadow-2xl shadow-gray-200/50 hover:shadow-indigo-500/10 hover:border-indigo-100 transition-all duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white flex items-center justify-center mb-6 shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">{{ __('messages.welcome.feature_1_title') }}</h3>
                    <p class="text-slate-500 leading-relaxed">{{ __('messages.welcome.feature_1_desc') }}</p>
                </div>

                <div class="group p-8 rounded-3xl bg-white border border-gray-100 shadow-2xl shadow-gray-200/50 hover:shadow-indigo-500/10 hover:border-indigo-100 transition-all duration-300 relative">
                    <div class="absolute top-0 right-0 m-6">
                        <span class="bg-indigo-50 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full">Pro</span>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 text-white flex items-center justify-center mb-6 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">{{ __('messages.welcome.feature_2_title') }}</h3>
                    <p class="text-slate-500 leading-relaxed">{{ __('messages.welcome.feature_2_desc') }}</p>
                </div>

                <div class="group p-8 rounded-3xl bg-white border border-gray-100 shadow-2xl shadow-gray-200/50 hover:shadow-indigo-500/10 hover:border-indigo-100 transition-all duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-500 text-white flex items-center justify-center mb-6 shadow-lg shadow-pink-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">{{ __('messages.welcome.feature_3_title') }}</h3>
                    <p class="text-slate-500 leading-relaxed">{{ __('messages.welcome.feature_3_desc') }}</p>
                </div>
            </div>
        </div>
    </section>
            {{--  section of packages  --}}

    <section class="py-24 bg-slate-50 relative overflow-hidden">
        <div class="blob w-96 h-96 bg-indigo-200 rounded-full top-0 left-0 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="blob w-96 h-96 bg-violet-200 rounded-full bottom-0 right-0 translate-x-1/2 translate-y-1/2"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 mb-4">{{ __('messages.nav.pricing') }}</h2>
                <p class="text-slate-500 text-lg">{{ __('messages.welcome.step_1_desc') }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                @foreach($plans as $key => $plan)
                <div class="relative bg-white rounded-3xl p-6 flex flex-col transition-transform duration-300 {{ $plan['is_popular'] ? 'shadow-2xl shadow-indigo-200 border-2 border-indigo-600 transform md:scale-105 z-10' : 'shadow-xl shadow-slate-200 border border-slate-100 hover:-translate-y-1' }}">
                    
                    @if($plan['is_popular'])
                    <div class="absolute top-0 inset-x-0 -mt-3 flex justify-center">
                        <span class="bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow">Popular</span>
                    </div>
                    @endif

                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-900">{{ $plan['name'] }}</h3>
                        <p class="text-sm text-{{ $plan['color'] }}-600 font-medium mt-1">{{ $plan['sub_name'] }}</p>
                        <span class="inline-block mt-2 px-2 py-1 text-xs font-semibold rounded bg-{{ $plan['color'] }}-50 text-{{ $plan['color'] }}-700">
                            @if($key == 'private') 👤 @else 👥 @endif {{ $plan['group_size'] }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mb-6 min-h-[40px]">{{ $plan['description'] }}</p>
                    
                    <div class="mb-6 bg-{{ $plan['is_popular'] ? 'indigo' : 'slate' }}-50 rounded-lg p-3 text-center border border-{{ $plan['is_popular'] ? 'indigo' : 'slate' }}-100">
                        <div class="flex justify-center items-baseline text-gray-900">
                            @php
                                $perSession = number_format($plan['price'] / $plan['lessons_count'], 2);
                                $perSession = str_replace('.00', '', $perSession);
                            @endphp
                            <span class="text-3xl font-extrabold">{{ $perSession }}</span>
                            <span class="ml-1 text-lg font-semibold text-gray-500">{{ $currency }}</span>
                            <span class="mr-1 text-gray-500 text-sm">/ {{ __('messages.dashboard.book_class') }}</span>
                        </div>
                        <p class="text-xs text-{{ $plan['is_popular'] ? 'indigo-600' : 'gray-500' }} mt-1 font-medium">{{ $plan['price'] }}{{ $currency }}</p>
                    </div>

                    <ul class="space-y-3 border-t border-gray-100 pt-6 mb-6">
                        @foreach($plan['features'] as $feature)
                        <li class="flex items-start"><svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg><span class="mr-2 text-sm text-gray-600">{{ $feature }}</span></li>
                        @endforeach
                    </ul>

                    <a href="{{ route('client.subscription.create', ['plan' => $key]) }}" class="mt-auto block w-full py-2 px-4 rounded-lg text-center font-bold transition {{ $plan['is_popular'] ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'border border-'.$plan['color'].'-600 text-'.$plan['color'].'-600 hover:bg-'.$plan['color'].'-50' }}">
                        {{ __('messages.common.submit') }}
                    </a>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('pricing.index') }}" class="text-indigo-600 font-bold hover:text-indigo-800 inline-flex items-center gap-2 group">
                    {{ __('messages.nav.pricing') }}
                    <svg class="w-4 h-4 rtl:rotate-180 transform group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- How It Works Section --}}
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center mb-20">
                <span class="text-indigo-600 font-bold tracking-wider uppercase text-sm">{{ __('messages.welcome.how_it_works_title') }}</span>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 mt-2 mb-4">{{ __('messages.welcome.how_it_works_title') }}</h2>
                <p class="text-slate-500 text-lg max-w-2xl mx-auto">{{ __('messages.welcome.step_4_desc') }}</p>
            </div>

            <div class="relative">
                {{-- Connecting Line (Desktop) --}}
                <div class="hidden md:block absolute top-1/2 left-0 w-full h-1 bg-gradient-to-r from-indigo-100 via-indigo-200 to-indigo-100 -translate-y-1/2 z-0"></div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative z-10">
                    
                    {{-- Step 1 --}}
                    <div class="group relative">
                        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-xl shadow-slate-200/50 hover:-translate-y-2 transition-transform duration-300 h-full flex flex-col items-center text-center">
                            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-6 text-2xl font-bold shadow-inner group-hover:scale-110 transition-transform bg-gradient-to-br from-indigo-50 to-white">
                                1
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-3">{{ __('messages.welcome.step_1_title') }}</h3>
                            <p class="text-sm text-slate-500 leading-relaxed">{{ __('messages.welcome.step_1_desc') }}</p>
                        </div>
                    </div>

                    {{-- Step 2 --}}
                    <div class="group relative">
                        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-xl shadow-slate-200/50 hover:-translate-y-2 transition-transform duration-300 h-full flex flex-col items-center text-center">
                            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-6 text-2xl font-bold shadow-inner group-hover:scale-110 transition-transform bg-gradient-to-br from-indigo-50 to-white">
                                2
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-3">{{ __('messages.welcome.step_2_title') }}</h3>
                            <p class="text-sm text-slate-500 leading-relaxed">{{ __('messages.welcome.step_2_desc') }}</p>
                        </div>
                    </div>

                    {{-- Step 3 --}}
                    <div class="group relative">
                        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-xl shadow-slate-200/50 hover:-translate-y-2 transition-transform duration-300 h-full flex flex-col items-center text-center">
                            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-6 text-2xl font-bold shadow-inner group-hover:scale-110 transition-transform bg-gradient-to-br from-indigo-50 to-white">
                                3
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-3">{{ __('messages.welcome.step_3_title') }}</h3>
                            <p class="text-sm text-slate-500 leading-relaxed">{{ __('messages.welcome.step_3_desc') }}</p>
                        </div>
                    </div>

                    {{-- Step 4 --}}
                    <div class="group relative">
                        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-xl shadow-slate-200/50 hover:-translate-y-2 transition-transform duration-300 h-full flex flex-col items-center text-center">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 text-white flex items-center justify-center mb-6 text-2xl font-bold shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-3">{{ __('messages.welcome.step_4_title') }}</h3>
                            <p class="text-sm text-slate-500 leading-relaxed">{{ __('messages.welcome.step_4_desc') }}</p>
                        </div>
                    </div>

                </div>
            </div>
            
            <div class="text-center mt-8 mb-6">
                 <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all duration-200 bg-gradient-to-r from-indigo-600 to-violet-600 rounded-2xl hover:from-indigo-700 hover:to-violet-700 shadow-lg shadow-indigo-500/30 hover:-translate-y-1">
                    {{ __('messages.welcome.hero_cta_primary') }}
                    <svg class="w-5 h-5 mr-2 -ml-1 rtl:ml-2 rtl:-mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </a>
            </div>
        </div>
    </section>

    <section class="py-24 bg-slate-900 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-12 opacity-10">
            <svg class="w-64 h-64 text-indigo-500" fill="currentColor" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50"/></svg>
        </div>
        
        <div class="container mx-auto px-6 text-center relative z-10">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-8">{{ __('messages.welcome.cta_footer_title') }}</h2>
            <p class="text-indigo-200 text-lg mb-12 max-w-2xl mx-auto">
                {{ __('messages.welcome.cta_footer_desc') }}
            </p>
            <a href="https://wa.me/21612345678" target="_blank" class="inline-flex items-center justify-center px-10 py-5 text-lg font-bold text-indigo-900 bg-white rounded-2xl hover:bg-indigo-50 hover:scale-105 transition-all duration-200 shadow-2xl">
                {{ __('messages.welcome.cta_footer_btn') }}
                <span class="mr-2">🚀</span>
            </a>
        </div>
    </section>
</x-public-layout>