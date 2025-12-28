<footer class="bg-slate-900 text-white border-t border-slate-800">
  <div class="container mx-auto px-6 py-12">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
      
      <div>
        <h3 class="text-xl font-black mb-4 flex items-center gap-2">
            <span class="text-indigo-500">❖</span> أكاديمية كمـــــون
        </h3>
        <p class="text-slate-400 text-sm leading-relaxed">
          نحن نربط الطلاب بأفضل معلمي اللغات من خلال منصة سهلة ومتابعة شخصية لضمان تحقيق أفضل النتائج.
        </p>
      </div>

      <div>
        <h3 class="text-lg font-bold mb-4 text-slate-200">روابط سريعة</h3>
        <ul class="space-y-2 text-sm text-slate-400">
          <li><a href="/" class="hover:text-indigo-400 transition-colors">الرئيسية</a></li>
          <li><a href="{{ route('teachers.index') }}" class="hover:text-indigo-400 transition-colors">معلمونا</a></li>
          <li><a href="{{ route('pricing.index') }}" class="hover:text-indigo-400 transition-colors">الأسعار</a></li>
        </ul>
      </div>

      <div>
        <h3 class="text-lg font-bold mb-4 text-slate-200">السياسات والشروط</h3>
        <ul class="space-y-2 text-sm text-slate-400">
          <li><a href="{{ route('legal.privacy') }}" class="hover:text-indigo-400 transition-colors">سياسة الخصوصية</a></li>
          <li><a href="{{ route('legal.terms') }}" class="hover:text-indigo-400 transition-colors">شروط الخدمة</a></li>
          <li><a href="{{ route('legal.refund') }}" class="hover:text-indigo-400 transition-colors">سياسة الاسترجاع</a></li>
        </ul>
      </div>

      <div>
        <h3 class="text-lg font-bold mb-4 text-slate-200">تواصل معنا</h3>
        <ul class="space-y-2 text-sm text-slate-400">
          <li class="flex items-center gap-2">
              <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
              <a href="mailto:support@linguaconnect.com" class="hover:text-indigo-400">support@linguaconnect.com</a>
          </li>
          <li class="flex items-center gap-2">
              <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
              <span>+1 (555) 123-4567</span>
          </li>
        </ul>
      </div>

    </div>
    
    <div class="mt-12 border-t border-slate-800 pt-6 text-center text-xs text-slate-500">
      <p>&copy; {{ date('Y') }} أكاديمية كمـــــون LLC. جميع الحقوق محفوظة.</p>
    </div>
  </div>
</footer>