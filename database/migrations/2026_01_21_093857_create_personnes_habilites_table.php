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
        Schema::create('personnes_habilites', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 200);
            $table->string('prenom', 200);
            $table->string('cinPasseport', 100);
            $table->string('fonction', 200);

            // Foreign key to pays table
            $table->unsignedBigInteger('nationalite');
            $table->foreign('nationalite')->references('id')->on('pays');

            $table->boolean('ppe')->default(false);
            $table->boolean('lienPPE')->default(false);

            $table->string('fichier_cin_file')->nullable();
            $table->string('fichier_habilitation_file')->nullable();

            // Foreign key to etablissement (info_generales)
            $table->unsignedBigInteger('idEtablissement');
            $table->foreign('idEtablissement')->references('id')->on('info_generales')->onDelete('cascade');

            $table->boolean('ppe2')->default(0);
            $table->boolean('lien2')->default(0);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnes_habilites');
    }
};
