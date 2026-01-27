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
            $table->unsignedBigInteger('info_generales_id'); // FK to infos_generales

            $table->string('nom', 200)->nullable();
            $table->string('pays', 100)->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('identite', 100)->nullable();
            $table->string('nationalite', 100)->nullable();
            $table->string('fonction', 150)->nullable();

            // PPE fields
            $table->boolean('ppe')->default(0);             // checkbox
            $table->unsignedBigInteger('libelle_ppe')->nullable(); // FK to ppes table
            $table->boolean('ppe_lien')->default(0);       // checkbox
            $table->unsignedBigInteger('libelle_ppe_lien')->nullable(); // FK to ppes table

            $table->timestamps();

            // Foreign keys
            $table->foreign('info_generales_id')
                  ->references('id')->on('info_generales')
                  ->onDelete('cascade');

            $table->foreign('libelle_ppe')
                  ->references('id')->on('ppes')
                  ->onDelete('set null');

            $table->foreign('libelle_ppe_lien')
                  ->references('id')->on('ppes')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('administrateurs');
    }
};
