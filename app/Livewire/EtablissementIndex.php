<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Etablissement;

class EtablissementIndex extends Component
{
    public $search = '';
    public $selected = [];
    public $selectAll = false;

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->getEtablissements()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function updatedSelected()
    {
        $this->selectAll = false;
    }

    public function validateSelection()
    {
        if (empty($this->selected)) return;

        Etablissement::whereIn('id', $this->selected)
            ->update(['validation' => 1]);

        $this->selected = [];
        $this->selectAll = false;
        session()->flash('message', 'Les établissements sélectionnés ont été validés.');
    }

    public function rejectSelection()
    {
        if (empty($this->selected)) return;

        Etablissement::whereIn('id', $this->selected)
            ->update(['validation' => 0]);

        $this->selected = [];
        $this->selectAll = false;
        session()->flash('message', 'Les établissements sélectionnés ont été rejetés.');
    }

    public function deleteSelection()
    {
        if (empty($this->selected)) return;

        $validated = Etablissement::whereIn('id', $this->selected)
            ->where('validation', 'valide')
            ->exists();

        if ($validated) {
            session()->flash('error', 'Impossible de supprimer des établissements validés.');
            return;
        }

        Etablissement::whereIn('id', $this->selected)->delete();

        $this->selected = [];
        $this->selectAll = false;
        session()->flash('message', 'Les établissements sélectionnés ont été supprimés.');
    }

    private function getEtablissements()
    {
        return Etablissement::with('typologieClient.secteur', 'infoGenerales.paysresidence')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.etablissement-index', [
            'etablissements' => $this->getEtablissements(),
        ]);
    }
}
