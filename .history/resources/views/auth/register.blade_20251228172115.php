<x-guest-layout>
    <div class="min-h-screen flex bg-white" dir="rtl">
        
        <div class="hidden lg:flex w-1/2 bg-slate-900 relative overflow-hidden flex-col justify-between p-12 text-white">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-1/2 -right-1/2 w-[1000px] h-[1000px] rounded-full bg-indigo-600/20 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-[800px] h-[800px] rounded-full bg-violet-600/20 blur-3xl"></div>
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            </div>

            <div class="relative z-10">
                <a href="/" class="flex items-center gap-3 mb-10">
                    <div class="flex items-center justify-center w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-lg shadow-indigo-500/20 ring-2 ring-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"></path>
                            <path d="M22 10v6"></path>
                            <path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"></path>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-black leading-none tracking-tight">أكاديمية</span>
                        <span class="text-sm font-bold text-indigo-300 tracking-wide mt-0.5">كمـــــون</span>
                    </div>
                </a>

                <h1 class="text-5xl font-black leading-tight mb-6">
                    انضم إلى مجتمع<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">التميز اللغوي</span>
                </h1>
                <p class="text-slate-300 text-lg leading-relaxed max-w-md">
                    سجل الآن وابدأ رحلتك التعليمية مع نخبة من أفضل المعلمين.
                </p>
            </div>

            <div class="relative z-10 flex gap-6 mt-auto">
                <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-4 py-2 rounded-lg border border-white/10">
                    <span class="text-yellow-400 text-xl">★</span>
                    <div class="flex flex-col">
                        <span class="font-bold text-sm">4.9/5</span>
                        <span class="text-[10px] text-slate-300">تقييم الطلاب</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-12 overflow-y-auto">
            <div class="max-w-md w-full">
                
                <div class="mb-8">
                    <h2 class="text-3xl font-black text-slate-900">إنشاء حساب جديد 👋</h2>
                    <p class="text-gray-500 mt-2">يرجى ملء البيانات التالية للمتابعة</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Name')" class="block text-sm font-bold text-gray-700 mb-2" />
                        <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all text-right"
                            placeholder="الاسم الكامل" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" class="block text-sm font-bold text-gray-700 mb-2" />
                        <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all text-right"
                            placeholder="name@example.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="phone" :value="__('Phone Number')" class="block text-sm font-bold text-gray-700 mb-2" />
                        <input id="phone" type="text" name="phone" :value="old('phone')" required placeholder="+1234567890"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all text-right" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="study_subject_id" :value="__('Interested Subject')" class="block text-sm font-bold text-gray-700 mb-2" />
                        <div class="relative">
                            <select id="study_subject_id" name="study_subject_id" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer text-gray-700">
                                <option value="" disabled selected>اختر المادة التي تريد دراستها</option>
                                {{-- Assuming $studySubjects is passed from the controller, otherwise remove this loop --}}
                                @if(isset($studySubjects))
                                    @foreach($studySubjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-4 text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('study_subject_id')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <x-input-label for="password" :value="__('Password')" class="block text-sm font-bold text-gray-700 mb-2" />
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                                placeholder="********" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="block text-sm font-bold text-gray-700 mb-2" />
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                                placeholder="********" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-indigo-500/30 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                            <span>{{ __('Register') }}</span>
                            <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>

                    <div class="text-center mt-6">
                        <p class="text-gray-600 text-sm">
                            {{ __('Already registered?') }}
                            <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline">تسجيل الدخول</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>