<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-white leading-tight">
                    {{ __('My Courses') }}
                </h2>
                <p class="text-slate-400 text-sm mt-1">Manage and monitor your educational content</p>
            </div>

            <a href="{{ route('instructor.courses.create') }}"
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-500/20 hover:from-indigo-500 hover:to-purple-500 transition-all transform hover:-translate-y-1 active:scale-95">
                <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                </svg>
                Create New Course
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        @if($courses->isEmpty())
            <div class="bg-[#1e293b] border border-dashed border-slate-700 rounded-[2.5rem] p-20 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-slate-800 rounded-3xl mb-6">
                    <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">No courses yet</h3>
                <p class="text-slate-400 mb-8">Start your journey by creating your first course today.</p>
                <a href="{{ route('instructor.courses.create') }}" class="text-indigo-400 font-bold hover:underline italic">Click here to begin &rarr;</a>
            </div>
        @else
            <div class="grid grid-cols-[repeat(auto-fit,minmax(320px,1fr))] gap-6">

            @foreach($courses as $course)
                    <div class="group bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden
            hover:border-indigo-500/30 transition-all duration-300">

                        {{-- Top strip (visual anchor) --}}
                        <div class="h-24 bg-gradient-to-r from-indigo-600/30 to-purple-600/30
                flex items-center justify-between px-6">

                            <h3 class="text-lg font-extrabold text-white truncate">
                                {{ $course->title }}
                            </h3>

                            @if($course->status === 'published')
                                <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full
                         bg-emerald-500/20 text-emerald-400">
                Live
            </span>
                            @else
                                <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full
                         bg-amber-500/20 text-amber-400">
                Draft
            </span>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="p-6 space-y-4">

                            <p class="text-slate-400 text-sm line-clamp-2">
                                {{ $course->description ?? 'No description provided.' }}
                            </p>

                            {{-- Stats row --}}
                            <div class="flex gap-3">
                                <div class="flex-1 bg-black/30 rounded-xl p-3 text-center">
                                    <p class="text-[10px] uppercase text-slate-400 font-black">Lessons</p>
                                    <p class="text-lg font-extrabold text-white">
                                        {{ $course->lessons->count() }}
                                    </p>

                                </div>
                                <div class="flex-1 bg-black/30 rounded-xl p-3 text-center">
                                    <p class="text-[10px] uppercase text-slate-400 font-black">Enrolled</p>
                                    <p class="text-lg font-extrabold text-white">
                                        {{ $course->students->count() }}
                                    </p>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex gap-3 pt-2">
                                <a href="{{ route('instructor.courses.edit', $course) }}"
                                   class="flex-1 py-2.5 text-sm font-bold rounded-xl
                      bg-white/10 hover:bg-white/20 text-white transition">
                                    Edit
                                </a>

                                <a href="{{ route('instructor.courses.lessons.index', $course) }}"
                                   class="flex-1 py-2.5 text-sm font-bold rounded-xl
                      bg-gradient-to-r from-indigo-600 to-purple-600
                      hover:from-indigo-500 hover:to-purple-500 text-white transition">
                                    Lessons
                                </a>
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
