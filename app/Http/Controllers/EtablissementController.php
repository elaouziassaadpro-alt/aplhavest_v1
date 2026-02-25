<?php

namespace App\Http\Controllers;

use App\Models\Etablissement;
use App\Models\Secteurs;
use App\Models\Segments;
use Illuminate\Http\Request;
use App\Models\InfoGeneral;
use App\Models\formejuridique;
use App\Models\Pays;
use App\Models\Banque;
use App\Models\Ville;
use App\Models\Ppe;


class EtablissementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("etablissements.index");
    }
   

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     return view("etablissements.create");
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Etablissement $etablissement)
    {
        return view('etablissements.show', compact('etablissement'));
    }

    public function showDetail(Etablissement $etablissement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Etablissement $etablissement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Etablissement $etablissement)
    {
        //
    }
    public function updateValidation(Request $request)
{
    $request->validate([
        'ids' => 'required|array',
        'validation' => 'nullable|integer|in:0,1'
    ]);

    Etablissement::whereIn('id', $request->ids)
        ->update([
            'validation_AK' => $request->validation
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Validation mise à jour avec succès'
            ]);
        }

        return redirect()->back()->with('success', 'Validation mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyMultiple(Request $request)
{
    // Expect an array of IDs
    $ids = $request->input('ids', []);

    if (empty($ids)) {
        return response()->json([
            'message' => 'Aucun établissement sélectionné.'
        ], 400);
    }

    // Fetch all selected établissements
    $etablissements = Etablissement::whereIn('id', $ids)->get();

    // Check if any are validated
    $validated = $etablissements->filter(fn($e) => $e->validation_AK == 1);

    if ($validated->isNotEmpty()) {
        return response()->json([
            'message' => 'Impossible de supprimer des établissements validés.'
        ], 403);
    }

    // Delete all non-validated établissements
    Etablissement::whereIn('id', $ids)->delete();

    return response()->json([
        'message' => 'Les établissements sélectionnés ont été supprimés avec succès.'
    ]);
}
    


}
