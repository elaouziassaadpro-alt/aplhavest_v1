<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Actionnariat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ActionnariatController extends Controller
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
        return view("etablissements.infoetablissement.Actionnariat.create", compact('info_generales_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // ✅ Validation
        $request->validate([
            'info_generales_id' => 'required|exists:info_generales,id',

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
                    'info_generales_id'   => $request->info_generales_id,
                    'nom_rs'  => $nom,
                    'prenom'              => $prenoms[$index] ?? null,
                    'identite'            => $ids[$index],
                    'nombre_titres'       => $titres[$index],
                    'pourcentage_capital' => $pourcentages[$index],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('benificiaireeffectif.create',['info_generales_id' => $request->info_generales_id])
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
    public function update(Request $request, Actionnariat $actionnariat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Actionnariat $actionnariat)
    {
        //
    }
}
