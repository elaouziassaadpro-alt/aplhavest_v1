<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Etablissement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        
        $updateData = [];
        if (Auth::user()->isCI() || Auth::user()->isAdmin()) {
            $updateData['validation_CI'] = 1;
            $updateData['validation_CI_date'] = now();
            $updateData['validation_CI_by'] = Auth::id();
        } elseif (Auth::user()->isAK()) {
            $updateData = [
                'validation_AK' => 1,
                'validation_AK_by' => Auth::id(),
                'validation_AK_date' => now(),
            ];
        } else {
            session()->flash('error', 'Vous n\'avez pas les permissions nécessaires pour valider.');
            return;
        }

        Etablissement::whereIn('id', $this->selected)
            ->update($updateData);

        $this->selected = [];
        $this->selectAll = false;
        session()->flash('message', 'Les établissements sélectionnés ont été validés.');
    }

    public function rejectSelection()
    {
          if (empty($this->selected)) return;

        $updateData = [];
        if (Auth::user()->isCI() || Auth::user()->isAdmin()) {
            $updateData['validation_CI'] = 0;
            $updateData['validation_CI_date'] = now();
            $updateData['validation_CI_by'] = Auth::id();
        } elseif (Auth::user()->isAK()) {
            $updateData = [
                'validation_AK' => 0,
                'validation_AK_by' => Auth::id(),
                'validation_AK_date' => now(),
            ];
        } else {
            session()->flash('error', 'Vous n\'avez pas les permissions nécessaires pour rejeter.');
            return;
        }
        Etablissement::whereIn('id', $this->selected)
            ->update($updateData);
        $this->selected = [];
        $this->selectAll = false;
        session()->flash('message', 'Les établissements sélectionnés ont été rejetés.');
    }

    public function deleteSelection()
    {
        if (empty($this->selected)) return;

        $validated = Etablissement::whereIn('id', $this->selected)
            ->where('validation_AK', 1)
            ->exists();
        $rejected = Etablissement::whereIn('id', $this->selected)
            ->where('validation_AK', 0)
            ->exists();

        if ($validated) {
            session()->flash('error', 'Impossible de supprimer des établissements validés.');
            return;
        }
        if ($rejected) {
            session()->flash('error', 'Impossible de supprimer des établissements rejetés.');
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
