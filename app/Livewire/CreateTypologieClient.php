<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Etablissement;
use App\Models\TypologieClient;
use App\Models\Secteurs;
use App\Models\Segments;
use App\Models\Pays;

class CreateTypologieClient extends Component
{
    public $etablissement;
    public $etablissement_id;
    public $redirect_to;

    // Lookups
    public $secteurs;
    public $segments;
    public $pays;

    // Form fields
    public $secteurActivite = '';
    public $segment = '';
    public $activiteEtranger = false;
    public $paysEtranger = '';
    public $publicEpargne = false;
    public $publicEpargne_label = '';

    public function mount($etablissement_id)
    {
        $this->etablissement_id = $etablissement_id;
        $this->etablissement = Etablissement::findOrFail($etablissement_id);
        $this->redirect_to = request()->query('redirect_to');

        $this->secteurs = Secteurs::all();
        $this->segments = Segments::all();
        $this->pays = Pays::all();

        // Load existing data if available
        $typo = $this->etablissement->typologieClient;

        if ($typo) {
            $this->secteurActivite = $typo->secteurActivite ?? '';
            $this->segment = $typo->segment ?? '';
            $this->activiteEtranger = (bool) $typo->activiteEtranger;
            $this->paysEtranger = $typo->paysEtranger ?? '';
            $this->publicEpargne = (bool) $typo->publicEpargne;
            $this->publicEpargne_label = $typo->publicEpargne_label ?? '';
        }
    }

    public function save()
    {
        $this->validate([
            'secteurActivite' => 'required|integer|exists:secteurs,id',
            'segment' => 'required|integer|exists:segments,id',
            'paysEtranger' => 'nullable|required_if:activiteEtranger,true|integer',
            'publicEpargne_label' => 'nullable|required_if:publicEpargne,true|string|max:255',
        ], [
            'secteurActivite.required' => 'Le secteur d\'activité est requis.',
            'segment.required' => 'Le segment est requis.',
            'paysEtranger.required_if' => 'Le pays est requis si l\'activité à l\'étranger est activée.',
            'publicEpargne_label.required_if' => 'Veuillez préciser le marché financier.',
        ]);

        $typo = $this->etablissement->typologieClient
            ?? new TypologieClient(['etablissement_id' => $this->etablissement->id]);

        $typo->secteurActivite = $this->secteurActivite;
        $typo->segment = $this->segment;
        $typo->activiteEtranger = $this->activiteEtranger;
        $typo->paysEtranger = $this->activiteEtranger ? $this->paysEtranger : null;
        $typo->publicEpargne = $this->publicEpargne;
        $typo->publicEpargne_label = $this->publicEpargne ? $this->publicEpargne_label : null;

        $typo->save();

        $this->etablissement->updateRiskRating();

        session()->flash('success', 'Typologie client enregistrée avec succès !');

        if ($this->redirect_to === 'dashboard') {
            return redirect()->route('etablissements.show', $this->etablissement->id);
        }

        return redirect()->route('statutfatca.create', ['etablissement_id' => $this->etablissement->id]);
    }

    public function render()
    {
        return view('livewire.create-typologie-client');
    }
}
