<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banque extends Model
{
    use HasFactory;

    protected $table = 'banques';

    protected $fillable = [
        'nom',
        'codeBanque',
    ];
}
