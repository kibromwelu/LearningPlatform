<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assessment_attempts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('learner_id');
            $table->string('course_id');
            $table->string('topic_id');
            $table->decimal('score');
            $table->foreign('learner_id')->references('id')->on('learners');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('topic_id')->references('id')->on('topics');
            $table->string('state')->default('pending');
            // $table->foreign('topic_id')->references('id')->on('topics');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_attempts');
    }
};
