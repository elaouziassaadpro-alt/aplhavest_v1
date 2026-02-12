<?php

namespace App\Http\Controllers;

use App\Models\Administrateur;
use App\Models\InfoGeneral;
use Illuminate\Http\Request;
use App\Models\Pays;
use App\Models\Ppe;use App\Models\Etablissement;
use Illuminate\Support\Facades\DB;



class AdministrateursController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all administrateurs with related pays and nationalite
        $administrateurs = Administrateur::with(['pays', 'nationalite', 'ppeRelation', 'lienPpeRelation'])
                            ->orderBy('nom')
                            ->get();

        // Pass to the view
        return view("etablissements.infoetablissement.Administrateurs.index", compact('administrateurs'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
         $etablissement_id = $request->etablissement_id;
        $etablissement = Etablissement::find($etablissement_id);
         $pays= Pays::all();
         $ppes= Ppe::all();

        return view("etablissements.infoetablissement.Administrateurs.create", compact("etablissement","pays","ppes"));
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
        ]);

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

    $etablissement = Etablissement::findOrFail($request->etablissement_id);

    // Trigger rating update
    $etablissement->updateRiskRating();

    if ($request->redirect_to === 'dashboard') {
        return redirect()->route('etablissements.show', $request->etablissement_id)
            ->with('success', 'Administrateurs ajoutés avec succès !');
    }

    if ($etablissement->fresh()->isCompleted()) {
        return redirect()->route('Rating', ['etablissement_id' => $etablissement->id]);
    }

    

    return redirect()->route('personneshabilites.create', ['etablissement_id' => $request->etablissement_id])
        ->with('success', 'Administrateurs ajoutés avec succès !');
}

    /**
     * Display the specified resource.
     */
    public function show(Administrateur $administrateurs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Administrateur $administrateurs)
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
        ]);

        DB::beginTransaction();
        try {
            // Delete existing
            Administrateur::where('etablissement_id', $etablissement->id)->delete();

            // Create new
            foreach ($request->noms_administrateurs ?? [] as $i => $nom) {
                $is_ppe      = ($request->ppe2_administrateurs[$i] ?? 0) == 1;
                $is_lien_ppe = ($request->lien2_administrateurs[$i] ?? 0) == 1;

                $adminData = [
                    'etablissement_id' => $etablissement->id,
                    'nom' => $nom,
                    'prenom' => $request->prenoms_administrateurs[$i] ?? null,
                    'pays_id' => $request->pays_administrateurs[$i] ?? null,
                    'date_naissance' => $request->dates_naissance_administrateurs[$i] ?? null,
                    'identite' => $request->cins_administrateurs[$i] ?? null,
                    'nationalite_id' => $request->nationalites_administrateurs[$i] ?? null,
                    'fonction' => $request->fonctions_administrateurs[$i] ?? null,
                    // ✅ PPE
                    'ppe' => $is_ppe ? 1 : 0,
                    'ppe_id' => $is_ppe
                        ? ($request->administrateur_ppe_input[$i] ?? null)
                        : null,

                    // ✅ LIEN PPE
                    'lien_ppe' => $is_lien_ppe ? 1 : 0,
                    'lien_ppe_id' => $is_lien_ppe
                        ? ($request->administrateur_ppe_lien_input[$i] ?? null)
                        : null,
                    'cin_file' => $request->existing_cin_administrateurs[$i] ?? null,
                    'pvn_file' => $request->existing_pvn_administrateurs[$i] ?? null,
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
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour des administrateurs : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Administrateur $administrateur)
    {
        try {
            $etablissement = $administrateur->etablissement;
            $administrateur->delete();

            // Trigger rating update
            $etablissement?->updateRiskRating();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:administrateurs,id', // Verify table name is correct. Model is Administrateur, table usually administrateurs.
        ]);

        try {
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
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }
}
