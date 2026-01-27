<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pays extends Model
{
    use HasFactory;

    protected $table = 'pays';

    protected $fillable = [
        'libelle',
        'iso',
        'niveauRisque',
    ];
    

    // If you don't want timestamps
    // public $timestamps = false;
}
