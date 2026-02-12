<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\NiveauRisqueTrait;

class Administrateur extends Model
{
    use HasFactory, NiveauRisqueTrait;

    protected $table = 'administrateurs';

    protected $fillable = [
        'etablissement_id',
        'nom',
        'prenom',
        'pays_id',
        'date_naissance',
        'identite',
        'nationalite_id',
        'fonction',
        'ppe',           // bool column
        'ppe_id',        // FK vers Ppes
        'lien_ppe',      // bool column
        'lien_ppe_id',   // FK vers Ppes
        'cin_file',
        'pvn_file',
    ];

    // Relations
    public function etablissement()
    {
        return $this->hasOne(Etablissement::class, 'etablissement_id');
    }

    public function pays()
    {
        return $this->belongsTo(Pays::class, 'pays_id');
    }

    public function nationalite()
    {
        return $this->belongsTo(Pays::class, 'nationalite_id');
    }

    // Rename relationships to avoid conflict with columns
    public function ppeRelation()
    {
        return $this->belongsTo(Ppe::class, 'ppe_id');
    }

    public function lienPpeRelation()
    {
        return $this->belongsTo(Ppe::class, 'lien_ppe_id');
    }

    // Risk calculation
    public function checkRisk()
    {
        return $this->niveauRisqueAuto();
    }

    // Helper to get libelle safely
    public function getPpeLibelle(): ?string
    {
        return $this->ppeRelation?->libelle 
            ?? $this->lienPpeRelation?->libelle 
            ?? null;
    }
}
