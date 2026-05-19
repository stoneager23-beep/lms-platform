<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;

class LessonController extends Controller
{
    /**
     * Show all lessons of enrolled courses (published only)
     */
    public function index()
    {
        $user = auth()->user();

        $lessons = Lesson::where('is_published', true)
            ->whereHas('course.students', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->with([
                'course.instructor',
                'completions' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }
            ])
            ->orderBy('course_id')
            ->orderBy('position')
            ->get();

        return view('student.lessons.index', compact('lessons'));
    }


    /**
     * Show single lesson (only if enrolled + published)
     */
    public function show(Lesson $lesson)
    {
        $user = auth()->user();

        // 🔐 Check enrollment
        $isEnrolled = $lesson->course
            ->students()
            ->where('users.id', $user->id)
            ->exists();

        abort_unless($isEnrolled, 403);

        // 🔐 Check published
        abort_unless($lesson->is_published, 403);

        // Load quiz and questions if they exist
        $lesson->load(['quiz.questions.options', 'completions']);

        $isCompleted = $lesson->isCompletedBy($user);
        $previousAttempt = null;

        if ($lesson->quiz) {
            $previousAttempt = $lesson->quiz->attempts()
                ->where('user_id', $user->id)
                ->latest()
                ->first();
        }

        return view('student.lessons.show', compact('lesson', 'isCompleted', 'previousAttempt'));
    }

    /**
     * Mark lesson as complete (if no quiz)
     */
    public function markComplete(Lesson $lesson)
    {
        $user = auth()->user();

        // Check if already completed
        if (!$lesson->isCompletedBy($user)) {
             $lesson->completions()->create(['user_id' => $user->id]);
        }

        return back()->with('success', 'Lesson marked as complete!');
    }

    /**
     * Submit quiz attempt
     */
    public function submitQuiz(\Illuminate\Http\Request $request, Lesson $lesson)
    {
        $user = auth()->user();
        $quiz = $lesson->quiz;

        if (!$quiz) {
            return back()->with('error', 'No quiz found.');
        }

        $data = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required',
        ]);

        $score = 0;
        $totalQuestions = $quiz->questions()->count();
        $correctAnswers = 0;

        foreach ($quiz->questions as $question) {
            $userAnswerId = $data['answers'][$question->id] ?? null;
            $correctOption = $question->options()->where('is_correct', true)->first();

            if ($correctOption && $userAnswerId == $correctOption->id) {
                $correctAnswers++;
            }
        }

        $percentage = ($totalQuestions > 0) ? ($correctAnswers / $totalQuestions) * 100 : 0;
        $passed = $percentage >= $quiz->pass_mark;

        // Record attempt
        $quiz->attempts()->create([
            'user_id' => $user->id,
            'score' => $percentage,
            'is_passed' => $passed,
        ]);

        // Mark lesson complete if passed
        if ($passed && !$lesson->isCompletedBy($user)) {
            $lesson->completions()->create(['user_id' => $user->id]);
        }

        return back()->with('success', $passed ? 'You passed the quiz!' : 'You failed the quiz. Try again.');
    }
}
