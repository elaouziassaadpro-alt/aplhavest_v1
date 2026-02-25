<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Etablissement;
use App\Models\Administrateur;
use App\Models\Pays;
use App\Models\Ppe;
use App\Models\CNASNU;
use App\Models\ANRF;
use Illuminate\Support\Facades\Storage;

class EditAdministrateurs extends Component
{
    use WithFileUploads;

    public $etablissement_id;
    public $etablissement;
    public $editing = false;

    public $administrateurs = [];
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
        $this->etablissement = Etablissement::with('Administrateur')->findOrFail($etablissement_id);
        $this->pays = Pays::all();
        $this->ppes = Ppe::all();

        $this->loadAdministrateurs();
    }

    public function loadAdministrateurs()
    {
        $this->administrateurs = [];
        if ($this->etablissement->Administrateur) {
            foreach ($this->etablissement->Administrateur as $admin) {
                $this->administrateurs[] = [
                    'id' => $admin->id,
                    'nom' => $admin->nom,
                    'prenom' => $admin->prenom,
                    'date_naissance' => $admin->date_naissance,
                    'identite' => $admin->identite,
                    'nationalite_id' => $admin->nationalite_id,
                    'fonction' => $admin->fonction,
                    'ppe' => (bool) $admin->ppe,
                    'ppe_id' => $admin->ppe_id,
                    'lien_ppe' => (bool) $admin->lien_ppe,
                    'lien_ppe_id' => $admin->lien_ppe_id,
                    'existing_cin_file' => $admin->cin_file,
                    'new_cin_file' => null,
                    'existing_pvn_file' => $admin->pvn_file,
                    'new_pvn_file' => null,
                ];
            }
        }
    }

    public function toggleEdit()
    {
        $this->editing = !$this->editing;
    }

    public function removeAdministrateur($index)
    {
        if (isset($this->administrateurs[$index]['id'])) {
            Administrateur::find($this->administrateurs[$index]['id'])?->delete();
        }
        unset($this->administrateurs[$index]);
        $this->administrateurs = array_values($this->administrateurs);
    }

    public function checkRisque()
    {
        $this->analysisResults = [];

        foreach ($this->administrateurs as $index => $row) {
            $admin = new Administrateur();
            $admin->nom = $row['nom'];
            $admin->prenom = $row['prenom'];
            $admin->identite = $row['identite'];
            $admin->nationalite_id = $row['nationalite_id'];
            $admin->fonction = $row['fonction'];
            $admin->ppe = $row['ppe'] ? 1 : 0;
            $admin->lien_ppe = $row['lien_ppe'] ? 1 : 0;

            if (!empty($row['existing_cin_file'])) {
                $admin->cin_file = $row['existing_cin_file'];
            }
            if (!empty($row['existing_pvn_file'])) {
                $admin->pvn_file = $row['existing_pvn_file'];
            }

            $risk = $admin->checkRisk();

            $this->analysisResults[$index] = [
                'data' => ['nom_rs' => $row['nom'], 'prenom' => $row['prenom'], 'identite' => $row['identite']],
                'risk' => $risk,
                'is_interdit' => ($risk['note'] ?? 1) >= 300,
                'is_high_risk' => ($risk['note'] ?? 1) >= 30 && ($risk['note'] ?? 1) < 300,
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
        $this->etablissement->update(['validation_AK' => 0]);
        session()->flash('error', 'Etablissement rejeté.');
        return redirect()->route('dashboard');
    }

    public function update()
    {
        foreach ($this->administrateurs as $data) {
            $updateData = [
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'date_naissance' => $data['date_naissance'],
                'identite' => $data['identite'],
                'nationalite_id' => $data['nationalite_id'],
                'fonction' => $data['fonction'],
                'ppe' => $data['ppe'],
                'ppe_id' => $data['ppe'] ? $data['ppe_id'] : null,
                'lien_ppe' => $data['lien_ppe'],
                'lien_ppe_id' => $data['lien_ppe'] ? $data['lien_ppe_id'] : null,
            ];

            if (isset($data['new_cin_file']) && $data['new_cin_file']) {
                if (!empty($data['existing_cin_file'])) {
                    Storage::disk('public')->delete($data['existing_cin_file']);
                }
                $updateData['cin_file'] = $data['new_cin_file']->store('administrateurs/cin', 'public');
            }

            if (isset($data['new_pvn_file']) && $data['new_pvn_file']) {
                if (!empty($data['existing_pvn_file'])) {
                    Storage::disk('public')->delete($data['existing_pvn_file']);
                }
                $updateData['pvn_file'] = $data['new_pvn_file']->store('administrateurs/pvn', 'public');
            }

            if (!empty($data['id'])) {
                Administrateur::where('id', $data['id'])->update($updateData);
            }
        }

        $this->etablissement->load('Administrateur');
        $this->loadAdministrateurs();
        $this->etablissement->updateRiskRating();

        $this->editing = false;
        $this->showRiskModal = false;
        session()->flash('message', 'Administrateurs mis à jour avec succès.');
    }

    public function render()
    {
        return view('livewire.edit-administrateurs');
    }
}
