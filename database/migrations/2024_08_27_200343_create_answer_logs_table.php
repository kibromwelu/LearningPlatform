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
            $table->foreignUuid('assessment_attempt_id');
            $table->foreignUuid('question_id');
            $table->foreignUuid('learner_answer');
            $table->boolean('is_correct')->nullable()->default(false);
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
