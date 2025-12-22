<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.messages.index') }}" class="ml-4 text-gray-500 hover:text-gray-700 dark: text-gray-400 dark:hover: text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                تفاصيل الرسالة
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm: px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
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
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $message->created_at->format('Y-m-d H:i') }}
                            </span>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $message->subject }}
                        </h1>
                    </div>

                    <!-- Recipient Info -->
                    <div class="flex items-center mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <img class="h-12 w-12 rounded-full object-cover" src="{{ $message->recipient->profile_photo_url }}" alt="">
                        <div class="mr-4">
                            <div class="text-sm text-gray-500 dark:text-gray-400">المرسل إليه: </div>
                            <div class="font-medium text-gray-900 dark: text-gray-100">{{ $message->recipient->name }}</div>
                            <div class="text-sm text-gray-500 dark: text-gray-400">{{ $message->recipient->email }}</div>
                        </div>
                        <div class="mr-auto">
                            @if($message->is_read)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    تمت القراءة {{ $message->read_at->diffForHumans() }}
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200">
                                    لم تتم القراءة بعد
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Message Body -->
                    <div class="prose dark:prose-invert max-w-none">
                        <div class="p-4 bg-gray-50 dark: bg-gray-700 rounded-lg whitespace-pre-wrap text-gray-800 dark:text-gray-200">{{ $message->body }}</div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الرسالة؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-. 867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                حذف الرسالة
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>