<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\constants;
use App\Constants\Constants as ConstantsConstants;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('identity_id');
            $table->integer('max_allowed_learners')->default(Constants::DEFULT_MAX_ALLOWED_LEARNERS);
            $table->integer('added_learners')->default(Constants::DEFAULT_ADDED_LEARNERS);
            $table->integer('max_allowed_courses')->default(Constants::DEFAULT_MAX_ALLOWED_COURSES);
            $table->integer('enrolled_courses')->default(Constants::DEFAULT_ENROLLED_COURSES);
            $table->foreignUuid('subscription_id')->nullable();
            $table->string('package');
            $table->string('mode');
            $table->decimal('payment', 8, 2);
            $table->string('currency');
            $table->string('ceo_id', 36)->nullable();
            $table->string('ceo_action')->nullable();
            $table->string('ceo_action_date')->nullable();
            $table->string('ceo_sign_id')->nullable();
            $table->string('accountant_id', 36)->nullable();
            $table->string('accountant_action')->nullable();
            $table->string('accountant_action_date')->nullable();
            $table->string('accountant_sign_id')->nullable();
            $table->foreign('ceo_id')->references('id')->on('identities');
            $table->foreign('ceo_sign_id')->references('id')->on('signatures');
            $table->foreign('accountant_id')->references('id')->on('identities');
            $table->foreign('accountant_sign_id')->references('id')->on('signatures');
            $table->string('remark')->nullable();
            $table->string('state')->default(Constants::DEFAULT_STATE);
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
