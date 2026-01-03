<x-guest-layout>
    <div
        class="min-h-screen flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute top-[-10%] right-[-5%] w-96 h-96 bg-green-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-[-10%] left-[-5%] w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
        </div>

        <div
            class="max-w-md w-full bg-white rounded-[2.5rem] shadow-2xl shadow-indigo-100/50 border border-white/50 relative z-10 overflow-hidden text-center p-8">

            <div
                class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h2 class="text-3xl font-black text-slate-900 mb-2">تم الاشتراك بنجاح! 🎉</h2>
            <p class="text-lg text-slate-600 mb-6">
                سنقوم بربطك بأفضل معلم للغة <span
                    class="text-indigo-600 font-bold">{{ $subscription->target_language }}</span> في أقرب وقت ممكن.
            </p>

            <div class="bg-indigo-50 p-6 rounded-2xl border border-indigo-100 mb-8">
                <h3 class="font-bold text-indigo-900 text-lg mb-2">خطوة أخيرة لإتمام التسجيل</h3>
                <p class="text-sm text-indigo-700 mb-4">
                    يرجى إدخال رقم الواتساب الخاص بك حتى نتمكن من الاتصال بك وشرح كافة التفاصيل.
                </p>

                <form action="{{ route('client.subscription.update-whatsapp', $subscription) }}" method="POST">
                    @csrf
                    <div class="relative mb-4">
                        <div
                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                            </svg>
                        </div>
                        <input type="text" name="whatsapp_number" required placeholder="05xxxxxxxx"
                            class="w-full text-left bg-white border border-indigo-200 rounded-xl px-4 py-3 pl-10 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium text-slate-800"
                            dir="ltr">
                    </div>

                    <button type="submit"
                        class="w-full bg-green-500 text-white font-bold py-3.5 rounded-xl hover:bg-green-600 shadow-lg shadow-green-200 transition-all transform hover:-translate-y-0.5">
                        إرسال وإتمام التسجيل
                    </button>
                </form>
            </div>

            <a href="{{ route('client.dashboard') }}" class="text-slate-400 text-sm hover:text-slate-600">تخطي مؤقتاً
                والذهاب للوحة التحكم</a>
        </div>
    </div>
</x-guest-layout>