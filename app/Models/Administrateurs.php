<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrateur extends Model
{
    use HasFactory;

    protected $table = 'administrateurs';

    protected $fillable = [
        'info_general_id',
        'nom',
        'pays',
        'date_naissance',
        'identite',
        'nationalite',
        'fonction',
        'ppe',
        'libelle_ppe',
        'ppe_lien',
        'libelle_ppe_lien',
    ];

    // Relation to InfosGenerales
    public function etablissement()
    {
        return $this->belongsTo(InfoGeneral::class, 'etablissement_id');
    }
}
