<x-public-layout>
    <div class="relative h-64 md:h-80 w-full overflow-hidden">
        <img src="{{ $teacher->banner_url }}" class="w-full h-full object-cover" alt="Cover">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
    </div>

    <div class="container mx-auto px-6 mb-20 relative z-10 -mt-20" dir="rtl">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-xl p-6 border border-gray-100 text-center sticky top-24">
                    <div class="relative w-32 h-32 mx-auto -mt-20 mb-4">
                        <img src="{{ $teacher->profile_photo_url }}" class="w-32 h-32 rounded-full border-4 border-white shadow-md object-cover bg-white">
                        <div class="absolute bottom-2 right-2 w-6 h-6 bg-green-500 border-4 border-white rounded-full" title="Online"></div>
                    </div>
                    
                    <h1 class="text-2xl font-black text-slate-800">{{ $teacher->name }}</h1>
                    <p class="text-indigo-600 font-bold text-sm mb-4">{{ $teacher->studySubject->name ?? 'مدرس لغات' }}</p>

                    <div class="flex justify-center gap-6 mb-6 border-b border-gray-100 pb-6">
                        <div class="text-center">
                            <span class="block text-xl font-black text-slate-800">{{ $teacher->average_rating }}</span>
                            <span class="text-xs text-gray-400">تقييم</span>
                        </div>
                        <div class="w-px bg-gray-200"></div>
                        <div class="text-center">
                            <span class="block text-xl font-black text-slate-800">{{ $teacher->reviews_count }}</span>
                            <span class="text-xs text-gray-400">رأي</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <a href="#reviews-section" class="block w-full py-3 rounded-xl bg-indigo-50 text-indigo-700 font-bold hover:bg-indigo-100 transition">
                            أضف تقييم ✍️
                        </a>
                        <a href="{{ route('register') }}" class="block w-full py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold shadow-lg hover:shadow-indigo-500/30 transition">
                            احجز حصة الآن
                        </a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-xl font-bold text-slate-800 mb-4 border-b pb-2">نبذة عن المعلم</h3>
                    <div class="prose prose-lg text-gray-600 leading-relaxed">
                        @if($teacher->bio)
                            {!! nl2br(e($teacher->bio)) !!}
                        @else
                            <p class="text-gray-400 italic">لم يقم المعلم بإضافة نبذة تعريفية بعد.</p>
                        @endif
                    </div>
                </div>

                <div id="reviews-section" class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-xl font-bold text-slate-800">آراء الطلاب ({{ $teacher->reviews_count }})</h3>
                        <div class="flex text-amber-400">
                            @for($i=1; $i<=5; $i++)
                                <svg class="w-5 h-5 {{ $i <= round($teacher->average_rating) ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    </div>

                    <div class="space-y-6 mb-10">
                        @forelse($teacher->receivedReviews as $review)
                            <div class="flex gap-4 p-4 bg-gray-50 rounded-2xl">
                                <img src="{{ $review->client->profile_photo_url }}" class="w-12 h-12 rounded-full object-cover">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-bold text-slate-900">{{ $review->client->name }}</span>
                                        <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="flex text-amber-400 text-xs mb-2">
                                        @for($i=0; $i<$review->rating; $i++) ★ @endfor
                                    </div>
                                    <p class="text-gray-600 text-sm">{{ $review->comment }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-400 py-4">لا توجد تقييمات حتى الآن. كن أول من يقيم هذا المعلم!</div>
                        @endforelse
                    </div>

                    @auth
                        <div class="bg-indigo-50 p-6 rounded-2xl border border-indigo-100">
                            <h4 class="font-bold text-indigo-900 mb-4">أضف تجربتك</h4>
                            <form action="{{ route('teachers.reviews.store', $teacher->id) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">التقييم</label>
                                    <select name="rating" class="w-full rounded-xl border-gray-200">
                                        <option value="5">⭐⭐⭐⭐⭐ ممتاز</option>
                                        <option value="4">⭐⭐⭐⭐ جيد جداً</option>
                                        <option value="3">⭐⭐⭐ جيد</option>
                                        <option value="2">⭐⭐ مقبول</option>
                                        <option value="1">⭐ سيء</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">تعليقك</label>
                                    <textarea name="comment" rows="3" class="w-full rounded-xl border-gray-200" placeholder="كيف كانت الحصة؟" required></textarea>
                                </div>
                                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-indigo-700">نشر التقييم</button>
                            </form>
                        </div>
                    @else
                        <div class="text-center bg-gray-50 p-6 rounded-2xl">
                            <p class="text-gray-500 mb-4">يجب عليك تسجيل الدخول لإضافة تقييم.</p>
                            <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline">تسجيل الدخول</a>
                        </div>
                    @endauth

                </div>
            </div>

        </div>
    </div>
</x-public-layout>