<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;

class InstructorQuizController extends Controller
{
    /**
     * Show the quiz management page for a lesson.
     */
    public function edit(Lesson $lesson)
    {
        // Ensure user owns the course
        abort_unless($lesson->course->instructor_id === auth()->id(), 403);

        $lesson->load('quiz.questions.options');
        $quiz = $lesson->quiz;

        return view('instructor.quiz.edit', compact('lesson', 'quiz'));
    }

    /**
     * Create or update the quiz details.
     */
    public function update(Request $request, Lesson $lesson)
    {
        abort_unless($lesson->course->instructor_id === auth()->id(), 403);

        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'pass_mark' => 'required|integer|min:0|max:100',
        ]);

        $quiz = $lesson->quiz ?? new Quiz(['lesson_id' => $lesson->id]);
        $quiz->fill($data);
        $quiz->save();

        return back()->with('success', 'Quiz settings updated.');
    }

    /**
     * Store a new question for the quiz.
     */
    public function storeQuestion(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->lesson->course->instructor_id === auth()->id(), 403);

        $data = $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|min:0', // Index of correct option
        ]);

        // Create Question
        $question = $quiz->questions()->create([
            'question_text' => $data['question_text'],
            'type' => 'multiple_choice',
        ]);

        // Create Options
        foreach ($data['options'] as $index => $optionText) {
            $question->options()->create([
                'option_text' => $optionText,
                'is_correct' => ($index == $data['correct_option']),
            ]);
        }

        return back()->with('success', 'Question added successfully.');
    }

    /**
     * Delete a question.
     */
    public function destroyQuestion(Question $question)
    {
        abort_unless($question->quiz->lesson->course->instructor_id === auth()->id(), 403);

        $question->delete();

        return back()->with('success', 'Question deleted.');
    }
}
