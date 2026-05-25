<?php



namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\QuizAttempt;
use App\Models\LessonCompletion;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();

    // ✅ Use enrolledCourses() — reads from course_user pivot
    $enrolledCourses = $user->enrolledCourses()
        ->with(['lessons' => fn($q) => $q->where('is_published', true),
                'lessons.completions' => fn($q) => $q->where('user_id', $user->id),
                'instructor'])
        ->latest('course_user.created_at')
        ->get()
        ->map(function ($course) use ($user) {
            $course->progress_percent = $course->progress($user);
            return $course;
        });

    $enrolledCourseIds = $enrolledCourses->pluck('id');

    // Recent lesson completions (last 5)
    $recentActivity = LessonCompletion::with(['lesson.course'])
        ->where('user_id', $user->id)
        ->latest()
        ->take(5)
        ->get();

    // Upcoming quizzes: not yet attempted
    $attemptedQuizIds = QuizAttempt::where('user_id', $user->id)->pluck('quiz_id');

    $upcomingQuizzes = \App\Models\Quiz::with(['lesson.course'])
        ->whereHas('lesson', function ($q) use ($enrolledCourseIds) {
            $q->where('is_published', true)
              ->whereIn('course_id', $enrolledCourseIds);
        })
        ->whereNotIn('id', $attemptedQuizIds)
        ->take(5)
        ->get();

    // Stats
    $totalCourses     = $enrolledCourses->count();
    $completedCourses = $enrolledCourses->filter(fn($c) => $c->progress_percent === 100)->count();
    $avgProgress      = $totalCourses > 0 ? round($enrolledCourses->avg('progress_percent')) : 0;
    $quizzesPassed    = QuizAttempt::where('user_id', $user->id)->where('is_passed', true)->count();

    return view('dashboards.student', compact(
        'enrolledCourses',
        'recentActivity',
        'upcomingQuizzes',
        'totalCourses',
        'completedCourses',
        'avgProgress',
        'quizzesPassed'
    ));
}
}