<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تعديل ملف العميل: ') }} {{ $client->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Main Profile Form -->
            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 md:p-8 text-gray-900">
                        <h3 class="text-lg font-semibold border-b pb-3 mb-6">المعلومات الأساسية</h3>
                        <form method="POST" action="{{ route('admin.clients.update', $client) }}" class="space-y-6">
                            @csrf
                            @method('PATCH')

                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('الاسم الكامل لولي الأمر')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $client->name)" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Email Address -->
                            <div>
                                <x-input-label for="email" :value="__('البريد الإلكتروني')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $client->email)" required autocomplete="username" />
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
                                <a href="{{ route('admin.clients.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
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

            <!-- Subscription Management Panel -->
            <!-- *** NO x-data needed here anymore *** -->
            <div class="lg:col-span-1">
                 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 md:p-8 text-gray-900">
                        <h3 class="text-lg font-semibold border-b pb-3 mb-6">إدارة الاشتراك</h3>
                        
                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        @if($subscription)
                            <div>
                                <div class="text-center mb-6">
                                    <p class="text-sm text-gray-500">الباقة الحالية</p>
                                    <p class="text-2xl font-bold text-indigo-600 mt-1">
                                        @if($subscription->plan_type === 'basic') الباقة الأساسية @endif
                                        @if($subscription->plan_type === 'advanced') الباقة المتقدمة @endif
                                        @if($subscription->plan_type === 'intensive') الباقة المكثفة @endif
                                    </p>
                                    <p class="text-sm text-gray-500 mt-2">تنتهي في: <span class="font-semibold">{{ \Carbon\Carbon::parse($subscription->ends_at)->translatedFormat('d M, Y') }}</span></p>
                                </div>

                                <h4 class="text-md font-semibold text-gray-800 mb-2">استهلاك الباقة</h4>
                                <div class="w-full bg-gray-200 rounded-full h-4">
                                    @php
                                        $percentage = ($subscription->total_lessons > 0) ? (($subscription->lessons_used / $subscription->total_lessons) * 100) : 0;
                                    @endphp
                                    <div class="bg-indigo-600 h-4 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <p class="text-sm text-gray-600 mt-2 text-center">
                                    تم استهلاك <span class="font-bold">{{ $subscription->lessons_used }}</span> من <span class="font-bold">{{ $subscription->total_lessons }}</span> حصص
                                </p>

                                <!-- *** THIS IS THE CORRECTED BUTTON *** -->
                                <div class="mt-6 border-t pt-6">
                                    <x-danger-button
                                        x-data=""
                                        x-on:click.prevent="$dispatch('open-modal', 'confirm-subscription-deletion')"
                                        class="w-full text-center justify-center"
                                    >
                                        {{ __('إلغاء الاشتراك الحالي') }}
                                    </x-danger-button>
                                </div>
                            </div>

                            <!-- *** THIS IS THE CORRECTED MODAL *** -->
                            <x-modal name="confirm-subscription-deletion" focusable>
                                <form method="POST" action="{{ route('admin.subscriptions.destroy', $subscription) }}" class="p-6">
                                    @csrf
                                    @method('DELETE')

                                    <h2 class="text-lg font-medium text-gray-900 text-right">
                                        {{ __('هل أنت متأكد من رغبتك في إلغاء هذا الاشتراك؟') }}
                                    </h2>

                                    <p class="mt-1 text-sm text-gray-600 text-right">
                                        {{ __('بمجرد إلغاء الاشتراك، سيتم حذفه نهائيًا. لا يمكن التراجع عن هذا الإجراء.') }}
                                    </p>

                                    <div class="mt-6 flex justify-end">
                                        <x-secondary-button x-on:click="$dispatch('close')">
                                            {{ __('إغلاق') }}
                                        </x-secondary-button>

                                        <x-danger-button class="mr-3">
                                            {{ __('تأكيد الإلغاء') }}
                                        </x-danger-button>
                                    </div>
                                </form>
                            </x-modal>
                            <!-- END OF UPDATED PART -->

                        @else
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                                <p class="mt-2 text-sm font-semibold text-gray-700">لا يوجد اشتراك نشط</p>
                                <p class="text-xs text-gray-500 mt-1">قم بتعيين باقة لهذا العميل لبدء حجز الحصص.</p>
                                <a href="{{ route('admin.clients.subscriptions.create', $client) }}" class="mt-4 block w-full text-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700">
                                    تعيين باقة جديدة
                                </a>
                            </div>
                        @endif
                        
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
