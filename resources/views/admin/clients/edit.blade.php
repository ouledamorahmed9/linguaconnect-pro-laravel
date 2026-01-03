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
                        <form method="POST" action="{{ route('admin.clients.update', $client) }}" class="space-y-6">
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

                            <div>
                                <x-input-label for="phone" :value="__('رقم الهاتف')" />
                                <x-text-input id="phone" class="block mt-1 w-full text-left" dir="ltr" type="text" name="phone" :value="old('phone', $client->phone)" required />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="study_subject_id" :value="__('المادة الدراسية المطلوبة')" />
                                <select id="study_subject_id" name="study_subject_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="" disabled>-- اختر المادة --</option>
                                    @foreach($studySubjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('study_subject_id', $client->study_subject_id) == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }} ({{ $subject->level }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('study_subject_id')" class="mt-2" />
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

            <div class="lg:col-span-1 space-y-6">
                 
                 {{-- Actions --}}
                 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" x-data="{ freeTrialModalOpen: false }">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">إجراءات سريعة</h3>
                        <a href="{{ route('admin.clients.subscriptions.create', $client) }}" class="flex items-center justify-center w-full px-4 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            تعيين باقة جديدة
                        </a>

                        {{-- Free Trial Button Trigger --}}
                        @if(!$client->hasActiveSubscription())
                            <button 
                                @click="freeTrialModalOpen = true"
                                type="button" 
                                class="mt-4 flex items-center justify-center w-full px-4 py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition shadow-lg shadow-green-200"
                            >
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                 تجربة مجانية
                            </button>
                        @endif
                    </div>

                    {{-- Free Trial Modal --}}
                    <div 
                        x-show="freeTrialModalOpen" 
                        style="display: none;"
                        class="fixed inset-0 z-50 overflow-y-auto" 
                        aria-labelledby="modal-title" 
                        role="dialog" 
                        aria-modal="true"
                    >
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div 
                                x-show="freeTrialModalOpen" 
                                x-transition:enter="ease-out duration-300" 
                                x-transition:enter-start="opacity-0" 
                                x-transition:enter-end="opacity-100" 
                                x-transition:leave="ease-in duration-200" 
                                x-transition:leave-start="opacity-100" 
                                x-transition:leave-end="opacity-0" 
                                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                                aria-hidden="true"
                                @click="freeTrialModalOpen = false"
                            ></div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                            <div 
                                x-show="freeTrialModalOpen" 
                                x-transition:enter="ease-out duration-300" 
                                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                x-transition:leave="ease-in duration-200" 
                                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full"
                            >
                                <form action="{{ route('admin.clients.subscriptions.store', $client) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="plan_type" value="free_trial">
                                    <input type="hidden" name="starts_at" value="{{ now() }}">
                                    
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="sm:flex sm:items-start">
                                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <div class="mt-3 text-center sm:mt-0 sm:mr-4 sm:text-right w-full">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                    منح تجربة مجانية
                                                </h3>
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-500 mb-4">
                                                        اختر اللغة التي يرغب العميل في تجربتها.
                                                    </p>
                                                    
                                                    <label for="target_language" class="block text-sm font-medium text-gray-700 mb-1 text-right">اللغة المستهدفة</label>
                                                    <select 
                                                        id="target_language" 
                                                        name="target_language" 
                                                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                                        required
                                                    >
                                                        @foreach($studySubjects as $subject)
                                                            <option value="{{ $subject->name }}" {{ ($client->studySubject->id ?? null) == $subject->id ? 'selected' : '' }}>
                                                                {{ $subject->name }} 
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            تأكيد المنح
                                        </button>
                                        <button 
                                            type="button" 
                                            @click="freeTrialModalOpen = false" 
                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                        >
                                            إلغاء
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                 </div>

                 <!-- Subscriptions List -->
                 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 md:p-8 text-gray-900">
                        <h3 class="text-lg font-semibold border-b pb-3 mb-6">إدارة الاشتراكات</h3>
                        
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        @php
                            // Fetch all active subscriptions for this client
                            $subscriptions = $client->subscriptions()
                                ->where('status', 'active')
                                ->where('ends_at', '>', now())
                                ->latest('starts_at')
                                ->get();
                        @endphp

                        @if($subscriptions->isNotEmpty())
                            @foreach($subscriptions as $index => $subscription)
                                @if($index > 0) <hr class="my-6 border-gray-100"> @endif

                                <div>
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="mb-2">
                                            <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-bold px-2 py-0.5 rounded mb-1">
                                                {{ $subscription->target_language ?? 'General' }}
                                            </span>
                                            <p class="text-xl font-bold text-indigo-600">
                                                @if($subscription->plan_type === 'basic') الباقة الأساسية @endif
                                                @if($subscription->plan_type === 'advanced') الباقة المتقدمة @endif
                                                @if($subscription->plan_type === 'intensive') الباقة المكثفة @endif
                                                @if(in_array($subscription->plan_type, ['one_to_one', 'duo', 'vip', 'normal'])) {{ ucfirst(str_replace('_', ' ', $subscription->plan_type)) }} @endif
                                            </p>
                                        </div>
                                        <div class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded">
                                            نشط
                                        </div>
                                    </div>
                                    
                                    <p class="text-sm text-gray-500 mb-4 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        تنتهي في: <span class="font-semibold">{{ \Carbon\Carbon::parse($subscription->ends_at)->translatedFormat('d M, Y') }}</span>
                                    </p>

                                    <h4 class="text-xs font-bold uppercase text-gray-400 mb-2">الاستهلاك</h4>
                                    <div class="w-full bg-gray-100 rounded-full h-3 mb-2">
                                        @php
                                            $percentage = ($subscription->total_lessons > 0) ? (($subscription->lessons_used / $subscription->total_lessons) * 100) : 0;
                                        @endphp
                                        <div class="bg-indigo-600 h-3 rounded-full relative" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-500 mb-4">
                                        <span>{{ $subscription->lessons_used }} حصص مستهلكة</span>
                                        <span>من أصل {{ $subscription->total_lessons }}</span>
                                    </div>

                                    <div class="border-t pt-4">
                                        <x-danger-button
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'confirm-subscription-deletion-{{ $subscription->id }}')"
                                            class="w-full text-center justify-center text-xs"
                                        >
                                            {{ __('إلغاء هذا الاشتراك') }}
                                        </x-danger-button>
                                    </div>
                                </div>

                                <x-modal name="confirm-subscription-deletion-{{ $subscription->id }}" focusable>
                                    <form method="POST" action="{{ route('admin.subscriptions.destroy', $subscription) }}" class="p-6">
                                        @csrf
                                        @method('DELETE')
                                        <h2 class="text-lg font-medium text-gray-900 text-right">{{ __('هل أنت متأكد؟') }}</h2>
                                        <p class="mt-1 text-sm text-gray-600 text-right">{{ __('سيتم حذف باقة ' . ($subscription->target_language ?? '') . ' نهائياً.') }}</p>
                                        <div class="mt-6 flex justify-end">
                                            <x-secondary-button x-on:click="$dispatch('close')">{{ __('إغلاق') }}</x-secondary-button>
                                            <x-danger-button class="mr-3">{{ __('تأكيد الإلغاء') }}</x-danger-button>
                                        </div>
                                    </form>
                                </x-modal>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-700">لا توجد اشتراكات نشطة</p>
                                <p class="text-xs text-gray-500 mt-1">اضغط على زر التعيين لإضافة باقة جديدة.</p>
                            </div>
                        @endif
                        
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>