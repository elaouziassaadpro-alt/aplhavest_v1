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
        'info_generales_id',
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
    public function infoGenerale()
    {
        return $this->belongsTo(InfoGeneral::class, 'info_generales_id');
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
    public function segmentInfo()
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
