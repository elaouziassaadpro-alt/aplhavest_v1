<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Etablissement;
use App\Models\SituationFinanciere;
use App\Models\Pays;
use Illuminate\Support\Facades\Storage;

class CreateSituationFinanciere extends Component
{
    use WithFileUploads;

    public $etablissement;
    public $etablissement_id;
    public $redirect_to;
    public $pays;

    // Form fields
    public $capitalSocial = '0';
    public $origineFonds = '';
    public $paysOrigineFonds = '';
    public $chiffreAffaires = '';
    public $resultatsNET = '0';
    public $holding = '';
    public $etat_synthese;

    public function mount($etablissement_id)
    {
        $this->etablissement_id = $etablissement_id;
        $this->etablissement = Etablissement::findOrFail($etablissement_id);
        $this->redirect_to = request()->query('redirect_to');
        $this->pays = Pays::all();

        // Load existing data
        $sf = $this->etablissement->situationFinanciere;

        if ($sf) {
            $this->capitalSocial = $sf->capitalSocial ?? '0';
            $this->origineFonds = $sf->origineFonds ?? '';
            $this->paysOrigineFonds = $sf->paysOrigineFonds ?? '';
            $this->chiffreAffaires = $sf->chiffreAffaires ?? '';
            $this->resultatsNET = $sf->resultatsNET ?? '0';
            $this->holding = $sf->holding !== null ? (string) $sf->holding : '';
        }
    }

    private function cleanNumber($value)
    {
        if ($value === null || $value === '') return null;
        $cleaned = preg_replace('/[^0-9]/', '', $value);
        return $cleaned !== '' ? (int) $cleaned : null;
    }

    public function save()
    {
        $this->validate([
            'capitalSocial' => 'nullable|numeric',
            'origineFonds' => 'nullable|string|max:300',
            'paysOrigineFonds' => 'nullable|exists:pays,id',
            'chiffreAffaires' => 'nullable|string',
            'resultatsNET' => 'nullable|numeric',
            'holding' => 'nullable|in:0,1',
            'etat_synthese' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048',
        ]);

        $sf = $this->etablissement->situationFinanciere
            ?? new SituationFinanciere(['etablissement_id' => $this->etablissement->id]);

        $sf->capitalSocial = $this->cleanNumber($this->capitalSocial);
        $sf->origineFonds = $this->origineFonds;
        $sf->paysOrigineFonds = !empty($this->paysOrigineFonds) ? $this->paysOrigineFonds : null;
        $sf->chiffreAffaires = !empty($this->chiffreAffaires) ? $this->chiffreAffaires : null;
        $sf->resultatsNET = $this->cleanNumber($this->resultatsNET);
        $sf->holding = $this->holding !== '' ? (int) $this->holding : null;

        // File upload
        if ($this->etat_synthese) {
            if ($sf->etat_synthese) {
                Storage::disk('public')->delete($sf->etat_synthese);
            }
            $sf->etat_synthese = $this->etat_synthese->store('uploads/etat_synthese', 'public');
        }

        $sf->save();

        session()->flash('success', 'Situation financière enregistrée avec succès.');

        if ($this->redirect_to === 'dashboard') {
            return redirect()->route('etablissements.show', $this->etablissement->id);
        }

        return redirect()->route('actionnariat.create', ['etablissement_id' => $this->etablissement->id]);
    }

    public function render()
    {
        return view('livewire.create-situation-financiere');
    }
}
