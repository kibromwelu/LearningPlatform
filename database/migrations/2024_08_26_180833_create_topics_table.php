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
        
        Schema::create('topics', function (Blueprint $table) {

            $table->uuid('id')->primary(); 
            $table->foreignUuid('module_id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('minutes')->nullable();
            $table->integer('number_of_questions_to_ask');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
