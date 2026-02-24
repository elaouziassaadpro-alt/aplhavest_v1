<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Etablissement;
use App\Models\Pays;

class EditSituationFinanciere extends Component
{
    use WithFileUploads;

    public $etablissement_id;
    public $etablissement;
    public $editing = false;

    public $capitalSocial = '';
    public $origineFonds = '';
    public $paysOrigineFonds = '';
    public $chiffreAffaires = '';
    public $resultatsNET = '';
    public $holding = 0;
    public $etat_synthese;
    public $existing_etat_synthese = '';

    public $pays = [];

    public function mount($etablissement_id)
    {
        $this->etablissement_id = $etablissement_id;
        $this->etablissement = Etablissement::with('SituationFinanciere')->findOrFail($etablissement_id);
        $this->pays = Pays::all();

        $sf = $this->etablissement->SituationFinanciere;
        if ($sf) {
            $this->capitalSocial = $sf->capitalSocial ?? '';
            $this->origineFonds = $sf->origineFonds ?? '';
            $this->paysOrigineFonds = $sf->paysOrigineFonds ?? '';
            $this->chiffreAffaires = $sf->chiffreAffaires ?? '';
            $this->resultatsNET = $sf->resultatsNET ?? '';
            $this->holding = $sf->holding ?? 0;
            $this->existing_etat_synthese = $sf->etat_synthese ?? '';
        }
    }

    public function toggleEdit()
    {
        $this->editing = !$this->editing;
    }

    public function update()
    {
        $cleanNumber = function ($value) {
            if ($value === null || $value === '') return null;
            $cleaned = preg_replace('/[^0-9]/', '', $value);
            return $cleaned !== '' ? (int) $cleaned : null;
        };

        $data = [
            'capitalSocial' => $cleanNumber($this->capitalSocial),
            'origineFonds' => $this->origineFonds,
            'paysOrigineFonds' => !empty($this->paysOrigineFonds) ? $this->paysOrigineFonds : null,
            'chiffreAffaires' => !empty($this->chiffreAffaires) ? $this->chiffreAffaires : null,
            'resultatsNET' => $cleanNumber($this->resultatsNET),
            'holding' => $this->holding !== '' ? (int) $this->holding : null,
        ];

        if ($this->etat_synthese) {
            if ($this->existing_etat_synthese) {
                \Storage::disk('public')->delete($this->existing_etat_synthese);
            }
            $data['etat_synthese'] = $this->etat_synthese->store('situation_financiere', 'public');
            $this->existing_etat_synthese = $data['etat_synthese'];
        }

        $this->etablissement->SituationFinanciere()->updateOrCreate(
            ['etablissement_id' => $this->etablissement_id],
            $data
        );

        $this->etat_synthese = null;
        $this->editing = false;
        session()->flash('message', 'Situation financière mise à jour avec succès.');
    }

    public function render()
    {
        return view('livewire.edit-situation-financiere');
    }
}
