<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('إدارة العملاء') }}
            </h2>
            <a href="{{ route('admin.clients.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                {{ __('+ إضافة عميل جديد') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- ** STEP 1: MODIFIED SEARCH FORM ** -->
                    <!-- I've added the method, action, and a flex container -->
                    <div class="mb-4">
                        <form method="GET" action="{{ route('admin.clients.index') }}" class="flex items-center space-x-2">   
                            <label for="client-search" class="sr-only">Search</label>
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 rtl:inset-r-0 ltr:inset-l-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                                </div>
                                <!-- 
                                  - Added: name="search" to send the data
                                  - Added: value="{{ $search ?? '' }}" to show the current search
                                -->
                                <input type="text" 
                                       id="client-search" 
                                       name="search" 
                                       value="{{ $search ?? '' }}"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-10 p-2.5" 
                                       placeholder="البحث عن طريق الاسم أو البريد الإلكتروني..." />
                            </div>
                            <button type="submit" class="p-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg border border-indigo-700 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300">
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                                <span class="sr-only">Search</span>
                            </button>
                            <!-- ** STEP 2: ADD A 'CLEAR' BUTTON ** -->
                            <!-- This only shows if a search is active -->
                            @if($search)
                                <a href="{{ route('admin.clients.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg border border-gray-300 hover:bg-gray-200">
                                    {{ __('مسح') }}
                                </a>
                            @endif
                        </form>
                    </div>

                    <!-- Client Table -->
                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-right text-gray-500 rtl:text-right">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('الاسم') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('البريد الإلكتروني') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('تاريخ الانضمام') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">{{ __('إجراءات') }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($clients as $client)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $client->name }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ $client->email }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $client->created_at->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4 text-left">
                                            <a href="{{ route('admin.clients.edit', $client) }}" class="font-medium text-indigo-600 hover:underline">{{ __('تعديل') }}</a>
                                        </td>
                                    </tr>
                                @empty
                                    <!-- ** STEP 3: SHOW A 'NO RESULTS' MESSAGE ** -->
                                    <tr class="bg-white border-b">
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            @if($search)
                                                {{ __('لم يتم العثور على عملاء يطابقون البحث.') }}
                                            @else
                                                {{ __('لا يوجد عملاء لعرضهم حتى الآن.') }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $clients->links() }} <!-- This will now automatically include the search query -->
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>