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

                    <!-- ** STEP 1: MODIFIED SEARCH INPUT ** -->
                    <!-- I've removed the <form> and <button> -->
                    <div class="mb-4">
                        <label for="client-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 rtl:inset-r-0 ltr:inset-l-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <!-- 
                              - Added: id="client-search-input"
                              - Removed: name="search" (JS will handle it)
                            -->
                            <input type="text" 
                                   id="client-search-input" 
                                   value="{{ $search ?? '' }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-10 p-2.5" 
                                   placeholder="البحث عن طريق الاسم أو البريد الإلكتروني... (يبدأ البحث تلقائياً)" />
                        </div>
                    </div>

                    <!-- Client Table -->
                    <!-- ** STEP 2: Added id="clients-table-container" ** -->
                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg" id="clients-table-container">
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
                            
                            <tbody id="client-rows-body">
                                @include('admin.clients._client-rows', ['clients' => $clients, 'search' => $search])
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <!-- ** STEP 4: Added id="pagination-links" ** -->
                    <div class="mt-4" id="pagination-links">
                        {{ $clients->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- ** STEP 5: ADD THE JAVASCRIPT ** -->
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('client-search-input');
            const tableBody = document.getElementById('client-rows-body');
            const paginationLinks = document.getElementById('pagination-links');
            const tableContainer = document.getElementById('clients-table-container');
            
            let debounceTimer;

            // --- Function to fetch results ---
            function fetchClients(searchValue, page = 1) {
                const url = `{{ route('admin.clients.search') }}?search=${encodeURIComponent(searchValue)}&page=${page}`;
                
                // Add loading indicator
                tableContainer.style.opacity = '0.5';

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Update table rows and pagination
                    tableBody.innerHTML = data.rows;
                    paginationLinks.innerHTML = data.pagination;
                })
                .catch(error => {
                    console.error('Error fetching search results:', error);
                    tableBody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">حدث خطأ أثناء البحث.</td></tr>';
                })
                .finally(() => {
                    // Remove loading indicator
                    tableContainer.style.opacity = '1';
                });
            }

            // --- Listen for typing in the search box ---
            searchInput.addEventListener('input', function (e) {
                const searchValue = e.target.value;
                
                // Clear the old timer
                clearTimeout(debounceTimer);
                
                // Set a new timer to wait 300ms after user stops typing
                debounceTimer = setTimeout(() => {
                    fetchClients(searchValue, 1); // Always go to page 1 for a new search
                }, 300);
            });

            // --- Listen for clicks on pagination links ---
            // We use event delegation on the container
            paginationLinks.addEventListener('click', function (e) {
                // Find the link that was clicked
                let targetLink = e.target.closest('a.relative'); // Tailwind's pagination link
                if (!targetLink) targetLink = e.target.closest('span.relative'); // Clicks on "..." or disabled
                
                // If it's not a link or it's disabled, do nothing
                if (!targetLink || !targetLink.href || targetLink.classList.contains('disabled')) {
                    e.preventDefault();
                    return;
                }

                // It's a valid link, prevent default page load
                e.preventDefault();

                // Get the page number from the link's URL
                const url = new URL(targetLink.href);
                const page = url.searchParams.get('page') || 1;
                const searchValue = searchInput.value;

                // Fetch the new page
                fetchClients(searchValue, page);
            });

        });
    </script>
    @endpush
</x-app-layout>