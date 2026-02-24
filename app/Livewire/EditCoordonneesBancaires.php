<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Etablissement;
use App\Models\CoordonneesBancaires;
use App\Models\Banque;
use App\Models\Ville;

class EditCoordonneesBancaires extends Component
{
    public $etablissement_id;
    public $etablissement;
    public $editing = false;

    public $comptes = [];
    public $banques = [];
    public $villes = [];

    public function mount($etablissement_id)
    {
        $this->etablissement_id = $etablissement_id;
        $this->etablissement = Etablissement::with('coordonneesBancaires.banque', 'coordonneesBancaires.ville')->findOrFail($etablissement_id);
        $this->banques = Banque::all();
        $this->villes = Ville::all();

        $this->loadComptes();
    }

    public function loadComptes()
    {
        $this->comptes = [];
        if ($this->etablissement->coordonneesBancaires) {
            foreach ($this->etablissement->coordonneesBancaires as $cb) {
                $this->comptes[] = [
                    'id' => $cb->id,
                    'banque_id' => $cb->banque_id,
                    'agences_banque' => $cb->agences_banque,
                    'ville_id' => $cb->ville_id,
                    'rib_banque' => $cb->rib_banque,
                ];
            }
        }
    }

    public function toggleEdit()
    {
        $this->editing = !$this->editing;
    }

    public function removeCompte($index)
    {
        if (isset($this->comptes[$index]['id'])) {
            CoordonneesBancaires::find($this->comptes[$index]['id'])?->delete();
        }
        unset($this->comptes[$index]);
        $this->comptes = array_values($this->comptes);
    }

    public function update()
    {
        foreach ($this->comptes as $data) {
            if (!empty($data['id'])) {
                CoordonneesBancaires::where('id', $data['id'])->update([
                    'banque_id' => $data['banque_id'],
                    'agences_banque' => $data['agences_banque'],
                    'ville_id' => $data['ville_id'],
                    'rib_banque' => $data['rib_banque'],
                ]);
            }
        }

        $this->etablissement->load('coordonneesBancaires');
        $this->loadComptes();
        $this->editing = false;
        session()->flash('message', 'Coordonnées bancaires mises à jour avec succès.');
    }

    public function render()
    {
        return view('livewire.edit-coordonnees-bancaires');
    }
}
