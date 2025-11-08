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