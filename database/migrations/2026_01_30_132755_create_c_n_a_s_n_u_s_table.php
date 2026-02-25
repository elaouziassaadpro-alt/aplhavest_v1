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
        Schema::create('c_n_a_s_n_u_s', function (Blueprint $table) {
            $table->id(); // id primary key
            $table->string('dataID', 500)->nullable();
            $table->string('firstName', 500)->nullable();
            $table->string('secondName', 500)->nullable();
            $table->string('thirdName', 500)->nullable();
            $table->string('fourthName', 500)->nullable();
            $table->string('originalName', 500)->nullable();
            $table->string('comment1', 5000)->nullable();
            $table->string('nationality', 500)->nullable();
            $table->string('aliasName', 500)->nullable();
            $table->string('typeOfDocument', 500)->nullable();
            $table->string('documentNumber', 500)->nullable();
            $table->string('adresse', 1000)->nullable();
            $table->string('city', 500)->nullable();
            $table->string('country', 500)->nullable();
            $table->string('dateOfBirth', 500)->nullable();
            $table->string('dateAjout', 500)->nullable();
            $table->text('aliasName')->nullable();
            $table->timestamps(); // optional
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_n_a_s_n_u_s');
    }
};
