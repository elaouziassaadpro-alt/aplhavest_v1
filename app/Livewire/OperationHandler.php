<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Operation;
use App\Models\Etablissement;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class OperationHandler extends Component
{
    use WithFileUploads, WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $excelFile;
    public $search = '';
    public $successMessage = '';
    public $errorMessage = '';

    public $operation = [];

    protected $updatesQueryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        //
    }

    public function importExcel()
    {
        $this->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $path = $this->excelFile->getRealPath();
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Skip header
            $data = array_slice($rows, 1);
            $tempOperations = [];

            foreach ($data as $row) {
                if (empty(array_filter($row))) continue;
                
                $tempOperations[] = [
                    'operation_number' => $row[0] ?? null,
                    'event_number'     => $row[1] ?? null,
                    'titre'            => $row[2] ?? null,
                    'titre_description'=> $row[3] ?? null,
                    'poste'            => $row[4] ?? null,
                    'entite'           => $row[5] ?? null,
                    'portefeuille'     => $row[6] ?? null,
                    'portefeuille_description' => $row[7] ?? null,
                    'statut'           => $row[8] ?? null,
                    'date_saisi'       => $this->parseDate($row[9]),
                    'date_operation'   => $this->parseDate($row[10]),
                    'date_valeur'      => $this->parseDate($row[11]),
                    'date_livraison'   => $this->parseDate($row[12]),
                    'date_validation'  => $this->parseDate($row[13]),
                    'date_annulation'  => $this->parseDate($row[14]),
                    'intermediaire'    => $row[15] ?? null,
                    'depositaire'      => $row[16] ?? null,
                    'compte_titre'     => $row[17] ?? null,
                    'compte_espece'    => $row[18] ?? null,
                    'contrepartie'     => $row[19] ?? null,
                    'contrepartie_description' => $row[20] ?? null,
                    'depositaire_contrepartie' => $row[21] ?? null,
                    'compte_titres_contrepartie' => $row[22] ?? null,
                    'quantite'         => (int) ($row[23] ?? 0),
                    'cours'            => $this->parseFloat($row[24]),
                    'montant_devise'   => $this->parseFloat($row[25]),
                    'devise_ref'       => $row[26] ?? null,
                    'taux_ref'         => $this->parseFloat($row[27]),
                    'devise_reg'       => $row[28] ?? null,
                    'frais_total'      => $this->parseFloat($row[29]),
                    'montant_brut'     => $this->parseFloat($row[30]),
                    'montant_net'      => $this->parseFloat($row[31]),
                    'interet_couru'    => $this->parseFloat($row[32]),
                    'pmv_back'         => $this->parseFloat($row[33]),
                    'contrat'          => $row[34] ?? null,
                    'titre_jouissance' => $this->parseDate($row[35]),
                    'titre_echeance'   => $this->parseDate($row[36]),
                    'prix_nego'        => $this->parseFloat($row[37]),
                    'prix_ppc'         => $this->parseFloat($row[38]),
                    'nego_spread'      => $this->parseFloat($row[39]),
                    'nego_taux'        => $this->parseFloat($row[40]),
                    'taux_placement'   => $this->parseFloat($row[41]),
                    'nbre_jours_placement' => (int) ($row[42] ?? 0),
                    'interets'         => $this->parseFloat($row[43]),
                    'decalage_valeur'  => (int) ($row[44] ?? 0),
                    'ope_front'        => $row[45] ?? null,
                    'ope_back'         => $row[46] ?? null,
                    'ope_annul'        => $row[47] ?? null,
                    'date_echeance'    => $this->parseDate($row[48]),
                    'code_isin'        => $row[49] ?? null,
                    'emetteur'         => $row[50] ?? null,
                    'classe'           => $row[51] ?? null,
                    'categorie'        => $row[52] ?? null,
                ];
            }

            $this->operation = $tempOperations;
            $this->successMessage = count($this->operation) . " opérations lues. Veuillez cliquer sur Envoyer pour enregistrer.";
            $this->excelFile = null;

        } catch (\Exception $e) {
            $this->errorMessage = "Erreur lors de l'importation : " . $e->getMessage();
        }
    }

    public function save()
    {
        if (empty($this->operation)) {
            return;
        }

        try {
            DB::beginTransaction();
            foreach ($this->operation as $data) {
                Operation::create($data);
            }
            DB::commit();

            $this->successMessage = count($this->operation) . " opérations enregistrées avec succès !";
            $this->operation = [];
            $this->resetPage();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorMessage = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }

    public function cancel()
    {
        $this->operation = [];
        $this->successMessage = "Importation annulée.";
    }

    private function parseDate($value)
    {
        if (!$value) return null;
        try {
            return \Carbon\Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parseFloat($value)
    {
        if (!$value) return 0;
        return (float) str_replace([' ', ','], ['', '.'], $value);
    }

    public function render()
    {
        if (!empty($this->operation)) {
            $collection = collect($this->operation)->map(fn($i) => (object) $i);
            
            if ($this->search) {
                $collection = $collection->filter(function($op) {
                    return str_contains(strtolower($op->titre), strtolower($this->search)) ||
                           str_contains(strtolower($op->operation_number), strtolower($this->search)) ||
                           str_contains(strtolower($op->code_isin), strtolower($this->search));
                });
            }

            $currentPage = $this->getPage();
            $items = $collection->forPage($currentPage, 20);
            $pagedOperations = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $collection->count(),
                20,
                $currentPage,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
            );
        } else {
            $query = Operation::query();
            if ($this->search) {
                $query->where(function($q) {
                    $q->where('titre', 'like', '%' . $this->search . '%')
                      ->orWhere('operation_number', 'like', '%' . $this->search . '%')
                      ->orWhere('code_isin', 'like', '%' . $this->search . '%')
                      ->orWhere('portefeuille', 'like', '%' . $this->search . '%');
                });
            }
            $pagedOperations = $query->latest()->paginate(20);
        }

        return view('livewire.operation-handler', [
            'pagedOperations' => $pagedOperations
        ]);
    }
}
