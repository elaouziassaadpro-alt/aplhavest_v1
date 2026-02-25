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

        // ============================
        // RELATION PRINCIPALE
        // ============================
        $table->unsignedBigInteger('etablissement_id');
            // Foreign keys (optional, if you have these tables)
            $table->foreign('etablissement_id')->references('id')->on('etablissements')->cascadeOnDelete();

        // ============================
        // INFOS PERSONNE
        // ============================
        $table->string('nom_rs', 200)->nullable();
        $table->string('prenom', 100)->nullable();

        $table->unsignedBigInteger('pays_naissance_id')->nullable();
        $table->date('date_naissance')->nullable();
        $table->string('identite', 100)->nullable();
        $table->integer('note')->default(0);    
        $table->string('cin_file', 100)->nullable();

        $table->unsignedBigInteger('nationalite_id')->nullable();

        $table->decimal('pourcentage_capital', 5, 2)->nullable();
        $table->integer('percentage')->nullable();
        $table->string('table_match', 255)->nullable();
        $table->string('match_id', 500)->nullable();

        // ============================
        // PPE
        // ============================
        $table->boolean('ppe')->default(0);
        $table->unsignedBigInteger('ppe_id')->nullable();

        // ============================
        // LIEN PPE
        // ============================
        $table->boolean('ppe_lien')->default(0);
        $table->unsignedBigInteger('ppe_lien_id')->nullable();


        $table->timestamps();

        // ============================
        // FOREIGN KEYS
        // ============================
        

        $table->foreign('pays_naissance_id')
              ->references('id')->on('pays')
              ->nullOnDelete();

        $table->foreign('nationalite_id')
              ->references('id')->on('pays')
              ->nullOnDelete();

        $table->foreign('ppe_id')
              ->references('id')->on('ppes')
              ->nullOnDelete();

        $table->foreign('ppe_lien_id')
              ->references('id')->on('ppes')
              ->nullOnDelete();
    });
}


    public function down()
    {
        Schema::dropIfExists('beneficiaires_effectifs');
    }
};
