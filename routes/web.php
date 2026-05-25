<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\InstructorApprovalController;
use App\Http\Controllers\Instructor\CourseController;
use App\Http\Controllers\Instructor\LessonController as InstructorLessonController;
use App\Http\Controllers\Student\EnrollmentController;
use App\Http\Controllers\Student\LessonController as StudentLessonController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Instructor\InstructorDashboardController;



/*
|--------------------------------------------------------------------------
| Home Redirect
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    return redirect()->route(
        auth()->user()->isAdmin()
            ? 'admin.dashboard'
            : (auth()->user()->isInstructor()
            ? 'instructor.dashboard'
            : 'student.dashboard')
    );
});

/*
|--------------------------------------------------------------------------
| Authenticated (Common)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [InstructorApprovalController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/instructors', [InstructorApprovalController::class, 'index'])
            ->name('instructors.index');

        Route::get('/instructors/active', [InstructorApprovalController::class, 'activeInstructors'])
            ->name('instructors.active');

        Route::post('/instructors/{user}/approve', [InstructorApprovalController::class, 'approve'])
            ->name('instructors.approve');

        Route::post('/instructors/{user}/reject', [InstructorApprovalController::class, 'reject'])
            ->name('instructors.reject');

        Route::delete('/instructors/{user}/terminate', [InstructorApprovalController::class, 'terminate'])
            ->name('instructors.terminate');
    });

/*
|--------------------------------------------------------------------------
| Instructor Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'instructor.approved'])
    ->prefix('instructor')
    ->name('instructor.')
    ->group(function () {

        Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');
        // Courses CRUD
        Route::resource('courses', CourseController::class);

        // Lessons under course
        Route::get('courses/{course}/lessons', [InstructorLessonController::class, 'index'])
            ->name('courses.lessons.index');

        Route::get('courses/{course}/lessons/create', [InstructorLessonController::class, 'create'])
            ->name('courses.lessons.create');

        Route::post('courses/{course}/lessons', [InstructorLessonController::class, 'store'])
            ->name('courses.lessons.store');

        Route::get('lessons/{lesson}/edit', [InstructorLessonController::class, 'edit'])
            ->name('lessons.edit');

        Route::put('lessons/{lesson}', [InstructorLessonController::class, 'update'])
            ->name('lessons.update');

        Route::delete('lessons/{lesson}', [InstructorLessonController::class, 'destroy'])
            ->name('lessons.destroy');

        // Q U I Z Z E S
        Route::controller(\App\Http\Controllers\Instructor\InstructorQuizController::class)->group(function () {
            Route::get('lessons/{lesson}/quiz', 'edit')->name('lessons.quiz.edit');
            Route::put('lessons/{lesson}/quiz', 'update')->name('lessons.quiz.update');
            Route::post('quizzes/{quiz}/questions', 'storeQuestion')->name('quizzes.questions.store');
            Route::delete('questions/{question}', 'destroyQuestion')->name('questions.destroy');
        });
    });

/*
|--------------------------------------------------------------------------
| Instructor Pending
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/instructor/pending', function () {
    return view('instructor.pending');
})->name('instructor.pending');

/*
|--------------------------------------------------------------------------
| Student Routes (FINAL WORKFLOW)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        // 🔹 COURSES (My Learning)
        Route::get('/my-courses', [EnrollmentController::class, 'index'])
            ->name('courses.index');

        Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])
            ->name('courses.enroll');

        Route::delete('/courses/{course}/unenroll', [EnrollmentController::class, 'destroy'])
            ->name('courses.unenroll');

        // 🔹 LESSONS (Direct access)
        Route::get('/lessons', [StudentLessonController::class, 'index'])
            ->name('lessons.index');

        Route::get('/lessons/{lesson}', [StudentLessonController::class, 'show'])
            ->name('lessons.show');

        Route::post('/lessons/{lesson}/complete', [StudentLessonController::class, 'markComplete'])
            ->name('lessons.complete');

        Route::post('/lessons/{lesson}/quiz', [StudentLessonController::class, 'submitQuiz'])
            ->name('lessons.quiz.submit');
    });

/*
|--------------------------------------------------------------------------
| Course Catalog (Public/Student)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/courses', [\App\Http\Controllers\CourseController::class, 'index'])
        ->name('courses.index'); // Public Catalog

    Route::get('/courses/{course}', [\App\Http\Controllers\CourseController::class, 'show'])
        ->name('courses.show'); // Course Details
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
