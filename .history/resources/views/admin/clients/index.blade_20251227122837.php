<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('إدارة العملاء') }}
            </h2>
            <a href="{{ route('admin.clients.create') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-indigo-700">
                إضافة عميل جديد
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ openModal: false, clientId: null, clientName: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Display success/error messages --}}
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                    {{ session('status') }}
                </div>
            @endif
             @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                     <form method="GET" action="{{ route('admin.clients.index') }}" class="mb-4">
                        <div class="flex gap-2">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="ابحث عن عميل بالاسم، الهاتف أو البريد الإلكتروني..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            
                            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition">
                                بحث
                            </button>
                            
                            @if(request('search'))
                                <a href="{{ route('admin.clients.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center">
                                    مسح
                                </a>
                            @endif
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الاسم</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم الهاتف</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">البريد الإلكتروني</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ التسجيل</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($clients as $client)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $client->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" dir="ltr" class="text-right">{{ $client->phone ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $client->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $client->created_at->translatedFormat('d M, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium space-x-4">
                                            <a href="{{ route('admin.clients.edit', $client) }}" class="text-indigo-600 hover:text-indigo-900">تعديل</a>
                                            <button @click="openModal = true; clientId = {{ $client->id }}; clientName = '{{ addslashes($client->name) }}'" class="text-red-600 hover:text-red-900">حذف</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">لا يوجد عملاء مسجلون حالياً.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $clients->links() }}
                    </div>

                </div>
            </div>
        </div>

        <div x-show="openModal" x-transition class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50" style="display: none;">
            <div @click.away="openModal = false" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mt-5">حذف حساب العميل</h3>
                    <p class="text-sm text-gray-500 mt-2">هل أنت متأكد من رغبتك في حذف حساب <strong x-text="clientName"></strong>؟</p>
                    <div class="mt-6 flex justify-center gap-4">
                        <form :action="'{{ url('admin/clients') }}/' + clientId" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700">نعم، قم بالحذف</button>
                        </form>
                        <button @click="openModal = false" class="px-4 py-2 bg-white text-gray-700 font-semibold rounded-lg border border-gray-300 hover:bg-gray-50">إلغاء</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>