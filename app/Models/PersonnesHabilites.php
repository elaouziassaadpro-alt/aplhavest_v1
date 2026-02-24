<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\NiveauRisqueTrait;   
use Illuminate\Database\Eloquent\Factories\HasFactory;
class PersonnesHabilites extends Model

{
    use HasFactory,NiveauRisqueTrait;

    protected $table = 'personnes_habilites';

    protected $fillable = [
        'etablissement_id',
        'nom_rs',
        'prenom',
        'identite',
        'fonction',
        'ppe',
        'libelle_ppe',
        'cin_file',
        'fichier_habilitation_file',
        'lien_ppe',
        'libelle_ppe_lien',
        'note',
        'nationalite_id',
        'percentage',
        'table_match',
        'match_id'
    ];

    // Relationships

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class, 'etablissement_id');
    }

    public function nationalite()
    {
        return $this->belongsTo(Pays::class, 'nationalite_id');
    }
    
    public function ppeRelation()
{
    return $this->belongsTo(Ppe::class, 'libelle_ppe');
}

    public function lienPpeRelation()
{
    return $this->belongsTo(Ppe::class, 'libelle_ppe_lien');
}
public function checkRisk()
    {
        return $this->niveauRisqueAuto();
    }
}
