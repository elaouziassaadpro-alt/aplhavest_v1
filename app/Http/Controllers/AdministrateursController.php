<?php

namespace App\Http\Controllers;

use App\Models\Administrateur;
use App\Models\InfoGeneral;
use Illuminate\Http\Request;
use App\Models\Pays;
use App\Models\Ppe;
use App\Models\Etablissement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdministrateursController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("etablissements.infoetablissement.Administrateurs.index");
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

        return view("etablissements.infoetablissement.Administrateurs.create", compact("etablissement", "pays", "ppes"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'noms_administrateurs.*' => 'required|string|max:200',
            'prenoms_administrateurs.*' => 'nullable|string|max:200',
            'pays_administrateurs.*' => 'nullable|integer|exists:pays,id',
            'dates_naissance_administrateurs.*' => 'nullable|date',
            'cins_administrateurs.*' => 'nullable|string|max:100',
            'nationalites_administrateurs.*' => 'nullable|integer|exists:pays,id',
            'fonctions_administrateurs.*' => 'nullable|string|max:150',
            'ppes_administrateurs_input.*' => 'nullable|integer|exists:ppes,id',
            'ppes_lien_administrateurs_input.*' => 'nullable|integer|exists:ppes,id',
            'cin_administrateurs.*' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'pvn_administrateurs.*' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'notes_administrateurs.*' => 'nullable|numeric',
            'percentages_administrateurs.*' => 'nullable|numeric',
            'tables_administrateurs.*' => 'nullable|string',
            'match_ids_administrateurs.*' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $etablissement_id = $request->input('etablissement_id');
            $count = count($request->input('noms_administrateurs', []));

            for ($i = 0; $i < $count; $i++) {
                $adminData = [
                    'etablissement_id' => $etablissement_id,
                    'nom' => $request->input("noms_administrateurs.$i"),
                    'prenom' => $request->input("prenoms_administrateurs.$i"),
                    'pays_id' => $request->input("pays_administrateurs.$i"),
                    'date_naissance' => $request->input("dates_naissance_administrateurs.$i"),
                    'identite' => $request->input("cins_administrateurs.$i"),
                    'nationalite_id' => $request->input("nationalites_administrateurs.$i"),
                    'fonction' => $request->input("fonctions_administrateurs.$i"),
                    'ppe' => $request->has("ppes_administrateurs_check.$i") ? 1 : 0,
                    'ppe_id' => $request->has("ppes_administrateurs_check.$i") ? ($request->input("ppes_administrateurs_input.$i")) : null,
                    'lien_ppe' => $request->has("ppes_lien_administrateurs_check.$i") ? 1 : 0,
                    'lien_ppe_id' => $request->has("ppes_lien_administrateurs_check.$i") ? ($request->input("ppes_lien_administrateurs_input.$i")) : null,
                    'cin_file' => $request->input("existing_cin_administrateurs.$i"),
                    'pvn_file' => $request->input("existing_pvn_administrateurs.$i"),
                    'note' => $request->input("notes_administrateurs.$i") ?? 1,
                    'percentage' => $request->input("percentages_administrateurs.$i") ?? 0,
                    'table_match' => $request->input("tables_administrateurs.$i"),
                    'match_id' => $request->input("match_ids_administrateurs.$i"),
                ];

                // Upload CIN
                if ($request->hasFile("cin_administrateurs.$i")) {
                    $adminData['cin_file'] = $request->file("cin_administrateurs.$i")->store('administrateurs/cin', 'public');
                }

                // Upload PV nomination
                if ($request->hasFile("pvn_administrateurs.$i")) {
                    $adminData['pvn_file'] = $request->file("pvn_administrateurs.$i")->store('administrateurs/pvn', 'public');
                }

                Administrateur::create($adminData);
            }

            $etablissement = Etablissement::findOrFail($etablissement_id);
            $etablissement->updateRiskRating();

            DB::commit();

            if ($request->redirect_to === 'dashboard') {
                return redirect()->route('etablissements.show', $etablissement_id)
                    ->with('success', 'Administrateurs ajoutés avec succès !');
            }

            return redirect()->route('personneshabilites.create', ['etablissement_id' => $etablissement_id])
                ->with('success', 'Administrateurs ajoutés avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error storing administrateurs: " . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l’ajout des administrateurs : ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Administrateur $administrateur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Etablissement $etablissement)
    {
        $request->validate([
            'noms_administrateurs.*' => 'required|string|max:200',
            'prenoms_administrateurs.*' => 'nullable|string|max:200',
            'pays_administrateurs.*' => 'nullable|integer|exists:pays,id',
            'dates_naissance_administrateurs.*' => 'nullable|date',
            'cins_administrateurs.*' => 'nullable|string|max:100',
            'nationalites_administrateurs.*' => 'nullable|integer|exists:pays,id',
            'fonctions_administrateurs.*' => 'nullable|string|max:150',
            'ppes_administrateurs_input.*' => 'nullable|integer|exists:ppes,id',
            'ppes_lien_administrateurs_input.*' => 'nullable|integer|exists:ppes,id',
            'cin_administrateurs.*' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'pvn_administrateurs.*' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'notes_administrateurs.*' => 'nullable|numeric',
            'percentages_administrateurs.*' => 'nullable|numeric',
            'tables_administrateurs.*' => 'nullable|string',
            'match_ids_administrateurs.*' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Delete existing
            Administrateur::where('etablissement_id', $etablissement->id)->delete();

            // Create new
            foreach ($request->input('noms_administrateurs', []) as $i => $nom) {
                $is_ppe      = ($request->input("ppe2_administrateurs.$i") ?? 0) == 1;
                $is_lien_ppe = ($request->input("lien2_administrateurs.$i") ?? 0) == 1;

                $adminData = [
                    'etablissement_id' => $etablissement->id,
                    'nom' => $nom,
                    'prenom' => $request->input("prenoms_administrateurs.$i"),
                    'pays_id' => $request->input("pays_administrateurs.$i"),
                    'date_naissance' => $request->input("dates_naissance_administrateurs.$i"),
                    'identite' => $request->input("cins_administrateurs.$i"),
                    'nationalite_id' => $request->input("nationalites_administrateurs.$i"),
                    'fonction' => $request->input("fonctions_administrateurs.$i"),
                    'ppe' => $is_ppe ? 1 : 0,
                    'ppe_id' => $is_ppe ? ($request->input("administrateur_ppe_input.$i")) : null,
                    'lien_ppe' => $is_lien_ppe ? 1 : 0,
                    'lien_ppe_id' => $is_lien_ppe ? ($request->input("administrateur_ppe_lien_input.$i")) : null,
                    'note' => $request->input("notes_administrateurs.$i") ?? 1,
                    'percentage' => $request->input("percentages_administrateurs.$i") ?? 0,
                    'table_match' => $request->input("tables_administrateurs.$i"),
                    'match_id' => $request->input("match_ids_administrateurs.$i"),
                    'cin_file' => $request->input("existing_cin_administrateurs.$i"),
                    'pvn_file' => $request->input("existing_pvn_administrateurs.$i"),
                ];

                $admin = Administrateur::create($adminData);
                
                // Upload CIN
                if ($request->hasFile("cin_administrateurs.$i")) {
                    $admin->cin_file = $request->file("cin_administrateurs.$i")->store('administrateurs/cin', 'public');
                    $admin->save();
                }

                // Upload PV nomination
                if ($request->hasFile("pvn_administrateurs.$i")) {
                    $admin->pvn_file = $request->file("pvn_administrateurs.$i")->store('administrateurs/pvn', 'public');
                    $admin->save();
                }
            }

            // Trigger rating update
            $etablissement->updateRiskRating();

            DB::commit();

            return redirect()->back()->with('success', 'Administrateurs mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating administrateurs: " . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour des administrateurs : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Administrateur $administrateur)
    {
        try {
            $etablissement = $administrateur->etablissement;
            $administrateur->delete();
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
            'ids.*' => 'integer|exists:administrateurs,id',
        ]);

        try {
            // Get etablissement_id from the first item BEFORE deleting
            $firstItem = Administrateur::find($request->ids[0]);
            $etablissement = $firstItem?->etablissement;

            Administrateur::whereIn('id', $request->ids)->delete();

            // Trigger rating update
            $etablissement?->updateRiskRating();

            return response()->json([
                'success' => true,
                'message' => 'Administrateurs supprimés avec succès.'
            ]);
        } catch (\Exception $e) {
            Log::error("Bulk delete error (Administrateurs): " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkRisque(Request $request)
    {
        $request->validate([
            'noms_administrateurs.*' => 'required|string|max:200',
            'prenoms_administrateurs.*' => 'nullable|string|max:200',
            'pays_administrateurs.*' => 'nullable|integer|exists:pays,id',
            'dates_naissance_administrateurs.*' => 'nullable|date',
            'cins_administrateurs.*' => 'nullable|string|max:100',
            'nationalites_administrateurs.*' => 'nullable|integer|exists:pays,id',
            'fonctions_administrateurs.*' => 'nullable|string|max:150',
            'ppes_administrateurs_input.*' => 'nullable|integer|exists:ppes,id',
            'ppes_lien_administrateurs_input.*' => 'nullable|integer|exists:ppes,id',
            'cin_administrateurs.*' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'pvn_administrateurs.*' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $etablissement_id = $request->input('etablissement_id');
        $etablissement = Etablissement::findOrFail($etablissement_id);
        $redirect_to = $request->input('redirect_to');

        $noms     = $request->input('noms_administrateurs', []);
        $prenoms  = $request->input('prenoms_administrateurs', []);
        $cins     = $request->input('cins_administrateurs', []);

        $analyses = [];

        foreach ($noms as $index => $nom) {
            $admin = new Administrateur();
            $admin->nom             = $nom;
            $admin->prenom          = $prenoms[$index] ?? null;
            $admin->identite        = $cins[$index] ?? null;
            $admin->pays_id         = $request->input("pays_administrateurs.$index");
            $admin->date_naissance  = $request->input("dates_naissance_administrateurs.$index");
            $admin->nationalite_id  = $request->input("nationalites_administrateurs.$index");
            $admin->fonction        = $request->input("fonctions_administrateurs.$index");
            
            /* CALCUL RISQUE */
            $risk = $admin->checkRisk();
            
            $admin->percentage  = $risk['percentage'] ?? 0;
            $admin->table_match = $risk['table'] ?? null;
            $admin->match_id  = $risk['match_id'] ?? null;

            /* Upload CIN */
            if ($request->hasFile("cin_administrateurs.$index")) {
                $admin->cin_file = $request->file("cin_administrateurs.$index")->store('administrateurs/cin', 'public');
            }

            /* Upload PVN */
            if ($request->hasFile("pvn_administrateurs.$index")) {
                $admin->pvn_file = $request->file("pvn_administrateurs.$index")->store('administrateurs/pvn', 'public');
            }

            /* PPE */
            $admin->ppe    = $request->has("ppes_administrateurs_check.$index") ? 1 : 0;
            $admin->ppe_id = $admin->ppe ? $request->input("ppes_administrateurs_input.$index") : null;

            /* Lien PPE */
            $admin->lien_ppe    = $request->has("ppes_lien_administrateurs_check.$index") ? 1 : 0;
            $admin->lien_ppe_id = $admin->lien_ppe ? $request->input("ppes_lien_administrateurs_input.$index") : null;

            $analyses[] = [
                'data' => $admin,
                'risk' => $risk,
            ];
        }

        return view(
            'etablissements.infoetablissement.Administrateurs.check_risque',
            compact('analyses', 'redirect_to', 'etablissement')
        );
    }
}
