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
        $etablissements = Etablissement::with('typologieClient.secteur')->with('infoGenerales.paysresidence')->get();

        
        
        return view("etablissements.index", compact('etablissements'));
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
    $formejuridiques = formejuridique::all();
    $pays = Pays::all();
    $banques = Banque::all();
    $villes = Ville::all();
    $secteurs = Secteurs::all();
    $segments  = Segments::all();
    $ppes= Ppe::all();
    $etablissement->load('infoGenerales.formeJuridique','CoordonneesBancaires.banque','CoordonneesBancaires.banque','CoordonneesBancaires.ville','typologieClient.secteur',
            'typologieClient.segment_get',
            'typologieClient.paysEtrangerInfo',
            'infoGenerales.paysresidence',
            'infoGenerales.paysActiviteInfo',
            'SituationFinanciere',
            'BeneficiaireEffectif',
            'BeneficiaireEffectif.ppeRelation',
            'BeneficiaireEffectif.lienPpeRelation',
            'Administrateur',
            'Administrateur.ppeRelation',
            'Administrateur.lienPpeRelation',
            'PersonnesHabilites',
            'PersonnesHabilites.ppeRelation',
            'PersonnesHabilites.lienPpeRelation');

    return view('etablissements.show', compact('etablissement','formejuridiques','pays','banques','villes','secteurs','segments','ppes'));
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
        'validation' => 'nullable|string|in:valide,rejete'
    ]);

    Etablissement::whereIn('id', $request->ids)
        ->update([
            'validation' => $request->validation
        ]);

    return response()->json([
        'message' => 'Validation mise à jour avec succès'
    ]);
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
    $validated = $etablissements->filter(fn($e) => $e->validation === 'valide');

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
