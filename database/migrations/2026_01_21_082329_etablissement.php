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
        Schema::create('etablissements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('InfoGeneral')->default(false);
            $table->boolean('CoordonneesBancaire')->default(false);
            $table->boolean('typologie_clients')->default(false);
            $table->boolean('statutfatcas')->default(false);
            $table->boolean('situation_financiere')->default(false);
            $table->boolean('actionnariat')->default(false);
            $table->boolean('beneficiaires_effectifs')->default(false);
            $table->boolean('administrateurs')->default(false);
            $table->boolean('personnes_habilites')->default(false);
            $table->boolean('objet_relations')->default(false);
            $table->boolean('profil_risques')->default(false);
            $table->string('validation')->nullable();
            $table->string('risque')->nullable();
            $table->integer('note')->default(0);
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
