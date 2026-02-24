<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Actionnariat;
use App\Models\InfoGeneral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use App\Models\Etablissement;



class ActionnariatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("etablissements.infoetablissement.Actionnariat.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $etablissement_id = $request->etablissement_id;
        $etablissement = Etablissement::find($etablissement_id);
        return view("etablissements.infoetablissement.Actionnariat.create", compact('etablissement'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // ✅ Validation
       

        $request->validate([
            'etablissement_id' => 'required|integer',

            'noms_rs_actionnaires.*' => 'required|string|max:255',
            'prenoms_actionnaires.*' => 'nullable|string|max:255',
            'identite_actionnaires.*' => 'required|string|max:255',
            'nombre_titres_actionnaires.*' => 'required|numeric|min:0',
            'pourcentage_capital_actionnaires.*' => 'required|numeric|min:0|max:100',
            'notes_actionnaires.*' => 'nullable|numeric',
            'percentages_actionnaires.*' => 'nullable|numeric',
            'tables_actionnaires.*' => 'nullable|string',
            'match_ids_actionnaires.*' => 'nullable|string',

        ]);
        
        DB::beginTransaction();
        try {
            $noms         = $request->noms_rs_actionnaires ?? [];
            $prenoms      = $request->prenoms_actionnaires ?? [];
            $ids          = $request->identite_actionnaires ?? [];
            $titres       = $request->nombre_titres_actionnaires ?? [];
            $pourcentages = $request->pourcentage_capital_actionnaires ?? [];
            
            $notes        = $request->notes_actionnaires ?? [];
            $percentages  = $request->percentages_actionnaires ?? [];
            $tables       = $request->tables_actionnaires ?? [];
            $match_ids  = $request->match_ids_actionnaires ?? [];

            foreach ($noms as $index => $nom) {
                Actionnariat::create([
                    'etablissement_id'   => $request->etablissement_id,
                    'nom_rs'             => $nom,
                    'prenom'             => $prenoms[$index] ?? null,
                    'identite'           => $ids[$index] ?? null,
                    'nombre_titres'      => $titres[$index] ?? 0,
                    'pourcentage_capital'=> $pourcentages[$index] ?? 0,
                    'note'               => $notes[$index] ?? 1,
                    'percentage'         => $percentages[$index] ?? 0,
                    'table_match'        => $tables[$index] ?? null,
                    'match_id'         => $match_ids[$index] ?? null,
                ]);
            }

            $etablissement = Etablissement::findOrFail($request->etablissement_id);
            $etablissement->updateRiskRating();

            DB::commit();

            if ($request->redirect_to === 'dashboard') {
                return redirect()
                    ->route('etablissements.show', $request->etablissement_id)
                    ->with('success', 'Actionnaires enregistrés avec succès.');
            }

            return redirect()
                ->route('benificiaireeffectif.create', ['etablissement_id' => $request->etablissement_id])
                ->with('success', 'Actionnaires enregistrés avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error storing actionnariat: " . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de l’enregistrement des actionnaires : ' . $e->getMessage())
                ->withInput();
        }
    }


    
    /**
     * Display the specified resource.
     */
    public function show(Actionnariat $Actionnariat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Actionnariat $actionnariat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Etablissement $etablissement)
    {
        $request->validate([
            'noms_rs_actionnaires.*'             => 'required|string|max:255',
            'prenoms_actionnaires.*'           => 'nullable|string|max:255',
            'identite_actionnaires.*'          => 'required|string|max:255',
            'nombre_titres_actionnaires.*'     => 'required|numeric|min:0',
            'pourcentage_capital_actionnaires.*' => 'required|numeric|min:0|max:100',
            'notes_actionnaires.*'             => 'nullable|numeric',
            'percentages_actionnaires.*'       => 'nullable|numeric',
            'tables_actionnaires.*'            => 'nullable|string',
            'match_ids_actionnaires.*'       => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Delete existing
            Actionnariat::where('etablissement_id', $etablissement->id)->delete();

            // Create new
            $noms         = $request->noms_rs_actionnaires ?? [];
            $prenoms      = $request->prenoms_actionnaires ?? [];
            $ids          = $request->identite_actionnaires ?? [];
            $titres       = $request->nombre_titres_actionnaires ?? [];
            $pourcentages = $request->pourcentage_capital_actionnaires ?? [];
            
            $notes        = $request->notes_actionnaires ?? [];
            $percentages  = $request->percentages_actionnaires ?? [];
            $tables       = $request->tables_actionnaires ?? [];
            $match_ids  = $request->match_ids_actionnaires ?? [];

            foreach ($noms as $index => $nom) {
                Actionnariat::create([
                    'etablissement_id'   => $etablissement->id,
                    'nom_rs'             => $nom,
                    'prenom'             => $prenoms[$index] ?? null,
                    'identite'           => $ids[$index] ?? null,
                    'nombre_titres'      => $titres[$index] ?? 0,
                    'pourcentage_capital'=> $pourcentages[$index] ?? 0,
                    'note'               => $notes[$index] ?? 1,
                    'percentage'         => $percentages[$index] ?? 0,
                    'table_match'        => $tables[$index] ?? null,
                    'match_id'         => $match_ids[$index] ?? null,
                ]);
            }

            // Trigger rating update
            $etablissement->updateRiskRating();

            DB::commit();

            return redirect()->back()->with('success', 'Actionnaires mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour des actionnaires : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function bulkDelete(Request $request)
{
    $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'exists:actionnariat,id',
    ]);

    try {
        $firstItem = Actionnariat::find($request->ids[0]);
        $etablissement = $firstItem?->etablissement;

        Actionnariat::whereIn('id', $request->ids)->delete();

        // Trigger rating update
        $etablissement?->updateRiskRating();

        return response()->json([
            'success' => true,
            'message' => 'Actionnaires supprimés avec succès.'
        ]);
    } catch (\Exception $e) {
        Log::error("Bulk delete error (Actionnariat): " . $e->getMessage());
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
            'noms_rs_actionnaires.*' => 'required|string|max:255',
            'prenoms_actionnaires.*' => 'nullable|string|max:255',
            'identite_actionnaires.*' => 'required|string|max:255',
            'nombre_titres_actionnaires.*' => 'required|numeric|min:0',
            'pourcentage_capital_actionnaires.*' => 'required|numeric|min:0|max:100',
        ]);

        $etablissement_id = $request->input('etablissement_id');
        $etablissement = Etablissement::findOrFail($etablissement_id);
        $redirect_to = $request->input('redirect_to');

        $noms = $request->input('noms_rs_actionnaires', []);
        $prenoms = $request->input('prenoms_actionnaires', []);
        $cins = $request->input('identite_actionnaires', []);
        $titres = $request->input('nombre_titres_actionnaires', []);
        $pourcentages = $request->input('pourcentage_capital_actionnaires', []);

        $analyses = [];

        foreach ($noms as $index => $nom) {
            $actionnaire = new Actionnariat();
            $actionnaire->nom_rs = $nom;
            $actionnaire->prenom = $prenoms[$index] ?? null;
            $actionnaire->identite = $cins[$index] ?? null;
            $actionnaire->nombre_titres = $titres[$index] ?? 0;
            $actionnaire->pourcentage_capital = $pourcentages[$index] ?? 0;

            $risk = $actionnaire->checkRisk();
            
            $actionnaire->note = $risk['note'] ?? 1;
            $actionnaire->percentage = $risk['percentage'] ?? 0;
            $actionnaire->table_match = $risk['table'] ?? null;
            $actionnaire->match_id = $risk['match_id'] ?? null;

            $analyses[] = [
                'data' => $actionnaire,
                'risk' => $risk,
            ];
        }

        return view(
            'etablissements.infoetablissement.Actionnariat.check_risque',
            compact('analyses', 'redirect_to', 'etablissement')
        );
    }
}
