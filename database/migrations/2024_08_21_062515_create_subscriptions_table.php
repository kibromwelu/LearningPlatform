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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('identity_id');
            $table->integer('max_allowed_learners')->default(1);
            $table->integer('added_learners')->default(1);
            $table->integer('max_allowed_courses')->default(1);
            $table->integer('enrolled_courses')->default(0);
            $table->foreignId('subscription_id')->nullable();
            $table->string('package');
            $table->string('mode');
            $table->decimal('payment', 8, 2);
            $table->string('currency');
            $table->string('state')->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
