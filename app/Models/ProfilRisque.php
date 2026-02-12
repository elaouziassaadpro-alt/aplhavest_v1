<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilRisque extends Model
{
    use HasFactory;

    protected $table = 'profil_risques';

    protected $fillable = [
        'etablissement_id',
        'departement_en_charge_check',
        'departement_gestion_input',
        'instruments_souhaites_input',
        'niveau_risque_tolere_radio',
        'annees_investissement_produits_finaniers',
        'transactions_courant_2_annees',
    ];

    // Cast JSON column to array
    protected $casts = [
        'departement_en_charge_check' => 'boolean',
    ];
    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class, 'etablissement_id');
    }
}
