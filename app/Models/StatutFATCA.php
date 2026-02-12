<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatutFatca extends Model
{
    use HasFactory;

    protected $table = 'statut_f_a_t_c_a_s';

    protected $fillable = [
    'etablissement_id',  
    'usEntity',
    'fichier_usEntity',
    'giin',
    'giin_label',
    'giin_label_Autres',
];


    // Relation to Etablissement
    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class, 'etablissement_id');
    }
}
