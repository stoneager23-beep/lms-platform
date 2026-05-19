<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function update(User $user, Course $course): bool
    {
        return $user->isInstructor()
            && $user->is_approved
            && $course->instructor_id === $user->id;
    }

    public function delete(User $user, Course $course): bool
    {
        return $this->update($user, $course);
    }

    /**
     * Student enrollment permission
     */
    public function enroll(User $user, Course $course): bool
    {
        // Only students can enroll
        if (!$user->isStudent()) {
            return false;
        }

        // Course must be published
        if ($course->status !== 'published') {
            return false;
        }

        // Student must NOT already be enrolled
        return !$course->students()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function forceDelete(User $user, Course $course): bool
    {
        return false;
    }
}
