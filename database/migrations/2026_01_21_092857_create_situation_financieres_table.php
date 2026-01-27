<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('situation_financiere', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('info_generales_id'); // FK to infos_generales

            $table->integer('capitalSocial')->nullable();
            $table->string('origineFonds', 300)->nullable();
            $table->unsignedBigInteger('paysOrigineFonds');
            $table->string('chiffreAffaires', 200)->nullable();
            $table->integer('resultatsNET')->nullable();
            $table->unsignedBigInteger('holding')->nullable();
            $table->string('etat_synthese', 255)->nullable();

            $table->timestamps();

            // Foreign key to InfosGenerales
            $table->foreign('info_generales_id')
                  ->references('id')->on('info_generales')
                  ->onDelete('cascade');

            // Optional foreign keys
            // $table->foreign('paysOrigineFonds')->references('id')->on('pays');
            // $table->foreign('holding')->references('id')->on('holdings');
        });
    }

    public function down()
    {
        Schema::dropIfExists('situation_financiere');
    }
};
