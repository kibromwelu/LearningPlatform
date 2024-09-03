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
                $table->unsignedBigInteger('identity_id'); // Foreign key column
    
                // Define the foreign key relationship
                $table->foreign('identity_id')
                      ->references('id')
                      ->on('identities');
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
