<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\NiveauRisqueTrait;

class BenificiaireEffectif extends Model
{
    use HasFactory,NiveauRisqueTrait;

    protected $table = 'beneficiaires_effectifs';

    protected $fillable = [
        'etablissement_id',
        'nom_rs',
        'prenom',
        'pays_naissance_id',
        'date_naissance',
        'identite',
        'cin_file',
        'nationalite_id',
        'pourcentage_capital',
        'ppe_id',  
        'ppe_lien_id',
        'ppe',
        'ppe_lien',
        
    ];


    // Relation to InfosGenerales
    public function etablissement()
    {
        return $this->hasOne(Etablissement::class, 'etablissement_id');
    }
    public function paysNaissance()
{
    return $this->belongsTo(Pays::class, 'pays_naissance_id');
}

public function nationalite()
{
    return $this->belongsTo(Pays::class, 'nationalite_id');
}
public function ppeRelation()
{
    return $this->belongsTo(Ppe::class, 'ppe_id');
}

public function lienPpeRelation()
{
    return $this->belongsTo(Ppe::class, 'ppe_lien_id');
}
public function checkRisk()
    {
        return $this->niveauRisqueAuto();
    }

}
