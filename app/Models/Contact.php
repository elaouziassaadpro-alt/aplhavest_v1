<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'info_general_id',
        'nom',
        'prenom',
        'fonction',
        'telephone',
        'email',
    ];

    // Each contact belongs to one InfoGeneral
    public function infoGeneral()
    {
        return $this->belongsTo(InfoGeneral::class);
    }
}
