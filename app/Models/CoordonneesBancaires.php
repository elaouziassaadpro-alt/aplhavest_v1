<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoordonneesBancaires extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'coordonnees_bancaires';

    // Fillable fields
    protected $fillable = [
        'etablissement_id',
        'banque_id',
        'agences_banque',
        'ville_id',
        'rib_banque',
    ];

    // Relationship to InfoGenerales (Etablissement)
    public function etablissement()
    {
        return $this->hasOne(Etablissement::class, 'etablissement_id');
    }

    // Relationship to Banque
    public function banque()
    {
        return $this->belongsTo(Banque::class, 'banque_id');
    }

    // Relationship to Ville
    public function ville()
    {
        return $this->belongsTo(Ville::class, 'ville_id');
    }
}
