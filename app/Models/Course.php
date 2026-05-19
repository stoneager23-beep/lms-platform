<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Enrollment;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'title',
        'slug',
        'description',
        'status',
        'thumbnail',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user')
            ->withTimestamps();
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('position');
    }


    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Calculate progress percentage for a specific user.
     */
    public function progress($user)
    {
        if (!$user) return 0;

        $totalLessons = $this->lessons()->where('is_published', true)->count();

        if ($totalLessons === 0) return 0; // Avoid division by zero

        $completedLessons = $this->lessons()
            ->where('is_published', true)
            ->whereHas('completions', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->count();

        return round(($completedLessons / $totalLessons) * 100);
    }
}
