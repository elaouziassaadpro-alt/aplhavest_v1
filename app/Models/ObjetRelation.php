<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjetRelation extends Model
{
    use HasFactory;

    protected $table = 'objet_relations';

    protected $fillable = [
        'info_general_id',
        'relation_affaire',
        'horizon_placement',
        'objet_relation',
        'mandataire_check',
        'mandataire_input',
        'mandataire_fin_mandat_date',
        'mandat_file',
    ];

    // Cast objet_relation as array
    protected $casts = [
        'objet_relation' => 'array',
        'mandataire_check' => 'boolean',
        'mandataire_fin_mandat_date' => 'date',
    ];

    // Relation to establishment
    public function infoGeneral()
    {
        return $this->belongsTo(InfoGeneral::class, 'info_general_id');
    }
}
