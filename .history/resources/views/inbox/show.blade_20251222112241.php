<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route(auth()->user()->role . '.inbox.index') }}" class="ml-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                قراءة الرسالة
            </h2>
        </div>
    </x-slot>

    <div class="py-12