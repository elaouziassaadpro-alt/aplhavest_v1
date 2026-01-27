<?php

namespace App\Http\Controllers;

use App\Models\SituationFinanciere;
use App\Models\Pays;

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
     $info_generales_id = $request->info_generales_id;
     $pays = Pays::all();

        return view("etablissements.infoetablissement.SituationFinanciere.create", compact('info_generales_id', 'pays'));
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
            'info_generales_id' => 'required|integer',
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
            'info_generales_id' => $request->info_generales_id,
            'capitalSocial'     => $capitalSocial,
            'origineFonds'      => $request->origine_fonds,
            'paysOrigineFonds'  => $request->paysResidence,
            'chiffreAffaires'   => $request->chiffre_affaires,
            'resultatsNET'      => $resultatNet,
            'holding'           => $request->groupe_holding,
            'etat_synthese'     => $etatSynthesePath,
        ]);

        return redirect()->route('actionnariat.create',['info_generales_id' => $request->info_generales_id])->with('success', 'Situation financière enregistrée avec succès.');
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
    public function update(Request $request, SituationFinanciere $situationFinanciere)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SituationFinanciere $situationFinanciere)
    {
        //
    }
}
