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

                @php
                    $firstLesson = $courseLessons->first();
                    $isSequential = $firstLesson?->course?->is_sequential;
                @endphp

                <div class="flex items-center gap-4 mb-2">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-indigo-500/50 to-transparent"></div>
                    <h2 class="text-xl font-bold text-white uppercase tracking-wider">
                        {{ $courseTitle }}
                    </h2>
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-indigo-500/50 to-transparent"></div>
                </div>

                {{-- Sequential mode indicator --}}
                <div class="text-center mb-6">
                    @if($isSequential)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-500/10 border border-indigo-500/20 rounded-full text-xs font-bold text-indigo-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Sequential — Complete lessons in order
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded-full text-xs font-bold text-emerald-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                            Free Access — Jump to any lesson
                        </span>
                    @endif
                </div>

                <div class="space-y-4">

                    @foreach($courseLessons as $lesson)
                        @php
                            $isAccessible = $lesson->is_accessible ?? true;
                            $isCompleted = $lesson->is_completed_by_user ?? false;
                            $hasQuiz = $lesson->quiz && $lesson->quiz->questions->count() > 0;
                        @endphp

                        <div class="group relative overflow-hidden rounded-2xl border transition-all duration-300 backdrop-blur-sm
                            {{ $isCompleted
                                ? 'bg-emerald-500/5 border-emerald-500/20 hover:border-emerald-500/40'
                                : ($isAccessible
                                    ? 'bg-white/5 border-white/10 hover:bg-white/10 hover:border-indigo-500/30 hover:shadow-lg hover:shadow-indigo-500/10'
                                    : 'bg-white/[0.02] border-white/5 opacity-60 cursor-not-allowed') }}
                            p-6">

                            {{-- Hover Glow (only for accessible) --}}
                            @if($isAccessible && !$isCompleted)
                                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                            @endif

                            <div class="flex items-center justify-between relative z-10">

                                <div class="flex items-start gap-4">
                                    {{-- Position badge with state --}}
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold border transition-colors flex-shrink-0
                                        {{ $isCompleted
                                            ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30'
                                            : ($isAccessible
                                                ? 'bg-slate-800 text-indigo-400 border-white/5 group-hover:border-indigo-500/50'
                                                : 'bg-slate-800/50 text-slate-600 border-white/5') }}">
                                        @if($isCompleted)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        @elseif(!$isAccessible)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        @else
                                            {{ $lesson->position ?? '#' }}
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold transition-colors
                                            {{ $isCompleted
                                                ? 'text-emerald-300'
                                                : ($isAccessible
                                                    ? 'text-white group-hover:text-indigo-300'
                                                    : 'text-slate-500') }}">
                                            {{ $lesson->title }}
                                        </h3>

                                        <div class="flex items-center gap-3 mt-1">
                                            @if($hasQuiz)
                                                <span class="inline-flex items-center gap-1 text-xs font-bold
                                                    {{ $isCompleted ? 'text-emerald-400/70' : 'text-amber-400/70' }}">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                                    Quiz Required
                                                </span>
                                            @endif

                                            @if($isCompleted)
                                                <span class="text-xs font-bold text-emerald-400/70">✓ Completed</span>
                                            @elseif(!$isAccessible)
                                                <span class="text-xs font-medium text-slate-500">Complete previous lessons to unlock</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Action --}}
                                @if($isAccessible)
                                    <a href="{{ route('student.lessons.show', $lesson) }}"
                                       class="px-5 py-2.5 text-sm font-bold rounded-xl transition-all duration-300 shadow-lg flex-shrink-0
                                        {{ $isCompleted
                                            ? 'bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 hover:border-emerald-500/40 text-emerald-400'
                                            : 'bg-white/5 hover:bg-indigo-600 border border-white/10 hover:border-indigo-500/50 text-slate-300 hover:text-white' }}">
                                        {{ $isCompleted ? 'Review' : 'Start Lesson' }}
                                    </a>
                                @else
                                    <div class="px-5 py-2.5 bg-white/[0.02] border border-white/5 text-slate-600 text-sm font-bold rounded-xl flex items-center gap-2 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        Locked
                                    </div>
                                @endif
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
