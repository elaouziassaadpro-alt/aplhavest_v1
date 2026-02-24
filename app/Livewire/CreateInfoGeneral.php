<?php

namespace App\Livewire;

use App\Models\CNASNU;
use App\Models\ANRF;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Etablissement;
use App\Models\InfoGeneral;
use App\Models\Pays;
use App\Models\formejuridique;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateInfoGeneral extends Component
{
    use WithFileUploads;

    // Lookups
    public $pays;
    public $formejuridiques;

    // Form fields
    public $raisonSocial = '';
    public $capitalSocialPrimaire = '';
    public $FormeJuridique = '';
    public $dateImmatriculation = '';
    public $ice = '';
    public $rc_input = '';
    public $ifiscal = '';
    public $siegeSocial = '';
    public $paysActivite = '';
    public $paysResidence = '';
    public $regule = false;
    public $nomRegulateur = '';
    public $telephone = '';
    public $email = '';
    public $siteweb = '';
    public $societe_gestion = false;

    // File uploads
    public $ice_file;
    public $status_file;
    public $rc_file;
    

    // Contacts (dynamic rows)
    public $contacts = [];

    // Risk analysis
    public $analysisResult = null;
    public $showRiskModal = false;
    public $note = 0;
    public $percentage = 0;
    public $table_match = null;
    public $match_id = null;
    public $detail_table_match;
    public $match_detail_identifiant;
    public $match_detail_full_name;
    public $match_detail;
    public $showDetailModal = false;

    // OPC files
    public $opcFiles = [];

    public function mount()
    {
        $this->pays = Pays::all();
        $this->formejuridiques = formejuridique::all();

        // Initialize with one empty contact
        $this->contacts = [
            $this->getEmptyContact()
        ];
        $this->opcFiles = [
            $this->getEmptyOpcFile()
        ];
        
    }

    public function getEmptyContact()
    {
        return [
            'nom' => '',
            'prenom' => '',
            'fonction' => '',
            'telephone' => '',
            'email' => '',
        ];
    }

    public function addContact()
    {
        $this->contacts[] = $this->getEmptyContact();
    }

    public function removeContact($index)
    {
        unset($this->contacts[$index]);
        $this->contacts = array_values($this->contacts);
    }

    public function checkRisque()
    {
        $this->validate([
            'raisonSocial' => 'required|string|max:200',
            'paysResidence' => 'required|exists:pays,id',
            'rc_input' => 'required|string|max:100',

        ], [
            'raisonSocial.required' => 'La raison sociale est obligatoire.',
            'paysResidence.required' => 'Le pays de résidence est obligatoire.',
            'rc_input.required' => 'Le numéro RC est obligatoire.',
        ]);

        $info = new InfoGeneral();
        $info->nom_rs = $this->raisonSocial;
        $info->raisonSocial = $this->raisonSocial;
        $info->paysResidence = $this->paysResidence;
        $info->rc_input = $this->rc_input;

        $risk = $info->checkRisk();

        $this->note = $risk['note'] ?? 1;
        $this->percentage = $risk['percentage'] ?? 0;
        $this->table_match = $risk['table'] ?? null;
        $this->match_id = $risk['match_id'] ?? null;
        if($this->table_match == 'Cnasnu'){
             $this->match_detail = CNASNU::find($this->match_id);
        }
        if($this->table_match == 'Anrf'){
             $this->match_detail = ANRF::find($this->match_id);
        }

        $this->analysisResult = [
            'risk' => $risk,
            'is_interdit' => ($this->note >= 300),
            'is_high_risk' => ($this->note >= 30 && $this->note < 300),
            'is_medium_risk' => ($this->note < 30 && $this->note > 7),
            'is_low_risk' => ($this->note <= 7 && $this->note >= 1),
            'is_false_positive' => false,
        ];

        $this->showRiskModal = true;
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
    public function markAsFalsePositive()
    {
        if (!$this->analysisResult) return;

        $tableSource = $this->analysisResult['risk']['table'] ?? '';
        $currentNote = $this->note;
        $newNote = $currentNote;

        if ($tableSource === 'Cnasnu') {
            $newNote -= 299;
        } elseif ($tableSource === 'Anrf') {
            $newNote -= 2;
        }

        if ($newNote < 1) $newNote = 1;

        $this->note = $newNote;
        $this->percentage = 0;
        $this->table_match = null;
        $this->match_id = null;

        $this->analysisResult['risk']['note'] = $newNote;
        $this->analysisResult['risk']['percentage'] = 0;
        $this->analysisResult['is_interdit'] = false;
        $this->analysisResult['is_high_risk'] = false; 
        $this->analysisResult['is_false_positive'] = true;
        
    }

    public function closeRiskModal()
    {
        $this->showRiskModal = false;
    }

    public function getEmptyOpcFile()
    {
        return [
            'opc' => '',
            'incrument' => '',
            'ni' => '',
            'fs' => '',
            'rg' => '',
        ];
    }
    public function addOpcFile()
    {
        $this->opcFiles[] = $this->getEmptyOpcFile();
    }

    public function removeOpcFile($index)
    {
        unset($this->opcFiles[$index]);
        $this->opcFiles = array_values($this->opcFiles);
    }

    public function save()
    {
        // Block if interdit and not false-positive
            if ($this->analysisResult['is_interdit'] ?? false && !$this->analysisResult['is_false_positive']) {
            session()->flash('error', 'Impossible de valider : Une entité INTERDITE a été détectée.');
            return;
        }

        $this->validate([
            'raisonSocial' => 'required|string|max:200',
            'capitalSocialPrimaire' => 'required|numeric|min:0',
            'FormeJuridique' => 'nullable|exists:formes_juridiques,id',
            'dateImmatriculation' => 'nullable|date',
            'ice' => 'nullable|string|max:100',
            'rc_input' => 'nullable|string|max:100',
            'ifiscal' => 'nullable|string|max:100',
            'siegeSocial' => 'nullable|string|max:350',
            'paysActivite' => 'nullable|exists:pays,id',
            'paysResidence' => 'nullable|exists:pays,id',
            'nomRegulateur' => 'nullable|string|max:200',
            'telephone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:200',
            'siteweb' => 'nullable|string|max:100',
            'ice_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'status_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'rc_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'contacts.*.nom' => 'nullable|string|max:200',
            // OPC Files Validation
            'opcFiles.*.opc' => 'nullable|string|max:255',
            'opcFiles.*.incrument' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'opcFiles.*.ni' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'opcFiles.*.fs' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'opcFiles.*.rg' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $etablissement = Etablissement::create([
                'name' => $this->raisonSocial,
            ]);

            $info = InfoGeneral::create([
                'etablissement_id' => $etablissement->id,
                'raisonSocial' => $this->raisonSocial,
                'capitalSocialPrimaire' => $this->capitalSocialPrimaire,
                'FormeJuridique' => !empty($this->FormeJuridique) ? $this->FormeJuridique : null,
                'dateImmatriculation' => !empty($this->dateImmatriculation) ? $this->dateImmatriculation : null,
                'ice' => $this->ice,
                'rc' => $this->rc_input,
                'ifiscal' => $this->ifiscal,
                'siegeSocial' => $this->siegeSocial,
                'paysActivite' => !empty($this->paysActivite) ? $this->paysActivite : null,
                'paysResidence' => !empty($this->paysResidence) ? $this->paysResidence : null,
                'regule' => $this->regule,
                'nomRegulateur' => $this->regule ? $this->nomRegulateur : null,
                'telephone' => $this->telephone,
                'email' => $this->email,
                'siteweb' => $this->siteweb,
                'societe_gestion' => $this->societe_gestion,
                'note' => $this->note,
                'percentage' => $this->percentage,
                'table_match' => $this->table_match,
                'match_id' => $this->match_id,
            ]);

            // File Uploads (Main)
            $slug = Str::slug($this->raisonSocial);
            $basePath = "informations_generales/{$info->id}";
            $fileMap = [
                'ice_file' => 'ice_file',
                'status_file' => 'status_file',
                'rc_file' => 'rc_file',
            ];

            foreach ($fileMap as $property => $folder) {
                if ($this->$property) {
                    $filename = "{$slug}-{$folder}-" . time() . "." . $this->$property->getClientOriginalExtension();
                    $info->$property = $this->$property->storeAs("{$basePath}/{$folder}", $filename, 'public');
                }
            }
            $info->save();

            // Save OPC Files
            if ($this->societe_gestion && !empty($this->opcFiles)) {
                foreach ($this->opcFiles as $opcData) {
                    $newOpc = new \App\Models\opc_files();
                    $newOpc->etablissement_id = $etablissement->id;
                    $newOpc->opc = $opcData['opc'] ?? null;

                    $opcSlug = Str::slug($opcData['opc'] ?? 'opc');
                    $opcBasePath = "opc_files/{$etablissement->id}";
                    
                    $opcFileFields = ['incrument', 'ni', 'fs', 'rg'];
                    foreach ($opcFileFields as $field) {
                        if (isset($opcData[$field]) && $opcData[$field] instanceof \Illuminate\Http\UploadedFile) {
                             $filename = "{$opcSlug}-{$field}-" . time() . "." . $opcData[$field]->getClientOriginalExtension();
                             $newOpc->$field = $opcData[$field]->storeAs($opcBasePath, $filename, 'public');
                        }
                    }
                    $newOpc->save();
                }
            }
            $info->save();

            // Contacts
            foreach ($this->contacts as $contact) {
                if (empty($contact['nom'])) continue;
                $info->contacts()->create([
                    'nom' => $contact['nom'],
                    'prenom' => $contact['prenom'],
                    'fonction' => $contact['fonction'],
                    'telephone' => $contact['telephone'],
                    'email' => $contact['email'],
                ]);
            }

            $etablissement->updateRiskRating();

            DB::commit();

            session()->flash('success', 'Informations enregistrées avec succès');
            return redirect()->route('coordonneesbancaires.create', ['etablissement_id' => $etablissement->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error storing InfoGeneral: " . $e->getMessage());
            session()->flash('error', 'Erreur lors de l\'enregistrement : ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.create-info-general');
    }
}
