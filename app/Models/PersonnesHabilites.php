<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class PersonnesHabilites extends Model

{
    use HasFactory;

    protected $table = 'habilites';

    protected $fillable = [
        'nom',
        'prenom',
        'cinPasseport',
        'fonction',
        'nationalite',
        'ppe',
        'lienPPE',
        'fichier_cin_file',
        'fichier_habilitation_file',
        'info_general_id',
        'ppe2',
        'lien2',
    ];

    // Relationships
    public function pays()
    {
        return $this->belongsTo(Pays::class, 'nationalite');
    }

    public function etablissement()
    {
        return $this->belongsTo(InfoGeneral::class, 'info_general_id');
    }
}
