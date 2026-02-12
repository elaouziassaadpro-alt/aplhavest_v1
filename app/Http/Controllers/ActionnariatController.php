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
        $actionnariats = Actionnariat::all();
        return view("etablissements.infoetablissement.Actionnariat.index", compact("actionnariats"));
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

        ]);
        
        DB::beginTransaction();

        try {

            $noms   = $request->noms_rs_actionnaires;
            $prenoms = $request->prenoms_actionnaires;
            $ids    = $request->identite_actionnaires;
            $titres = $request->nombre_titres_actionnaires;
            $pourcentages = $request->pourcentage_capital_actionnaires;

            foreach ($noms as $index => $nom) {

                Actionnariat::create([
                    'etablissement_id'   => $request->etablissement_id,
                    'nom_rs'  => $nom,
                    'prenom'              => $prenoms[$index] ?? null,
                    'identite'            => $ids[$index],
                    'nombre_titres'       => $titres[$index],
                    'pourcentage_capital' => $pourcentages[$index],
                ]);
            }

            $etablissement = Etablissement::findOrFail($request->etablissement_id);

            // Trigger rating update
            $etablissement->updateRiskRating();
            if ($request->redirect_to === 'dashboard') {
                return redirect()
                    ->route('etablissements.show', $request->etablissement_id)
                    ->with('success', 'Actionnaires enregistrés avec succès.');
            }
            if ($etablissement->fresh()->isCompleted()) {
                return redirect()->route('Rating', ['etablissement_id' => $etablissement->id]);
            }

            DB::commit();

        

        return redirect()
            ->route('benificiaireeffectif.create', ['etablissement_id' => $request->etablissement_id])
            ->with('success', 'Actionnaires enregistrés avec succès.');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Erreur lors de l’enregistrement des actionnaires.')
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
            'noms_rs_actionnaires.*' => 'required|string|max:255',
            'prenoms_actionnaires.*' => 'nullable|string|max:255',
            'identite_actionnaires.*' => 'required|string|max:255',
            'nombre_titres_actionnaires.*' => 'required|numeric|min:0',
            'pourcentage_capital_actionnaires.*' => 'required|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Delete existing
            Actionnariat::where('etablissement_id', $etablissement->id)->delete();

            // Create new
            $noms   = $request->noms_rs_actionnaires ?? [];
            $prenoms = $request->prenoms_actionnaires;
            $ids    = $request->identite_actionnaires;
            $titres = $request->nombre_titres_actionnaires;
            $pourcentages = $request->pourcentage_capital_actionnaires;

            foreach ($noms as $index => $nom) {
                Actionnariat::create([
                    'etablissement_id'   => $etablissement->id,
                    'nom_rs'  => $nom,
                    'prenom'              => $prenoms[$index] ?? null,
                    'identite'            => $ids[$index],
                    'nombre_titres'       => $titres[$index],
                    'pourcentage_capital' => $pourcentages[$index],
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
        Actionnariat::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Actionnaires supprimés avec succès.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
        ], 500);
    }
}


}
