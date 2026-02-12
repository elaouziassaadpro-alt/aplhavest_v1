<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ppe extends Model
{
    use HasFactory;

    protected $table = 'ppes';

    protected $fillable = [
        'libelle',
    ];
    public function beneficiaires()
    {
        return $this->hasMany(BenificiaireEffectif::class, 'ppe_id');
    }

    public function beneficiairesLien()
    {
        return $this->hasMany(BenificiaireEffectif::class, 'ppe_lien_id');
    }
    public function Administrateur()
{
    return $this->hasOne(Administrateur::class);
}
}
