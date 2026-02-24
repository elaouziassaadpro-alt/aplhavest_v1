<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CoordonneesBancaires;

class CoordonneesBancairesIndex extends Component
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

        CoordonneesBancaires::whereIn('id', $this->selected)->delete();

        $this->selected = [];
        $this->selectAll = false;
        session()->flash('message', 'Les coordonnées bancaires sélectionnées ont été supprimées.');
    }

    private function getItems()
    {
        return CoordonneesBancaires::with('banque', 'ville')
            ->when($this->search, function ($query) {
                $query->whereHas('banque', function ($q) {
                    $q->where('nom', 'like', '%' . $this->search . '%');
                })
                ->orWhere('rib_banque', 'like', '%' . $this->search . '%')
                ->orWhereHas('ville', function ($q) {
                    $q->where('libelle', 'like', '%' . $this->search . '%');
                });
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.coordonnees-bancaires-index', [
            'coordonneesbancaires' => $this->getItems(),
        ]);
    }
}
