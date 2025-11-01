<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تعديل ملف المعلم: ') }} {{ $teacher->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Display success messages --}}
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Main Profile Form (Left Column) -->
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
                                    <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور الجديدة')" />
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

                <!-- Assigned Clients Management Panel (Right Column) -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <form method="POST" action="{{ route('admin.teachers.clients.sync', $teacher) }}">
                            @csrf
                            <div class="p-6 md:p-8 text-gray-900">
                                <h3 class="text-lg font-semibold border-b pb-3 mb-6">العملاء المعينون</h3>
                                <p class="text-sm text-gray-600 mb-4">اختر العملاء الذين سيتمكن هذا المعلم من حجز الحصص لهم.</p>

                                <div class="space-y-3 max-h-96 overflow-y-auto border rounded-md p-4">
                                    @forelse($allClients as $client)
                                        <label for="client_{{ $client->id }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 cursor-pointer">
                                            <input type="checkbox" 
                                                   id="client_{{ $client->id }}" 
                                                   name="clients[]" 
                                                   value="{{ $client->id }}"
                                                   @if(in_array($client->id, $assignedClientIds)) checked @endif
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="mr-3 text-sm font-medium text-gray-800">{{ $client->name }}</span>
                                        </label>
                                    @empty
                                        <p class="text-sm text-gray-500">لا يوجد عملاء لإضافتهم. قم بإضافة عميل أولاً.</p>
                                    @endforelse
                                </div>
                            </div>
                            <div class="bg-gray-50 px-6 py-4 flex justify-end">
                                <x-primary-button>
                                    {{ __('حفظ قائمة العملاء') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

