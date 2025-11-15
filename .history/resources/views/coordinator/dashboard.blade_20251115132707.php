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
                        
                        <a href="#" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-indigo-50 border border-gray-200 hover:border-indigo-300 transition-all duration-150">
                            <svg class="h-10 w-10 text-indigo-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372m-11.25.372a9.38 9.38 0 012.625-.372M11.25 11.25v.007m0 7.5a7.5 7.5 0 01-7.5-7.5h15a7.5 7.5 0 01-7.5 7.5zm-4.5-3a3.375 3.375 0 00-3.375 3.375h9.75a3.375 3.375 0 00-3.375-3.375h-3z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">إدارة عملائي</span>
                        </a>

                        <a href="#" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-indigo-50 border border-gray-200 hover:border-indigo-300 transition-all duration-150">
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
</x-app-layout>