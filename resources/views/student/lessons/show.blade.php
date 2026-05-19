<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">

        {{-- 🔙 Back --}}
        <a href="{{ route('student.lessons.index') }}"
           class="inline-flex items-center text-sm font-bold text-slate-400
                  hover:text-indigo-400 transition mb-6">
            ← Back to Lessons
        </a>

        {{-- 📘 Lesson Card --}}
        <div class="bg-white/5 border border-white/10 rounded-2xl p-8 backdrop-blur-xl">

            {{-- Lesson Meta --}}
            <div class="mb-6">
                <p class="text-xs font-black uppercase tracking-widest text-indigo-400 mb-2">
                    {{ $lesson->course->title }}
                </p>

                <h1 class="text-3xl font-extrabold text-white leading-tight mb-4">
                    {{ $lesson->title }}
                </h1>

                {{-- 🎥 Video Player --}}
                @if ($lesson->video_path)
                    <div class="relative w-full aspect-video bg-black rounded-xl overflow-hidden shadow-2xl mb-6 border border-white/10">
                        <video controls class="w-full h-full">
                            <source src="{{ asset('storage/' . $lesson->video_path) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                @elseif ($lesson->embed_url)
                    <div class="relative w-full aspect-video bg-black rounded-xl overflow-hidden shadow-2xl mb-6 border border-white/10">
                        <iframe src="{{ $lesson->embed_url }}"
                                title="YouTube video player"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                                class="w-full h-full">
                        </iframe>
                    </div>
                @elseif ($lesson->video_url)
                    <div class="mb-6 p-4 bg-indigo-500/10 border border-indigo-500/20 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-indigo-200 font-medium">External Video Resource</span>
                        </div>
                        <a href="{{ $lesson->video_url }}" target="_blank"
                           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-lg transition shadow-lg shadow-indigo-500/20">
                            Watch Video &rarr;
                        </a>
                    </div>
                @endif

                {{-- 📎 Attachment --}}
                @if ($lesson->attachment)
                    <div class="mb-2">
                        <a href="{{ asset('storage/' . $lesson->attachment) }}" target="_blank" download
                           class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-sm font-medium transition border border-white/5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Attachment
                        </a>
                    </div>
                @endif
            </div>

            {{-- Divider --}}
            <div class="h-px bg-white/10 my-6"></div>

            <div class="prose prose-invert max-w-none text-slate-300 leading-relaxed">
                {!! nl2br(e($lesson->content)) !!}
            </div>

            {{-- Divider --}}
            <div class="h-px bg-white/10 my-8"></div>

            {{-- ✅ COMPLETION & QUIZ SECTION --}}
            <div class="bg-black/20 rounded-xl p-6 border border-white/5">
                
                @if($isCompleted)
                    <div class="flex items-center gap-4 text-green-400">
                        <div class="w-10 h-10 rounded-full bg-green-500/10 flex items-center justify-center border border-green-500/20">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Lesson Completed</h3>
                            <p class="text-sm text-green-400/60">Great job! You have completed this lesson.</p>
                        </div>
                    </div>
                @endif

                {{-- QUIZ LOGIC --}}
                @if($lesson->quiz && $lesson->quiz->questions->count() > 0)
                    
                    <div class="mt-6">
                        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            Lesson Quiz: {{ $lesson->quiz->title ?? 'Untitled Quiz' }}
                        </h3>

                        @if($previousAttempt)
                            <div class="p-4 rounded-xl border {{ $previousAttempt->is_passed ? 'bg-green-500/10 border-green-500/20' : 'bg-red-500/10 border-red-500/20' }} mb-6">
                                <p class="font-bold text-lg {{ $previousAttempt->is_passed ? 'text-green-400' : 'text-red-400' }}">
                                    Result: {{ $previousAttempt->is_passed ? 'Passed' : 'Failed' }} 
                                    ({{ $previousAttempt->score }}%)
                                </p>
                                <p class="text-sm opacity-70 mt-1">Pass Mark: {{ $lesson->quiz->pass_mark }}%</p>
                            </div>

                            @if(!$previousAttempt->is_passed)
                                <div class="mt-4">
                                     <button onclick="document.getElementById('quiz-form').classList.remove('hidden'); this.style.display='none'"
                                            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition">
                                        Retake Quiz
                                     </button>
                                </div>
                            @endif

                        @else
                            <p class="text-slate-400 mb-6">Complete this quiz to enable "Lesson Completed" status.</p>
                        @endif

                        {{-- QUIZ FORM --}}
                        <form id="quiz-form" action="{{ route('student.lessons.quiz.submit', $lesson) }}" method="POST"
                              class="{{ ($previousAttempt && $previousAttempt->is_passed) ? 'hidden' : ($previousAttempt ? 'hidden' : '') }} space-y-8 mt-4">
                            @csrf
                            
                            @foreach($lesson->quiz->questions as $question)
                                <div class="bg-white/5 p-5 rounded-xl border border-white/5">
                                    <p class="font-bold text-white mb-4">{{ $loop->iteration }}. {{ $question->question_text }}</p>
                                    <div class="space-y-3">
                                        @foreach($question->options as $option)
                                            <label class="flex items-center gap-3 cursor-pointer group">
                                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" required
                                                       class="w-5 h-5 text-indigo-600 bg-slate-800 border-slate-600 focus:ring-indigo-500 group-hover:border-indigo-500 transition">
                                                <span class="text-slate-300 group-hover:text-white transition">{{ $option->option_text }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/20 transition transform active:scale-95">
                                Submit Quiz
                            </button>
                        </form>
                    </div>

                @elseif(!$isCompleted)
                    {{-- MANUAL COMPLETE --}}
                    <div class="flex flex-col items-center justify-center py-4">
                         <form action="{{ route('student.lessons.complete', $lesson) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/20 transition transform hover:-translate-y-1 active:scale-95">
                                Mark as Complete
                            </button>
                        </form>
                        <p class="text-xs text-slate-500 mt-3">Click to track your progress</p>
                    </div>
                @endif
                
            </div>

        </div>

    </div>
</x-app-layout>
