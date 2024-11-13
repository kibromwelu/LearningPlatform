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
        Schema::create('publish_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('course_id');
            $table->foreignUuid('requested_by');
            $table->string('request_action');
            $table->foreignUuid('ceo_id')->nullable();
            $table->string('ceo_action')->nullable();
            $table->timestamp('ceo_action_date')->nullable();
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('publish_requests');
    }
};
