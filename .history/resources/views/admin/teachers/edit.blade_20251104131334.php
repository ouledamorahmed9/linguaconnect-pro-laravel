<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تعديل ملف المعلم: ') }} {{ $teacher->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Main Profile Form -->
            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 md:p-8 text-gray-900">
                        <h3 class="text-lg font-semibold border-b pb-3 mb-6">المعلومات الأساسية</h3>
                        <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}" class="space-y-6">
                            @csrf
                            @method('PATCH')

                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('الاسم الكامل للمعلم')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $teacher->name)" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Email Address -->
                            <div>
                                <x-input-label for="email" :value="__('البريد الإلكتروني')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $teacher->email)" required autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        <!-- ** ADD THIS subject of teacher ** -->
                         <div>
                             <x-input-label for="subject" :value="__('المادة التي يدرسها (مثل: لغة فرنسية, رياضيات)')" />
                             <x-text-input id="subject" class="block mt-1 w-full" type="text" name="subject" :value="old('subject', $teacher->subject)" />
                             <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                         </div>

                            <hr class="my-6">
                            <p class="text-sm text-gray-600">اترك حقول كلمة المرور فارغة إذا كنت لا ترغب في تغييرها.</p>

                            <!-- Password -->
                            <div>
                                <x-input-label for="password" :value="__('كلمة المرور الجديدة')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <x-input-label for="password_confirmation" :value="__('تâ"id كلمة المرور الجديدة')" />
                                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <a href="{{ route('admin.teachers.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                    إلغاء
                                </a>

                                <x-primary-button>
                                    {{ __('حفظ التغييرات') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Client Management Panel -->
            <div class="lg:col-span-1">
                 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    
                    <!-- Search Box -->
                    <div class="p-6 md:p-8 text-gray-900 border-b border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">تعيين العملاء</h3>
                            <!-- Professional Status Indicator -->
                            <div id="assignment-status" class="flex items-center text-sm text-gray-500" style="display: none;">
                                <!-- Spinner (hidden by default) -->
                                <svg id="status-spinner" class="animate-spin -ml-1 mr-2 h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display: none;">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <!-- Text -->
                                <span id="status-text">جاري الحفظ...</span>
                            </div>
                        </div>
                        
                        <!-- Live Search Form -->
                        <form method="GET" action="{{ route('admin.teachers.edit', $teacher) }}" id="client-search-form" class="mb-0">
                            <label for="search" class="sr-only">البحث عن عميل</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <x-text-input id="search" class="block w-full pl-10" type="text" name="search" :value="$search" placeholder="ابحث بالاسم أو البريد الإلكتروني..." />
                            </div>
                        </form>
                    </div>

                    <!-- Client List (No Form Tag) -->
                    <div class="p-6 md:p-8 text-gray-900">
                        <h4 class="text-md font-semibold text-gray-800 mb-3">قائمة العملاء (ذوي الاشتراك النشط)</h4>
                        
                        <div id="client-list" class="space-y-3 max-h-96 overflow-y-auto pr-2">
                            @forelse($eligibleClients as $client)
                                <label for="client_{{ $client->id }}" class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                    <div class="flex items-center">
                                        <!-- The dynamically controlled checkbox -->
                                        <input type="checkbox" 
                                               id="client_{{ $client->id }}" 
                                               name="clients[]" 
                                               value="{{ $client->id }}" 
                                               class="client-toggle-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                               data-client-id="{{ $client->id }}"
                                               @if(in_array($client->id, $assignedClientIds)) checked @endif>
                                        
                                        <div class="mr-3 text-right">
                                            <span class="font-semibold text-gray-900">{{ $client->name }}</span>
                                            <span class="block text-xs text-gray-500">{{ $client->email }}</span>
                                        </div>
                                    </div>
                                    <!-- Status Badge -->
                                    <span class="client-badge-{{ $client->id }} text-xs font-medium px-2 py-0.5 rounded-full {{ in_array($client->id, $assignedClientIds) ? 'text-indigo-800 bg-indigo-100' : 'text-gray-800 bg-gray-100' }}">
                                        {{ in_array($client->id, $assignedClientIds) ? 'معين' : 'متاح' }}
                                    </span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500 text-center py-4">
                                    @if($search)
                                        لا توجد نتائج للبحث "{{ $search }}".
                                    @else
                                        لا يوجد أي عملاء لديهم اشتراك نشط حاليًا.
                                    @endif
                                </p>
                            @endforelse
                        </div>
                        
                        <!-- Pagination Links -->
                        <div class="mt-4">
                            {{ $eligibleClients->links() }}
                        </div>
                    </div>
                 </div>
            </div>

        </div>
    </div>

    <!-- 
        SECTION 3: DYNAMIC SCRIPTS
    -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- Live Search Script ---
            let searchDebounceTimer;
            const searchInput = document.getElementById('search');
            const searchForm = document.getElementById('client-search-form');

            if (searchInput && searchForm) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchDebounceTimer);
                    searchDebounceTimer = setTimeout(() => {
                        searchForm.submit();
                    }, 500); // 500ms debounce
                });
            }

            // --- Dynamic Toggle Script ---
            const statusContainer = document.getElementById('assignment-status');
            const statusSpinner = document.getElementById('status-spinner');
            const statusText = document.getElementById('status-text');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const toggleUrl = "{{ route('admin.teachers.clients.toggle', $teacher) }}";

            // Get all checkboxes
            const clientCheckboxes = document.querySelectorAll('.client-toggle-checkbox');

            // Add listener to each checkbox
            clientCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const clientId = this.dataset.clientId;
                    const isChecked = this.checked;
                    const badge = document.querySelector(`.client-badge-${clientId}`);
                    
                    // 1. Show "Saving..." status
                    showStatus('جاري الحفظ...', 'loading');
                    
                    // 2. Send the fetch request
                    fetch(toggleUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            client_id: clientId,
                            checked: isChecked
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            // Handle HTTP errors
                            return response.json().then(err => { throw new Error(err.message || 'Server error') });
                        }
                        return response.json();
                    })
                    .then(data => {
                        // 3. Handle Success
                        if (data.status === 'attached') {
                            showStatus('تم الحفظ بنجاح', 'success');
                            if(badge) {
                                badge.textContent = 'معين';
                                badge.classList.remove('bg-gray-100', 'text-gray-800');
                                badge.classList.add('bg-indigo-100', 'text-indigo-800');
                            }
                        } else if (data.status === 'detached') {
                            showStatus('تم الحفظ بنجاح', 'success');
                            if(badge) {
                                badge.textContent = 'متاح';
                                badge.classList.remove('bg-indigo-100', 'text-indigo-800');
                                badge.classList.add('bg-gray-100', 'text-gray-800');
                            }
                        }
                        // Hide status after 2 seconds
                        setTimeout(hideStatus, 2000);
                    })
                    .catch(error => {
                        // 4. Handle Failure
                        console.error('Error:', error);
                        showStatus('حدث خطأ. حاول مرة أخرى.', 'error');
                        // Revert the checkbox since it failed
                        this.checked = !isChecked; 
                        // Hide status after 3 seconds
                        setTimeout(hideStatus, 3000);
                    });
                });
            });

            // --- Status Indicator Functions ---
            function showStatus(message, type = 'loading') {
                statusText.textContent = message;
                statusContainer.style.display = 'flex';

                if (type === 'loading') {
                    statusSpinner.style.display = 'block';
                    statusText.className = 'text-sm text-gray-500';
                } else if (type === 'success') {
                    statusSpinner.style.display = 'none';
                    statusText.className = 'text-sm text-green-600';
                } else if (type === 'error') {
                    statusSpinner.style.display = 'none';
                    statusText.className = 'text-sm text-red-600';
                }
            }

            function hideStatus() {
                statusContainer.style.display = 'none';
                statusSpinner.style.display = 'none';
            }
        });
    </script>
</x-app-layout>

