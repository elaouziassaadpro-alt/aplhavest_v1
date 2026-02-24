<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Etablissement;

class EditStatutFatca extends Component
{
    use WithFileUploads;

    public $etablissement_id;
    public $etablissement;
    public $editing = false;

    public $usEntity = false;
    public $fichier_usEntity;
    public $existing_fichier_usEntity = '';
    public $giin = false;
    public $giin_label = '';
    public $giin_label_Autres = '';

    public function mount($etablissement_id)
    {
        $this->etablissement_id = $etablissement_id;
        $this->etablissement = Etablissement::with('statutFatca')->findOrFail($etablissement_id);

        $sf = $this->etablissement->statutFatca;
        if ($sf) {
            $this->usEntity = (bool) $sf->usEntity;
            $this->existing_fichier_usEntity = $sf->fichier_usEntity ?? '';
            $this->giin = (bool) $sf->giin;
            $this->giin_label = $sf->giin_label ?? '';
            $this->giin_label_Autres = $sf->giin_label_Autres ?? '';
        }
    }

    public function toggleEdit()
    {
        $this->editing = !$this->editing;
    }

    public function update()
    {
        $data = [
            'usEntity' => $this->usEntity,
            'giin' => $this->giin,
            'giin_label' => $this->giin ? $this->giin_label : null,
            'giin_label_Autres' => $this->giin_label_Autres,
        ];

        if ($this->fichier_usEntity) {
            if ($this->existing_fichier_usEntity) {
                \Storage::disk('public')->delete($this->existing_fichier_usEntity);
            }
            $data['fichier_usEntity'] = $this->fichier_usEntity->store('statut_fatca', 'public');
            $this->existing_fichier_usEntity = $data['fichier_usEntity'];
        }

        $this->etablissement->statutFatca()->updateOrCreate(
            ['etablissement_id' => $this->etablissement_id],
            $data
        );

        $this->fichier_usEntity = null;
        $this->editing = false;
        session()->flash('message', 'Statut FATCA mis à jour avec succès.');
    }

    public function render()
    {
        return view('livewire.edit-statut-fatca');
    }
}
