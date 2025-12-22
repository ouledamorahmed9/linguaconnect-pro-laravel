<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route(auth()->user()->role . '.inbox.index') }}" class="ml-4 text-gray-500 hover:text-gray-700 dark: text-gray-400 dark:hover: text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                قراءة الرسالة
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark: text-gray-100">
                    <!-- Message Header -->
                    <div class="border-b border-gray-200 dark: border-gray-700 pb-4 mb-4">
                        <div class="flex items-center justify-between mb-4">
                            @php
                                $categoryColors = [
                                    'urgent' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                    'announcement' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                    'notice' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                    'general' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                ];
                            @endphp
                            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $categoryColors[$message->category] }}">
                                {{ $message->category_label }}
                            </span>
                            <span class="text-sm text-gray-500 dark: text-gray-400">
                                {{ $message->created_at->format('Y-m-d H:i') }}
                            </span>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $message->subject }}
                        </h1>
                    </div>

                    <!-- Sender Info -->
                    <div class="flex items-center mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <img class="h-12 w-12 rounded-full object-cover" src="{{ $message->sender->profile_photo_url }}" alt="">
                        <div class="mr-4">
                            <div class="text-sm text-gray-500 dark:text-gray-400">من: </div>
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $message->sender->name }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 text-xs">
                                    مدير النظام
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Message Body -->
                    <div class="prose dark:prose-invert max-w-none">
                        <div class="p-6 bg-gray-50 dark: bg-gray-700 rounded-lg whitespace-pre-wrap text-gray-800 dark:text-gray-200 leading-relaxed">{{ $message->body }}</div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                تمت القراءة:  {{ $message->read_at ?  $message->read_at->format('Y-m-d H:i') : 'الآن' }}
                            </span>
                            <a href="{{ route(auth()->user()->role . '.inbox.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                                </svg>
                                العودة للصندوق
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>