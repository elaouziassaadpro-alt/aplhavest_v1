<?php

namespace App\Http\Controllers;

use App\Models\Etablissement;
use App\Traits\NiveauRisqueTrait;
use Illuminate\Http\Request;
use App\Models\ANRF;
use App\Models\CNASNU;
class RatingEtablissementController extends Controller
{
    public function Rating(Request $request)
    {
        $etablissementId = $request->get('etablissement_id');
        $etablissement = Etablissement::with(
            'infoGenerales.paysActiviteInfo',
            'typologieClient.secteur',
            'typologieClient.segment_get',
            'typologieClient.paysEtrangerInfo',
            'infoGenerales.paysresidence',
            'SituationFinanciere',
            'BeneficiaireEffectif.ppeRelation',
            'BeneficiaireEffectif.lienPpeRelation',
            'Administrateur.ppeRelation',
            'Administrateur.lienPpeRelation',
            'PersonnesHabilites.ppeRelation',
            'PersonnesHabilites.lienPpeRelation',)->findOrFail($etablissementId);

        /** 🔒 Vérification complétion */
        if (!method_exists($etablissement, 'isCompleted') || !$etablissement->isCompleted()) {
            $etablissement->update([
            'risque' => '-',
            'note'   => 0,
        ]);
            return back()->with('error', 'Etablissement non complété');
        }

        // Use the centralized method to calculate all factors
        $facteursCalcul = $etablissement->updateRiskRating();
        if ($etablissement->risque == 0 || $etablissement->note == 0) {
            return redirect()->back()->with('error', 'Etablissement non complété');
        }

        // Update the establishment's note and risque based on calculated data
        $etablissement->update([
            'risque' => $facteursCalcul['global']['scoring'],
            'note'   => $facteursCalcul['global']['note_totale'],
        ]);

        /* ================= VIEW ================= */
        return view('rating', [
            'etablissement'  => $etablissement,
            'facteursCalcul' => $facteursCalcul,
        ]);
    }
}
