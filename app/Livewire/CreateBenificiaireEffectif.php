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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateBenificiaireEffectif extends Component
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
            'pays_naissance_id' => '',
            'date_naissance' => '',
            'identite' => '',
            'nationalite_id' => '',
            'pourcentage_capital' => 0,
            
            // PPE
            'is_ppe' => false,
            'ppe_id' => '',
            
            // Lien PPE
            'is_ppe_lien' => false,
            'ppe_lien_id' => '',
            
            // File
            'cin_file' => null,
            // 'existing_cin_file' => null, // For edit mode later if needed

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
            $beneficiaire = new BenificiaireEffectif();
            $beneficiaire->nom_rs = $row['nom_rs'];
            $beneficiaire->prenom = $row['prenom'];
            $beneficiaire->identite = $row['identite'];
            $beneficiaire->nationalite_id = $row['nationalite_id'];
            $beneficiaire->pourcentage_capital = $row['pourcentage_capital'];
            $beneficiaire->date_naissance = $row['date_naissance'];
            $beneficiaire->pays_naissance_id = $row['pays_naissance_id'];
            
            // Set PPE for risk calculation if it affects it (Trait usually checks 'ppe' property)
            $beneficiaire->ppe = $row['is_ppe'] ? 1 : 0;
            $beneficiaire->ppe_lien = $row['is_ppe_lien'] ? 1 : 0;

            // Note: We don't save files here, just checking risk based on data.
            // If risk depends on file presence (checkIdentity -> cin_file check in trait), 
            // we might need to simulate it or pass a flag.
            // Traits/NiveauRisqueTrait.php: if (!$this->cin_file) { $identityRisk = 2; }
            // So we should set it if we have a file uploaded in $row['cin_file'].
            // Livewire upload object is TemporaryUploadedFile
            if (!empty($row['cin_file'])) {
                $beneficiaire->cin_file = 'temp'; // Mock for risk check
            }

            $risk = $beneficiaire->checkRisk();

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
                'is_medium_risk' => ($risk['note'] ?? 1) <=29 && ($risk['note'] ?? 1) > 7,
                'is_low_risk' => ($risk['note'] ?? 1) <= 7 && ($risk['note'] ?? 1) >= 1,
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
             // Base 300 for Beneficiaires
            $newNote -= 299;
        } else if ($tableSource === 'Anrf') {
            // Base 3
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
                session()->flash('error', 'Impossible de valider : Bénéficiaire INTERDIT détecté.');
                return;
            }
        }

        $this->validate([
            'rows.*.nom_rs' => 'required|string|max:200',
            'rows.*.nationalite_id' => 'required|exists:pays,id',
            // Add other validations as needed
        ]);

        DB::transaction(function () {
             // Create mode means generally adding. 
             // If we wanted to "sync", we would delete existing. 
             // Just like Actionnariat, let's assume we append or just create.
             // controller 'create' -> 'store' appends. 
             
            foreach ($this->rows as $row) {
                // Ensure array keys exist if added later
                
                $beneficiaire = new BenificiaireEffectif();
                $beneficiaire->etablissement_id = $this->etablissement->id;
                $beneficiaire->nom_rs = $row['nom_rs'];
                $beneficiaire->prenom = $row['prenom'];
                $beneficiaire->pays_naissance_id = !empty($row['pays_naissance_id']) ? $row['pays_naissance_id'] : null;
                $beneficiaire->date_naissance = !empty($row['date_naissance']) ? $row['date_naissance'] : null;
                $beneficiaire->identite = $row['identite'];
                $beneficiaire->nationalite_id = !empty($row['nationalite_id']) ? $row['nationalite_id'] : null;
                $beneficiaire->pourcentage_capital = $row['pourcentage_capital'];
                
                $beneficiaire->ppe = $row['is_ppe'] ? 1 : 0;
                $beneficiaire->ppe_id = ($row['is_ppe'] && !empty($row['ppe_id'])) ? $row['ppe_id'] : null;
                
                $beneficiaire->ppe_lien = $row['is_ppe_lien'] ? 1 : 0;
                $beneficiaire->ppe_lien_id = ($row['is_ppe_lien'] && !empty($row['ppe_lien_id'])) ? $row['ppe_lien_id'] : null;

                $beneficiaire->note = $row['note'];
                $beneficiaire->percentage = $row['percentage'];
                $beneficiaire->table_match = $row['table_match'];
                $beneficiaire->match_id = $row['match_id'];
                
                if (isset($row['cin_file']) && $row['cin_file']) {
                     // store file
                     $path = $row['cin_file']->store('Beneficiaires_Effectif/cin', 'public');
                     $beneficiaire->cin_file = $path;
                }

                $beneficiaire->save();
            }

            $this->etablissement->updateRiskRating();
        });

        session()->flash('success', 'Bénéficiaires enregistrés avec succès !');

        if ($this->redirect_to === 'dashboard') {
            return redirect()->route('etablissements.show', $this->etablissement->id);
        }

        return redirect()->route('administrateurs.create', ['etablissement_id' => $this->etablissement->id]);
    }

    public function render()
    {
        return view('livewire.create-benificiaire-effectif');
    }
}
