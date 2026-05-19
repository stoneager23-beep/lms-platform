<x-app-layout>
    <div class="max-w-4xl mx-auto px-6 py-12">

        {{-- Page Header --}}
        <div class="mb-12 text-center">
            <h1 class="text-4xl font-black text-white tracking-tight mb-4">My <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">Lessons</span></h1>
            <p class="text-slate-400 text-lg">
                Continue your learning journey
            </p>
        </div>

        @php
            $groupedLessons = $lessons->groupBy(fn($lesson) => $lesson->course->title);
        @endphp

        @forelse($groupedLessons as $courseTitle => $courseLessons)

            {{-- Course Section --}}
            <div class="mb-12">

                <div class="flex items-center gap-4 mb-6">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-indigo-500/50 to-transparent"></div>
                    <h2 class="text-xl font-bold text-white uppercase tracking-wider">
                        {{ $courseTitle }}
                    </h2>
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-indigo-500/50 to-transparent"></div>
                </div>

                <div class="space-y-4">

                    @foreach($courseLessons as $lesson)
                        <div class="group bg-white/5 border border-white/10 rounded-2xl p-6
                                    hover:bg-white/10 hover:border-indigo-500/30 hover:shadow-lg hover:shadow-indigo-500/10 transition-all duration-300 backdrop-blur-sm relative overflow-hidden">
                            
                            {{-- Hover Glow --}}
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

                            <div class="flex items-center justify-between relative z-10">

                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-indigo-400 font-bold border border-white/5 group-hover:border-indigo-500/50 transition-colors">
                                        {{ $lesson->position ?? '#' }}
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white group-hover:text-indigo-300 transition-colors">
                                            {{ $lesson->title }}
                                        </h3>

                                        <p class="text-sm text-slate-400 mt-1">
                                            Estimated time: 10 mins
                                        </p>
                                    </div>
                                </div>

                                {{-- Action --}}
                                <a href="{{ route('student.lessons.show', $lesson) }}"
                                   class="px-5 py-2.5 bg-white/5 hover:bg-indigo-600 border border-white/10 hover:border-indigo-500/50
                                          text-slate-300 hover:text-white text-sm font-bold rounded-xl transition-all duration-300 shadow-lg">
                                    Start Lesson
                                </a>
                            </div>

                        </div>
                    @endforeach

                </div>
            </div>

        @empty
            {{-- Empty State --}}
            <div class="text-center py-20 bg-white/5 rounded-3xl border border-dashed border-white/10">
                <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">
                    No lessons available
                </h3>
                <p class="text-slate-400 mb-8 max-w-md mx-auto">
                    You haven't enrolled in any courses yet. Enroll now to access premium content.
                </p>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition">
                    Browse Courses
                </a>
            </div>
        @endforelse

    </div>
</x-app-layout>
