<?php

namespace App\Http\Controllers;

use App\Models\SituationFinanciere;
use App\Models\Pays;
use App\Models\InfoGeneral;
use App\Models\Etablissement;
use Illuminate\Support\Facades\Storage;



use Illuminate\Http\Request;

class SituationFinanciereController extends Controller
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
     $pays = Pays::all();

        return view("etablissements.infoetablissement.SituationFinanciere.create", compact('etablissement', 'pays'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ✅ Debug OK
        // dd($request->all());

        // ✅ Validation (important)
        $request->validate([
            'etablissement_id' => 'required|integer',
            'capital_social'    => 'nullable|string',
            'origine_fonds'     => 'nullable|string',
            'paysResidence'     => 'required|integer',
            'chiffre_affaires'  => 'required|string',
            'resultat_net'      => 'nullable|string',
            'groupe_holding'    => 'required|boolean',
            'etat_synthese'     => 'nullable|file|mimes:pdf,png,jpg,jpeg',
        ]);

        // ✅ Clean numbers (REMOVE SPACES)
        $capitalSocial = $this->cleanNumber($request->capital_social);
        $resultatNet   = $this->cleanNumber($request->resultat_net);

        // ✅ File upload
        $etatSynthesePath = null;
        if ($request->hasFile('etat_synthese')) {
            $etatSynthesePath = $request->file('etat_synthese')
                ->store('uploads/etat_synthese', 'public');
        }
        // ✅ Save to DB
        SituationFinanciere::create([
            'etablissement_id' => $request->etablissement_id,
            'capitalSocial'     => $capitalSocial,
            'origineFonds'      => $request->origine_fonds,
            'paysOrigineFonds'  => $request->paysResidence,
            'chiffreAffaires'   => $request->chiffre_affaires,
            'resultatsNET'      => $resultatNet,
            'holding'           => $request->groupe_holding,
            'etat_synthese'     => $etatSynthesePath,
        ]);
        $etablissement = Etablissement::findOrFail($request->etablissement_id);

        

        if ($etablissement->fresh()->isCompleted()) {
            return redirect()->route('Rating', [
                    'etablissement_id' => $etablissement->id
                ]);

        }

        return redirect()->route('actionnariat.create',['etablissement_id' => $request->etablissement_id])->with('success', 'Situation financière enregistrée avec succès.');
    }

    // ✅ Helper function
    private function cleanNumber($value)
    {
        return $value ? (int) str_replace(' ', '', $value) : null;
    }
    /**
     * Display the specified resource.
     */
    public function show(SituationFinanciere $situationFinanciere)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SituationFinanciere $situationFinanciere)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Etablissement $etablissement)
{
    $situationFinanciere = $etablissement->situationFinanciere;

    $data = $request->validate([
        'capitalSocial'       => 'nullable|string|max:255',
        'origineFonds'        => 'nullable|string|max:255',
        'paysOrigineFonds'    => 'nullable|exists:pays,id',
        'chiffreAffaires'     => 'nullable|string|max:50',
        'resultatsNET'        => 'nullable|string|max:255',
        'holding'             => 'nullable|boolean',
        'etat_synthese'       => 'nullable|file|mimes:pdf,jpg,png|max:2048',
    ]);

    // Remove spaces or commas before saving numeric values
    $data['capitalSocial'] = isset($data['capitalSocial']) ? str_replace([' ', ','], '', $data['capitalSocial']) : null;
    $data['resultatsNET'] = isset($data['resultatsNET']) ? str_replace([' ', ','], '', $data['resultatsNET']) : null;

    // Upload fichier états de synthèse
    if ($request->hasFile('etat_synthese')) {
        if ($situationFinanciere->etat_synthese) {
            Storage::disk('public')->delete($situationFinanciere->etat_synthese);
        }
        $data['etat_synthese'] = $request->file('etat_synthese')->store('situation_financiere', 'public');
    }

    $situationFinanciere->update($data);

    return back()->with('success', 'Situation financière mise à jour avec succès.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SituationFinanciere $situationFinanciere)
    {
        //
    }
}
