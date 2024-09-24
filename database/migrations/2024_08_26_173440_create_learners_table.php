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
        
        if (!Schema::hasTable('learners')) {
            Schema::create('learners', function (Blueprint $table) {

                $table->uuid('id')->primary();
                $table->foreignUuid('identity_id'); 
                $table->timestamps();
            });
           
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learners');
    }
};
