<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypologieClient extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'typologie_clients';

    // Fillable fields for mass assignment
    protected $fillable = [
        'etablissement_id',
        'secteurActivite',
        'segment',
        'activiteEtranger',
        'paysEtranger',
        'publicEpargne',
        'publicEpargne_label',
    ];

    /**
     * Relationship to InfoGenerales (Etablissement)
     */
    public function etablissement()
    {
        return $this->hasOne(Etablissement::class, foreignKey: 'etablissement_id');
    }

    /**
     * Relationship to Secteur
     */
    public function secteur()
{
    return $this->belongsTo(Secteurs::class, 'secteurActivite');
}


    /**
     * Relationship to Segment
     */
    public function segment_get()
{
    return $this->belongsTo(Segments::class, 'segment');
}


    /**
     * Relationship to Pays (for activiteEtranger)
     */
    public function paysEtrangerInfo()
    {
        return $this->belongsTo(Pays::class, 'paysEtranger');
    }
}
