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
        Schema::create('addresses', function (Blueprint $table) {
           
            $table->uuid('id')->primary();
            $table->foreignId('identity_id');
            $table->string('residence_id')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('pobox')->nullable();
            $table->string('house_number')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('specific_location')->nullable();
            $table->string('tabia')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
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
        Schema::dropIfExists('addresses');
    }
};
