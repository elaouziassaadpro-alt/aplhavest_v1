<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoGeneral extends Model
{
    use HasFactory, \App\Traits\NiveauRisqueTrait;

    protected $table = 'info_generales';

    protected $fillable = [
        'etablissement_id',
        'raisonSocial',
        'capitalSocialPrimaire',
        'FormeJuridique',
        'dateImmatriculation',
        'ice',
        'rc',
        'ice_file',
        'status_file',
        'rc_file',
        'ifiscal',
        'siegeSocial',
        'paysActivite',
        'paysResidence',
        'regule',
        'nomRegulateur',
        'telephone',
        'email',
        'siteweb',
        'societe_gestion',
        'note',
        'percentage',
        'table_match',
        'match_id',
        'validation_CI',
        'validation_CI_date',
    ];

    public function checkRisk()
    {
        return $this->checkIdentity();
    }

    // One InfoGeneral has many contacts
public function paysActiviteInfo()
{
    return $this->belongsTo(Pays::class, 'paysActivite', 'id');
}



    public function paysresidence()
    {
        return $this->belongsTo(Pays::class, 'paysResidence', 'id');
    }

public function formeJuridique()
{
    return $this->belongsTo(FormeJuridique::class, 'FormeJuridique', 'id');
}

public function etablissement()
{
    return $this->hasOne(Etablissement::class,  'id');
}
public function contacts()
    {
        return $this->hasMany(Contact::class, 'info_general_id'); // foreign key in contacts table
    }
    
    
}
