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
        Schema::create('etablissements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            
            $table->boolean('validation_AK')->nullable();
            $table->boolean('validation_CI')->nullable();
            
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('validation_AK_by')->nullable();
            $table->unsignedBigInteger('validation_CI_by')->nullable();
            
            $table->timestamp('validation_AK_date')->nullable();
            $table->timestamp('validation_CI_date')->nullable();
            
            $table->string('risque')->nullable();
            $table->integer('note')->default(0);

            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('validation_AK_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('validation_CI_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
