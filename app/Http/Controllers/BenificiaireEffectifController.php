<?php

namespace App\Http\Controllers;

use App\Models\BenificiaireEffectif;
use App\Models\Pays;
use Illuminate\Http\Request;
use App\Models\Ppe;
use App\Models\InfoGeneral;
use App\Models\Etablissement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BenificiaireEffectifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("etablissements.infoetablissement.BenificiaireEffectif.index");
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
            'notes_benificiaires.*' => 'nullable|numeric',
            'percentages_benificiaires.*' => 'nullable|numeric',
            'tables_benificiaires.*' => 'nullable|string',
            'match_ids_benificiaires.*' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $noms = $request->input('noms_rs_benificiaires', []);

            for ($i = 0; $i < count($noms); $i++) {
                $beneficiaire = BenificiaireEffectif::create([
                    'etablissement_id' => $request->etablissement_id,
                    'nom_rs' => $request->input("noms_rs_benificiaires.$i"),
                    'prenom' => $request->input("prenoms_benificiaires.$i"),
                    'pays_naissance_id' => $request->input("pays_naissance_benificiaires.$i"),
                    'date_naissance' => $request->input("dates_naissance_benificiaires.$i"),
                    'identite' => $request->input("identite_benificiaires.$i"),
                    'nationalite_id' => $request->input("nationalites_benificiaires.$i"),
                    'ppe' => $request->input("benificiaires_ppe_check.$i") ?? 0,
                    'ppe_id' => ($request->input("benificiaires_ppe_check.$i") ?? 0) ? ($request->input("benificiaires_ppe_input.$i")) : null,
                    'ppe_lien' => $request->input("benificiaires_ppe_lien_check.$i") ?? 0,
                    'ppe_lien_id' => ($request->input("benificiaires_ppe_lien_check.$i") ?? 0) ? ($request->input("benificiaires_ppe_lien_input.$i")) : null,
                    'pourcentage_capital' => $request->input("benificiaires_pourcentage_capital.$i"),
                    'note' => $request->input("notes_benificiaires.$i") ?? 1,
                    'percentage' => $request->input("percentages_benificiaires.$i") ?? 0,
                    'table_match' => $request->input("tables_benificiaires.$i"),
                    'match_id' => $request->input("match_ids_benificiaires.$i"),
                ]);

                if ($request->hasFile("cin_Beneficiaires_Effectif.$i")) {
                    $beneficiaire->cin_file = $request->file("cin_Beneficiaires_Effectif.$i")->store('Beneficiaires_Effectif/cin', 'public');
                    $beneficiaire->save();
                }
            }

            $etablissement = Etablissement::findOrFail($request->etablissement_id);
            $etablissement->updateRiskRating();

            DB::commit();

            if ($request->redirect_to === 'dashboard') {
                return redirect()->route('etablissements.show', $request->etablissement_id)
                    ->with('success', 'Bénéficiaires enregistrés avec succès !');
            }

            return redirect()->route('administrateurs.create', ['etablissement_id' => $request->etablissement_id])
                ->with('success', 'Bénéficiaires enregistrés avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error storing beneficiaires: " . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l’enregistrement des bénéficiaires : ' . $e->getMessage())->withInput();
        }
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
            'notes_benificiaires.*' => 'nullable|numeric',
            'percentages_benificiaires.*' => 'nullable|numeric',
            'tables_benificiaires.*' => 'nullable|string',
            'match_ids_benificiaires.*' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Delete existing
            BenificiaireEffectif::where('etablissement_id', $etablissement->id)->delete();

            // Create new
            foreach ($request->input('noms_rs_benificiaires', []) as $i => $nom) {
                $is_ppe = ($request->input("benificiaires_ppe_check.$i") ?? 0) == 1;
                $is_lien_ppe = ($request->input("benificiaires_ppe_lien_check.$i") ?? 0) == 1;

                $beneficiaire = BenificiaireEffectif::create([
                    'etablissement_id' => $etablissement->id,
                    'nom_rs' => $nom ?? null,
                    'prenom' => $request->input("prenoms_benificiaires.$i"),
                    'pays_naissance_id' => $request->input("pays_naissance_benificiaires.$i"),
                    'date_naissance' => $request->input("dates_naissance_benificiaires.$i"),
                    'identite' => $request->input("identite_benificiaires.$i"),
                    'nationalite_id' => $request->input("nationalites_benificiaires.$i"),
                    'ppe' => $is_ppe ? 1 : 0,
                    'ppe_id' => $is_ppe ? ($request->input("benificiaires_ppe_input.$i")) : null,
                    'ppe_lien' => $is_lien_ppe ? 1 : 0,
                    'ppe_lien_id' => $is_lien_ppe ? ($request->input("benificiaires_ppe_lien_input.$i")) : null,
                    'pourcentage_capital' => $request->input("benificiaires_pourcentage_capital.$i"),
                    'note' => $request->input("notes_benificiaires.$i") ?? 1,
                    'percentage' => $request->input("percentages_benificiaires.$i") ?? 0,
                    'table_match' => $request->input("tables_benificiaires.$i"),
                    'match_id' => $request->input("match_ids_benificiaires.$i"),
                    'cin_file' => $request->input("existing_cin_beneficiaire.$i"),
                ]);

                // Upload CIN file if exists
                if ($request->hasFile("cin_Beneficiaires_Effectif.$i")) {
                    $beneficiaire->cin_file = $request->file("cin_Beneficiaires_Effectif.$i")->store('Beneficiaires_Effectif/cin', 'public');
                    $beneficiaire->save();
                }
            }

            // Trigger rating update
            $etablissement->updateRiskRating();

            DB::commit();

            return redirect()->back()->with('success', 'Bénéficiaires mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating beneficiaires: " . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour des bénéficiaires : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BenificiaireEffectif $benificiaireEffectif)
    {
        try {
            $etablissement = $benificiaireEffectif->etablissement;
            $benificiaireEffectif->delete();
            $etablissement?->updateRiskRating();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
    }

    /**
     * Remove multiple resources.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:beneficiaires_effectifs,id',
        ]);

        try {
            // Get etablissement_id from the first item BEFORE deleting
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
            Log::error("Bulk delete error (BenificiaireEffectif): " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkRisque(Request $request)
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
            'notes_benificiaires.*' => 'nullable|numeric',
        ]);

        $etablissement_id = $request->input('etablissement_id');
        $etablissement = Etablissement::findOrFail($etablissement_id);
        $redirect_to = $request->input('redirect_to');

        $noms = $request->input('noms_rs_benificiaires', []);
        $prenoms = $request->input('prenoms_benificiaires', []);
        $cins = $request->input('identite_benificiaires', []);

        $analyses = [];

        foreach ($noms as $index => $nom) {
            $beneficiaire = new BenificiaireEffectif();
            $beneficiaire->nom_rs = $nom;
            $beneficiaire->prenom = $prenoms[$index] ?? null;
            $beneficiaire->identite = $cins[$index] ?? null;
            $beneficiaire->nationalite_id = $request->input("nationalites_benificiaires.$index");
            $beneficiaire->pourcentage_capital = $request->input("benificiaires_pourcentage_capital.$index");
            $beneficiaire->note = $request->input("notes_benificiaires.$index");
            $beneficiaire->date_naissance = $request->input("dates_naissance_benificiaires.$index");
            $beneficiaire->pays_naissance_id = $request->input("pays_naissance_benificiaires.$index");

            if ($request->hasFile("cin_Beneficiaires_Effectif.$index")) {
                $beneficiaire->cin_file = $request->file("cin_Beneficiaires_Effectif.$index")->store('Beneficiaires_Effectif/cin', 'public');
            }   
            
            // PPE
            $beneficiaire->ppe = $request->input("benificiaires_ppe_check.$index") == "1" ? 1 : 0;
            $beneficiaire->ppe_id = $beneficiaire->ppe ? $request->input("benificiaires_ppe_input.$index") : null;

            // Lien PPE
            $beneficiaire->ppe_lien = $request->input("benificiaires_ppe_lien_check.$index") == "1" ? 1 : 0;
            $beneficiaire->ppe_lien_id = $beneficiaire->ppe_lien ? $request->input("benificiaires_ppe_lien_input.$index") : null;

            $risk = $beneficiaire->checkRisk();
            
            $beneficiaire->percentage  = $risk['percentage'] ?? 0;
            $beneficiaire->table_match = $risk['table'] ?? null;
            $beneficiaire->match_id  = $risk['match_id'] ?? null;

            $analyses[] = [
                'data' => $beneficiaire,
                'risk' => $risk,
            ];
        }

        return view(
            'etablissements.infoetablissement.BenificiaireEffectif.check_risque',
            compact('analyses', 'redirect_to', 'etablissement')
        );
    }
}
