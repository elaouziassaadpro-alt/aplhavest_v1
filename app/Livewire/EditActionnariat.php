<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Etablissement;
use App\Models\Actionnariat;
use App\Models\CNASNU;
use App\Models\ANRF;

class EditActionnariat extends Component
{
    public $etablissement_id;
    public $etablissement;
    public $editing = false;

    public $actionnaires = [];
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
        $this->etablissement = Etablissement::with('Actionnaire')->findOrFail($etablissement_id);

        $this->loadActionnaires();
    }

    public function loadActionnaires()
    {
        $this->actionnaires = [];
        if ($this->etablissement->Actionnaire) {
            foreach ($this->etablissement->Actionnaire as $a) {
                $this->actionnaires[] = [
                    'id' => $a->id,
                    'nom_rs' => $a->nom_rs,
                    'prenom' => $a->prenom,
                    'identite' => $a->identite,
                    'nombre_titres' => $a->nombre_titres,
                    'pourcentage_capital' => $a->pourcentage_capital,
                ];
            }
        }
    }

    public function toggleEdit()
    {
        $this->editing = !$this->editing;
    }

    public function removeActionnaire($index)
    {
        if (isset($this->actionnaires[$index]['id'])) {
            Actionnariat::find($this->actionnaires[$index]['id'])?->delete();
        }
        unset($this->actionnaires[$index]);
        $this->actionnaires = array_values($this->actionnaires);
    }

    public function checkRisque()
    {
        $this->analysisResults = [];

        foreach ($this->actionnaires as $index => $row) {
            $actionnaire = new Actionnariat($row);

            $risk = $actionnaire->checkRisk();

            $this->analysisResults[$index] = [
                'data' => $row,
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
            $newNote -= 29;
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
        foreach ($this->actionnaires as $data) {
            if (!empty($data['id'])) {
                Actionnariat::where('id', $data['id'])->update([
                    'nom_rs' => $data['nom_rs'],
                    'prenom' => $data['prenom'],
                    'identite' => $data['identite'],
                    'nombre_titres' => $data['nombre_titres'],
                    'pourcentage_capital' => $data['pourcentage_capital'],
                ]);
            }
        }

        $this->etablissement->load('Actionnaire');
        $this->loadActionnaires();
        $this->etablissement->updateRiskRating();

        $this->editing = false;
        session()->flash('message', 'Actionnariat mis à jour avec succès.');
    }

    public function render()
    {
        return view('livewire.edit-actionnariat');
    }
}
