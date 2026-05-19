<?php

namespace App\Policies;
use App\Models\User;
use App\Models\Lesson;

class LessonPolicy
{
    public function manage(User $user, Lesson $lesson): bool
    {
        return $user->isInstructor()
            && $user->is_approved
            && $lesson->course->instructor_id === $user->id;
    }

    public function view(User $user, Lesson $lesson): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isInstructor()) {
            return $lesson->course->instructor_id === $user->id;
        }

        if ($user->isStudent()) {
            return $lesson->is_published
                && $lesson->course->students()
                    ->where('user_id', $user->id)
                    ->exists();
        }

        return false;
    }
}

