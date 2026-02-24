<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BenificiaireEffectif;

class BenificiaireEffectifIndex extends Component
{
    public $search = '';
    public $selected = [];
    public $selectAll = false;

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->getItems()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function updatedSelected()
    {
        $this->selectAll = false;
    }

    public function deleteSelection()
    {
        if (empty($this->selected)) return;

        BenificiaireEffectif::whereIn('id', $this->selected)->delete();

        $this->selected = [];
        $this->selectAll = false;
        session()->flash('message', 'Les bénéficiaires sélectionnés ont été supprimés.');
    }

    private function getItems()
    {
        return BenificiaireEffectif::with('paysNaissance', 'nationalite')
            ->when($this->search, function ($query) {
                $query->where('nom_rs', 'like', '%' . $this->search . '%')
                      ->orWhere('prenom', 'like', '%' . $this->search . '%');
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.benificiaire-effectif-index', [
            'beneficiaires' => $this->getItems(),
        ]);
    }
}
