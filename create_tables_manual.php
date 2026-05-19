<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

echo "Starting Manual Table Creation...\n";

// 1. Quizzes
if (!Schema::hasTable('quizzes')) {
    echo "Creating quizzes...\n";
    Schema::create('quizzes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
        $table->string('title')->nullable();
        $table->integer('pass_mark')->default(50);
        $table->timestamps();
    });
} else {
    echo "Quizzes table already exists.\n";
}

// 2. Questions
if (!Schema::hasTable('questions')) {
    echo "Creating questions...\n";
    Schema::create('questions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
        $table->text('question_text');
        $table->string('type')->default('multiple_choice');
        $table->timestamps();
    });
} else {
    echo "Questions table already exists.\n";
}

// 3. Options
if (!Schema::hasTable('options')) {
    echo "Creating options...\n";
    Schema::create('options', function (Blueprint $table) {
        $table->id();
        $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
        $table->string('option_text');
        $table->boolean('is_correct')->default(false);
        $table->timestamps();
    });
} else {
    echo "Options table already exists.\n";
}

// 4. Quiz Attempts
if (!Schema::hasTable('quiz_attempts')) {
    echo "Creating quiz_attempts...\n";
    Schema::create('quiz_attempts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->integer('score');
        $table->boolean('is_passed');
        $table->timestamps();
    });
} else {
    echo "Quiz attempts table already exists.\n";
}

// 5. Lesson Completions
if (!Schema::hasTable('lesson_completions')) {
    echo "Creating lesson_completions...\n";
    Schema::create('lesson_completions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->timestamp('completed_at')->useCurrent();
        $table->timestamps();
        $table->unique(['lesson_id', 'user_id']);
    });
} else {
    echo "Lesson completions table already exists.\n";
}

echo "Manual Table Creation Completed.\n";
