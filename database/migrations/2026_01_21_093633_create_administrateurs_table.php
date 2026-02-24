<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('administrateurs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('etablissement_id');
            // Foreign keys (optional, if you have these tables)
            $table->foreign('etablissement_id')->references('id')->on('etablissements')->cascadeOnDelete();

            $table->string('nom', 200)->nullable();
            $table->string('prenom', 200)->nullable();
            $table->unsignedBigInteger('pays_id')->nullable(); // FK vers pays
            $table->date('date_naissance')->nullable();
            $table->string('identite', 100)->nullable();
            $table->unsignedBigInteger('nationalite_id')->nullable(); // FK vers pays
            $table->string('fonction', 150)->nullable();
            $table->integer('note')->default(0);    


            // PPE fields
            $table->boolean('ppe')->default(0);                   // checkbox
            $table->unsignedBigInteger('ppe_id')->nullable();     // FK vers ppes
            $table->boolean('lien_ppe')->default(0);             // checkbox
            $table->unsignedBigInteger('lien_ppe_id')->nullable(); // FK vers ppes

            // Fichiers
            $table->string('cin_file')->nullable();
            $table->string('pvn_file')->nullable();

            $table->timestamps();

            

            $table->foreign('pays_id')
                  ->references('id')->on('pays')
                  ->onDelete('set null');

            $table->foreign('nationalite_id')
                  ->references('id')->on('pays')
                  ->onDelete('set null');

            $table->foreign('ppe_id')
                  ->references('id')->on('ppes')
                  ->onDelete('set null');

            $table->foreign('lien_ppe_id')
                  ->references('id')->on('ppes')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('administrateurs');
    }
};
