<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة تحكم المنسق') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 md:p-8 text-gray-900">
                    <h3 class="text-2xl font-semibold">مرحباً بعودتك، {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600 mt-2">هنا ملخص سريع للعملاء الذين تديرهم.</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-r-4 border-indigo-500">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                        
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                                رابط الدعوة الخاص بك (Referral Link)
                            </h3>
                            <p class="text-sm text-gray-600 mt-2">
                                شارك هذا الرابط مع الطلاب. عند تسجيلهم عن طريقك، سيتم إضافتهم إلى قائمتك تلقائياً.
                            </p>
                        </div>

                        <div class="w-full md:w-auto text-left" dir="ltr">
                            <div class="flex items-center bg-gray-50 p-2 rounded-lg border border-gray-200">
                                <code id="refLink" class="text-indigo-600 font-mono text-sm px-3 select-all">
                                    {{ $referralLink ?? 'Link Unavailable' }}
                                </code>
                                <button onclick="copyToClipboard()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-md transition duration-150 ease-in-out flex items-center gap-2 mx-2">
                                    <span>Copy</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                </button>
                            </div>
                            <p id="copySuccess" class="text-green-600 text-xs mt-2 text-center font-bold hidden">تم النسخ بنجاح!</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-100 flex items-center gap-8">
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">عدد الطلاب المسجلين</span>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $referralCount ?? 0 }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">حالة الحساب</span>
                            <p class="text-sm font-bold text-green-600 mt-2 flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                نشط لاستقبال الإحالات
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372m-11.25.372a9.38 9.38 0 012.625-.372M11.25 11.25v.007m0 7.5a7.5 7.5 0 01-7.5-7.5h15a7.5 7.5 0 01-7.5 7.5zm-4.5-3a3.375 3.375 0 00-3.375 3.375h9.75a3.375 3.375 0 00-3.375-3.375h-3z" />
                            </svg>
                        </div>
                        <div class="mr-4 text-right">
                            <dt class="text-sm font-medium text-gray-500 truncate">إجمالي عملائك</dt>
                            <dd class="text-3xl font-bold text-gray-900">{{ $totalClients }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h6m3-3.75l-3 3m0 0l-3-3m3 3V1.5m6 5.25v11.25A2.25 2.25 0 0118 22.5H6a2.25 2.25 0 01-2.25-2.25V6.75A2.25 2.25 0 016 4.5h12A2.25 2.25 0 0120.25 6.75v11.25-11.25z" />
                            </svg>
                        </div>
                        <div class="mr-4 text-right">
                            <dt class="text-sm font-medium text-gray-500 truncate">الاشتراكات النشطة</dt>
                            <dd class="text-3xl font-bold text-gray-900">{{ $activeSubscriptions }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex items-center">
                        <div class="flex-shrink-0 bg-amber-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="mr-4 text-right">
                            <dt class="text-sm font-medium text-gray-500 truncate">حصص بانتظار المراجعة</dt>
                            <dd class="text-3xl font-bold text-gray-900">{{ $pendingSessions }}</dd>
                        </div>
                    </div>
                </div>

            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 border-b pb-3">روابط سريعة</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        
                        <a href="{{ route('coordinator.clients.index') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-indigo-50 border border-gray-200 hover:border-indigo-300 transition-all duration-150">
                            <svg class="h-10 w-10 text-indigo-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372m-11.25.372a9.38 9.38 0 012.625-.372M11.25 11.25v.007m0 7.5a7.5 7.5 0 01-7.5-7.5h15a7.5 7.5 0 01-7.5 7.5zm-4.5-3a3.375 3.375 0 00-3.375 3.375h9.75a3.375 3.375 0 00-3.375-3.375h-3z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">إدارة عملائي</span>
                        </a>

                        <a href="{{ route('coordinator.roster.index') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-indigo-50 border border-gray-200 hover:border-indigo-300 transition-all duration-150">
                            <svg class="h-10 w-10 text-indigo-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">جدول الحصص</span>
                        </a>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function copyToClipboard() {
            const link = document.getElementById('refLink').innerText.trim();
            navigator.clipboard.writeText(link).then(() => {
                const msg = document.getElementById('copySuccess');
                msg.classList.remove('hidden');
                setTimeout(() => msg.classList.add('hidden'), 2000);
            });
        }
    </script>
</x-app-layout>