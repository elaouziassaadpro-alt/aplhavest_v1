<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_risques', function (Blueprint $table) {
            $table->id();

            // Checkbox / switch
            $table->boolean('departement_en_charge_check')->default(false);
            $table->string('departement_gestion_input')->nullable();

            // Instruments financiers souhaités (store as JSON)
            $table->json('instruments_souhaites_input')->nullable();

            // Niveau de risque toléré (radio)
            $table->string('niveau_risque_tolere_radio')->nullable();

            // Années d'investissement (radio)
            $table->string('annees_investissement_produits_finaniers')->nullable();

            // Transactions sur le marché courant (radio)
            $table->string('transactions_courant_2_annees')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_risques');
    }
};
