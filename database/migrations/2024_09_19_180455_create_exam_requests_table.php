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
            $table->foreignUuid('enrollment_id');
            $table->foreignUuid('clo_id')->nullable();
            $table->string('clo_action')->nullable();
            $table->timestamp('clo_action_date')->nullable();
            $table->foreignUuid('ceo_id')->nullable();
            $table->string('ceo_action')->nullable();
            $table->timestamp('ceo_action_date')->nullable();
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
