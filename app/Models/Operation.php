<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $fillable = [
        'operation_number',
        'event_number',
        'titre',
        'titre_description',
        'poste',
        'entite',
        'portefeuille',
        'portefeuille_description',
        'statut',
        'date_saisi',
        'date_operation',
        'date_valeur',
        'date_livraison',
        'date_validation',
        'date_annulation',
        'intermediaire',
        'depositaire',
        'compte_titre',
        'compte_espece',
        'contrepartie',
        'contrepartie_description',
        'depositaire_contrepartie',
        'compte_titres_contrepartie',
        'quantite',
        'cours',
        'montant_devise',
        'devise_ref',
        'taux_ref',
        'devise_reg',
        'frais_total',
        'montant_brut',
        'montant_net',
        'interet_couru',
        'pmv_back',
        'contrat',
        'titre_jouissance',
        'titre_echeance',
        'prix_nego',
        'prix_ppc',
        'nego_spread',
        'nego_taux',
        'taux_placement',
        'nbre_jours_placement',
        'interets',
        'decalage_valeur',
        'ope_front',
        'ope_back',
        'ope_annul',
        'date_echeance',
        'code_isin',
        'emetteur',
        'classe',
        'categorie',
    ];

    protected $casts = [
        'date_saisi' => 'date',
        'date_operation' => 'date',
        'date_valeur' => 'date',
        'date_livraison' => 'date',
        'date_validation' => 'date',
        'date_annulation' => 'date',
        'titre_jouissance' => 'date',
        'titre_echeance' => 'date',
        'date_echeance' => 'date',
        'quantite' => 'integer',
        'cours' => 'decimal:6',
        'montant_devise' => 'decimal:2',
        'taux_ref' => 'decimal:6',
        'frais_total' => 'decimal:2',
        'montant_brut' => 'decimal:2',
        'montant_net' => 'decimal:2',
        'interet_couru' => 'decimal:2',
        'pmv_back' => 'decimal:2',
        'prix_nego' => 'decimal:6',
        'prix_ppc' => 'decimal:6',
        'nego_spread' => 'decimal:6',
        'nego_taux' => 'decimal:6',
        'taux_placement' => 'decimal:6',
        'interets' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
