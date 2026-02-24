<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Etablissement;
use App\Models\ProfilRisque;

class CreateProfilRisque extends Component
{
    public $etablissement;
    public $etablissement_id;
    public $redirect_to;

    // Form fields
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
        $this->etablissement = Etablissement::findOrFail($etablissement_id);
        $this->redirect_to = request()->query('redirect_to');

        // Load existing data if available
        $profilRisque = $this->etablissement->ProfilRisque;

        if ($profilRisque) {
            $this->departement_en_charge_check = $profilRisque->departement_en_charge_check;
            $this->departement_gestion_input = $profilRisque->departement_gestion_input ?? '';
            $this->niveau_risque_tolere_radio = $profilRisque->niveau_risque_tolere_radio ?? '';
            $this->annees_investissement_produits_finaniers = $profilRisque->annees_investissement_produits_finaniers ?? '';
            $this->transactions_courant_2_annees = $profilRisque->transactions_courant_2_annees ?? '';

            // Parse instruments (stored as comma-separated string)
            if ($profilRisque->instruments_souhaites_input) {
                $knownInstruments = [
                    'OPCVM Monétaires', 'OPCVM Obligataires', 'OPCVM Actions',
                    'OPCVM Diversifiés', 'Bons de Trésor', 'Titres de dette privé', 'Actions'
                ];
                $saved = array_map('trim', explode(',', $profilRisque->instruments_souhaites_input));
                
                $this->instruments_souhaites = array_values(array_intersect($saved, $knownInstruments));
                $autres = array_values(array_diff($saved, $knownInstruments));
                $this->instruments_souhaites_autres = implode(', ', $autres);
            }
        }
    }

    public function save()
    {
        $this->validate([
            'niveau_risque_tolere_radio' => 'required|string',
            'annees_investissement_produits_finaniers' => 'required|string',
            'transactions_courant_2_annees' => 'required|string',
            'departement_gestion_input' => 'nullable|required_if:departement_en_charge_check,true|string|max:255',
        ], [
            'niveau_risque_tolere_radio.required' => 'Le niveau de risque toléré est requis.',
            'annees_investissement_produits_finaniers.required' => 'Les années d\'investissement sont requises.',
            'transactions_courant_2_annees.required' => 'Les transactions courant 2 dernières années est requis.',
            'departement_gestion_input.required_if' => 'Le département de gestion est requis si le switch est activé.',
        ]);

        $profilRisque = $this->etablissement->ProfilRisque
            ?? new ProfilRisque(['etablissement_id' => $this->etablissement->id]);

        $profilRisque->departement_en_charge_check = $this->departement_en_charge_check;

        if ($this->departement_en_charge_check) {
            $profilRisque->departement_gestion_input = $this->departement_gestion_input;
        } else {
            $profilRisque->departement_gestion_input = null;
        }

        // Build instruments string
        $instruments = $this->instruments_souhaites;
        $autres = trim($this->instruments_souhaites_autres);
        if ($autres) {
            $instruments[] = $autres;
        }
        $profilRisque->instruments_souhaites_input = !empty($instruments) ? implode(', ', $instruments) : null;

        $profilRisque->niveau_risque_tolere_radio = $this->niveau_risque_tolere_radio;
        $profilRisque->annees_investissement_produits_finaniers = $this->annees_investissement_produits_finaniers;
        $profilRisque->transactions_courant_2_annees = $this->transactions_courant_2_annees;

        $profilRisque->save();

        session()->flash('success', 'Profil Risque enregistré avec succès !');

        if ($this->redirect_to === 'dashboard') {
            return redirect()->route('etablissements.show', $this->etablissement->id);
        }

        return redirect()->route('etablissements.index');
    }

    public function render()
    {
        return view('livewire.create-profil-risque');
    }
}
