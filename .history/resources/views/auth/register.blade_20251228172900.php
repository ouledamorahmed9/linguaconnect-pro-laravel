<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>إنشاء حساب جديد - أكاديمية كمون</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-slate-50">
        
        <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 to-indigo-50/50 relative overflow-hidden py-12 px-4 sm:px-6 lg:px-8">
            
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
                <div class="absolute top-[-10%] right-[-5%] w-96 h-96 bg-indigo-600/5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-[-10%] left-[-5%] w-96 h-96 bg-violet-600/5 rounded-full blur-3xl"></div>
            </div>
    
            <div class="max-w-xl w-full bg-white rounded-[2.5rem] shadow-2xl shadow-indigo-100/50 border border-white/50 relative z-10 overflow-hidden">
                
                <div class="pt-10 pb-6 text-center bg-gradient-to-b from-indigo-50/30 to-transparent">
                    <a href="/" class="inline-flex items-center justify-center gap-3 mb-4 hover:opacity-80 transition-opacity">
                        <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-700 text-white shadow-lg shadow-indigo-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"></path>
                                <path d="M22 10v6"></path>
                                <path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"></path>
                            </svg>
                        </div>
                    </a>
                    
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">أكاديمية كمـــــون</h1>
                    <p class="text-indigo-500 font-bold text-sm tracking-wide uppercase mt-1">Kammoun Academy</p>
                    <p class="mt-4 text-gray-500 text-sm">أنشئ حسابك وابدأ التعلم مع نخبة المعلمين</p>
                </div>
    
                <div class="px-8 pb-10 sm:px-12">
                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf
    
                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-700 mb-1.5 mr-1">الاسم الكامل</label>
                            <div class="relative">
                                <input id="name" type="text" name="name" :value="old('name')" required autofocus 
                                    class="w-full bg-gray-50 text-gray-900 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder-gray-400" 
                                    placeholder="الاسم الثلاثي" />
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-1 mr-1" />
                        </div>
    
                        <div>
                            <label for="email" class="block text-sm font-bold text-gray-700 mb-1.5 mr-1">البريد الإلكتروني</label>
                            <input id="email" type="email" name="email" :value="old('email')" required 
                                class="w-full bg-gray-50 text-gray-900 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder-gray-400" 
                                placeholder="name@example.com" />
                            <x-input-error :messages="$errors->get('email')" class="mt-1 mr-1" />
                        </div>
    
                        <div>
                            <label for="phone" class="block text-sm font-bold text-gray-700 mb-1.5 mr-1">رقم الهاتف</label>
                            <input id="phone" type="text" name="phone" :value="old('phone')" required 
                                class="w-full bg-gray-50 text-gray-900 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder-gray-400" 
                                placeholder="05xxxxxxxx" style="direction: ltr; text-align: right;" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-1 mr-1" />
                        </div>
    
                        <div>
                            <label for="study_subject_id" class="block text-sm font-bold text-gray-700 mb-1.5 mr-1">المادة الدراسية</label>
                            <div class="relative">
                                <select id="study_subject_id" name="study_subject_id" required
                                    class="w-full bg-gray-50 text-gray-900 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all cursor-pointer appearance-none">
                                    <option value="" disabled selected class="text-gray-400">اختر المادة التي تهتم بها</option>
                                    @if(isset($studySubjects))
                                        @foreach($studySubjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('study_subject_id')" class="mt-1 mr-1" />
                        </div>
    
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="password" class="block text-sm font-bold text-gray-700 mb-1.5 mr-1">كلمة المرور</label>
                                <input id="password" type="password" name="password" required autocomplete="new-password"
                                    class="w-full bg-gray-50 text-gray-900 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder-gray-400" 
                                    placeholder="********" />
                                <x-input-error :messages="$errors->get('password')" class="mt-1 mr-1" />
                            </div>
    
                            <div>
                                <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-1.5 mr-1">تأكيد كلمة المرور</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" required 
                                    class="w-full bg-gray-50 text-gray-900 border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder-gray-400" 
                                    placeholder="********" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 mr-1" />
                            </div>
                        </div>
    
                        <button type="submit" class="w-full mt-6 bg-slate-900 text-white font-bold text-lg py-4 rounded-xl hover:bg-indigo-600 transition-all duration-300 shadow-xl shadow-slate-200 hover:shadow-indigo-500/20 transform hover:-translate-y-0.5 flex items-center justify-center gap-3">
                            <span>إنشاء الحساب</span>
                            <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </button>
    
                        <div class="text-center pt-2">
                            <p class="text-sm text-gray-500">
                                لديك حساب بالفعل؟ 
                                <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:text-indigo-700 hover:underline transition-colors">
                                    تسجيل الدخول
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="absolute bottom-6 w-full text-center">
                <p class="text-xs text-gray-400 font-medium">© {{ date('Y') }} Kammoun Academy. All rights reserved.</p>
            </div>
        </div>

    </body>
</html>