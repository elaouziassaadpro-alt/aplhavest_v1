<?php

namespace App\Http\Controllers;

use App\Models\Etablissement;
use Illuminate\Http\Request;

class RatingEtablissementController extends Controller
{
    public function Rating(Request $request)
    {
        $etablissementId = $request->get('etablissement_id');
        $etablissement = Etablissement::findOrFail($etablissementId);

        return view('rating', compact('etablissement'));
    }
}
