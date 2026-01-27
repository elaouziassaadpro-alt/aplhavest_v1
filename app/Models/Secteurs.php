<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secteurs extends Model
{
    use HasFactory;

    protected $table = 'secteurs';

    protected $fillable = [
        'libelle',
        'niveauRisque',
        'noteRisque',
        'source',
    ];
    public function TypologieClient()
    {
        return $this->hasMany(TypologieClient::class, 'segment_id');
    }
}
