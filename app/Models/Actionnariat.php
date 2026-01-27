<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actionnariat extends Model
{
    use HasFactory;

    protected $table = 'actionnariat';

    protected $fillable = [
        'info_generales_id',
        'nom_rs',
        'prenom',
        'identite',
        'nombre_titres',
        'pourcentage_capital',
    ];

    // Relation to InfosGenerales
    public function infoGenerale()
    {
        return $this->belongsTo(InfoGeneral::class, 'info_generales_id');
    }


}
