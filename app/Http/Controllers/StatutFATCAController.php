<?php

namespace App\Http\Controllers;

use App\Models\StatutFATCA;
use Illuminate\Http\Request;

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
        $info_generales_id = $request->info_generales_id;
        return view('etablissements.infoetablissement.statutfatca.create', compact('info_generales_id'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    // dd($request->all());
        // Validation
        $validated = $request->validate([
            'info_generales_id' => 'required|integer',
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
        'info_generales_id' => $request->input('info_generales_id'), // <- obligatoire
        'usEntity' => $request->has('us_entity_check') ? 1 : 0,
        'fichier_usEntity' => $fatcaPath ?? null,
        'giin' => $request->has('giin_check') ? 1 : 0,
        'giin_label' => $request->giin_inputs ?? null,
        'giin_label_Autres' => !$request->has('giin_check') ? $request->giin_autres_input : null,
       ]);


        return redirect()->route('situationfinanciere.create',['info_generales_id' => $request->info_generales_id])->with('success', 'Statut FATCA enregistré avec succès !');
    
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
    public function update(Request $request, StatutFATCA $statutFATCA)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StatutFATCA $statutFATCA)
    {
        //
    }
}
