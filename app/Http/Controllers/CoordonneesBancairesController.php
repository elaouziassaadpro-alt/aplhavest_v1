<?php

namespace App\Http\Controllers;

use App\Models\Etablissement;
use Illuminate\Http\Request;
use App\Models\Banque;
use App\Models\Ville;

use App\Models\CoordonneesBancaires;
use App\Models\InfoGeneral;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class CoordonneesBancairesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coordonneesbancaires = CoordonneesBancaires::all();
    return view(
            'etablissements.infoetablissement.CoordonneesBancaires.index',
            compact('coordonneesbancaires')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $banques = Banque::all();
        $villes  = Ville::all();
        $etablissement_id = $request->etablissement_id;
        $etablissement = Etablissement::find($etablissement_id);
        return view(
            'etablissements.infoetablissement.CoordonneesBancaires.create',
            compact('banques', 'villes', 'etablissement')
        );
    }
    public function store(Request $request)
    {
        $request->validate([
            'etablissement_id' => ['required', 'exists:etablissements,id'],
            'banque_id' => ['required', 'array'],
            'banque_id.*' => ['required', 'integer', 'exists:banques,id'],
            'ville_id' => ['required', 'array'],
            'ville_id.*' => ['required', 'integer', 'exists:villes,id'],
            'agences_banque' => ['nullable', 'array'],
            'agences_banque.*' => ['nullable', 'string', 'max:255'],
            'rib_banque' => ['required', 'array'],
            'rib_banque.*' => ['required', 'string', 'max:50'],
        ]);

        $etablissement = Etablissement::findOrFail($request->etablissement_id);

        for ($i = 0; $i < count($request->banque_id); $i++) {
            CoordonneesBancaires::create([
                'etablissement_id' => $etablissement->id,
                'banque_id'         => $request->banque_id[$i],
                'ville_id'          => $request->ville_id[$i],
                'agences_banque'    => $request->agences_banque[$i] ?? null,
                'rib_banque'        => $request->rib_banque[$i],
            ]);
        }

        // Trigger rating update
        $etablissement->updateRiskRating();

        if ($request->redirect_to === 'dashboard') {
        return redirect()->route('etablissements.show', $request->etablissement_id)
            ->with('success', 'Coordonnées bancaires enregistrées avec succès.');
    }
        if ($etablissement->fresh()->isCompleted()) {
            return redirect()->route('Rating', ['etablissement_id' => $etablissement->id]);
        }
        

        return redirect()->route('typologie.create', ['etablissement_id' => $etablissement->id])
            ->with('success', 'Coordonnées bancaires enregistrées avec succès.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Etablissement $etablissement)
    {
        $request->validate([
            'banque_id.*' => 'required|exists:banques,id',
            'agences_banque.*' => 'nullable|string|max:255',
            'ville_id.*' => 'required|exists:villes,id',
            'rib_banque.*' => 'required|string|max:50',
        ]);

        $banque_ids = $request->input('banque_id', []);
        $agences = $request->input('agences_banque', []);
        $villes = $request->input('ville_id', []);
        $ribs = $request->input('rib_banque', []);

        DB::transaction(function () use ($etablissement, $banque_ids, $agences, $villes, $ribs) {
            $etablissement->coordonneesBancaires()->delete();
            foreach ($banque_ids as $i => $banque_id) {
                if (!empty($banque_id) && !empty($villes[$i]) && !empty($ribs[$i])) {
                    $etablissement->coordonneesBancaires()->create([
                        'banque_id' => $banque_id,
                        'agences_banque' => $agences[$i] ?? null,
                        'ville_id' => $villes[$i],
                        'rib_banque' => $ribs[$i],
                    ]);
                }
            }
        });

        // Trigger rating update
        $etablissement->updateRiskRating();

        return redirect()->back()->with('success', 'Coordonnées bancaires mises à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CoordonneesBancaires $coordonneesBancaires)
    {
        try {
            $etablissement = $coordonneesBancaires->Etablissement;
            $coordonneesBancaires->delete();

            // Trigger rating update
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
            $ids = $request->input('ids');
            if (empty($ids)) {
                return response()->json(['success' => false, 'message' => 'Aucune sélection.']);
            }

            $firstItem = CoordonneesBancaires::find($ids[0]);
            $etablissement = $firstItem?->Etablissement;

            CoordonneesBancaires::whereIn('id', $ids)->delete();

            // Trigger rating update
            $etablissement?->updateRiskRating();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression groupée']);
        }
    }
}
