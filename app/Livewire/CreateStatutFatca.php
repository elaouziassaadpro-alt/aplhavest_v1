<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Etablissement;
use App\Models\StatutFatca;
use Illuminate\Support\Facades\Storage;

class CreateStatutFatca extends Component
{
    use WithFileUploads;

    public $etablissement;
    public $etablissement_id;
    public $redirect_to;

    // Form fields
    public $usEntity = false;
    public $fichier_usEntity;
    public $giin = false;
    public $giin_label = '';
    public $giin_label_Autres = '';

    public function mount($etablissement_id)
    {
        $this->etablissement_id = $etablissement_id;
        $this->etablissement = Etablissement::findOrFail($etablissement_id);
        $this->redirect_to = request()->query('redirect_to');

        // Load existing data
        $fatca = $this->etablissement->statutFatca;

        if ($fatca) {
            $this->usEntity = (bool) $fatca->usEntity;
            $this->giin = (bool) $fatca->giin;
            $this->giin_label = $fatca->giin_label ?? '';
            $this->giin_label_Autres = $fatca->giin_label_Autres ?? '';
        }
    }

    public function save()
    {
        $rules = [
            'fichier_usEntity' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

        if ($this->giin) {
            $rules['giin_label'] = 'required|string|max:255';
        } else {
            $rules['giin_label_Autres'] = 'nullable|string|max:255';
        }

        $this->validate($rules, [
            'giin_label.required' => 'Le numéro GIIN est requis.',
        ]);

        $fatca = $this->etablissement->statutFatca
            ?? new StatutFatca(['etablissement_id' => $this->etablissement->id]);

        $fatca->usEntity = $this->usEntity;
        $fatca->giin = $this->giin;

        // GIIN logic
        if ($this->giin) {
            $fatca->giin_label = $this->giin_label;
            $fatca->giin_label_Autres = null;
        } else {
            $fatca->giin_label = null;
            $fatca->giin_label_Autres = $this->giin_label_Autres;
        }

        // File logic
        if ($this->usEntity) {
            if ($this->fichier_usEntity) {
                // Delete old file if exists
                if ($fatca->fichier_usEntity) {
                    Storage::disk('public')->delete($fatca->fichier_usEntity);
                }
                $fatca->fichier_usEntity = $this->fichier_usEntity->store('uploads/fatca', 'public');
            }
        } else {
            // Remove file if unchecked
            if ($fatca->fichier_usEntity) {
                Storage::disk('public')->delete($fatca->fichier_usEntity);
            }
            $fatca->fichier_usEntity = null;
        }

        $fatca->save();

        session()->flash('success', 'Statut FATCA enregistré avec succès !');

        if ($this->redirect_to === 'dashboard') {
            return redirect()->route('etablissements.show', $this->etablissement->id);
        }

        return redirect()->route('situationfinanciere.create', ['etablissement_id' => $this->etablissement->id]);
    }

    public function render()
    {
        return view('livewire.create-statut-fatca');
    }
}
