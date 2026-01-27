<?php

namespace App\Http\Controllers;

use App\Models\TypologieClient;
use App\Models\Secteurs;
use App\Models\Segments;
use App\Models\Pays;
use Illuminate\Http\Request;
use Termwind\Components\Dd;

class TypologieClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $info_generales_id = $request->info_generales_id;
        $secteurs = Secteurs::all();
        $segments = Segments::all();
        $pays = Pays::all();

        return view('etablissements.infoetablissement.TypologieClient.create', compact('secteurs', 'segments', 'pays', 'info_generales_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
{
    // Validation
    $validated = $request->validate([
        'info_generales_id' => 'required|integer',
        'secteurActivite' => 'required|integer',
        'segment' => 'required|integer',
        'activite_etranger' => 'nullable|boolean',
        'pays' => 'nullable|integer|required_if:activite_etranger,1',
        'sur_marche_financier' => 'nullable|boolean',
        'sur_marche_financier_input' => 'nullable|string|required_if:sur_marche_financier,1|max:255',
    ]);
    // // Création de l'enregistrement
    $typologieClient = TypologieClient::create([
        'info_generales_id' => $validated['info_generales_id'],
        'secteurActivite' => $validated['secteurActivite'],
        'segment' => $validated['segment'],
        'activiteEtranger' => $request->has('activite_etranger') ? 1 : 0,
        'paysEtranger' => $request->has('activite_etranger') ? $validated['pays'] : null,
        'publicEpargne' => $request->has('sur_marche_financier') ? 1 : 0,
        'publicEpargne_label' => $request->has('sur_marche_financier') ? $validated['sur_marche_financier_input'] : null,
    ]);

    return redirect()->route('statutfatca.create',['info_generales_id' => $request->info_generales_id])
                     ->with('success', 'Typologie client enregistrée avec succès !');
}

    /**
     * Display the specified resource.
     */
    public function show(TypologieClient $typologieClient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TypologieClient $typologieClient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TypologieClient $typologieClient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypologieClient $typologieClient)
    {
        //
    }
}
