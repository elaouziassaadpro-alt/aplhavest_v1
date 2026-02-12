<?php

namespace App\Http\Controllers;

use App\Models\InfoGeneral;
use App\Models\TypologieClient;
use App\Models\Secteurs;
use App\Models\Segments;
use App\Models\Pays;
use Illuminate\Http\Request;
use App\Models\Etablissement;

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

        $etablissement_id = $request->etablissement_id;
        $etablissement = Etablissement::find($etablissement_id);
        $secteurs = Secteurs::all();
        $segments = Segments::all();
        $pays = Pays::all();

        return view('etablissements.infoetablissement.TypologieClient.create', compact('secteurs', 'segments', 'pays', 'etablissement'));
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
{
    // Validation
    $validated = $request->validate([
        'etablissement_id' => 'required|integer',
        'secteurActivite' => 'required|integer',
        'segment' => 'required|integer',
        'activite_etranger' => 'nullable|boolean',
        'pays' => 'nullable|integer|required_if:activite_etranger,1',
        'sur_marche_financier' => 'nullable|boolean',
        'sur_marche_financier_input' => 'nullable|string|required_if:sur_marche_financier,1|max:255',
    ]);
    // // Création de l'enregistrement
    $typologieClient = TypologieClient::create([
        'etablissement_id' => $validated['etablissement_id'],
        'secteurActivite' => $validated['secteurActivite'],
        'segment' => $validated['segment'],
        'activiteEtranger' => $request->has('activite_etranger') ? 1 : 0,
        'paysEtranger' => $request->has('activite_etranger') ? $validated['pays'] : null,
        'publicEpargne' => $request->has('sur_marche_financier') ? 1 : 0,
        'publicEpargne_label' => $request->has('sur_marche_financier') ? $validated['sur_marche_financier_input'] : null,
    ]);
        $etablissement = Etablissement::findOrFail($request->etablissement_id);

        if ($etablissement->fresh()->isCompleted()) {
            return redirect()->route('Rating', [
                'etablissement_id' => $etablissement->id
            ]);

        }
    return redirect()->route('statutfatca.create',['etablissement_id' => $etablissement->id])
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
    public function update(Request $request, Etablissement $etablissement)
{
    $etablissement->typologieClient()->updateOrCreate(
        ['etablissement_id' => $etablissement->id],
        [
            'secteurActivite'     => $request->secteurActivite,
            'segment'             => $request->segment,
            'activiteEtranger'    => $request->has('activiteEtranger'),
            'paysEtranger'        => $request->has('activiteEtranger') ? $request->paysEtranger : null,
            'publicEpargne'       => $request->has('publicEpargne'),
            'publicEpargne_label' => $request->has('publicEpargne') ? $request->publicEpargne_label : null,
        ]
    );
    
    $etablissement->updateRiskRating();

    return back()->with('success', 'Typologie du client mise à jour');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypologieClient $typologieClient)
    {
        //
    }
}
