<footer class="bg-gray-800 text-white">
  <div class="container mx-auto px-6 py-12">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
      <!-- About Section -->
      <div>
        <h3 class="text-xl font-bold mb-4">أكاديمية كمـــــون</h3>
        <p class="text-gray-400 text-sm">
          نحن نربط الطلاب بأفضل معلمي اللغات من خلال منصة سهلة ومتابعة شخصية لضمان تحقيق أفضل النتائج.
        </p>
      </div>

      <!-- Quick Links -->
      <div>
        <h3 class="text-lg font-semibold mb-4">روابط سريعة</h3>
        <ul class="space-y-2 text-sm">
          <li><a href="#" class="hover:text-indigo-400 transition-colors">من نحن</a></li>
          <li><a href="{{ route('teachers.index') }}" class="hover:text-indigo-400 transition-colors">معلمونا</a></li>
          <li><a href="{{ route('pricing.index') }}" class="hover:text-indigo-400 transition-colors">الأسعار</a></li>
          <li><a href="{{ route('contact.index') }}" class="hover:text-indigo-400 transition-colors">اتصل بنا</a></li>
        </ul>
      </div>

      <!-- Contact Info -->
      <div>
        <h3 class="text-lg font-semibold mb-4">تواصل معنا</h3>
        <ul class="space-y-2 text-sm text-gray-400">
          <li><a href="mailto:contact@linguaconnect.com" class="hover:text-indigo-400">contact@linguaconnect.com</a></li>
          <li><a href="https://wa.me/21612345678" class="hover:text-indigo-400">WhatsApp: +216 12 345 678</a></li>
        </ul>
      </div>

      <!-- Social Media -->
      <div>
        <h3 class="text-lg font-semibold mb-4">تابعنا</h3>
        <div class="flex space-x-4">
          <a href="#" class="text-gray-400 hover:text-indigo-400"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35C.59 0 0 .59 0 1.325v21.35C0 23.41.59 24 1.325 24H12.82v-9.29H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.735 0 1.325-.59 1.325-1.325V1.325C24 .59 23.41 0 22.675 0z"></path></svg></a>
          <a href="#" class="text-gray-400 hover:text-indigo-400"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.85s-.011 3.584-.069 4.85c-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07s-3.584-.012-4.85-.07c-3.252-.148-4.771-1.691-4.919-4.919-.058-1.265-.069-1.645-.069-4.85s.011-3.584.069-4.85c.149-3.225 1.664-4.771 4.919 4.919 1.266-.057 1.644-.069 4.85-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12s.014 3.667.072 4.947c.2 4.358 2.618 6.78 6.98 6.98 1.267.058 1.674.072 4.947.072s3.68-.014 4.947-.072c4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.684.073-4.947s-.014-3.667-.072-4.947c-.196-4.354-2.617-6.78-6.979-6.98C15.68 0 15.26 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.88 1.44 1.44 0 000-2.88z"></path></svg></a>
        </div>
      </div>
    </div>
    <div class="mt-8 border-t border-gray-700 pt-6 text-center text-sm text-gray-400">
      <p>&copy; {{ date('Y') }} أكاديمية كمـــــون (LinguaConnect). جميع الحقوق محفوظة.</p>
    </div>
  </div>
</footer>
