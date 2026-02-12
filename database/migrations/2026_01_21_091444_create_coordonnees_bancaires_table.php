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
        $table->unsignedBigInteger('etablissement_id');
            // Foreign keys (optional, if you have these tables)
        $table->foreign('etablissement_id')->references('id')->on('etablissements')->cascadeOnDelete();
        $table->unsignedBigInteger('banque_id')->nullable();
        $table->string('agences_banque')->nullable();
        $table->unsignedBigInteger('ville_id')->nullable();

        $table->string('rib_banque')->nullable();
        $table->timestamps();
        $table->foreign('banque_id')->references('id')->on('banques');
        $table->foreign('ville_id')->references('id')->on('villes');

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
