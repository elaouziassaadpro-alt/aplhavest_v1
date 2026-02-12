<?php

namespace App\Http\Controllers;

use App\Models\PersonnesHabilites;
use App\Models\Ppe;
use App\Models\InfoGeneral;
use Illuminate\Http\Request;
use App\Models\Etablissement;
use Illuminate\Support\Facades\DB;


class PersonnesHabilitesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Récupérer toutes les personnes habilitées avec leurs relations
        $personnesHabilites = PersonnesHabilites::with(['ppeRelation', 'lienPpeRelation', 'etablissement'])
                                ->orderBy('nom_rs')
                                ->get();

        return view('etablissements.infoetablissement.PersonnesHabilites.index', compact('personnesHabilites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $etablissement_id = $request->etablissement_id;
        $etablissement = Etablissement::find($etablissement_id);
         $ppes = Ppe::all();
        return view("etablissements.infoetablissement.PersonnesHabilites.create", compact("etablissement","ppes"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $etablissement_id = $request->etablissement_id;

        $noms               = $request->noms_habilites ?? [];
        $prenoms            = $request->prenoms_habilites ?? [];
        $cinPasseport       = $request->cin_habilites ?? [];
        $fonctions          = $request->fonctions_habilites ?? [];
        $ppes_input         = $request->ppes_habilites_input ?? [];
        $ppes_lien_input    = $request->ppes_lien_habilites_input ?? [];

        $cin_files = $request->file('cin_habilites_file', []);
        $hab_files = $request->file('hab_habilites', []);

        $total = count($noms);

        for ($i = 0; $i < $total; $i++) {
            $personne = PersonnesHabilites::create([
                'etablissement_id' => $etablissement_id,
                'nom_rs' => $noms[$i] ?? null,
                'prenom' => $prenoms[$i] ?? null,
                'identite' => $cinPasseport[$i] ?? null,
                'fonction' => $fonctions[$i] ?? null,
                'ppe' => $request->has("ppes_habilites_check.$i") ? 1 : 0,
                'libelle_ppe' => $request->has("ppes_habilites_check.$i") ? ($ppes_input[$i] ?? null) : null,
                'lien_ppe' => $request->has("ppes_lien_habilites_check.$i") ? 1 : 0,
                'libelle_ppe_lien' => $request->has("ppes_lien_habilites_check.$i") ? ($ppes_lien_input[$i] ?? null) : null,
            ]);

            // Upload CIN
            if (isset($cin_files[$i]) && $cin_files[$i]->isValid()) {
                $personne->cin_file = $cin_files[$i]->store('personneshabilites/cin', 'public');
                $personne->save();
            }

            // Upload Habilitation
            if (isset($hab_files[$i]) && $hab_files[$i]->isValid()) {
                $personne->fichier_habilitation_file = $hab_files[$i]->store('personneshabilites/habilitation', 'public');
                $personne->save();
            }
        }

        $etablissement = Etablissement::findOrFail($request->etablissement_id);
        $etablissement->updateRiskRating();

        if ($request->redirect_to === 'dashboard') {
            return redirect()->route('etablissements.show', $request->etablissement_id)
                ->with('success', 'Personnes habilitées enregistrées avec succès');
        }

        if ($etablissement->fresh()->isCompleted()) {
            return redirect()->route('Rating', ['etablissement_id' => $etablissement->id]);
        }

        

        return redirect()->route('objetrelation.create', ['etablissement_id' => $request->etablissement_id])
            ->with('success', 'Personnes habilitées enregistrées avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(PersonnesHabilites $personnesHabilites)
    {
        //
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
        ]);

        DB::beginTransaction();
        try {
            // Delete existing
            PersonnesHabilites::where('etablissement_id', $etablissement->id)->delete();

            // Create new
            foreach ($request->noms_habilites ?? [] as $i => $nom) {
                $is_ppe = ($request->ppes_habilites_check[$i] ?? 0) == 1;
                $is_lien_ppe = ($request->ppes_lien_habilites_check[$i] ?? 0) == 1;

                $personne = PersonnesHabilites::create([
                    'etablissement_id' => $etablissement->id,
                    'nom_rs' => $nom,
                    'prenom' => $request->prenoms_habilites[$i] ?? null,
                    'identite' => $request->cin_habilites[$i] ?? null,
                    'fonction' => $request->fonctions_habilites[$i] ?? null,
                    'ppe' => $is_ppe ? 1 : 0,
                    'libelle_ppe' => $is_ppe ? ($request->ppes_habilites_input[$i] ?? null) : null,
                    'lien_ppe' => $is_lien_ppe ? 1 : 0,
                    'libelle_ppe_lien' => $is_lien_ppe ? ($request->ppes_lien_habilites_input[$i] ?? null) : null,
                    'cin_file' => $request->existing_cin_habilites_file[$i] ?? null,
                    'fichier_habilitation_file' => $request->existing_hab_habilites[$i] ?? null,
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

            // Trigger rating update
            $etablissement?->updateRiskRating();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids');
            if (empty($ids)) {
                return response()->json(['success' => false, 'message' => 'Aucune sélection.']);
            }

            $firstItem = PersonnesHabilites::find($ids[0]);
            $etablissement = $firstItem?->etablissement;

            PersonnesHabilites::whereIn('id', $ids)->delete();

            // Trigger rating update
            $etablissement?->updateRiskRating();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression groupée']);
        }
    }
}
