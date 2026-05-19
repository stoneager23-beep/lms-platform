<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add soft deletes to users table
        if (!Schema::hasColumn('users', 'deleted_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add 'unpublished' to courses.status enum
        DB::statement("ALTER TABLE courses MODIFY COLUMN status ENUM('draft', 'published', 'unpublished') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove soft deletes from users
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        // Revert courses.status enum (remove 'unpublished')
        DB::statement("ALTER TABLE courses MODIFY COLUMN status ENUM('draft', 'published') DEFAULT 'draft'");
    }
};
