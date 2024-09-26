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
        Schema::create('certificate_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('learner_id', 36);
            $table->string('course_id', 36);

            $table->string('approved_by', 36)->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('approval_sign_id', 36)->nullable();

            $table->string('authorized_by', 36);
            $table->timestamp('authorized_at')->nullable();
            $table->string('authorization_sign_id', 36)->nullable();
            $table->string('state');

            $table->foreign('learner_id')->references('id')->on('identities');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('approved_by')->references('id')->on('identities');
            $table->foreign('authorized_by')->references('id')->on('identities');
            $table->foreign('approval_sign_id')->references('id')->on('signatures');
            $table->foreign('authorization_sign_id')->references('id')->on('signatures');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_requests');
    }
};
