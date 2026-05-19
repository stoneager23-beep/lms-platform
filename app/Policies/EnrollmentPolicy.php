<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Course;

class EnrollmentPolicy
{
    public function enroll(User $user, Course $course): bool
    {
        return $user->isStudent()
            && $course->status === 'published'
            && !$course->students()->where('user_id', $user->id)->exists();
    }
}
