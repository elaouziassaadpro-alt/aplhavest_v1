<?php

namespace App\Http\Controllers;

use App\Models\PersonnesHabilites;
use App\Models\Ppe;
use App\Models\InfoGeneral;
use App\Models\Pays;
use Illuminate\Http\Request;
use App\Models\Etablissement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PersonnesHabilitesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('etablissements.infoetablissement.PersonnesHabilites.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $etablissement_id = $request->etablissement_id;
        $etablissement = Etablissement::find($etablissement_id);
        $ppes = Ppe::all();
        $pays = Pays::all();
        return view("etablissements.infoetablissement.PersonnesHabilites.create", compact("etablissement", "ppes", "pays"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'etablissement_id' => 'required|integer',
            'noms_habilites.*' => 'nullable|string|max:200',
            'prenoms_habilites.*' => 'nullable|string|max:200',
            'cin_habilites.*' => 'nullable|string|max:200',
            'fonctions_habilites.*' => 'nullable|string|max:200',
            'ppes_habilites_input.*' => 'nullable|exists:ppes,id',
            'ppes_lien_habilites_input.*' => 'nullable|exists:ppes,id',
            'nationalites_habilites.*' => 'nullable|exists:pays,id',
            'notes_habilites.*' => 'nullable|numeric',
            'percentages_habilites.*' => 'nullable|numeric',
            'tables_habilites.*' => 'nullable|string',
            'match_ids_habilites.*' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $etablissement_id = $request->etablissement_id;
            $noms = $request->input('noms_habilites', []);

            for ($i = 0; $i < count($noms); $i++) {
                $personne = PersonnesHabilites::create([
                    'etablissement_id' => $etablissement_id,
                    'nom_rs' => $request->input("noms_habilites.$i"),
                    'prenom' => $request->input("prenoms_habilites.$i"),
                    'identite' => $request->input("cin_habilites.$i"),
                    'fonction' => $request->input("fonctions_habilites.$i"),
                    'ppe' => $request->input("ppes_habilites_check.$i") == 1 ? 1 : 0,
                    'libelle_ppe' => $request->input("ppes_habilites_check.$i") == 1 ? $request->input("ppes_habilites_input.$i") : null,
                    'lien_ppe' => $request->input("ppes_lien_habilites_check.$i") == 1 ? 1 : 0,
                    'libelle_ppe_lien' => $request->input("ppes_lien_habilites_check.$i") == 1 ? $request->input("ppes_lien_habilites_input.$i") : null,
                    'nationalite_id' => $request->input("nationalites_habilites.$i"),
                    'note' => $request->input("notes_habilites.$i") ?? 1,
                    'percentage'  => $request->input("percentages_habilites.$i") ?? 0,
                    'table_match' => $request->input("tables_habilites.$i"),
                    'match_id'  => $request->input("match_ids_habilites.$i"),
                    'cin_file' => $request->input("existing_cin_habilites_file.$i"),
                    'fichier_habilitation_file' => $request->input("existing_hab_habilites.$i"),
                ]);

                // Upload CIN
                if ($request->hasFile("cin_habilites_file.$i")) {
                    $personne->cin_file = $request->file("cin_habilites_file.$i")->store('personneshabilites/cin', 'public');
                    $personne->save();
                }

                // Upload Habilitation
                if ($request->hasFile("hab_habilites.$i")) {
                    $personne->fichier_habilitation_file = $request->file("hab_habilites.$i")->store('personneshabilites/habilitation', 'public');
                    $personne->save();
                }
            }

            $etablissement = Etablissement::findOrFail($etablissement_id);
            $etablissement->updateRiskRating();

            DB::commit();

            if ($request->redirect_to === 'dashboard') {
                return redirect()->route('etablissements.show', $etablissement_id)
                    ->with('success', 'Personnes habilitées enregistrées avec succès');
            }

            return redirect()->route('objetrelation.create', ['etablissement_id' => $etablissement_id])
                ->with('success', 'Personnes habilitées enregistrées avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error storing personnes habilites: " . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l’enregistrement des personnes habilitées : ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PersonnesHabilites $personnesHabilites)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Etablissement $etablissement)
    {
        $request->validate([
            'noms_habilites.*' => 'nullable|string|max:200',
            'prenoms_habilites.*' => 'nullable|string|max:200',
            'cin_habilites.*' => 'nullable|string|max:200',
            'fonctions_habilites.*' => 'nullable|string|max:200',
            'ppes_habilites_input.*' => 'nullable|exists:ppes,id',
            'ppes_lien_habilites_input.*' => 'nullable|exists:ppes,id',
            'nationalites_habilites.*' => 'nullable|exists:pays,id',
            'notes_habilites.*' => 'nullable|numeric',
            'percentages_habilites.*' => 'nullable|numeric',
            'tables_habilites.*' => 'nullable|string',
            'match_ids_habilites.*' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Delete existing
            PersonnesHabilites::where('etablissement_id', $etablissement->id)->delete();

            // Create new
            foreach ($request->input('noms_habilites', []) as $i => $nom) {
                $personne = PersonnesHabilites::create([
                    'etablissement_id' => $etablissement->id,
                    'nom_rs' => $nom,
                    'prenom' => $request->input("prenoms_habilites.$i"),
                    'identite' => $request->input("cin_habilites.$i"),
                    'fonction' => $request->input("fonctions_habilites.$i"),
                    'ppe' => $request->input("ppes_habilites_check.$i") == 1 ? 1 : 0,
                    'libelle_ppe' => $request->input("ppes_habilites_check.$i") == 1 ? $request->input("ppes_habilites_input.$i") : null,
                    'lien_ppe' => $request->input("ppes_lien_habilites_check.$i") == 1 ? 1 : 0,
                    'libelle_ppe_lien' => $request->input("ppes_lien_habilites_check.$i") == 1 ? $request->input("ppes_lien_habilites_input.$i") : null,
                    'nationalite_id' => $request->input("nationalites_habilites.$i"),
                    'note' => $request->input("notes_habilites.$i") ?? 1,
                    'percentage'  => $request->input("percentages_habilites.$i") ?? 0,
                    'table_match' => $request->input("tables_habilites.$i"),
                    'match_id'  => $request->input("match_ids_habilites.$i"),
                    'cin_file' => $request->input("existing_cin_habilites_file.$i"),
                    'fichier_habilitation_file' => $request->input("existing_hab_habilites.$i"),
                ]);

                // Upload CIN
                if ($request->hasFile("cin_habilites_file.$i")) {
                    $personne->cin_file = $request->file("cin_habilites_file.$i")->store('personneshabilites/cin', 'public');
                    $personne->save();
                }

                // Upload Habilitation
                if ($request->hasFile("hab_habilites.$i")) {
                    $personne->fichier_habilitation_file = $request->file("hab_habilites.$i")->store('personneshabilites/habilitation', 'public');
                    $personne->save();
                }
            }

            $etablissement->updateRiskRating();
            DB::commit();

            return redirect()->back()->with('success', 'Personnes habilitées mises à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating personnes habilites: " . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PersonnesHabilites $personnesHabilites)
    {
        try {
            $etablissement = $personnesHabilites->etablissement;
            $personnesHabilites->delete();
            $etablissement?->updateRiskRating();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
    }

    /**
     * Bulk delete resources.
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            if (empty($ids)) {
                return response()->json(['success' => false, 'message' => 'Aucune sélection.']);
            }

            $firstItem = PersonnesHabilites::find($ids[0]);
            $etablissement = $firstItem?->etablissement;

            PersonnesHabilites::whereIn('id', $ids)->delete();
            $etablissement?->updateRiskRating();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression groupée']);
        }
    }

    public function checkRisque(Request $request)
    {
        $request->validate([
            'noms_habilites.*' => 'required|string|max:200',
            'prenoms_habilites.*' => 'nullable|string|max:200',
            'cin_habilites.*' => 'nullable|string|max:200',
            'fonctions_habilites.*' => 'nullable|string|max:200',
            'ppes_habilites_input.*' => 'nullable|exists:ppes,id',
            'ppes_lien_habilites_input.*' => 'nullable|exists:ppes,id',
            'ppes_habilites_check.*' => 'nullable|in:0,1',
            'ppes_lien_habilites_check.*' => 'nullable|in:0,1',
            'notes_habilites.*' => 'nullable|numeric',
            'existing_cin_habilites_file.*' => 'nullable|string',
            'existing_hab_habilites.*' => 'nullable|string',
            'cin_habilites_file.*' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'hab_habilites.*' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $etablissement_id = $request->input('etablissement_id');
        $etablissement = Etablissement::findOrFail($etablissement_id);
        $redirect_to = $request->input('redirect_to');

        $noms = $request->input('noms_habilites', []);
        $prenoms = $request->input('prenoms_habilites', []);
        $cins = $request->input('cin_habilites', []);

        $analyses = [];

        foreach ($noms as $index => $nom) {
            $personne = new PersonnesHabilites();
            $personne->nom_rs = $nom;
            $personne->prenom = $prenoms[$index] ?? null;
            $personne->identite = $cins[$index] ?? null;
            $personne->nationalite_id = $request->input("nationalites_habilites.$index");
            $personne->fonction = $request->input("fonctions_habilites.$index");
            $personne->note = $request->input("notes_habilites.$index");
            $personne->cin_file = $request->input("existing_cin_habilites_file.$index");
            $personne->fichier_habilitation_file = $request->input("existing_hab_habilites.$index");

            // Temporary file storage for analysis
            if ($request->hasFile("cin_habilites_file.$index")) {
                $personne->cin_file = $request->file("cin_habilites_file.$index")->store('personneshabilites/cin', 'public');
            }
            if ($request->hasFile("hab_habilites.$index")) {
                $personne->fichier_habilitation_file = $request->file("hab_habilites.$index")->store('personneshabilites/habilitation', 'public');
            }
            
            // PPE
            $personne->ppe = $request->input("ppes_habilites_check.$index") == "1" ? 1 : 0;
            $personne->libelle_ppe = $personne->ppe ? $request->input("ppes_habilites_input.$index") : null;

            // Lien PPE
            $personne->lien_ppe = $request->input("ppes_lien_habilites_check.$index") == "1" ? 1 : 0;
            $personne->libelle_ppe_lien = $personne->lien_ppe ? $request->input("ppes_lien_habilites_input.$index") : null;
            
            $risk = $personne->checkRisk();
            
            $personne->percentage  = $risk['percentage'] ?? 0;
            $personne->table_match = $risk['table'] ?? null;
            $personne->match_id  = $risk['match_id'] ?? null;

            $analyses[] = [
                'data' => $personne,
                'risk' => $risk,
            ];
        }

        return view(
            'etablissements.infoetablissement.PersonnesHabilites.check_risque',
            compact('analyses', 'redirect_to', 'etablissement')
        );
    }
}
