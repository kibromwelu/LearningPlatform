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
        Schema::create('profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('identity_id');
            $table->integer('age')->nullable();
            $table->string('biography')->nullable();
            $table->string('category')->nullable();
            $table->string('religion')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('education_level')->nullable();
            $table->string('mother_tongue_language')->nullable();
            
            $table->string('income_source')->nullable();
            $table->string('occupation')->nullable();
            $table->string('employment_term')->nullable();
            $table->string('organization')->nullable();
            $table->string('household_size')->nullable();
            $table->integer('height')->nullable();
            $table->integer('weight')->nullable();
            $table->string('avatar')->nullable();
            $table->string('cover')->nullable();
            $table->string('purpose')->nullable();
            $table->string('file_number')->nullable()->change();
            $table->string('state')->default('null');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
