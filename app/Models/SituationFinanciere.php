<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SituationFinanciere extends Model
{
    use HasFactory;

    protected $table = 'situation_financiere';

    protected $fillable = [
    'info_generales_id',
    'capitalSocial',
    'origineFonds',
    'paysOrigineFonds',
    'chiffreAffaires',
    'resultatsNET',
    'holding',
    'etat_synthese',
];


    // Relation to InfosGenerales
    public function etablissement()
    {
        return $this->belongsTo(InfoGeneral::class, 'info_general_id');
    }
}
