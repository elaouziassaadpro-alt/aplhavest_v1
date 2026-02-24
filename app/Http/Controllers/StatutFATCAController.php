<?php

namespace App\Http\Controllers;

use App\Models\StatutFATCA;
use App\Models\InfoGeneral;
use Illuminate\Http\Request;
use App\Models\Etablissement;
use Illuminate\Support\Facades\Storage;



class StatutFATCAController extends Controller
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
        return view('etablissements.infoetablissement.statutfatca.create', compact('etablissement'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    // dd($request->all());
        // Validation
        $validated = $request->validate([
            'etablissement_id' => 'required|integer',
            'us_entity_check' => 'nullable|boolean',
            'fichiers.fatca' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'giin_check' => 'nullable|boolean',
            'giin_inputs' => 'nullable|string|required_if:giin_check,1|max:255',
            'giin_autres_input' => 'nullable|string|required_if:giin_check,0|max:255',
        ]);

        // Gestion du fichier FATCA
        $fatcaPath = null;
        if ($request->hasFile('fichiers.fatca') && $request->us_entity_check) {
            $fatcaPath = $request->file('fichiers.fatca')->store('uploads/fatca', 'public');
        }

       StatutFatca::create([
        'etablissement_id' => $request->input('etablissement_id'), // <- obligatoire
        'usEntity' => $request->has('us_entity_check') ? 1 : 0,
        'fichier_usEntity' => $fatcaPath ?? null,
        'giin' => $request->has('giin_check') ? 1 : 0,
        'giin_label' => $request->giin_inputs ?? null,
        'giin_label_Autres' => !$request->has('giin_check') ? $request->giin_autres_input : null,
       ]);
        $etablissement = Etablissement::findOrFail($request->etablissement_id);

        

        

        return redirect()->route('situationfinanciere.create',['etablissement_id' => $request->etablissement_id])->with('success', 'Statut FATCA enregistré avec succès !');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(StatutFATCA $statutFATCA)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StatutFATCA $statutFATCA)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Etablissement $etablissement)
{
    $statutFatca = $etablissement->statutFatca;

    $data = $request->validate([
        'usEntity'          => 'nullable|boolean',
        'fichier_usEntity'  => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        'giin'              => 'nullable|boolean',
        'giin_label'        => 'nullable|string|max:255',
        'giin_label_Autres' => 'nullable|string|max:255',
    ]);

    // Normalize checkbox
    $data['usEntity'] = $request->boolean('usEntity');
    $data['giin']     = $request->boolean('giin');

    // GIIN logic
    if ($data['giin']) {
        $data['giin_label_Autres'] = null;
    } else {
        $data['giin_label'] = null;
    }

    // File logic
    if ($data['usEntity']) {

        if ($request->hasFile('fichier_usEntity')) {

            // delete old file if exists
            if ($statutFatca && $statutFatca->fichier_usEntity) {
                Storage::disk('public')->delete($statutFatca->fichier_usEntity);
            }

            $data['fichier_usEntity'] = $request
                ->file('fichier_usEntity')
                ->store('fatca', 'public');
        }

    } else {

        // remove file if unchecked
        if ($statutFatca && $statutFatca->fichier_usEntity) {
            Storage::disk('public')->delete($statutFatca->fichier_usEntity);
        }

        $data['fichier_usEntity'] = null;
    }

    // ✅ SINGLE SOURCE OF TRUTH
    $etablissement->statutFatca()->updateOrCreate(
        ['etablissement_id' => $etablissement->id],
        $data
    );

    return back()->with('success', 'Statut FATCA mis à jour avec succès.');
}




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StatutFATCA $statutFATCA)
    {
        //
    }
}
