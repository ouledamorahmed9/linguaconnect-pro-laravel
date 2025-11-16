<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('سجل أنشطة المنسقين') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    
                    <form method="GET" action="{{ route('admin.activity-log.index') }}" class="mb-6">
                        <div class="flex">
                            <x-text-input 
                                id="search" 
                                class="block w-full rounded-r-none" 
                                type="text" 
                                name="search" 
                                :value="$search" 
                                placeholder="ابحث عن نشاط أو اسم منسق..." />
                            <x-primary-button class="rounded-l-none">
                                {{ __('بحث') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <div class="flow-root">
                        @if($activities->isEmpty())
                            <p class="text-center text-gray-500 py-10">لا توجد أنشطة مسجلة حتى الآن.</p>
                        @else
                            <ul role="list" class="-mb-8">
                                @foreach($activities as $activity)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3 rtl:space-x-reverse">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center ring-8 ring-white">
                                                    @if(Str::contains($activity->description, 'أنشأ') || Str::contains($activity->description, 'ربط'))
                                                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                                    @elseif(Str::contains($activity->description, 'حذف') || Str::contains($activity->description, 'أزال') || Str::contains($activity->description, 'ألغى'))
                                                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" /></svg>
                                                    @else
                                                        <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 justify-between items-center md:flex">
                                                <div class="text-sm">
                                                    <span class="font-medium text-gray-900">{{ $activity->causer->name ?? 'مستخدم محذوف' }}</span>
                                                    <span class="text-gray-500"> {{ $activity->description }}</span>
                                                </div>
                                                <div class="mt-2 md:mt-0 text-sm text-gray-400 whitespace-nowrap">
                                                    {{ $activity->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div class="mt-6">
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>