<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'content',
        'position',
        'is_published',
        'video_path',
        'video_url',
        'attachment',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }

    public function completions()
    {
        return $this->hasMany(LessonCompletion::class);
    }

    // Helper: Check if user completed this lesson
    public function isCompletedBy($user)
    {
        if (!$user) return false;
        return $this->completions()->where('user_id', $user->id)->exists();
    }

    /**
     * Get the embed URL if the video_url is a YouTube link.
     */
    public function getEmbedUrlAttribute()
    {
        if (!$this->video_url) {
            return null;
        }

        // Simple regex for YouTube IDs
        // Supports: youtube.com/watch?v=ID, youtu.be/ID, youtube.com/embed/ID
        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';

        if (preg_match($pattern, $this->video_url, $match)) {
            return 'https://www.youtube.com/embed/' . $match[1];
        }

        return null;
    }
}
