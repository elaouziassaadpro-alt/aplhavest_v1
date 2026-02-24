<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Etablissement;
use App\Models\Secteurs;
use App\Models\Segments;
use App\Models\Pays;

class EditTypologieClient extends Component
{
    public $etablissement_id;
    public $etablissement;
    public $editing = false;

    public $secteurActivite = '';
    public $segment = '';
    public $activiteEtranger = false;
    public $paysEtranger = '';
    public $publicEpargne = false;
    public $publicEpargne_label = '';

    public $secteurs = [];
    public $segments_list = [];
    public $pays = [];

    public function mount($etablissement_id)
    {
        $this->etablissement_id = $etablissement_id;
        $this->etablissement = Etablissement::with('typologieClient')->findOrFail($etablissement_id);
        $this->secteurs = Secteurs::all();
        $this->segments_list = Segments::all();
        $this->pays = Pays::all();

        $tc = $this->etablissement->typologieClient;
        if ($tc) {
            $this->secteurActivite = $tc->secteurActivite ?? '';
            $this->segment = $tc->segment ?? '';
            $this->activiteEtranger = (bool) $tc->activiteEtranger;
            $this->paysEtranger = $tc->paysEtranger ?? '';
            $this->publicEpargne = (bool) $tc->publicEpargne;
            $this->publicEpargne_label = $tc->publicEpargne_label ?? '';
        }
    }

    public function toggleEdit()
    {
        $this->editing = !$this->editing;
    }

    public function update()
    {
        $this->etablissement->typologieClient()->updateOrCreate(
            ['etablissement_id' => $this->etablissement_id],
            [
                'secteurActivite' => $this->secteurActivite,
                'segment' => $this->segment,
                'activiteEtranger' => $this->activiteEtranger,
                'paysEtranger' => $this->activiteEtranger ? $this->paysEtranger : null,
                'publicEpargne' => $this->publicEpargne,
                'publicEpargne_label' => $this->publicEpargne ? $this->publicEpargne_label : null,
            ]
        );

        $this->editing = false;
        session()->flash('message', 'Typologie client mise à jour avec succès.');
    }

    public function render()
    {
        return view('livewire.edit-typologie-client');
    }
}
