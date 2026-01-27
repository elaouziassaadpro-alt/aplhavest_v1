<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('info_generales', function (Blueprint $table) {
            $table->id();
            $table->string('raisonSocial', 200)->nullable();
            $table->decimal('capitalSocialPrimaire', 15, 2)->default(0);
            $table->unsignedBigInteger('FormeJuridique')->nullable();
            $table->date('dateImmatriculation')->nullable();
            $table->string('ice', 100)->nullable();
            $table->string('ice_file', 100)->nullable();
            $table->string('status_file', 100)->nullable();

            $table->string('rc', 100)->nullable();
            $table->string('rc_file', 100)->nullable();
            $table->string('ifiscal', 100)->nullable();
            $table->string('siegeSocial', 350)->nullable();
            $table->unsignedBigInteger('paysActivite')->nullable();
            $table->unsignedBigInteger('paysResidence')->nullable();

            $table->boolean('regule')->default(0);
            $table->string('nomRegulateur', 200)->nullable();
            $table->string('telephone', 15)->nullable();
            $table->string('email', 200)->nullable();
            $table->string('siteweb', 100)->nullable();
            $table->boolean('societe_gestion')->default(0);
            $table->string('agrement_file')->nullable();
            $table->string('NI')->nullable();
            $table->string('FS')->nullable();
            $table->string('RG')->nullable();
            $table->timestamps();
            // Foreign keys (optional, if you have these tables)
            $table->foreign('paysActivite')->references('id')->on('pays')->nullOnDelete();
            $table->foreign('paysResidence')->references('id')->on('pays')->nullOnDelete();
            $table->foreign('FormeJuridique')->references('id')->on('formes_juridiques')->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infos_generales');
    }
};
