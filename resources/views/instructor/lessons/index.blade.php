<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-xs font-bold uppercase tracking-widest text-slate-500">
                        <li><a href="{{ route('instructor.courses.index') }}" class="hover:text-indigo-400 transition">My Courses</a></li>
                        <li><span class="mx-2">/</span></li>
                        <li class="text-indigo-400">Curriculum</li>
                    </ol>
                </nav>
                <h2 class="font-black text-3xl text-white leading-tight">
                    {{ $course->title }}
                </h2>
                <p class="text-slate-400 text-sm mt-1 flex items-center">
                    <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Manage your lessons and course content
                </p>
            </div>

            <a href="{{ route('instructor.courses.lessons.create', $course) }}"
               class="inline-flex items-center px-8 py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-500/20 hover:bg-indigo-500 transition-all transform hover:-translate-y-1 active:scale-95">
                <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                Add New Lesson
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto">
        <div class="relative">
            <div class="absolute left-8 top-0 bottom-0 w-1 bg-slate-800 rounded-full hidden md:block"></div>

            <div class="space-y-6">
                @forelse($lessons as $index => $lesson)
                    <div class="relative group pl-0 md:pl-16">
                        <div class="absolute left-5 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-900 border-4 border-slate-800 rounded-full z-10 hidden md:flex items-center justify-center group-hover:border-indigo-500 transition-colors duration-300">
                            <span class="text-[10px] font-black text-slate-500 group-hover:text-indigo-400">{{ $lesson->position }}</span>
                        </div>

                        <div class="bg-[#1e293b] border border-slate-800 rounded-3xl p-6 shadow-sm hover:shadow-2xl hover:shadow-indigo-500/5 transition-all duration-300 flex flex-col md:flex-row md:items-center justify-between gap-4" style="background-color: #1e293b;">
                            <div class="flex items-center space-x-4">
                                <div class="p-3 bg-slate-800 rounded-2xl text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white group-hover:text-indigo-400 transition-colors">
                                        {{ $lesson->title }}
                                    </h3>
                                    <div class="flex items-center space-x-3 mt-1 text-xs font-bold uppercase tracking-wider">
                                        @if($lesson->is_published)
                                            <span class="text-emerald-500">● Published</span>
                                        @else
                                            <span class="text-amber-500">○ Draft Mode</span>
                                        @endif
                                        <span class="text-slate-600">• Lesson #{{ $lesson->position }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2 border-t md:border-t-0 border-slate-800 pt-4 md:pt-0">
                                <a href="{{ route('instructor.lessons.edit', $lesson) }}"
                                   class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white text-xs font-black rounded-xl transition-all border border-slate-700 uppercase tracking-widest">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('instructor.lessons.destroy', $lesson) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Bhai, pakka delete karna hai? Ye wapis nahi aayega!')"
                                            class="p-2.5 text-red-500/50 hover:text-red-500 hover:bg-red-500/10 rounded-xl transition-all duration-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-[#1e293b] border border-dashed border-slate-700 rounded-[2.5rem] p-16 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-800 rounded-2xl mb-4">
                            <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-9-4h18c1.104 0 2 .896 2 2v10c0 1.104-.896 2-2 2H3c-1.104 0-2-.896-2-2V7c0-1.104.896-2 2-2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Empty Curriculum</h3>
                        <p class="text-slate-400 mb-6">Aapne abhi tak koi lesson add nahi kiya. Course ko mukammal karne ke liye lessons zaroori hain.</p>
                        <a href="{{ route('instructor.courses.lessons.create', $course) }}" class="bg-indigo-600 px-6 py-3 rounded-xl font-black text-white hover:bg-indigo-500 transition shadow-lg">Create First Lesson</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
