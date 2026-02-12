<?php

namespace App\Http\Controllers;

use App\Models\InfoGeneral;
use App\Models\ProfilRisque;
use Illuminate\Http\Request;use App\Models\Etablissement;


class ProfilRisqueController extends Controller
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

        return view("etablissements.infoetablissement.ProfilRisque.create", compact('etablissement'));
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
    {
        // Validate input
        // $validated = $request->validate([
        //     'etablissement_id' => 'required|integer|exists:info_generales,id',
        //     'departement_en_charge_check' => 'nullable|boolean',
        //     'departement_gestion_input' => 'nullable|string|max:255',
        //     'instruments_souhaites_input' => 'nullable|array',
        //     'instruments_souhaites_input.*' => 'nullable|string|max:255',
        //     'niveau_risque_tolere_radio' => 'nullable|string|max:50',
        //     'annees_investissement_produits_finaniers' => 'nullable|string|max:50',
        //     'transactions_courant_2_annees' => 'nullable|string|max:50',
        // ]);

        // Handle checkbox: convert to boolean
        $departementCheck = $request->has('departement_en_charge_check') ? true : false;

        // Convert array inputs to comma-separated strings for storage (or JSON)
        $instruments = $request->input('instruments_souhaites_input', []);
        $instrumentsStr = !empty($instruments) ? implode(', ', $instruments) : null;

        // Save to database
        $personne = ProfilRisque::create([
            'etablissement_id' => $request['etablissement_id'],
            'departement_en_charge_check' => $departementCheck,
            'departement_gestion_input' => $request->input('departement_gestion_input'),
            'instruments_souhaites_input' => $instrumentsStr,
            'niveau_risque_tolere_radio' => $request->input('niveau_risque_tolere_radio'),
            'annees_investissement_produits_finaniers' => $request->input('annees_investissement_produits_finaniers'),
            'transactions_courant_2_annees' => $request->input('transactions_courant_2_annees'),
        ]);
        $etablissement = Etablissement::findOrFail($request->etablissement_id);

        

        if ($etablissement->fresh()->isCompleted()) {
            return redirect()->route('Rating', [
                'etablissement_id' => $etablissement->id
            ]);

        }

        // Redirect with success message
        return redirect()->route('etablissements.index')->with('success', 'Profil Risque enregistré avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProfilRisque $profilRisque)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProfilRisque $profilRisque)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Etablissement $etablissement)
    {
        $data = $request->validate([
            'departement_en_charge_check' => 'nullable|boolean',
            'departement_gestion_input'   => 'nullable|string|max:255',

            'instruments_souhaites_input' => 'nullable|array',
            'instruments_souhaites_input.*' => 'nullable|string|max:255',

            'niveau_risque_tolere_radio' => 'nullable|string|max:50',
            'annees_investissement_produits_finaniers' => 'nullable|string|max:50',
            'transactions_courant_2_annees' => 'nullable|string|max:50',
        ]);

        /** checkbox */
        $data['departement_en_charge_check'] =
            $request->boolean('departement_en_charge_check');

        /** si checkbox OFF → on vide le select */
        if (!$data['departement_en_charge_check']) {
            $data['departement_gestion_input'] = null;
        }

            $instruments = array_filter(
            $request->input('instruments_souhaites_input', [])
        );

        $autres = trim($request->input('instruments_souhaites_autres'));

        if ($autres) {
            $instruments[] = $autres;
        }

        $data['instruments_souhaites_input'] =
            !empty($instruments) ? implode(', ', $instruments) : null;


        $etablissement->ProfilRisque->update($data);

        return back()->with('success', 'Profil risque mis à jour avec succès.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProfilRisque $profilRisque)
    {
        //
    }
}
