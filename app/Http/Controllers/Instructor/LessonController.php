<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{

     //Show lessons of a course

    public function index(Course $course)
    {
        abort_unless($course->instructor_id === auth()->id(), 403);

        $lessons = $course->lessons()->orderBy('position')->get();

        return view('instructor.lessons.index', compact('course', 'lessons'));
    }

    /**
     * Show create form
     */
    public function create(Course $course)
    {
        abort_unless($course->instructor_id === auth()->id(), 403);

        return view('instructor.lessons.create', compact('course'));
    }

    /**
     * Store new lesson
     */
    public function store(Request $request, Course $course)
    {
        abort_unless($course->instructor_id === auth()->id(), 403);

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'position'    => 'required|integer|min:1',
            'video_url'   => 'nullable|url',
            'video'       => 'nullable|file|mimes:mp4,webm|max:51200',
            'attachment'  => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:20480',
        ]);

        //  Video file upload
        if ($request->hasFile('video')) {
            $data['video_path'] = $request->file('video')
                ->store('lessons/videos', 'public');
        }

        //  Attachment upload
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')
                ->store('lessons/files', 'public');
        }

        $course->lessons()->create($data);

        return redirect()
            ->route('instructor.courses.lessons.index', $course)
            ->with('success', 'Lesson created successfully.');
    }

    /**
     * Show edit form
     */
    public function edit(Lesson $lesson)
    {
        abort_unless($lesson->course->instructor_id === auth()->id(), 403);

        return view('instructor.lessons.edit', compact('lesson'));
    }

    /**
     * Update lesson
     */
    public function update(Request $request, Lesson $lesson)
    {
        abort_unless($lesson->course->instructor_id === auth()->id(), 403);

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'position'    => 'required|integer|min:1',
            'is_published'=> 'boolean',
            'video_url'   => 'nullable|url',
            'video'       => 'nullable|file|mimes:mp4,webm|max:51200',
            'attachment'  => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:20480',
        ]);

        //  Replace video if uploaded
        if ($request->hasFile('video')) {
            if ($lesson->video_path) {
                Storage::disk('public')->delete($lesson->video_path);
            }

            $data['video_path'] = $request->file('video')
                ->store('lessons/videos', 'public');
        }

        //  Replace attachment if uploaded
        if ($request->hasFile('attachment')) {
            if ($lesson->attachment) {
                Storage::disk('public')->delete($lesson->attachment);
            }

            $data['attachment'] = $request->file('attachment')
                ->store('lessons/files', 'public');
        }

        $lesson->update($data);

        return redirect()
            ->route('instructor.courses.lessons.index', $lesson->course)
            ->with('success', 'Lesson updated successfully.');
    }

    /**
     * Delete lesson
     */
    public function destroy(Lesson $lesson)
    {
        abort_unless($lesson->course->instructor_id === auth()->id(), 403);

        //  Delete files
        if ($lesson->video_path) {
            Storage::disk('public')->delete($lesson->video_path);
        }

        if ($lesson->attachment) {
            Storage::disk('public')->delete($lesson->attachment);
        }

        $lesson->delete();

        return back()->with('success', 'Lesson deleted successfully.');
    }
}
