<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\NiveauRisqueTrait; // ✅ correct import

class Actionnariat extends Model
{
    use HasFactory,NiveauRisqueTrait;

    protected $table = 'actionnariat';

    protected $fillable = [
        'etablissement_id',
        'nom_rs',
        'prenom',
        'identite',
        'nombre_titres',
        'pourcentage_capital',
    ];

    // Relation to InfosGenerales
    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class, 'etablissement_id');
    }
    
    
public function checkRisk()
    {
        return $this->checkidentity();
    }
}
