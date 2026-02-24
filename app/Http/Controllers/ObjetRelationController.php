<?php

namespace App\Http\Controllers;

use App\Models\ObjetRelation;
use App\Models\InfoGeneral;
use Illuminate\Http\Request;
use App\Models\Etablissement;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;




class ObjetRelationController extends Controller
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
        return view("etablissements.infoetablissement.ObjetRelation.create", compact("etablissement")); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // dd($request->all());
    // ✅ Validation
    //  $validated = $request->validate([
    //     'info_general_id' => 'required|exists:info_generales,id',
    //     'relation_affaire_radio' => 'required|string',
    //     'horizon_placement_radio' => 'required|string',
    //     'objet_relation' => 'required|array',
    //     'mandataire_check' => 'nullable|boolean',
    //     'mandataire_label' => 'nullable|string',
    //     'mandataire_fin_mandat_date' => 'nullable|date',
    //     'mandat_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    // ]);

    // ✅ Création
    $objetRelation = new ObjetRelation();
    $objetRelation->etablissement_id = $request['etablissement_id'];

    $objetRelation->relation_affaire = $request['relation_affaire_radio'] ?? null;
    $objetRelation->horizon_placement = $request['horizon_placement_radio'] ?? null;

    // Stockage objet_relation (array → string)
    $objetRelation->objet_relation = isset($request['objet_relation'])
        ? implode(',', $request['objet_relation'])
        : null;

    // ✅ Mandataire
    $objetRelation->mandataire_check = (bool) $request['mandataire_check'];

    if ($objetRelation->mandataire_check) {
        $objetRelation->mandataire_input = $request['mandataire_label'];
        $objetRelation->mandataire_fin_mandat_date = $request['mandataire_fin_mandat_date'];

        // Upload fichier
        if ($request->hasFile('mandat_file')) {
            $path = $request->file('mandat_file')
                ->store('mandataire', 'public');
            $objetRelation->mandat_file = $path;
        }
    } else {
        // Nettoyage si NON
        $objetRelation->mandataire_input = null;
        $objetRelation->mandataire_fin_mandat_date = null;
        $objetRelation->mandat_file = null;
    }

    // ✅ Save
    $objetRelation->save();
    $etablissement = Etablissement::findOrFail($request->etablissement_id);

    // Trigger rating update
    $etablissement->updateRiskRating();

    
    return redirect()
        ->route('profilrisque.create',['etablissement_id' => $request->etablissement_id])
        ->with('success', 'Objet de la relation enregistré avec succès.');
}


    /**
     * Display the specified resource.
     */
    public function show(ObjetRelation $objetRelation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ObjetRelation $objetRelation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Etablissement $etablissement)
{
    $data = $request->validate([
        'relation_affaire_radio'        => 'required|string',
        'horizon_placement_radio'       => 'required|string',
        'objet_relation'                => 'nullable|array',
        'objet_relation.*'              => 'string',

        'mandataire_check'              => 'nullable|boolean',
        'mandataire_label'              => 'nullable|string|max:255',
        'mandataire_fin_mandat_date'    => 'nullable|date',
        'mandat_file'                   => 'nullable|file|mimes:pdf,jpg,png|max:2048',
    ]);

    // récupérer ou créer
    $objetRelation = $etablissement->ObjetRelation
        ?? new ObjetRelation(['etablissement_id' => $etablissement->id]);

    // assignations simples
    $objetRelation->relation_affaire = $data['relation_affaire_radio'];
    $objetRelation->horizon_placement = $data['horizon_placement_radio'];
    $objetRelation->objet_relation = $data['objet_relation'] ?? [];
    $objetRelation->mandataire_check = $request->boolean('mandataire_check');

    // gestion mandataire
    if ($objetRelation->mandataire_check) {
        $objetRelation->mandataire_input = $request->mandataire_label;
        $objetRelation->mandataire_fin_mandat_date = $request->mandataire_fin_mandat_date;
    } else {
        $objetRelation->mandataire_input = null;
        $objetRelation->mandataire_fin_mandat_date = null;
    }

    // fichier mandat
    if ($request->hasFile('mandat_file')) {

        if ($objetRelation->mandat_file && Storage::disk('public')->exists($objetRelation->mandat_file)) {
        Storage::disk('public')->delete($objetRelation->mandat_file);
}


        $slug = Str::slug($etablissement->raisonSocial ?? 'mandat');
        $path = $request->file('mandat_file')->storeAs(
            "objet_relation/{$etablissement->id}",
            "{$slug}-mandat-" . time() . '.' . $request->file('mandat_file')->getClientOriginalExtension(),
            'public'
        );

        $objetRelation->mandat_file = $path;
    }

    $objetRelation->save();
    // Trigger rating update
    $etablissement->updateRiskRating();

    return back()->with('success', "Objet et nature de la relation d'affaire mis à jour avec succès.");
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ObjetRelation $objetRelation)
    {
        //
    }
}
