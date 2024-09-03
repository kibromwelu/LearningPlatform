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

        Schema::create('products', function (Blueprint $table) {
            // $table->id();
            $table->uuid('id')->primary();
            $table->string('user_id'); // Foreign key column

            // Define the foreign key relationship
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
            $table->string('name');
            $table->decimal('price', 8, 2)->nullable()->change();
            $table->text('description');
            $table->timestamps();
        });
        //
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('products');
    }
};
