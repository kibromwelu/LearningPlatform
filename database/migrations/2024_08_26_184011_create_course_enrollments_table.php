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
        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('learner_id');
            $table->foreignUuid('subscription_id');
            $table->foreignUuid('course_id');
            // $table->decimal('result');
            // $table->integer('total_topics');
            $table->integer('completed_topics')->default(0);
            $table->string('state')->default('pending'); // store state values at constants
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_enrollments');
    }
};
