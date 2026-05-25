<?php

// Place at: App\Http\Controllers\Instructor\InstructorDashboardController.php
// Update your route to:
// use App\Http\Controllers\Instructor\InstructorDashboardController;
// Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InstructorDashboardController extends Controller
{
    public function index()
    {
        $instructor = Auth::user();

        // All instructor courses with counts
        $courses = Course::withCount(['lessons', 'students'])
            ->where('instructor_id', $instructor->id)
            ->latest()
            ->get();

        $totalCourses     = $courses->count();
        $publishedCourses = $courses->where('status', 'published')->count();
        $draftCourses     = $courses->where('status', 'draft')->count();

        // Total unique students across all courses
        $totalStudents = DB::table('course_user')
            ->whereIn('course_id', $courses->pluck('id'))
            ->distinct('user_id')
            ->count('user_id');

        // Recent enrollments (last 7)
        $recentEnrollments = DB::table('course_user')
            ->join('users', 'course_user.user_id', '=', 'users.id')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->where('courses.instructor_id', $instructor->id)
            ->select('users.name as student_name', 'courses.title as course_title', 'course_user.created_at')
            ->orderByDesc('course_user.created_at')
            ->take(7)
            ->get();

        // Quiz performance per course
        $quizStats = Course::withCount(['lessons'])
            ->where('instructor_id', $instructor->id)
            ->where('status', 'published')
            ->with(['lessons.quiz.attempts'])
            ->get()
            ->map(function ($course) {
                $attempts = $course->lessons->flatMap(fn($l) => $l->quiz ? $l->quiz->attempts : collect());
                $total    = $attempts->count();
                $passed   = $attempts->where('is_passed', true)->count();
                $avgScore = $total > 0 ? round($attempts->avg('score')) : 0;
                return [
                    'title'     => $course->title,
                    'total'     => $total,
                    'passed'    => $passed,
                    'avg_score' => $avgScore,
                    'pass_rate' => $total > 0 ? round(($passed / $total) * 100) : 0,
                ];
            })
            ->filter(fn($s) => $s['total'] > 0);

        // Students per course (for chart)
        $chartLabels = $courses->map(fn($c) => strlen($c->title) > 18 ? substr($c->title, 0, 18).'…' : $c->title);
        $chartData   = $courses->pluck('students_count');

        // Total quiz attempts across all instructor courses
        $totalAttempts = QuizAttempt::whereHas('quiz.lesson.course', function ($q) use ($instructor) {
            $q->where('instructor_id', $instructor->id);
        })->count();

        return view('dashboards.instructor', compact(
            'courses',
            'totalCourses',
            'publishedCourses',
            'draftCourses',
            'totalStudents',
            'recentEnrollments',
            'quizStats',
            'chartLabels',
            'chartData',
            'totalAttempts'
        ));
    }
}