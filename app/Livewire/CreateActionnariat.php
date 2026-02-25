<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Etablissement;
use App\Models\Actionnariat;
use App\Models\CNASNU;
use App\Models\ANRF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateActionnariat extends Component
{
    public $etablissement;
    public $etablissement_id;
    public $rows = [];
    public $analysisResults = [];
    public $showRiskModal = false;
    public $redirect_to;
    
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

        // Initialize with one empty row
        $this->rows = [
            [
                'nom_rs' => '',
                'prenom' => '',
                'identite' => '',
                'nombre_titres' => 0,
                'pourcentage_capital' => 0,
                'note' => 1,
                'percentage' => 0,
                'table_match' => null,
                'match_id' => null
            ]
        ];
    }

    public function addRow()
    {
        $this->rows[] = [
            'nom_rs' => '',
            'prenom' => '',
            'identite' => '',
            'nombre_titres' => 0,
            'pourcentage_capital' => 0,
            'note' => 1,
            'percentage' => 0,
            'table_match' => null,
            'match_id' => null
        ];
    }

    public function removeRow($index)
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows);
    }

    public function checkRisque()
    {
        $this->validate([
            'rows.*.nom_rs' => 'required|string|max:255',
            'rows.*.identite' => 'required|string|max:255',
        ], [
            'rows.*.nom_rs.required' => 'Le nom/raison sociale est obligatoire.',
            'rows.*.identite.required' => 'L\'identité est obligatoire.',
        ]);

        $this->analysisResults = [];

        foreach ($this->rows as $index => $row) {
            $actionnaire = new Actionnariat($row);
            // We need to set etablissement_id for the trait to know the class context if needed, 
            // though strict class checking in trait might look at 'Actionnariat' class name.
            
            $risk = $actionnaire->checkRisk();

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
            $newNote -= 29; // Assuming base was 30 for Actionnariat
        } else if ($tableSource === 'Anrf') {
            $newNote -= 2; // Assuming base was 3
        }

        if ($newNote < 1) $newNote = 1;

        // Update analysis result
        $this->analysisResults[$index]['risk']['note'] = $newNote;
        $this->analysisResults[$index]['risk']['percentage'] = 0;
        $this->analysisResults[$index]['is_interdit'] = false;
        $this->analysisResults[$index]['is_high_risk'] = false; // Assuming false positive clears high risk
        $this->analysisResults[$index]['is_false_positive'] = true;

        // Update actual row data
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

    public function save()
    {
        // Check if forbidden exists
        foreach ($this->analysisResults as $result) {
            if ($result['is_interdit'] && empty($result['is_false_positive'])) {
                session()->flash('error', 'Impossible de valider : Actionnaire INTERDIT détecté.');
                return;
            }
        }
        
         $this->validate([
            'rows.*.nom_rs' => 'required|string|max:255',
            'rows.*.identite' => 'required|string|max:255',
            'rows.*.nombre_titres' => 'required|numeric|min:0',
            'rows.*.pourcentage_capital' => 'required|numeric|min:0|max:100',
        ]);

        DB::transaction(function () {
             // Delete existing for this etablissement? 
             // The controller deleted existing ones: Actionnariat::where('etablissement_id', $etablissement->id)->delete();
             // But usually 'create' might append. 
             // However, the controller `update` method deletes all and recreates. 
             // The `store` method just appends.
             // Given the context of "Create Actionnariat", it usually appends.
             // But if we want to mimic the "Update" behavior which seems to be what the UI implies by showing existing rows...
             // actually create.blade.php showed existing rows but the form posted to `store`.
             // `store` just created new ones.
             
            foreach ($this->rows as $row) {
                Actionnariat::create([
                    'etablissement_id' => $this->etablissement->id,
                    'nom_rs' => $row['nom_rs'],
                    'prenom' => $row['prenom'],
                    'identite' => $row['identite'],
                    'nombre_titres' => $row['nombre_titres'],
                    'pourcentage_capital' => $row['pourcentage_capital'],
                    'note' => $row['note'],
                    'percentage' => $row['percentage'],
                    'table_match' => $row['table_match'],
                    'match_id' => $row['match_id'],
                ]);
            }

            $this->etablissement->updateRiskRating();
        });

        session()->flash('success', 'Actionnaires enregistrés avec succès.');

        if ($this->redirect_to === 'dashboard') {
            return redirect()->route('etablissements.show', $this->etablissement->id);
        }

        return redirect()->route('benificiaireeffectif.create', ['etablissement_id' => $this->etablissement->id]);
    }

    public function rejectEtablissement()
    {
        $this->etablissement->update(['validation_AK' => 0]);
        session()->flash('error', 'Etablissement rejeté.'); 
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.create-actionnariat');
    }
}
