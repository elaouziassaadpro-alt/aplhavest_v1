<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class opc_files extends Model
{
    use HasFactory;

    protected $table = 'opc_files';
    protected $fillable = [
        'etablissement_id',
        'opc',
        'incrument',
        'ni',
        'fs',
        'rg',
    ];
    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }
}
