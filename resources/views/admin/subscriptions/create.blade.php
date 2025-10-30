<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تعيين باقة جديدة للعميل: ') }} {{ $client->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    {{-- UPDATE THE FORM ACTION TO POINT TO THE NEW 'store' ROUTE --}}
                    <form method="POST" action="{{ route('admin.clients.subscriptions.store', $client) }}" class="space-y-6">
                        @csrf

                        <!-- Plan Selection -->
                        <div>
                            <x-input-label for="plan_type" :value="__('اختر الباقة')" />
                            <select id="plan_type" name="plan_type" required class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">-- يرجى الاختيار --</option>
                                <option value="basic">الباقة الأساسية (4 حصص)</option>
                                <option value="advanced">الباقة المتقدمة (8 حصص)</option>
                                <option value="intensive">الباقة المكثفة (12 حصة)</option>
                            </select>
                        </div>

                        <!-- Start Date -->
                        <div>
                            <x-input-label for="starts_at" :value="__('تاريخ بدء الاشتراك')" />
                            <x-text-input id="starts_at" class="block mt-1 w-full" type="date" name="starts_at" :value="date('Y-m-d')" required />
                            <p class="mt-1 text-xs text-gray-500">سينتهي الاشتراك تلقائياً بعد شهر واحد من هذا التاريخ.</p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.clients.edit', $client) }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                إلغاء
                            </a>

                            <x-primary-button>
                                {{ __('حفظ وتفعيل الاشتراك') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

