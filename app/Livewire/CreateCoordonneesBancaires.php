<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Etablissement;
use App\Models\Banque;
use App\Models\Ville;
use App\Models\CoordonneesBancaires;
use Illuminate\Support\Facades\DB;

class CreateCoordonneesBancaires extends Component
{
    public $etablissement;
    public $etablissement_id;
    public $rows = [];
    public $banques;
    public $villes;

    public function mount($etablissement_id)
    {
        $this->etablissement_id = $etablissement_id;
        $this->etablissement = Etablissement::findOrFail($etablissement_id);
        $this->banques = Banque::all();
        $this->villes = Ville::all();

        // Initialize with one empty row
        $this->rows = [
            ['banque_id' => '', 'ville_id' => '', 'agences_banque' => '', 'rib_banque' => '']
        ];
    }

    public function addRow()
    {
        if (count($this->rows) < 5) {
            $this->rows[] = ['banque_id' => '', 'ville_id' => '', 'agences_banque' => '', 'rib_banque' => ''];
        }
    }

    public function removeRow($index)
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows);
    }

    public function save($redirectTo = 'next')
    {
        $this->validate([
            'rows.*.banque_id' => 'required|exists:banques,id',
            'rows.*.ville_id' => 'required|exists:villes,id',
            'rows.*.rib_banque' => 'required|string|max:50',
            'rows.*.agences_banque' => 'nullable|string|max:255',
        ], [
            'rows.*.banque_id.required' => 'La banque est obligatoire.',
            'rows.*.ville_id.required' => 'La ville est obligatoire.',
            'rows.*.rib_banque.required' => 'Le RIB est obligatoire.',
        ]);

        DB::transaction(function () {
            foreach ($this->rows as $row) {
                CoordonneesBancaires::create([
                    'etablissement_id' => $this->etablissement->id,
                    'banque_id' => $row['banque_id'],
                    'ville_id' => $row['ville_id'],
                    'agences_banque' => $row['agences_banque'],
                    'rib_banque' => $row['rib_banque'],
                ]);
            }
            
            // Trigger rating update
            $this->etablissement->updateRiskRating();
        });

        session()->flash('success', 'Coordonnées bancaires enregistrées avec succès.');

        if ($redirectTo === 'dashboard') {
            return redirect()->route('etablissements.show', $this->etablissement->id);
        }

        return redirect()->route('typologie.create', ['etablissement_id' => $this->etablissement->id]);
    }

    public function render()
    {
        return view('livewire.create-coordonnees-bancaires');
    }
}
