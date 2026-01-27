<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoGeneral extends Model
{
    use HasFactory;

    protected $table = 'info_generales';

    protected $fillable = [
        'raisonSocial',
        'capitalSocialPrimaire',
        'FormeJuridique',
        'dateImmatriculation',
        'ice',
        'ice_file',
        'status_file',
        'rc',
        'rc_file',
        'ifiscal',
        'siegeSocial',
        'paysActivite',
        'paysResidence',
        'regule',
        'nomRegulateur',
        'telephone',
        'email',
        'site_web',
        'societe_gestion',
        'agrement_file',
        'NI',
        'FS',
        'RG',
    ];

    // One InfoGeneral has many contacts
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
    public function CoordonneesBancaires()
    {
        return $this->hasMany(CoordonneesBancaires::class);
    }
    public function TypologieClient()
    {
        return $this->hasMany(TypologieClient::class);
    }
    public function StatutFatca()
    {
        return $this->hasMany(StatutFatca::class);
    }
    public function SituationFinanciere()
    {
        return $this->hasMany(SituationFinanciere::class);
    }
    public function Actionnaire()
    {
        return $this->hasMany(Actionnaire::class);
    }
    public function BeneficiaireEffectif()
    {
        return $this->hasMany(BeneficiaireEffectif::class);
    }
    public function Administrateur()
    {
        return $this->hasMany(Administrateur::class);
    }
    public function PersonnesHabilites()
    {
        return $this->hasMany(PersonnesHabilites::class);
    }
    public function ObjetRelation()
    {
        return $this->hasMany(ObjetRelation::class);
    }
    public function ProfilRisque()
    {
        return $this->hasMany(ProfilRisque::class);
    }



}
