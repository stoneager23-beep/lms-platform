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
                'course.lessons' => function ($query) {
                    $query->where('is_published', true)->orderBy('position');
                },
                'quiz.questions', // Load quiz with questions to check if quiz has content
                'completions' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }
            ])
            ->orderBy('course_id')
            ->orderBy('position')
            ->get();

        // Pre-compute accessibility for each lesson
        $completedLessonIds = $user->lessonCompletions()->pluck('lesson_id')->toArray();

        foreach ($lessons as $lesson) {
            $lesson->is_accessible = $this->isLessonAccessible($lesson, $user, $completedLessonIds);
            $lesson->is_completed_by_user = in_array($lesson->id, $completedLessonIds);
        }

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

        // 🔐 Sequential gating: check if previous lessons are completed
        if ($lesson->course->is_sequential) {
            $previousLessons = $lesson->course->lessons()
                ->where('is_published', true)
                ->where('position', '<', $lesson->position)
                ->get();

            foreach ($previousLessons as $prev) {
                if (!$prev->isCompletedBy($user)) {
                    return redirect()->route('student.lessons.show', $prev)
                        ->with('error', 'Please complete the previous lessons first before moving ahead.');
                }
            }
        }

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

        // Find the next lesson for the "Next Lesson" button
        $nextLesson = $lesson->course->lessons()
            ->where('is_published', true)
            ->where('position', '>', $lesson->position)
            ->orderBy('position')
            ->first();

        return view('student.lessons.show', compact('lesson', 'isCompleted', 'previousAttempt', 'nextLesson'));
    }

    /**
     * Mark lesson as complete (if no quiz)
     */
    public function markComplete(Lesson $lesson)
    {
        $user = auth()->user();

        // 🔐 Block manual completion if lesson has a quiz with questions
        if ($lesson->quiz && $lesson->quiz->questions()->count() > 0) {
            return back()->with('error', 'You must pass the quiz to complete this lesson.');
        }

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

    /**
     * Check if a lesson is accessible to the user (based on sequential mode)
     */
    private function isLessonAccessible(Lesson $lesson, $user, array $completedLessonIds): bool
    {
        // If the course is not sequential, all lessons are accessible
        if (!$lesson->course->is_sequential) {
            return true;
        }

        // Get all published lessons for this course, ordered by position
        $courseLessons = $lesson->course->lessons
            ->where('is_published', true)
            ->sortBy('position')
            ->values();

        // First lesson is always accessible
        if ($courseLessons->first()?->id === $lesson->id) {
            return true;
        }

        // Check all previous lessons are completed
        foreach ($courseLessons as $courseLesson) {
            if ($courseLesson->position >= $lesson->position) {
                break;
            }
            if (!in_array($courseLesson->id, $completedLessonIds)) {
                return false;
            }
        }

        return true;
    }
}
