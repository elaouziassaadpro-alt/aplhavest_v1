<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class formejuridique extends Model
{
use HasFactory;

    protected $table = 'formes_juridiques';

    protected $fillable = [
        'libelle',
        'code',
    ];
    public function InfoGeneral()
    {
        return $this->hasOne(InfoGeneral::class);
    }
    }
