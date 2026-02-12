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

            // Relation to Etablissement
            $table->unsignedBigInteger('etablissement_id');
            $table->foreign('etablissement_id')
                  ->references('id')->on('etablissements')
                  ->cascadeOnDelete();

            // Personal info
            $table->string('nom_rs', 200)->nullable();
            $table->string('prenom', 200)->nullable();
            $table->string('identite', 100)->nullable();
            $table->string('fonction', 200)->nullable();

            // PPE and PPE link
            $table->boolean('ppe')->default(false);
            $table->unsignedBigInteger('libelle_ppe')->nullable(); // FK vers table ppes
            $table->boolean('lien_ppe')->default(false);
            $table->unsignedBigInteger('libelle_ppe_lien')->nullable(); // FK vers table ppes

            // Files
            $table->string('cin_file')->nullable();
            $table->string('fichier_habilitation_file')->nullable();

            // Foreign keys to ppes
            $table->foreign('libelle_ppe')
                  ->references('id')->on('ppes')
                  ->nullOnDelete();

            $table->foreign('libelle_ppe_lien')
                  ->references('id')->on('ppes')
                  ->nullOnDelete();

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
