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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreatePersonneHabilite extends Component
{
    use WithFileUploads;

    public $etablissement;
    public $etablissement_id;
    public $rows = [];
    public $pays;
    public $ppes;
    public $redirect_to;

    public $analysisResults = [];
    public $showRiskModal = false;

    // Risk Detail
    public $match_detail;
    public $match_detail_identifiant;
    public $match_detail_full_name;
    public $showDetailModal = false;
    public $detail_table_match;

    public function mount($etablissement_id)
    {
        $this->etablissement_id = $etablissement_id;
        $this->etablissement = Etablissement::findOrFail($etablissement_id);
        $this->redirect_to = request()->query('redirect_to');
        
        $this->pays = Pays::all();
        $this->ppes = Ppe::all();

        // Initialize with one empty row
        $this->rows = [
            $this->getEmptyRow()
        ];
    }

    public function getEmptyRow()
    {
        return [
            'nom_rs' => '',
            'prenom' => '',
            'identite' => '',
            'fonction' => '',
            'nationalite_id' => '',
            
            // PPE
            'is_ppe' => false,
            'ppe_id' => '',
            
            // Lien PPE
            'is_ppe_lien' => false,
            'ppe_lien_id' => '',
            
            // Files
            'cin_file' => null,
            'hab_file' => null,

            // Risk
            'note' => 1,
            'percentage' => 0,
            'table_match' => null,
            'match_id' => null
        ];
    }

    public function addRow()
    {
        $this->rows[] = $this->getEmptyRow();
    }

    public function removeRow($index)
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows);
    }

    public function checkRisque()
    {
        $this->validate([
            'rows.*.nom_rs' => 'required|string|max:200',
            'rows.*.nationalite_id' => 'required|exists:pays,id',
        ], [
            'rows.*.nom_rs.required' => 'Le nom est obligatoire.',
            'rows.*.nationalite_id.required' => 'La nationalité est obligatoire.',
        ]);

        $this->analysisResults = [];

        foreach ($this->rows as $index => $row) {
            $personne = new PersonnesHabilites();
            $personne->nom_rs = $row['nom_rs'];
            $personne->prenom = $row['prenom'];
            $personne->identite = $row['identite'];
            $personne->nationalite_id = $row['nationalite_id'];
            $personne->fonction = $row['fonction'];
            
            $personne->ppe = $row['is_ppe'] ? 1 : 0;
            $personne->libelle_ppe = $row['is_ppe'] ? $row['ppe_id'] : null;
            $personne->lien_ppe = $row['is_ppe_lien'] ? 1 : 0;
            $personne->libelle_ppe_lien = $row['is_ppe_lien'] ? $row['ppe_lien_id'] : null;

            // Mock files for risk check
            if (!empty($row['cin_file'])) {
                $personne->cin_file = 'temp';
            }
            if (!empty($row['hab_file'])) {
                $personne->fichier_habilitation_file = 'temp';
            }

            $risk = $personne->checkRisk();

            // Update row with risk data
            $this->rows[$index]['note'] = $risk['note'] ?? 1;
            $this->rows[$index]['percentage'] = $risk['percentage'] ?? 0;
            $this->rows[$index]['table_match'] = $risk['table'] ?? null;
            $this->rows[$index]['match_id'] = $risk['match_id'] ?? null;

            $this->analysisResults[$index] = [
                'data' => $row,
                'risk' => $risk,
                'is_interdit' => ($risk['note'] ?? 1) >= 300,
                'is_high_risk' => ($risk['note'] ?? 1) >= 30 && ($risk['note'] ?? 1) < 300,
                'is_medium_risk' => ($risk['note'] ?? 1) > 7 && ($risk['note'] ?? 1) < 30,
                'is_low_risk' => ($risk['note'] ?? 1) >= 1 && ($risk['note'] ?? 1) <= 7,
                'is_false_positive' => false,
            ];
        }

        $this->showRiskModal = true;
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

        $this->rows[$index]['note'] = $newNote;
        $this->rows[$index]['percentage'] = 0;
        $this->rows[$index]['table_match'] = null;
        $this->rows[$index]['match_id'] = null;
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

    public function rejectEtablissement()
    {
        $this->etablissement->update(['validation_AK' => 0]);
        session()->flash('error', 'Etablissement rejeté.'); 
        return redirect()->route('dashboard');
    }

    public function save()
    {
         foreach ($this->analysisResults as $result) {
            if ($result['is_interdit'] && empty($result['is_false_positive'])) {
                session()->flash('error', 'Impossible de valider : Personne INTERDITE détectée.');
                return;
            }
        }

        $this->validate([
            'rows.*.nom_rs' => 'required|string|max:200',
            'rows.*.nationalite_id' => 'required|exists:pays,id',
        ]);

        DB::transaction(function () {
            foreach ($this->rows as $row) {
                $personne = new PersonnesHabilites();
                $personne->etablissement_id = $this->etablissement->id;
                $personne->nom_rs = $row['nom_rs'];
                $personne->prenom = $row['prenom'];
                $personne->identite = $row['identite'];
                $personne->nationalite_id = !empty($row['nationalite_id']) ? $row['nationalite_id'] : null;
                $personne->fonction = $row['fonction'];
                
                $personne->ppe = $row['is_ppe'] ? 1 : 0;
                $personne->libelle_ppe = ($row['is_ppe'] && !empty($row['ppe_id'])) ? $row['ppe_id'] : null;
                
                $personne->lien_ppe = $row['is_ppe_lien'] ? 1 : 0;
                $personne->libelle_ppe_lien = ($row['is_ppe_lien'] && !empty($row['ppe_lien_id'])) ? $row['ppe_lien_id'] : null;

                $personne->note = $row['note'];
                $personne->percentage = $row['percentage'];
                $personne->table_match = $row['table_match'];
                $personne->match_id = $row['match_id'];
                
                if (isset($row['cin_file']) && $row['cin_file']) {
                     $personne->cin_file = $row['cin_file']->store('personneshabilites/cin', 'public');
                }
                if (isset($row['hab_file']) && $row['hab_file']) {
                     $personne->fichier_habilitation_file = $row['hab_file']->store('personneshabilites/habilitation', 'public');
                }

                $personne->save();
            }

            $this->etablissement->updateRiskRating();
        });

        session()->flash('success', 'Personnes habilitées enregistrées avec succès !');

        if ($this->redirect_to === 'dashboard') {
            return redirect()->route('etablissements.show', $this->etablissement->id);
        }

        return redirect()->route('objetrelation.create', ['etablissement_id' => $this->etablissement->id]);
    }

    public function render()
    {
        return view('livewire.create-personne-habilite');
    }
}