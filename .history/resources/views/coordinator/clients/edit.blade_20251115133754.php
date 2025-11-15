<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تعديل ملف العميل: ') }} {{ $client->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 md:p-8 text-gray-900">
                        <h3 class="text-lg font-semibold border-b pb-3 mb-6">المعلومات الأساسية</h3>
                        <form method="POST" action="{{ route('coordinator.clients.update', $client) }}" class="space-y-6">
                            @csrf
                            @method('PATCH')

                            <div>
                                <x-input-label for="name" :value="__('الاسم الكامل لولي الأمر')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $client->name)" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('البريد الإلكتروني')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $client->email)" required autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <hr class="my-6">
                            
                            <p class="text-sm text-gray-600">اترك حقول كلمة المرور فارغة إذا كنت لا ترغب في تغييرها.</p>

                            <div>
                                <x-input-label for="password" :value="__('كلمة المرور الجديدة')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور الجديدة')" />
                                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <a href="{{ route('coordinator.clients.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
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

            <div class="lg:col-span-1">
                 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 md:p-8 text-gray-900">
                        <h3 class="text-lg font-semibold border-b pb-3 mb-6">إدارة الاشتراك</h3>
                        <p class="text-sm text-gray-500">قريباً... ستتمكن من إدارة اشتراكات هذا العميل من هنا.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>