<?php
use Illuminate\Support\Facades\Schema;

Schema::disableForeignKeyConstraints();
Schema::dropIfExists('lesson_completions');
Schema::dropIfExists('quiz_attempts');
Schema::dropIfExists('options');
Schema::dropIfExists('questions');
Schema::dropIfExists('quizzes');
Schema::enableForeignKeyConstraints();

echo "Tables dropped.";
