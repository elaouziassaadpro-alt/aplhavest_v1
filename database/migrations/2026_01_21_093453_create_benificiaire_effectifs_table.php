<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('beneficiaires_effectifs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('info_generales_id'); // FK to infos_generales

            $table->string('nom_rs', 200)->nullable();        // Nom / Raison sociale
            $table->string('prenom', 100)->nullable();        // Prénom si personne physique
            $table->string('identite', 100)->nullable();      // N° d'identité ou RC
            $table->decimal('pourcentage_capital', 5, 2)->nullable(); // % capital détenu
            $table->string('fonction', 150)->nullable();      // Fonction dans la société
            $table->string('nationalite', 100)->nullable();  // Nationalité
            $table->unsignedBigInteger('PPE')->default(0);               // Personne politiquement exposée
            $table->string('libelle_PPE', 200)->nullable();  // Libellé de PPE

            $table->timestamps();

            // Foreign key to InfosGenerales
            $table->foreign('info_generales_id')
                  ->references('id')->on('info_generales')
                  ->onDelete('cascade');
            $table->foreign('PPE')
                  ->references('id')->on('ppes')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('beneficiaires_effectifs');
    }
};
