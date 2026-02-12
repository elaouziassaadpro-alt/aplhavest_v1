<?php

namespace App\Http\Controllers;

use App\Models\BenificiaireEffectif;
use App\Models\Pays;
use Edit\benificiaireseffectifs;
use Illuminate\Http\Request;
use App\Models\Ppe;
use App\Models\InfoGeneral;
use Illuminate\Support\Facades\Validator; // ← c'est ça qu'il manquait
use App\Models\Etablissement;
use Illuminate\Support\Facades\DB;


class BenificiaireEffectifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Load Bénéficiaires Effectifs with related countries
    $beneficiaires = BenificiaireEffectif::with(['paysNaissance', 'nationalite'])
                        ->orderBy('nom_rs', 'asc')
                        ->get();

    return view("etablissements.infoetablissement.BenificiaireEffectif.index", compact('beneficiaires'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
         $etablissement_id = $request->etablissement_id;
        $etablissement = Etablissement::find($etablissement_id);
         $pays = Pays::all();
         $ppes = Ppe::all();
        return view('etablissements.infoetablissement.BenificiaireEffectif.create', compact('etablissement', 'pays', 'ppes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'etablissement_id' => 'required|integer',
        'noms_rs_benificiaires.*' => 'nullable|string|max:200',
        'prenoms_benificiaires.*' => 'nullable|string|max:100',
        'pays_naissance_benificiaires.*' => 'nullable|exists:pays,id',
        'dates_naissance_benificiaires.*' => 'nullable|date',
        'identite_benificiaires.*' => 'nullable|string|max:100',
        'nationalites_benificiaires.*' => 'nullable|exists:pays,id',
        'benificiaires_ppe_input.*' => 'nullable|exists:ppes,id',
        'benificiaires_ppe_lien_input.*' => 'nullable|exists:ppes,id',
        'benificiaires_pourcentage_capital.*' => 'nullable|numeric|min:0|max:100',
    ]);


    for ($i = 0; $i < count($request->noms_rs_benificiaires ?? []); $i++) {

        // Create the record
        $beneficiaire = BenificiaireEffectif::create([
            'etablissement_id' => $request->etablissement_id,
            'nom_rs' => $request->noms_rs_benificiaires[$i] ?? null,
            'prenom' => $request->prenoms_benificiaires[$i] ?? null,
            'pays_naissance_id' => $request->pays_naissance_benificiaires[$i] ?? null,
            'date_naissance' => $request->dates_naissance_benificiaires[$i] ?? null,
            'identite' => $request->identite_benificiaires[$i] ?? null,
            'nationalite_id' => $request->nationalites_benificiaires[$i] ?? null,
            'ppe' => $request->benificiaires_ppe_check[$i] ?? 0,
            'ppe_id' => ($request->benificiaires_ppe_check[$i] ?? 0) ? ($request->benificiaires_ppe_input[$i] ?? null) : null,
            'ppe_lien' => $request->benificiaires_ppe_lien_check[$i] ?? 0,
            'ppe_lien_id' => ($request->benificiaires_ppe_lien_check[$i] ?? 0) ? ($request->benificiaires_ppe_lien_input[$i] ?? null) : null,
            'pourcentage_capital' => $request->benificiaires_pourcentage_capital[$i] ?? null,
            
        ]);

        // Upload CIN file if exists
        if ($request->hasFile("cin_Beneficiaires_Effectif.$i")) {
            $file = $request->file("cin_Beneficiaires_Effectif.$i");
            $beneficiaire->cin_file = $file->store('Beneficiaires_Effectif/cin', 'public');
            $beneficiaire->save();
        }
    }

    $etablissement = Etablissement::findOrFail($request->etablissement_id);

    // Trigger rating update
    $etablissement->updateRiskRating();

    if ($request->redirect_to === 'dashboard') {
        return redirect()->route('etablissements.show', $request->etablissement_id)
            ->with('success', 'Bénéficiaires enregistrés avec succès !');
    }

    if ($etablissement->fresh()->isCompleted()) {
        return redirect()->route('Rating', ['etablissement_id' => $etablissement->id]);
    }

    

    return redirect()->route('administrateurs.create', ['etablissement_id' => $request->etablissement_id])
        ->with('success', 'Bénéficiaires enregistrés avec succès !');
}



    /**
     * Display the specified resource.
     */
    public function show(BenificiaireEffectif $benificiaireEffectif)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BenificiaireEffectif $benificiaireEffectif)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Etablissement $etablissement)
    {
        $request->validate([
            'noms_rs_benificiaires.*' => 'nullable|string|max:200',
            'prenoms_benificiaires.*' => 'nullable|string|max:100',
            'pays_naissance_benificiaires.*' => 'nullable|exists:pays,id',
            'dates_naissance_benificiaires.*' => 'nullable|date',
            'identite_benificiaires.*' => 'nullable|string|max:100',
            'nationalites_benificiaires.*' => 'nullable|exists:pays,id',
            'benificiaires_ppe_input.*' => 'nullable|exists:ppes,id',
            'benificiaires_ppe_lien_input.*' => 'nullable|exists:ppes,id',
            'benificiaires_pourcentage_capital.*' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Delete existing
            BenificiaireEffectif::where('etablissement_id', $etablissement->id)->delete();

            // Create new
            foreach ($request->noms_rs_benificiaires ?? [] as $i => $nom) {
                $is_ppe = ($request->ppe2_benificiaires[$i] ?? 0) == 1;
                $is_lien_ppe = ($request->lien2_benificiaires[$i] ?? 0) == 1;

                $beneficiaire = BenificiaireEffectif::create([
                    'etablissement_id' => $etablissement->id,
                    'nom_rs' => $nom ?? null,
                    'prenom' => $request->prenoms_benificiaires[$i] ?? null,
                    'pays_naissance_id' => $request->pays_naissance_benificiaires[$i] ?? null,
                    'date_naissance' => $request->dates_naissance_benificiaires[$i] ?? null,
                    'identite' => $request->identite_benificiaires[$i] ?? null,
                    'nationalite_id' => $request->nationalites_benificiaires[$i] ?? null,
                    'is_ppe' => $is_ppe ? 1 : 0,
                    'ppe_id' => $is_ppe ? ($request->benificiaires_ppe_input[$i] ?? null) : null,
                    'is_lien_ppe' => $is_lien_ppe ? 1 : 0,
                    'ppe_lien_id' => $is_lien_ppe ? ($request->benificiaires_ppe_lien_input[$i] ?? null) : null,
                    'pourcentage_capital' => $request->benificiaires_pourcentage_capital[$i] ?? null,
                    'cin_file' => $request->existing_cin_beneficiaire[$i] ?? null,
                ]);

                // Upload CIN file if exists
                if ($request->hasFile("cin_Beneficiaires_Effectif.$i")) {
                    $file = $request->file("cin_Beneficiaires_Effectif.$i");
                    $beneficiaire->cin_file = $file->store('Beneficiaires_Effectif/cin', 'public');
                    $beneficiaire->save();
                }
            }

            // Trigger rating update
            $etablissement->updateRiskRating();

            DB::commit();

            return redirect()->back()->with('success', 'Bénéficiaires mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour des bénéficiaires : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BenificiaireEffectif $benificiaireEffectif)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:beneficiaires_effectifs,id',
        ]);

        try {
            $firstItem = BenificiaireEffectif::find($request->ids[0]);
            $etablissement = $firstItem?->etablissement;

            BenificiaireEffectif::whereIn('id', $request->ids)->delete();

            // Trigger rating update
            $etablissement?->updateRiskRating();

            return response()->json([
                'success' => true,
                'message' => 'Bénéficiaires supprimés avec succès.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }
}
