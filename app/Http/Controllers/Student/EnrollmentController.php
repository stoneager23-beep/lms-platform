<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonCompletion;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Show all available courses for students
     */
    public function index()
    {
        $courses = auth()->user()
            ->enrolledCourses()
            ->with('instructor')
            ->withPivot('enrolled_at')
            ->get();

        return view('student.courses.index', compact('courses'));
    }

    /**
     * Enroll authenticated student in a course
     */
    public function store(Course $course)
    {
        $user = auth()->user();

        // Authorization check
        $this->authorize('enroll', $course);

        // Prevent duplicate enrollment
        if ($course->students()->where('user_id', $user->id)->exists()) {
            return back()->with('info', 'You are already enrolled.');
        }

        // Enroll student (pivot table)
        $course->students()->attach($user->id);

        return redirect()
            ->route('student.courses.index')
            ->with('success', 'Enrollment successful!');
    }

    /**
     * Unenroll student from a course
     */
    public function destroy(Course $course)
    {
        $user = auth()->user();

        // Check if actually enrolled
        if (!$course->students()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'You are not enrolled in this course.');
        }

        DB::transaction(function () use ($course, $user) {
            // 1. Remove from pivot table (course_user)
            $course->students()->detach($user->id);

            // 2. Delete enrollment record if exists
            Enrollment::where('course_id', $course->id)
                ->where('user_id', $user->id)
                ->delete();

            // 3. Delete lesson completions for this course
            $lessonIds = $course->lessons()->pluck('id');
            if ($lessonIds->isNotEmpty()) {
                LessonCompletion::where('user_id', $user->id)
                    ->whereIn('lesson_id', $lessonIds)
                    ->delete();
            }
        });

        return redirect()
            ->route('student.courses.index')
            ->with('success', "You have been unenrolled from \"{$course->title}\".");
    }
}
