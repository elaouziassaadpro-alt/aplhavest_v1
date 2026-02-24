<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Etablissement;

class EditProfilRisque extends Component
{
    public $etablissement_id;
    public $etablissement;
    public $editing = false;

    public $departement_en_charge_check = false;
    public $departement_gestion_input = '';
    public $instruments_souhaites = [];
    public $instruments_souhaites_autres = '';
    public $niveau_risque_tolere_radio = '';
    public $annees_investissement_produits_finaniers = '';
    public $transactions_courant_2_annees = '';

    public function mount($etablissement_id)
    {
        $this->etablissement_id = $etablissement_id;
        $this->etablissement = Etablissement::with('ProfilRisque')->findOrFail($etablissement_id);

        $pr = $this->etablissement->ProfilRisque;
        if ($pr) {
            $this->departement_en_charge_check = (bool) $pr->departement_en_charge_check;
            $this->departement_gestion_input = $pr->departement_gestion_input ?? '';
            $this->niveau_risque_tolere_radio = $pr->niveau_risque_tolere_radio ?? '';
            $this->annees_investissement_produits_finaniers = $pr->annees_investissement_produits_finaniers ?? '';
            $this->transactions_courant_2_annees = $pr->transactions_courant_2_annees ?? '';

            if (!empty($pr->instruments_souhaites_input)) {
                $this->instruments_souhaites = array_map('trim', explode(',', $pr->instruments_souhaites_input));
            }

            $knownInstruments = ['OPCVM Monétaires', 'OPCVM Obligataires', 'OPCVM Actions', 'OPCVM Diversifiés', 'Bons de Trésor', 'Titres de dette privé', 'Actions'];
            $autres = array_diff($this->instruments_souhaites, $knownInstruments);
            $this->instruments_souhaites_autres = implode(', ', $autres);
            $this->instruments_souhaites = array_intersect($this->instruments_souhaites, $knownInstruments);
        }
    }

    public function toggleEdit()
    {
        $this->editing = !$this->editing;
    }

    public function update()
    {
        $allInstruments = $this->instruments_souhaites;
        if (!empty($this->instruments_souhaites_autres)) {
            $extras = array_map('trim', explode(',', $this->instruments_souhaites_autres));
            $allInstruments = array_merge($allInstruments, $extras);
        }

        $this->etablissement->ProfilRisque()->updateOrCreate(
            ['etablissement_id' => $this->etablissement_id],
            [
                'departement_en_charge_check' => $this->departement_en_charge_check,
                'departement_gestion_input' => $this->departement_en_charge_check ? $this->departement_gestion_input : null,
                'instruments_souhaites_input' => implode(',', $allInstruments),
                'niveau_risque_tolere_radio' => $this->niveau_risque_tolere_radio,
                'annees_investissement_produits_finaniers' => $this->annees_investissement_produits_finaniers,
                'transactions_courant_2_annees' => $this->transactions_courant_2_annees,
            ]
        );

        $this->editing = false;
        session()->flash('message', 'Profil risque mis à jour avec succès.');
    }

    public function render()
    {
        return view('livewire.edit-profil-risque');
    }
}
