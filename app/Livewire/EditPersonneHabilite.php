<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Etablissement;
use App\Models\PersonnesHabilites;
use App\Models\Pays;
use App\Models\Ppe;
use App\Models\CNASNU;
use App\Models\ANRF;
use Illuminate\Support\Facades\Storage;

class EditPersonneHabilite extends Component
{
    use WithFileUploads;

    public $etablissement_id;
    public $etablissement;
    public $editing = false;

    public $personnes = [];
    public $pays = [];
    public $ppes = [];
    public $showRiskModal = false;
    public $analysisResults = [];

    // Risk Detail
    public $match_detail;
    public $match_detail_identifiant;
    public $match_detail_full_name;
    public $showDetailModal = false;
    public $detail_table_match;

    public function mount($etablissement_id)
    {
        $this->etablissement_id = $etablissement_id;
        $this->etablissement = Etablissement::with('PersonnesHabilites')->findOrFail($etablissement_id);
        $this->pays = Pays::all();
        $this->ppes = Ppe::all();

        $this->loadPersonnes();
    }

    public function loadPersonnes()
    {
        $this->personnes = [];
        if ($this->etablissement->PersonnesHabilites) {
            foreach ($this->etablissement->PersonnesHabilites as $ph) {
                $this->personnes[] = [
                    'id' => $ph->id,
                    'nom_rs' => $ph->nom_rs,
                    'prenom' => $ph->prenom,
                    'identite' => $ph->identite,
                    'nationalite_id' => $ph->nationalite_id,
                    'fonction' => $ph->fonction,
                    'ppe' => (bool) $ph->ppe,
                    'libelle_ppe' => $ph->libelle_ppe,
                    'lien_ppe' => (bool) $ph->lien_ppe,
                    'libelle_ppe_lien' => $ph->libelle_ppe_lien,
                    'existing_cin_file' => $ph->cin_file,
                    'new_cin_file' => null,
                    'existing_habilitation_file' => $ph->fichier_habilitation_file,
                    'new_habilitation_file' => null,
                ];
            }
        }
    }

    public function toggleEdit()
    {
        $this->editing = !$this->editing;
    }

    public function removePersonne($index)
    {
        if (isset($this->personnes[$index]['id'])) {
            PersonnesHabilites::find($this->personnes[$index]['id'])?->delete();
        }
        unset($this->personnes[$index]);
        $this->personnes = array_values($this->personnes);
    }

    public function checkRisque()
    {
        $this->analysisResults = [];

        foreach ($this->personnes as $index => $row) {
            $personne = new PersonnesHabilites();
            $personne->nom_rs = $row['nom_rs'];
            $personne->prenom = $row['prenom'];
            $personne->identite = $row['identite'];
            $personne->nationalite_id = $row['nationalite_id'];
            $personne->fonction = $row['fonction'];
            $personne->ppe = $row['ppe'] ? 1 : 0;
            $personne->libelle_ppe = $row['ppe'] ? $row['libelle_ppe'] : null;
            $personne->lien_ppe = $row['lien_ppe'] ? 1 : 0;
            $personne->libelle_ppe_lien = $row['lien_ppe'] ? $row['libelle_ppe_lien'] : null;

            if (!empty($row['existing_cin_file'])) {
                $personne->cin_file = $row['existing_cin_file'];
            }
            if (!empty($row['existing_habilitation_file'])) {
                $personne->fichier_habilitation_file = $row['existing_habilitation_file'];
            }

            $risk = $personne->checkRisk();

            $this->analysisResults[$index] = [
                'data' => ['nom_rs' => $row['nom_rs'], 'prenom' => $row['prenom'], 'identite' => $row['identite']],
                'risk' => $risk,
                'is_high_risk' => ($risk['note'] ?? 1) >= 30 ,
                'is_medium_risk' => ($risk['note'] ?? 1) > 7 && ($risk['note'] ?? 1) < 30,
                'is_low_risk' => ($risk['note'] ?? 1) >= 1 && ($risk['note'] ?? 1) <= 7,
                'is_false_positive' => false
            ];
        }

        $this->showRiskModal = true;
    }

    public function closeRiskModal()
    {
        $this->showRiskModal = false;
    }

    public function showDetail($id, $table)
    {
        $this->detail_table_match = $table;

        if($table == 'Cnasnu'){
            $match_detail = CNASNU::find($id);
            $this->match_detail = $match_detail;
            $this->match_detail_identifiant = $match_detail->dataID;
            $this->match_detail_full_name = $match_detail->firstName.' '.$match_detail->secondName.' '.$match_detail->thirdName.' '.$match_detail->fourthName;
        }
        if($table == 'Anrf'){
            $match_detail = ANRF::find($id);
            $this->match_detail = $match_detail;
            $this->match_detail_identifiant = $match_detail->identifiant;
            $this->match_detail_full_name = $match_detail->nom;
        }

        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
    }

    public function markAsFalsePositive($index)
    {
        if (!isset($this->analysisResults[$index])) return;

        $result = $this->analysisResults[$index];
        $tableSource = $result['risk']['table'] ?? '';
        $currentNote = $result['risk']['note'] ?? 1;
        $newNote = $currentNote;

        if ($tableSource === 'Cnasnu') {
            $newNote -= 299;
        } else if ($tableSource === 'Anrf') {
            $newNote -= 2;
        }

        if ($newNote < 1) $newNote = 1;

        $this->analysisResults[$index]['risk']['note'] = $newNote;
        $this->analysisResults[$index]['risk']['percentage'] = 0;
        $this->analysisResults[$index]['is_interdit'] = false;
        $this->analysisResults[$index]['is_high_risk'] = false;
        $this->analysisResults[$index]['is_false_positive'] = true;
    }

    public function rejectEtablissement()
    {
        $this->etablissement->update(['validation' => 'rejete']);
        session()->flash('error', 'Etablissement rejeté.');
        return redirect()->route('dashboard');
    }

    public function update()
    {
        foreach ($this->personnes as $data) {
            $updateData = [
                'nom_rs' => $data['nom_rs'],
                'prenom' => $data['prenom'],
                'identite' => $data['identite'],
                'nationalite_id' => $data['nationalite_id'],
                'fonction' => $data['fonction'],
                'ppe' => $data['ppe'],
                'libelle_ppe' => $data['ppe'] ? $data['libelle_ppe'] : null,
                'lien_ppe' => $data['lien_ppe'],
                'libelle_ppe_lien' => $data['lien_ppe'] ? $data['libelle_ppe_lien'] : null,
            ];

            if (isset($data['new_cin_file']) && $data['new_cin_file']) {
                if (!empty($data['existing_cin_file'])) {
                    Storage::disk('public')->delete($data['existing_cin_file']);
                }
                $updateData['cin_file'] = $data['new_cin_file']->store('personnes_habilites/cin', 'public');
            }

            if (isset($data['new_habilitation_file']) && $data['new_habilitation_file']) {
                if (!empty($data['existing_habilitation_file'])) {
                    Storage::disk('public')->delete($data['existing_habilitation_file']);
                }
                $updateData['fichier_habilitation_file'] = $data['new_habilitation_file']->store('personnes_habilites/habilitation', 'public');
            }

            if (!empty($data['id'])) {
                PersonnesHabilites::where('id', $data['id'])->update($updateData);
            }
        }

        $this->etablissement->load('PersonnesHabilites');
        $this->loadPersonnes();
        $this->etablissement->updateRiskRating();

        $this->editing = false;
        session()->flash('message', 'Personnes habilitées mises à jour avec succès.');
    }

    public function render()
    {
        return view('livewire.edit-personne-habilite');
    }
}
