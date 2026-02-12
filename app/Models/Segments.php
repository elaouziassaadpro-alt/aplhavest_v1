<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Segments extends Model
{
    use HasFactory;

    protected $table = 'segments';

    protected $fillable = [
        'libelle',
        'niveauRisque',
        'noteRisque',
    ];
    public function TypologieClient()
    {
        return $this->hasMany(TypologieClient::class, 'segment');
    }
}
