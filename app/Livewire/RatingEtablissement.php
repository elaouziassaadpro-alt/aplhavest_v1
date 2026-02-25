<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Etablissement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RatingEtablissement extends Component
{
    public $etablissement;
    public $facteursCalcul = [];
    public $isCompleted = true;

    public function mount($etablissement_id)
    {
        $this->etablissement = Etablissement::with(
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
            'PersonnesHabilites.lienPpeRelation',
            'objetRelation',
            'Actionnaire'
        )->findOrFail($etablissement_id);

        /** Vérification complétion */
        if (!method_exists($this->etablissement, 'isCompleted') || !$this->etablissement->isCompleted()) {
            $this->etablissement->update([
                'risque' => '-',
                'note'   => 0,
            ]);
            $this->isCompleted = false;
            session()->flash('error', 'Etablissement non complété');
            return;
        }

        // Use the centralized method to calculate all factors
        $this->facteursCalcul = $this->etablissement->updateRiskRating();

        if ($this->etablissement->risque == 0 || $this->etablissement->note == 0) {
            $this->isCompleted = false;
            session()->flash('error', 'Etablissement non complété');
            return;
        }

        // Update the establishment's note and risque based on calculated data
        $this->etablissement->update([
            'risque' => $this->facteursCalcul['global']['scoring'],
            'note'   => $this->facteursCalcul['global']['note_totale'],
        ]);
    }

    public function validateEtablissement()
    {
        $updateData = [];

        if (Auth::user()->isCI()) {
            $updateData['validation_CI'] = 1;
            $updateData['validation_CI_date'] = now();
            $updateData['validation_CI_by'] = Auth::id();
        } else {
            $updateData['validation_AK'] = 1;
            $updateData['validation_AK_date'] = now();
            $updateData['validation_AK_by'] = Auth::id();
        }

        $this->etablissement->update($updateData);
        session()->flash('success', 'Etablissement validé avec succès.');
    }

    public function rejectEtablissement()
    {
        $this->etablissement->update(['validation_AK' => 0]);
        session()->flash('success', 'Etablissement rejeté avec succès.');
    }

    public function render()
    {
        return view('livewire.rating-etablissement');
    }
}
