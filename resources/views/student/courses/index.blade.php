<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-white leading-tight">
                    My Learning
                </h2>
                <p class="text-slate-400 text-sm mt-1">
                    Courses you are currently enrolled in
                </p>
            </div>

            <a href="{{ route('courses.index') }}"
               class="px-4 py-2 bg-indigo-600/10 hover:bg-indigo-600/20 text-indigo-400 font-bold rounded-lg transition text-sm">
                Browse New Courses →
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6 py-10">

        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-white tracking-tight">My Learning</h2>
            <p class="text-slate-400 mt-2">Continue where you left off</p>
        </div>

        {{-- Grid Container --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($courses as $course)

                <div class="bg-white/5 backdrop-blur-lg border border-white/10 rounded-2xl overflow-hidden flex flex-col h-full hover:shadow-2xl hover:shadow-indigo-500/20 transition-all duration-300 group transform hover:-translate-y-1">

                    {{-- TOP: THUMBNAIL --}}
                    <div class="h-48 w-full relative overflow-hidden">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center text-slate-500 flex-col bg-slate-800/50">
                                <svg class="w-12 h-12 mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4a3 3 0 014 0l4 4m0 0l4-4m-4 4V4" />
                                </svg>
                                <span class="text-xs font-bold uppercase tracking-widest opacity-50">No Preview</span>
                            </div>
                        @endif
                        
                        {{-- Overlay Gradient --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-transparent to-transparent opacity-80"></div>

                        {{-- Category Badge (Optional/Placeholder) --}}
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1 bg-black/50 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider rounded-full border border-white/10">
                                Course
                            </span>
                        </div>
                    </div>

                    {{-- BOTTOM: CONTENT --}}
                    <div class="p-6 flex flex-col flex-1">
                        
                        <div class="flex-1 space-y-4">
                            <div>
                                <h3 class="text-xl font-bold text-white leading-tight group-hover:text-indigo-400 transition-colors">
                                    {{ $course->title }}
                                </h3>

                                @if($course->instructor)
                                    <div class="flex items-center gap-2 mt-2">
                                        <div class="w-6 h-6 rounded-full bg-indigo-500/20 flex items-center justify-center text-[10px] text-indigo-300 font-bold border border-indigo-500/30">
                                            {{ substr($course->instructor->name, 0, 1) }}
                                        </div>
                                        <span class="text-xs text-slate-400 font-medium">
                                            {{ $course->instructor->name }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                           {{-- Progress --}}
                            @php $progress = $course->progress(auth()->user()); @endphp
                            <div class="space-y-2">
                                <div class="flex justify-between text-xs font-bold text-slate-300">
                                    <span>Progress</span>
                                    <span class="text-indigo-400">{{ $progress }}%</span>
                                </div>
                                <div class="w-full bg-slate-700/30 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-1.5 rounded-full shadow-[0_0_10px_rgba(99,102,241,0.5)] transition-all duration-500"
                                         style="width: {{ $progress }}%">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-white/5 space-y-2">
                            <a href="{{ route('student.lessons.index') }}"
                               class="block w-full py-3 bg-white/5 hover:bg-white/10 border border-white/5 hover:border-white/10 text-white text-center text-sm font-bold rounded-xl transition backdrop-blur-sm">
                                Continue Learning
                            </a>
                            <form method="POST" action="{{ route('student.courses.unenroll', $course) }}"
                                  onsubmit="return confirm('Are you sure you want to unenroll from this course? Your progress will be lost.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full py-2.5 bg-red-500/5 hover:bg-red-500/15 border border-red-500/10 hover:border-red-500/25 text-red-400 hover:text-red-300 text-center text-xs font-bold rounded-xl transition cursor-pointer">
                                    Unenroll
                                </button>
                            </form>
                        </div>
                    </div>

                </div>

            @empty

                <div class="col-span-full">
                    <div class="bg-white/5 border border-dashed border-white/10 rounded-2xl p-12 text-center backdrop-blur-md">
                        <div class="w-16 h-16 bg-indigo-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">
                            No Courses Enrolled
                        </h3>
                        <p class="text-slate-400 mb-6 max-w-sm mx-auto">
                            You haven’t enrolled in any courses yet. Explore our catalog to start learning.
                        </p>
                        <a href="{{ route('courses.index') }}"
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:scale-105 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-300">
                            Browse Courses
                        </a>
                    </div>
                </div>

            @endforelse
        </div>
    </div>
</x-app-layout>
