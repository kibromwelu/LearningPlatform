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
        Schema::create('course_creator_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('resume_id');
            $table->longText('biography');
            $table->string('course_name');
            $table->longText('description');
            $table->foreignUuid('user_id');
            $table->foreignUuid('clo_id')->nullable();
            $table->string('clo_action')->nullable();
            $table->foreignUuid('clo_action_date')->nullable();
            $table->foreignUuid('ceo_id')->nullable();
            $table->foreignUuid('ceo_action_date')->nullable();
            $table->string('ceo_action')->nullable();
            $table->string('state')->nullable();
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_creator_requests');
    }
};
