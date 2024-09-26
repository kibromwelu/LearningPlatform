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
        Schema::create('exam_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('learner_id');
            $table->foreignUuid('course_id');
            $table->foreignUuid('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignUuid('authorized_by')->nullable();
            $table->timestamp('authorized_at')->nullable();
            $table->string('state')->default('new');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_requests');
    }
};
