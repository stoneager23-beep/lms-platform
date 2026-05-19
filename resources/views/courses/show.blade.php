<x-app-layout>
    {{-- Hero Section --}}
    <div class="relative bg-[#0f172a] border-b border-white/5 pb-12 pt-8">
        <div class="absolute inset-0 bg-indigo-600/5"></div>
        <div class="max-w-6xl mx-auto px-4 relative z-10">

            {{-- Breadcrumb --}}
            <a href="{{ route('courses.index') }}" class="inline-flex items-center text-sm font-bold text-slate-400 hover:text-indigo-400 transition mb-8">
                ← Back to Catalog
            </a>

            <div class="flex flex-col md:flex-row md:items-start gap-8">
                <div class="flex-1">
                    <h1 class="text-3xl md:text-5xl font-black text-white leading-tight mb-4">
                        {{ $course->title }}
                    </h1>
                    <p class="text-lg text-slate-400 leading-relaxed max-w-2xl mb-6">
                        {{ $course->description }}
                    </p>

                    <div class="flex items-center gap-4 text-sm text-slate-300">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>{{ $course->instructor->name ?? 'Instructor' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span>{{ $course->lessons->count() }} Lessons</span>
                        </div>
                    </div>
                </div>

                {{-- Enroll Card --}}
                <div class="w-full md:w-80 shrink-0">
                    <div class="bg-[#1e293b] border border-slate-700 rounded-2xl p-6 shadow-2xl sticky top-6">
                        @if ($isEnrolled)
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-500/20 text-green-400 mb-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <h3 class="text-white font-bold text-lg mb-1">Already Enrolled</h3>
                                <p class="text-slate-400 text-sm mb-6">You have access to this course.</p>
                                <a href="{{ route('student.courses.index') }}"
                                   class="block w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition text-center">
                                    Continue Learning
                                </a>
                                <form method="POST" action="{{ route('student.courses.unenroll', $course) }}" class="mt-3"
                                      onsubmit="return confirm('Are you sure you want to unenroll? Your progress will be lost.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full py-2.5 bg-transparent hover:bg-red-500/10 border border-red-500/20 hover:border-red-500/30 text-red-400 hover:text-red-300 text-sm font-semibold rounded-xl transition cursor-pointer">
                                        Unenroll from Course
                                    </button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('student.courses.enroll', $course) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/20 transition transform active:scale-95">
                                    Enroll Now
                                </button>
                            </form>
                            <p class="text-center text-xs text-slate-500 mt-4">
                                Instant access to all lessons
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="max-w-6xl mx-auto px-4 py-12">
        <h2 class="text-2xl font-bold text-white mb-6">Course Curriculum</h2>

        <div class="space-y-4">
            @forelse ($course->lessons as $lesson)
                <div class="bg-[#1e293b]/50 border border-slate-800 rounded-xl p-4 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-slate-500 font-mono text-xs">
                            {{ $loop->iteration }}
                        </div>
                        <span class="text-slate-200 font-medium">{{ $lesson->title }}</span>
                    </div>

                    @if($isEnrolled)
                        <span class="text-xs text-indigo-400 font-bold px-2 py-1 bg-indigo-500/10 rounded">Access</span>
                    @else
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    @endif
                </div>
            @empty
                <p class="text-slate-400 italic">No lessons available yet.</p>
            @endforelse
        </div>
    </div>

</x-app-layout>
