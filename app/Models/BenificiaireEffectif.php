<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BenificiaireEffectif extends Model
{
    use HasFactory;

    protected $table = 'beneficiaires_effectifs';

    protected $fillable = [
        'info_general_id',
        'nom_rs',
        'prenom',
        'identite',
        'pourcentage_capital',
        'fonction',
        'nationalite',
        'PPE',
        'libelle_PPE',
    ];

    // Relation to InfosGenerales
    public function etablissement()
    {
        return $this->belongsTo(InfoGeneral::class, 'etablissement_id');
    }
}
