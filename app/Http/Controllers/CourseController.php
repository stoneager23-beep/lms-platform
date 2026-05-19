<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Show the public course catalog.
     */
    public function index()
    {
        // Fetch all published courses (assuming you might have a 'is_published' flag later,
        // for now just all, or filter by instructor approval if needed)
        // Ideally, we only show courses from approved instructors.
        $courses = Course::with('instructor')
            ->where('status', 'published')
            ->latest()
            ->get();

        return view('courses.index', compact('courses'));
    }

    /**
     * Show the course details page.
     */
    public function show(Course $course)
    {
        // Load lessons and instructor info
        $course->load(['lessons' => function ($query) {
            $query->where('is_published', true)->orderBy('position');
        }, 'instructor']);

        // Check if current user is enrolled
        $isEnrolled = false;
        if (auth()->check()) {
            $isEnrolled = $course->students()->where('users.id', auth()->id())->exists();
        }

        return view('courses.show', compact('course', 'isEnrolled'));
    }
}
