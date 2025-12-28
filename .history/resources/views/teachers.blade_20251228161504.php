<x-public-layout>
    {{-- HERO SECTION --}}
    <div class="relative bg-slate-900 py-24 overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-10" alt="Teachers">
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/50 to-slate-900"></div>
        </div>
        
        <div class="container mx-auto px-6 relative z-10 text-center">
            <span class="inline-block py-1 px-3 rounded-full bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 text-sm font-bold mb-6 backdrop-blur-sm">
                🎓 نخبة من أفضل المعلمين
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6 leading-tight">
                تعلم من <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">الخبراء</span>
            </h1>
            <p class="text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed">
                في أكاديمية كمون، نختار معلمينا بعناية فائقة. جميعهم معتمدون، ذوو خبرة، ومتحمسون لمساعدتك في الوصول إلى أهدافك.
            </p>
        </div>
    </div>

    {{-- CONTENT SECTION --}}
    <div class="py-20 bg-gray-50 min-h-screen" dir="rtl">
        <div class="container mx-auto px-6">
            
            {{-- Filter Bar (Visual) --}}
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-12 flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="flex items-center gap-2 text-gray-500 font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>تصفية حسب:</span>
                </div>
                <div class="flex gap-3 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
                    <button class="px-5 py-2 rounded-xl bg-indigo-600 text-white font-bold text-sm shadow-lg shadow-indigo-200">الكل</button>
                    <button class="px-5 py-2 rounded-xl bg-gray-50 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 font-bold text-sm border border-gray-200 transition-colors">الإنجليزية</button>
                    <button class="px-5 py-2 rounded-xl bg-gray-50 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 font-bold text-sm border border-gray-200 transition-colors">الفرنسية</button>
                    <button class="px-5 py-2 rounded-xl bg-gray-50 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 font-bold text-sm border border-gray-200 transition-colors">الألمانية</button>
                </div>
            </div>

            {{-- TEACHERS GRID --}}
            @if($teachers->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($teachers as $teacher)
                        <div class="group bg-white rounded-3xl border border-gray-100 overflow-hidden hover:shadow-2xl hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all duration-300 flex flex-col">
                            
                            {{-- Header / Cover --}}
                            <div class="h-24 bg-gradient-to-r from-indigo-500 to-violet-600 relative">
                                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                            </div>

                            {{-- Avatar & Info --}}
                            <div class="px-8 pb-8 flex-1 flex flex-col items-center -mt-12 text-center">
                                <div class="relative w-24 h-24 mb-4">
                                    <div class="absolute inset-0 bg-white rounded-full"></div>
                                    <img src="{{ $teacher->profile_photo_url }}" 
                                         alt="{{ $teacher->name }}" 
                                         class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md relative z-10 bg-gray-100">
                                    <div class="absolute bottom-1 right-1 w-5 h-5 bg-green-500 border-4 border-white rounded-full z-20" title="متاح الآن"></div>
                                </div>

                                <h3 class="text-xl font-black text-slate-800 mb-1">{{ $teacher->name }}</h3>
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold border border-indigo-100">
                                        {{ $teacher->studySubject->name ?? 'معلم لغات' }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-center gap-1 mb-6 text-amber-400 text-sm">
                                    ★★★★★ <span class="text-gray-400 text-xs font-medium">(5.0)</span>
                                </div>

                                <p class="text-gray-500 text-sm leading-relaxed mb-8 line-clamp-3">
                                    {{ $teacher->bio ?? 'معلم متخصص ذو خبرة في تدريس اللغات بأساليب تفاعلية حديثة تساعد الطلاب على التحدث بطلاقة.' }}
                                </p>
                                
                                <div class="mt-auto w-full">
                                    <a href="{{ route('register') }}" class="block w-full py-3 rounded-xl bg-slate-900 text-white font-bold hover:bg-indigo-600 transition-colors shadow-lg hover:shadow-indigo-500/30">
                                        احجز حصة تجريبية
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-20">
                    <div class="w-24 h-24 bg-indigo-50 text-indigo-400 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">لا يوجد معلمين حالياً</h3>
                    <p class="text-gray-500">نحن بصدد إضافة نخبة جديدة من المعلمين قريباً.</p>
                </div>
            @endif

        </div>
    </div>
</x-public-layout>