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
       Schema::create('typologie_clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('info_generales_id'); // FK to etablissements
            $table->unsignedBigInteger('secteurActivite');
            $table->unsignedBigInteger('segment');
            $table->boolean('activiteEtranger')->default(0);
            $table->unsignedBigInteger('paysEtranger');
            $table->boolean('publicEpargne')->default(0);
            $table->string('publicEpargne_label', 500)->nullable();
            $table->timestamps();

            // Foreign keys (optional)
            $table->foreign('info_generales_id')->references('id')->on('info_generales')->onDelete('cascade');

            $table->foreign('secteurActivite')->references('id')->on('secteurs'); // if you have secteurs table
            
            $table->foreign('segment')->references('id')->on('segments'); // if you have segments table
            $table->foreign('paysEtranger')->references('id')->on('pays'); // if you have pays table
        });
    }   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typologie_clients');
    }
};
