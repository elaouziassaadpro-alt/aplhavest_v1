<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banque;
use App\Models\Ville;

use App\Models\CoordonneesBancaires;
use Illuminate\Support\Facades\Redirect;
class CoordonneesBancairesController extends Controller
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
        $banques = Banque::all();
        $villes  = Ville::all();
        $info_generales_id = $request->info_generales_id;

        return view(
            'etablissements.infoetablissement.CoordonneesBancaires.create',
            compact('banques', 'villes', 'info_generales_id')
        );
    }
public function store(Request $request)
{
    $request->validate([
    'info_generales_id' => ['required', 'exists:info_generales,id'],

    'banque_id' => ['required', 'array'],
    'banque_id.*' => ['required', 'integer', 'exists:banques,id'],

    'ville_id' => ['required', 'array'],
    'ville_id.*' => ['required', 'integer', 'exists:villes,id'],

    'agences_banque' => ['nullable', 'array'],
    'agences_banque.*' => ['nullable', 'string', 'max:255'],

    'rib_banque' => ['required', 'array'],
    'rib_banque.*' => ['required', 'string', 'max:50'],
]);

dump($request->all()); // Will output the request in the page without stopping execution

            // // ✅ Loop through each row and save
            for ($i=0; $i < count($request->banque_id); $i++) {
                CoordonneesBancaires::create([
                    'info_generales_id' => $request->info_generales_id,
                    'banque_id'         => $request->banque_id[$i],
                    'ville_id'          => $request->ville_id[$i],
                    'agences_banque'    => $request->agences_banque[$i] ?? null,
                    'rib_banque'        => $request->rib_banque[$i],
                ]);
            }

            return redirect()->route('typologie.create', ['info_generales_id' => $request->info_generales_id])->with('success', 'Coordonnées bancaires enregistrées avec succès.');

       }



    /**
     * Display the specified resource.
     */
    public function show(CoordonneesBancaires $coordonneesBancaires)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CoordonneesBancaires $coordonneesBancaires)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CoordonneesBancaires $coordonneesBancaires)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CoordonneesBancaires $coordonneesBancaires)
    {
        //
    }
}
