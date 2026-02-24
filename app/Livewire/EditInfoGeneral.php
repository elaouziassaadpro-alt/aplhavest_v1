<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Etablissement;
use App\Models\InfoGeneral; // Ensure this model exists and is correct
use App\Models\Pays;
use App\Models\formejuridique;
use App\Models\Contact;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\CNASNU;
use App\Models\ANRF;

class EditInfoGeneral extends Component
{
    use WithFileUploads;

    public $etablissement_id;
    public $etablissement;
    public $editing = false;

    // Lookups
    public $pays = [];
    public $formejuridiques = [];

    // Form Fieds
    public $raisonSocial = '';
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

    // Files
    public $ice_file;
    public $status_file;
    public $rc_file;
    public $agrement_file;
    public $NI;
    public $FS;
    public $RG;

    // OPC Files
    public $opcFiles = [];

    // Existing Files (paths)
    public $existing_ice_file;
    public $existing_status_file;
    public $existing_rc_file;
    
    // Contacts
    public $contacts = [];

    // Risk check
    public $showRiskModal = false;
    public $analysisResult = null;
    public $note = 1;
    public $percentage = 0;
    public $table_match = null;
    public $match_id = null;
    public $match_detail;
    public $match_detail_identifiant;
    public $match_detail_full_name;
    public $showDetailModal = false;

    public function mount($etablissement_id)
    {
        $this->etablissement_id = $etablissement_id;
        $this->etablissement = Etablissement::with(['infoGenerales.contacts', 'opcFiles'])->findOrFail($etablissement_id);
        $this->pays = Pays::all();
        $this->formejuridiques = formejuridique::all();

        $info = $this->etablissement->infoGenerales;
        if ($info) {
            $this->raisonSocial = $info->raisonSocial ?? $this->etablissement->name;
            $this->FormeJuridique = $info->FormeJuridique ?? '';
            $this->dateImmatriculation = $info->dateImmatriculation ?? '';
            $this->ice = $info->ice ?? '';
            $this->rc_input = $info->rc ?? '';
            $this->ifiscal = $info->ifiscal ?? '';
            $this->siegeSocial = $info->siegeSocial ?? '';
            $this->paysActivite = $info->paysActivite ?? '';
            $this->paysResidence = $info->paysResidence ?? '';
            $this->regule = (bool) $info->regule;
            $this->nomRegulateur = $info->nomRegulateur ?? '';
            $this->telephone = $info->telephone ?? '';
            $this->email = $info->email ?? '';
            $this->siteweb = $info->siteweb ?? '';
            $this->societe_gestion = (bool) $info->societe_gestion;

            // Load existing files
            $this->existing_ice_file = $info->ice_file;
            $this->existing_status_file = $info->status_file;
            $this->existing_rc_file = $info->rc_file;

            // Load contacts
            foreach ($info->contacts as $contact) {
                $this->contacts[] = [
                    'id' => $contact->id,
                    'nom' => $contact->nom,
                    'prenom' => $contact->prenom,
                    'fonction' => $contact->fonction,
                    'telephone' => $contact->telephone,
                    'email' => $contact->email,
                ];
            }
        } else {
            // Etablissement usually has a name
            $this->raisonSocial = $this->etablissement->name;
        }

        // Load OPC Files
        foreach ($this->etablissement->opcFiles as $opc) {
            $this->opcFiles[] = [
                'id' => $opc->id,
                'opc' => $opc->opc,
                'existing_incrument' => $opc->incrument,
                'existing_ni' => $opc->ni,
                'existing_fs' => $opc->fs,
                'existing_rg' => $opc->rg,
                // Placeholders for new uploads
                'incrument' => null,
                'ni' => null,
                'fs' => null,
                'rg' => null,
            ];
        }

        if (empty($this->opcFiles)) {
             $this->opcFiles[] = $this->getEmptyOpcFile();
        }

        if (empty($this->contacts)) {
            $this->addContact();
        }
    }

    public function getEmptyOpcFile()
    {
        return [
            'opc' => '',
            'incrument' => null,
            'ni' => null,
            'fs' => null,
            'rg' => null,
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

    public function toggleEdit()
    {
        $this->editing = !$this->editing;
    }

    public function addContact()
    {
        $this->contacts[] = [
            'nom' => '',
            'prenom' => '',
            'fonction' => '',
            'telephone' => '',
            'email' => '',
        ];
    }

    public function removeContact($index)
    {
        if (isset($this->contacts[$index]['id'])) {
            Contact::find($this->contacts[$index]['id'])?->delete();
        }
        unset($this->contacts[$index]);
        $this->contacts = array_values($this->contacts);
    }

    public function checkRisque()
    {
        $info = new InfoGeneral();
        $info->nom_rs = $this->raisonSocial;
        $info->raisonSocial = $this->raisonSocial;
        $info->paysResidence = $this->paysResidence;

        $risk = $info->checkRisk();

        $this->note = $risk['note'] ?? 1;
        $this->percentage = $risk['percentage'] ?? 0;
        $this->table_match = $risk['table'] ?? null;
        $this->match_id = $risk['match_id'] ?? null;

        $this->analysisResult = [
            'risk' => $risk,
            'is_interdit' => ($this->note >= 300),
            'is_high_risk' => ($this->note >= 30 && $this->note < 300),
            'is_medium_risk' => ($this->note < 30 && $this->note > 7),
            'is_low_risk' => ($this->note <= 7 && $this->note >= 1),
        ];

        $this->showRiskModal = true;
    }

    public function closeRiskModal()
    {
        $this->showRiskModal = false;
    }

    public function showDetail($id, $table)
    {
        $this->match_id = $id;
        $this->table_match = $table;

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

    public function rejectEtablissement()
    {
        $this->etablissement->update(['validation' => 'rejete']);
        session()->flash('error', 'Etablissement rejeté.');
        return redirect()->route('dashboard');
    }

    public function update()
    {
        // Validation similar to CreateInfoGeneral
        $this->validate([
            'raisonSocial' => 'required|string|max:200',
            'opcFiles.*.opc' => 'nullable|string|max:255',
            'opcFiles.*.incrument' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'opcFiles.*.ni' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'opcFiles.*.fs' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'opcFiles.*.rg' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        // Update InfoGeneral
        $info = $this->etablissement->infoGenerales()->updateOrCreate(
            ['etablissement_id' => $this->etablissement_id],
            [
                'raisonSocial' => $this->raisonSocial,
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
            ]
        );

        // Update Etablissement name
        $this->etablissement->update(['name' => $this->raisonSocial]);

        // Handle Files
        $slug = Str::slug($this->raisonSocial);
        $basePath = "informations_generales/{$info->id}";
        $fileMap = [
            'ice_file' => 'ice_file',
            'status_file' => 'status_file',
            'rc_file' => 'rc_file',
        ];

        foreach ($fileMap as $property => $folder) {
            if ($this->$property) {
                // Determine existing file property name
                $existingProp = 'existing_' . $property;

                if ($this->$existingProp) {
                    Storage::disk('public')->delete($this->$existingProp);
                }

                $filename = "{$slug}-{$folder}-" . time() . "." . $this->$property->getClientOriginalExtension();
                $path = $this->$property->storeAs("{$basePath}/{$folder}", $filename, 'public');
                $info->$property = $path;
                
                // Update local state
                $this->$existingProp = $path;
            }
        }
        $info->save();

        // Handle OPC Files
        if ($this->societe_gestion) {
            // 1. Identify IDs to keep
            $keptIds = [];
            foreach ($this->opcFiles as $opcData) {
                if (isset($opcData['id'])) {
                    $keptIds[] = $opcData['id'];
                }
            }
            
            // 2. Delete removed OPCs
            $this->etablissement->opcFiles()->whereNotIn('id', $keptIds)->delete();
            
            // 3. Update or Create
            foreach ($this->opcFiles as $opcData) {
                $opcModel = null;
                if (isset($opcData['id'])) {
                    $opcModel = \App\Models\opc_files::find($opcData['id']);
                } else {
                    $opcModel = new \App\Models\opc_files();
                    $opcModel->etablissement_id = $this->etablissement_id;
                }
                
                if ($opcModel) {
                     $opcModel->opc = $opcData['opc'] ?? null;
                     $opcSlug = Str::slug($opcModel->opc ?? 'opc');
                     $opcBasePath = "opc_files/{$this->etablissement_id}";
                     
                     // Handle File Fields
                     $opcFileFields = ['incrument', 'ni', 'fs', 'rg'];
                     foreach ($opcFileFields as $field) {
                         // Check if new file uploaded
                         if (isset($opcData[$field]) && $opcData[$field] instanceof \Illuminate\Http\UploadedFile) {
                             // Delete old if exists
                             if ($opcModel->$field) {
                                 Storage::disk('public')->delete($opcModel->$field);
                             }
                             $filename = "{$opcSlug}-{$field}-" . time() . "." . $opcData[$field]->getClientOriginalExtension();
                             $opcModel->$field = $opcData[$field]->storeAs($opcBasePath, $filename, 'public');
                         }
                     }
                     $opcModel->save();
                }
            }
        } else {
            // Prop not checked, maybe delete all if desired? Or just ignore.
            // Assuming if unchecked we don't clear logic, but usually user would clear list.
        }

        // Handle Contacts
        // Simplest strategy: update existing by ID, create new ones.
        // We already deleted removed ones in removeContact()
        foreach ($this->contacts as $contactData) {
            if (empty($contactData['nom'])) continue;

            $data = [
                'nom' => $contactData['nom'],
                'prenom' => $contactData['prenom'],
                'fonction' => $contactData['fonction'],
                'telephone' => $contactData['telephone'],
                'email' => $contactData['email'],
            ];

            if (isset($contactData['id'])) {
                $info->contacts()->where('id', $contactData['id'])->update($data);
            } else {
                $info->contacts()->create($data);
            }
        }

        // Refresh contacts to get new IDs
        $this->contacts = [];
        foreach ($info->contacts()->get() as $c) {
            $this->contacts[] = [
                'id' => $c->id,
                'nom' => $c->nom,
                'prenom' => $c->prenom,
                'fonction' => $c->fonction,
                'telephone' => $c->telephone,
                'email' => $c->email,
            ];
        }
        
         // Refresh OPC Files to get new IDs/Paths
        $this->opcFiles = [];
        foreach ($this->etablissement->opcFiles()->get() as $opc) {
             $this->opcFiles[] = [
                'id' => $opc->id,
                'opc' => $opc->opc,
                'existing_incrument' => $opc->incrument,
                'existing_ni' => $opc->ni,
                'existing_fs' => $opc->fs,
                'existing_rg' => $opc->rg,
                'incrument' => null,
                'ni' => null,
                'fs' => null,
                'rg' => null,
            ];
        }

        $this->editing = false;
        session()->flash('message', 'Informations générales mises à jour avec succès.');
    }

    public function render()
    {
        return view('livewire.edit-info-general');
    }
}
