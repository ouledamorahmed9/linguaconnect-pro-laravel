<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('المعلمون المتاحون') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="p-4 mb-6 bg-indigo-50 border-l-4 border-indigo-400 rounded-r-lg">
                <p class="text-sm text-indigo-700">
                    <span class="font-semibold">إدارة المعلمين:</span>
                    هنا يمكنك عرض جميع المعلمين في المنصة، واختيار "إدارة" لربط عملائك بهم.
                </p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المادة</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عدد العملاء (الكلي)</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($teachers as $teacher)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $teacher->profile_photo_url }}" alt="{{ $teacher->name }}">
                                                <div class="mr-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $teacher->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $teacher->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $teacher->subject ?? 'غير محدد' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-700">{{ $teacher->clients_count }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium space-x-4">
                                            <a href="{{ route('coordinator.teachers.edit', $teacher) }}" class="text-indigo-600 hover:text-indigo-900">
                                                إدارة ربط العملاء
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                            لا يوجد معلمون مسجلون في المنصة حالياً.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-6">
                        {{ $teachers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>