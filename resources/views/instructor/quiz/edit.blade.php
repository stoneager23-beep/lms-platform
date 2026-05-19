<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-white leading-tight">
                    Manage Quiz
                </h2>
                <p class="text-slate-400 text-sm mt-1">
                    Lesson: <span class="text-indigo-400 font-bold">{{ $lesson->title }}</span>
                </p>
            </div>
            <a href="{{ route('instructor.lessons.edit', $lesson) }}" class="text-slate-400 hover:text-white transition text-sm font-bold">
                &larr; Back to Lesson
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 py-8 space-y-8">

        {{-- 1. Quiz Settings --}}
        <div class="bg-[#1e293b] border border-slate-800 rounded-2xl p-6">
            <h3 class="text-lg font-bold text-white mb-4">Quiz Settings</h3>
            <form action="{{ route('instructor.lessons.quiz.update', $lesson) }}" method="POST" class="flex gap-4 items-end">
                @csrf
                @method('PUT')

                <div class="flex-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Pass Mark (%)</label>
                    <input type="number" name="pass_mark" value="{{ old('pass_mark', $quiz->pass_mark ?? 50) }}"
                           min="0" max="100"
                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="flex-[2]">
                     <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Quiz Title (Optional)</label>
                     <input type="text" name="title" value="{{ old('title', $quiz->title ?? $lesson->title . ' Quiz') }}"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition">
                    Save Settings
                </button>
            </form>
        </div>

        @if($quiz)
            {{-- 2. Add Question --}}
            <div class="bg-[#1e293b] border border-slate-800 rounded-2xl p-6">
                <h3 class="text-lg font-bold text-white mb-4">Add New Question</h3>
                <form action="{{ route('instructor.quizzes.questions.store', $quiz) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Question Text</label>
                        <textarea name="question_text" rows="2" required
                                  class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-white focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="e.g. What is the capital of France?"></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Options</label>

                        @foreach(range(0, 3) as $i)
                            <div class="flex items-center gap-3">
                                <input type="radio" name="correct_option" value="{{ $i }}" {{ $loop->first ? 'checked' : '' }}
                                       class="w-4 h-4 text-indigo-600 bg-slate-900 border-slate-700 focus:ring-indigo-500">
                                <input type="text" name="options[]" required
                                       class="flex-1 bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm text-white"
                                       placeholder="Option {{ $i + 1 }}">
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl border border-slate-700 transition">
                            Add Question
                        </button>
                    </div>
                </form>
            </div>

            {{-- 3. Existing Questions --}}
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-white px-1">Questions ({{ $quiz->questions->count() }})</h3>

                @forelse($quiz->questions as $question)
                    <div class="bg-[#1e293b] border border-slate-800 rounded-xl p-5 relative group">
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition">
                            <form action="{{ route('instructor.questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Delete this question?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>

                        <h4 class="text-white font-bold mb-3">{{ $loop->iteration }}. {{ $question->question_text }}</h4>
                        <ul class="space-y-1 text-sm">
                            @foreach($question->options as $option)
                                <li class="flex items-center gap-2 {{ $option->is_correct ? 'text-green-400 font-bold' : 'text-slate-400' }}">
                                    @if($option->is_correct)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <span class="w-4 h-4 inline-block"></span>
                                    @endif
                                    {{ $option->option_text }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @empty
                    <div class="text-center p-8 bg-slate-800/50 rounded-xl border border-dashed border-slate-700 text-slate-500">
                        No questions added yet.
                    </div>
                @endforelse
            </div>
        @else
            <div class="p-4 bg-indigo-500/10 border border-indigo-500/20 rounded-xl text-indigo-300 text-center">
                Please save the quiz settings above to start adding questions.
            </div>
        @endif

    </div>
</x-app-layout>
