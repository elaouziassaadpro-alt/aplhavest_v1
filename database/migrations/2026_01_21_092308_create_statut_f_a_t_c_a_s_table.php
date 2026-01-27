<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('statut_f_a_t_c_a_s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('info_generales_id'); // FK to etablissements


            $table->boolean('usEntity')->default(0);
            $table->string('fichier_usEntity', 350)->nullable();

            $table->boolean('giin')->default(0);
            $table->string('giin_label', 50)->nullable();
            $table->string('giin_label_Autres', 300)->nullable();

            $table->timestamps();

            // Foreign key to Etablissement
            $table->foreign('info_generales_id')
                  ->references('id')->on('info_generales')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('statut_f_a_t_c_a_s');
    }
};
