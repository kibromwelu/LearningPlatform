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
        Schema::create('refund_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('requested_by');
            $table->foreignUuid('subscription_id');
            $table->string('accountant_action')->nullable();
            $table->foreignUuid('accountant_id')->nullable();
            $table->timestamp('accountant_action_date')->nullable();
            $table->foreignUuid('ceo_id')->nullable();
            $table->timestamp('ceo_action_date')->nullable();
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
        Schema::dropIfExists('refund_requests');
    }
};
