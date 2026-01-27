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
        Schema::create('objet_relations', function (Blueprint $table) {
            $table->id();

            // Foreign key to info_generales
            $table->unsignedBigInteger('info_general_id');
            $table->foreign('info_general_id')->references('id')->on('info_generales')->onDelete('cascade');

            // Form fields
            $table->string('relation_affaire')->nullable(); // frequency of operations
            $table->string('horizon_placement')->nullable(); // investment horizon
            $table->json('objet_relation')->nullable(); // array of checkboxes

            $table->boolean('mandataire_check')->default(false);
            $table->string('mandataire_input')->nullable();
            $table->date('mandataire_fin_mandat_date')->nullable();
            $table->string('mandat_file')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('objet_relations');
    }
};
