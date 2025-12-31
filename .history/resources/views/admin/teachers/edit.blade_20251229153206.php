<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تعديل ملف المعلم: ') }} {{ $teacher->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 md:p-8 text-gray-900">
                        <h3 class="text-lg font-semibold border-b pb-3 mb-6">المعلومات الأساسية</h3>
                        
                        <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}" class="space-y-6" id="teacher-form">
                            @csrf
                            @method('PATCH')

                            <div>
                                <x-input-label for="name" :value="__('الاسم الكامل للمعلم')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $teacher->name)" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('البريد الإلكتروني')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $teacher->email)" required autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="subject" :value="__('المادة / التخصص')" />
                                <x-text-input id="subject" class="block mt-1 w-full" type="text" name="subject" :value="old('subject', $teacher->subject)" />
                                <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                            </div>

                            <div class="pt-4 border-t mt-4">
                                <h4 class="text-sm font-bold text-gray-500 mb-4">تغيير كلمة المرور (اختياري)</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="password" :value="__('كلمة المرور الجديدة')" />
                                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور')" />
                                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <x-primary-button>{{ __('حفظ التعديلات') }}</x-primary-button>
                                @if (session('status'))
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600">{{ session('status') }}</p>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold border-b pb-3 mb-4">
                            قائمة العملاء (ذوي الاشتراك النشط)
                        </h3>
                        
                        <div class="max-h-[500px] overflow-y-auto space-y-2 custom-scrollbar">
                            @forelse($clients as $client)
                                <label class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" 
                                               name="clients[]" 
                                               value="{{ $client->id }}" 
                                               form="teacher-form"
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                               {{ $teacher->clients->contains($client->id) ? 'checked' : '' }}>
                                        
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-800">{{ $client->name }}</span>
                                            <span class="text-xs text-gray-500">{{ $client->email }}</span>
                                        </div>
                                    </div>
                                    <span class="text-green-500 text-xs font-bold">نشط</span>
                                </label>
                            @empty
                                <div class="text-center py-6 text-gray-400">
                                    <p>لا يوجد طلاب باشتراكات نشطة حالياً.</p>
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>