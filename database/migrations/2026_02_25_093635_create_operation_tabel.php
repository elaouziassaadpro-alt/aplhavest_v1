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
        Schema::create('operations', function (Blueprint $table) {
    $table->id();

    $table->string('operation_number')->nullable();
    $table->string('event_number')->nullable();

    $table->string('titre')->nullable();
    $table->string('titre_description')->nullable();

    $table->string('poste')->nullable();
    $table->string('entite')->nullable();

    $table->string('portefeuille')->nullable();
    $table->string('portefeuille_description')->nullable();

    $table->string('statut')->nullable();

    $table->date('date_saisi')->nullable();
    $table->date('date_operation')->nullable();
    $table->date('date_valeur')->nullable();
    $table->date('date_livraison')->nullable();
    $table->date('date_validation')->nullable();
    $table->date('date_annulation')->nullable();

    $table->string('intermediaire')->nullable();
    $table->string('depositaire')->nullable();

    $table->string('compte_titre')->nullable();
    $table->string('compte_espece')->nullable();

    $table->string('contrepartie')->nullable();
    $table->string('contrepartie_description')->nullable();

    $table->string('depositaire_contrepartie')->nullable();
    $table->string('compte_titres_contrepartie')->nullable();

    $table->integer('quantite')->nullable();

    $table->decimal('cours', 15, 6)->nullable();
    $table->decimal('montant_devise', 18, 2)->nullable();

    $table->string('devise_ref')->nullable();
    $table->decimal('taux_ref', 10, 6)->nullable();
    $table->string('devise_reg')->nullable();

    $table->decimal('frais_total', 18, 2)->nullable();
    $table->decimal('montant_brut', 18, 2)->nullable();
    $table->decimal('montant_net', 18, 2)->nullable();

    $table->decimal('interet_couru', 18, 2)->nullable();
    $table->decimal('pmv_back', 18, 2)->nullable();

    $table->string('contrat')->nullable();

    $table->date('titre_jouissance')->nullable();
    $table->date('titre_echeance')->nullable();

    $table->decimal('prix_nego', 15, 6)->nullable();
    $table->decimal('prix_ppc', 15, 6)->nullable();

    $table->decimal('nego_spread', 10, 6)->nullable();
    $table->decimal('nego_taux', 10, 6)->nullable();

    $table->decimal('taux_placement', 10, 6)->nullable();
    $table->integer('nbre_jours_placement')->nullable();

    $table->decimal('interets', 18, 2)->nullable();
    $table->integer('decalage_valeur')->nullable();

    $table->string('ope_front')->nullable();
    $table->string('ope_back')->nullable();
    $table->string('ope_annul')->nullable();

    $table->date('date_echeance')->nullable();

    $table->string('code_isin')->nullable();
    $table->string('emetteur')->nullable();

    $table->string('classe')->nullable();
    $table->string('categorie')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
