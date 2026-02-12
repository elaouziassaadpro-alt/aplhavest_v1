<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SituationFinanciere extends Model
{
    use HasFactory;

    protected $table = 'situation_financiere';

    protected $fillable = [
    'etablissement_id',
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
        return $this->hasOne(Etablissement::class, 'etablissement_id');
    }
    public function paysOr()
    {
        return $this->belongsTo(Pays::class, 'paysOrigineFonds', 'id');
    }
}
