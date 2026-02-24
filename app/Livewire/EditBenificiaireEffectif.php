<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Etablissement;
use App\Models\BenificiaireEffectif;
use App\Models\Pays;
use App\Models\Ppe;
use App\Models\CNASNU;
use App\Models\ANRF;
use Illuminate\Support\Facades\Storage;

class EditBenificiaireEffectif extends Component
{
    use WithFileUploads;

    public $etablissement_id;
    public $etablissement;
    public $editing = false;

    public $beneficiaires = [];
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
        $this->etablissement = Etablissement::with('BeneficiaireEffectif.paysNaissance', 'BeneficiaireEffectif.nationalite', 'BeneficiaireEffectif.ppeRelation', 'BeneficiaireEffectif.lienPpeRelation')->findOrFail($etablissement_id);
        $this->pays = Pays::all();
        $this->ppes = Ppe::all();

        $this->loadBeneficiaires();
    }

    public function loadBeneficiaires()
    {
        $this->beneficiaires = [];
        if ($this->etablissement->BeneficiaireEffectif) {
            foreach ($this->etablissement->BeneficiaireEffectif as $be) {
                $this->beneficiaires[] = [
                    'id' => $be->id,
                    'nom_rs' => $be->nom_rs,
                    'prenom' => $be->prenom,
                    'date_naissance' => $be->date_naissance,
                    'identite' => $be->identite,
                    'pays_naissance_id' => $be->pays_naissance_id,
                    'nationalite_id' => $be->nationalite_id,
                    'pourcentage_capital' => $be->pourcentage_capital,
                    'ppe' => (bool) $be->ppe,
                    'ppe_id' => $be->ppe_id,
                    'ppe_lien' => (bool) $be->ppe_lien,
                    'ppe_lien_id' => $be->ppe_lien_id,
                    'existing_cin_file' => $be->cin_file,
                    'new_cin_file' => null,
                ];
            }
        }
    }

    public function toggleEdit()
    {
        $this->editing = !$this->editing;
    }

    public function removeBeneficiaire($index)
    {
        if (isset($this->beneficiaires[$index]['id'])) {
            BenificiaireEffectif::find($this->beneficiaires[$index]['id'])?->delete();
        }
        unset($this->beneficiaires[$index]);
        $this->beneficiaires = array_values($this->beneficiaires);
    }

    public function checkRisque()
    {
        $this->analysisResults = [];

        foreach ($this->beneficiaires as $index => $row) {
            $ben = new BenificiaireEffectif();
            $ben->nom_rs = $row['nom_rs'];
            $ben->prenom = $row['prenom'];
            $ben->identite = $row['identite'];
            $ben->nationalite_id = $row['nationalite_id'];
            $ben->pourcentage_capital = $row['pourcentage_capital'];
            $ben->date_naissance = $row['date_naissance'];
            $ben->pays_naissance_id = $row['pays_naissance_id'];
            $ben->ppe = $row['ppe'] ? 1 : 0;
            $ben->ppe_lien = $row['ppe_lien'] ? 1 : 0;

            if (!empty($row['existing_cin_file'])) {
                $ben->cin_file = $row['existing_cin_file'];
            }

            $risk = $ben->checkRisk();

            $this->analysisResults[$index] = [
                'data' => ['nom_rs' => $row['nom_rs'], 'prenom' => $row['prenom'], 'identite' => $row['identite']],
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
        $this->etablissement->update(['validation' => 'rejete']);
        session()->flash('error', 'Etablissement rejeté.');
        return redirect()->route('dashboard');
    }

    public function update()
    {
        foreach ($this->beneficiaires as $data) {
            $updateData = [
                'nom_rs' => $data['nom_rs'],
                'prenom' => $data['prenom'],
                'date_naissance' => $data['date_naissance'],
                'identite' => $data['identite'],
                'pays_naissance_id' => $data['pays_naissance_id'],
                'nationalite_id' => $data['nationalite_id'],
                'pourcentage_capital' => $data['pourcentage_capital'],
                'ppe' => $data['ppe'],
                'ppe_id' => $data['ppe'] ? $data['ppe_id'] : null,
                'ppe_lien' => $data['ppe_lien'],
                'ppe_lien_id' => $data['ppe_lien'] ? $data['ppe_lien_id'] : null,
            ];

            if (isset($data['new_cin_file']) && $data['new_cin_file']) {
                if (!empty($data['existing_cin_file'])) {
                    Storage::disk('public')->delete($data['existing_cin_file']);
                }
                $updateData['cin_file'] = $data['new_cin_file']->store('beneficiaires', 'public');
            }

            if (!empty($data['id'])) {
                BenificiaireEffectif::where('id', $data['id'])->update($updateData);
            }
        }

        $this->etablissement->load('BeneficiaireEffectif');
        $this->loadBeneficiaires();
        $this->etablissement->updateRiskRating();
        
        $this->editing = false;
        session()->flash('message', 'Bénéficiaires effectifs mis à jour avec succès.');
    }

    public function render()
    {
        return view('livewire.edit-benificiaire-effectif');
    }
}
