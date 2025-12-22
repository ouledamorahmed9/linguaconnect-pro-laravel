<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark: text-gray-200 leading-tight">
                صندوق الوارد
                @if($unreadCount > 0)
                    <span class="mr-2 px-2 py-1 text-xs font-semibold rounded-full bg-red-500 text-white">
                        {{ $unreadCount }} جديد
                    </span>
                @endif
            </h2>
            @if($unreadCount > 0)
                <form action="{{ route(auth()->user()->role .  '.inbox.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        تحديد الكل كمقروء
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($messages->count() > 0)
                        <div class="space-y-4">
                            @foreach($messages as $message)
                                <a href="{{ route(auth()->user()->role . '.inbox.show', $message) }}" 
                                   class="block p-4 rounded-lg border transition-all duration-150 hover:shadow-md {{ ! $message->is_read ? 'bg-indigo-50 dark:bg-indigo-900/20 border-indigo-200 dark:border-indigo-700' : 'bg-white dark:bg-gray-700 border-gray-200 dark:border-gray-600' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start space-x-4 rtl:space-x-reverse">
                                            @if(! $message->is_read)
                                                <span class="mt-2 flex-shrink-0 w-2 h-2 rounded-full bg-indigo-600"></span>
                                            @endif
                                            <div>
                                                <div class="flex items-center space-x-2 rtl:space-x-reverse mb-1">
                                                    @php
                                                        $categoryColors = [
                                                            'urgent' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                            'announcement' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark: text-blue-200',
                                                            'notice' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                            'general' => 'bg-gray-100 text-gray-800 dark: bg-gray-600 dark:text-gray-200',
                                                        ];
                                                    @endphp
                                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $categoryColors[$message->category] }}">
                                                        {{ $message->category_label }}
                                                    </span>
                                                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 {{ ! $message->is_read ? 'font-bold' : '' }}">
                                                        {{ $message->subject }}
                                                    </h3>
                                                </div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                                    {{ Str::limit($message->body, 120) }}
                                                </p>
                                                <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                    <span>من:  {{ $message->sender->name }}</span>
                                                    <span class="mx-2">•</span>
                                                    <span>{{ $message->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $messages->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2. 586a1 1 0 00-. 707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006. 586 13H4"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark: text-gray-100">صندوق الوارد فارغ</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">لا توجد رسائل حتى الآن. </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>