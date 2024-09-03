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
        Schema::create('answer_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('assessment_attempt_id');
            $table->string('question_id');
            $table->string('learner_answer');
            $table->boolean('is_correct')->nullable()->default(false);
            $table->foreign('assessment_attempt_id')->references('id')->on('assessment_attempts');
            $table->foreign('question_id')->references('id')->on('questions');
            $table->string('state')->default('in_progress');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answer_logs');
    }
};
