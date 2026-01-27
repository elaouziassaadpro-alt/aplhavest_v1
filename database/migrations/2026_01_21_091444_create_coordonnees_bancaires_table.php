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
    Schema::create('coordonnees_bancaires', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('info_generales_id')->nullable();
        $table->unsignedBigInteger('banque_id')->nullable();
        $table->string('agences_banque')->nullable();
        $table->unsignedBigInteger('ville_id')->nullable();

        $table->string('rib_banque')->nullable();
        $table->timestamps();
        $table->foreign('banque_id')->references('id')->on('banques');
        $table->foreign('ville_id')->references('id')->on('villes');
        $table->foreign('info_generales_id')->references('id')->on('info_generales')->onDelete('cascade');

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coordonnees_bancaires');
    }
};
