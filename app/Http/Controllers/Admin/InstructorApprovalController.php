<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstructorApprovalController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        $stats = [
            'totalStudents'    => User::where('role', 'student')->count(),
            'totalInstructors' => User::where('role', 'instructor')->count(),
            'activeCourses'    => Course::count(),
            'pendingCount'     => User::where('role', 'instructor')->where('is_approved', false)->count(),
        ];

        $pendingInstructors = User::where('role', 'instructor')
            ->where('is_approved', false)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingInstructors'));
    }

    /**
     * Full Pending List
     */
    public function index()
    {
        $pendingInstructors = User::where('role', 'instructor')
            ->where('is_approved', false)
            ->latest()
            ->get();

        return view('admin.instructors.index', compact('pendingInstructors'));
    }

    /**
     * List all active (approved) instructors
     */
    public function activeInstructors()
    {
        $activeInstructors = User::where('role', 'instructor')
            ->where('is_approved', true)
            ->withCount('courses')
            ->latest()
            ->get();

        return view('admin.instructors.active', compact('activeInstructors'));
    }

    /**
     * Approve an instructor
     */
    public function approve(User $user)
    {
        if ($user->role !== 'instructor' || $user->is_approved) {
            return back()->with('error', 'Invalid request or instructor already approved.');
        }

        $user->update(['is_approved' => true]);

        return to_route('admin.instructors.index')->with('success', "Instructor {$user->name} has been approved successfully!");
    }

    /**
     * Reject/Delete a pending instructor
     */
    public function reject(User $user)
    {
        abort_unless($user->role === 'instructor', 403);

        $user->delete();

        return back()->with('info', 'Instructor application has been rejected and removed.');
    }

    /**
     * Terminate an active instructor (cascade: enrollments → courses → user)
     */
    public function terminate(User $user)
    {
        abort_unless($user->role === 'instructor', 403);

        DB::transaction(function () use ($user) {
            // 1. Get all course IDs for this instructor
            $courseIds = $user->courses()->pluck('id');

            // 2. Hard-delete all enrollments in those courses
            if ($courseIds->isNotEmpty()) {
                Enrollment::whereIn('course_id', $courseIds)->delete();
            }

            // 3. Unpublish all instructor's courses
            $user->courses()->update(['status' => 'unpublished']);

            // 4. Soft-delete the instructor
            $user->delete();
        });

        return back()->with('success', "Instructor {$user->name} has been terminated. All courses unpublished and enrollments removed.");
    }
}
