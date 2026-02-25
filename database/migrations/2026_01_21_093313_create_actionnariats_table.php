<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('actionnariat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('etablissement_id');
            // Foreign keys (optional, if you have these tables)
            $table->foreign('etablissement_id')->references('id')->on('etablissements')->cascadeOnDelete();

            $table->string('nom_rs', 200)->nullable();       // Nom / Raison sociale
            $table->string('prenom', 100)->nullable();       // Prénom (si personne physique)
            $table->string('identite', 100)->nullable();     // N° d'identité ou RC
            $table->integer('nombre_titres')->nullable();    // Nombre de titres
            $table->integer('note')->default(0);    
            $table->decimal('pourcentage_capital', 5, 2)->nullable(); // % capital ou droit de vote
            $table->integer('percentage')->nullable();
            $table->string('table_match', 255)->nullable();
            $table->string('match_id', 500)->nullable();
            

            $table->timestamps();

            
        });
    }

    public function down()
    {
        Schema::dropIfExists('actionnariat');
    }
};
