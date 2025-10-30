@extends('layouts.public')

@section('title', 'معلمونا - وصلة تعليم')

@section('content')
    <!-- Page Header Section -->
    <section class="bg-gray-50 pt-32 pb-16">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800">تعرف على فريقنا الاستثنائي</h1>
            <p class="text-lg text-gray-600 mt-4 max-w-3xl mx-auto">
                نحن نؤمن بأن المعلم الملهم هو أساس التجربة التعليمية الناجحة. تعرف على الخبراء الذين سيرافقون طفلك في رحلته.
            </p>
        </div>
    </section>

    <!-- Teachers Grid Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                @php
                    $allTeachers = [
                        [
                                    'name' => 'نورة عبدالله',
                                    'title' => 'خبيرة لغة إنجليزية للأطفال',
                                    'imageUrl' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=1888&auto=format&fit=crop',
                                    'bio' => 'بخبرة تزيد عن 8 سنوات في تدريس الأطفال، تستخدم نورة أساليب تفاعلية وممتعة لجعل تعلم اللغة الإنجليزية رحلة شيقة ومثمرة للصغار.',
                                    'specialties' => ['التعليم المبكر', 'المنهج التفاعلي', 'النطق السليم'],
                                ],
                                [
                                    'name' => 'كريم محمود',
                                    'title' => 'متخصص في اللغة الفرنسية للمبتدئين',
                                    'imageUrl' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=1887&auto=format&fit=crop',
                                    'bio' => 'يؤمن كريم بأن تأسيس قاعدة قوية هو مفتاح إتقان أي لغة. يركز على القواعد الأساسية والمحادثة العملية لمساعدة الطلاب على بناء الثقة.',
                                    'specialties' => ['قواعد اللغة الفرنسية', 'المحادثة اليومية', 'التحضير للسفر'],
                                ],
                                [
                                    'name' => 'سارة علي',
                                    'title' => 'محترفة التحضير لاختبارات IELTS',
                                    'imageUrl' => 'https://images.unsplash.com/photo-1543269865-cbf427effbad?q=80&w=2070&auto=format&fit=crop',
                                    'bio' => 'ساعدت سارة عشرات الطلاب على تحقيق الدرجات التي يطمحون إليها في اختبار IELTS. تركز على استراتيجيات الاختبار وإدارة الوقت.',
                                    'specialties' => ['IELTS Academic', 'الكتابة المتقدمة', 'استراتيجيات الاختبار'],
                                ],
                                [
                                    'name' => 'محمد غانم',
                                    'title' => 'مدرب محادثة وطلاقة باللغة الإنجليزية',
                                    'imageUrl' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=1887&auto=format&fit=crop',
                                    'bio' => 'لمن يرغب في كسر حاجز الخوف من التحدث، يقدم محمد دروساً ديناميكية تركز بشكل كامل على المحادثة في مواضيع متنوعة وعصرية.',
                                    'specialties' => ['طلاقة المحادثة', 'الإنجليزية للأعمال', 'اللهجة الأمريكية'],
                                ],
                                [
                                    'name' => 'ليلى إبراهيم',
                                    'title' => 'خبيرة اللغة الفرنسية للأعمال',
                                    'imageUrl' => 'https://images.unsplash.com/photo-1580894742597-87bc73293557?q=80&w=2070&auto=format&fit=crop',
                                    'bio' => 'متخصصة في تعليم اللغة الفرنسية للمحترفين في مجالات الأعمال والدبلوماسية، مع التركيز على المصطلحات الدقيقة وآداب التواصل الرسمي.',
                                    'specialties' => ['الفرنسية للأعمال', 'المصطلحات التقنية', 'مهارات العرض والتقديم'],
                                ],
                                [
                                    'name' => 'ياسين أحمد',
                                    'title' => 'مدرس لغة إنجليزية للمراهقين',
                                    'imageUrl' => 'https://images.unsplash.com/photo-1610933762391-72f50c055f67?q=80&w=1887&auto=format&fit=crop',
                                    'bio' => 'يستخدم ياسين اهتمامات الطلاب من أفلام وموسيقى لجعل الدروس أكثر ارتباطاً بواقعهم، مما يعزز دافعيتهم للتعلم والممارسة.',
                                    'specialties' => ['التعليم بالترفيه', 'الثقافة الشعبية', 'الكتابة الإبداعية'],
                                ],
                    ];
                @endphp

                @foreach ($allTeachers as $teacher)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col group transform hover:-translate-y-2 transition-transform duration-300">
                        <div class="h-80 w-full overflow-hidden">
                            <img 
                                src="{{ $teacher['imageUrl'] }}" 
                                alt="Profile of {{ $teacher['name'] }}" 
                                class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-500"
                            >
                        </div>
                        <div class="p-6 flex-grow flex flex-col">
                            <h3 class="text-2xl font-bold text-gray-800 mb-1">{{ $teacher['name'] }}</h3>
                            <p class="text-indigo-600 font-semibold mb-4">{{ $teacher['title'] }}</p>
                            <p class="text-gray-600 text-sm mb-4 flex-grow">{{ $teacher['bio'] }}</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($teacher['specialties'] as $spec)
                                    <span class="bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1 rounded-full">
                                        {{ $spec }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

