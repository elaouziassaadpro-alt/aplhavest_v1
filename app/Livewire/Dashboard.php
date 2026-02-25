<?php

namespace App\Livewire;

use App\Models\Actionnariat;
use App\Models\Administrateur;
use App\Models\BenificiaireEffectif;
use App\Models\Contact;
use App\Models\Etablissement;
use App\Models\PersonnesHabilites;
use App\Models\Operation;
use App\Models\opc_files;
use Livewire\Component;
use App\Models\InfoGeneral;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public function render()
    {
        // Totals
        $etablissments = Etablissement::all();
        $actionnaires = Actionnariat::all();
        $benificiaireseffectifs = BenificiaireEffectif::all();
        $administrateurs = Administrateur::all();
        $personnesHabilites = PersonnesHabilites::all();
        $contacts = Contact::all();

        // Risk Breakdown (for Breakup Chart)
        $riskCounts = [
            'LR' => Etablissement::where('risque', 'LR')->count(),
            'MR' => Etablissement::where('risque', 'MR')->count(),
            'HR' => Etablissement::where('risque', 'HR')->count(),
        ];

        // Recent Operations
        $recentOperations = Operation::latest()->take(5)->get();
        $operationCount = Operation::count();

        // Recent Files
        $recentFiles = opc_files::with('etablissement')->latest()->take(5)->get();

        // Total Operations Amount
        $totalOperationsAmount = Operation::sum('montant_net');

        // PPE Counts (Beneficiaries and Administrators who are PPE or have a link to PPE)
        $ppeBenCount = BenificiaireEffectif::whereNotNull('ppe_id')->orWhereNotNull('ppe_lien_id')->count() ?: 0;
        $ppeAdminCount = Administrateur::whereNotNull('ppe_id')->orWhereNotNull('lien_ppe_id')->count() ?: 0;
        $totalPpeCount = $ppeBenCount + $ppeAdminCount;

        // Incomplete Etablissements
        $incompleteEtablissementsCount = Etablissement::where('risque', '-')->count();

        // Distribution by Sector (Top 5)
        $sectorDistribution = DB::table('typologie_clients')
            ->join('secteurs', 'typologie_clients.secteurActivite', '=', 'secteurs.id')
            ->select('secteurs.libelle', DB::raw('count(*) as count'))
            ->groupBy('secteurs.libelle')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        // Distribution by Country (Top 5)
        $top5Pays = DB::table('info_generales as ig')
            ->join('pays as p', 'ig.paysResidence', '=', 'p.id')
            ->select(
                'p.id',
                'p.libelle',
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull('ig.paysResidence')
            ->groupBy('p.id', 'p.libelle')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        // Monthly Trend (for main chart) - Simple count per month for the last 6 months
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Etablissement::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $monthlyTrend[] = [
                'month' => $date->format('M'),
                'count' => $count
            ];
        }

        return view('livewire.dashboard', compact(
            'etablissments',
            'actionnaires',
            'benificiaireseffectifs',
            'administrateurs',
            'personnesHabilites',
            'contacts',
            'riskCounts',
            'recentOperations',
            'recentFiles',
            'monthlyTrend',
            'operationCount',
            'totalOperationsAmount',
            'totalPpeCount',
            'incompleteEtablissementsCount',
            'sectorDistribution',
            'top5Pays'
        ));
    }
}
