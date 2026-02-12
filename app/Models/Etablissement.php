<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Etablissement extends Model
{
  use HasFactory;

    protected $table = 'etablissements';

    protected $fillable = [

        'name',
        'InfoGeneral',
        'CoordonneesBancaire',
        'typologie_clients',
        'statutfatcas',
        'situation_financiere',
        'actionnariat',
        'beneficiaires_effectifs',
        'administrateurs',
        'personnes_habilites',
        'objet_relations',
        'profil_risques',
        'validation',
        'risque',
        'note',

           ];
    public function isCompleted(): bool
{
    return collect([
        'infoGenerales',
        'typologieClient',
        'Actionnaire',
        'BeneficiaireEffectif',
        'Administrateur',


    ])->every(fn ($relation) => method_exists($this, $relation) && $this->$relation()->exists());
}
        public function infoGenerales()
    {
        return $this->hasOne(InfoGeneral::class);   
    }
    public function CoordonneesBancaires()
    {
        return $this->hasMany(CoordonneesBancaires::class);
    }
    public function typologieClient()
    {
        return $this->hasOne(TypologieClient::class);
    }
    public function StatutFatca()
    {
        return $this->hasOne(StatutFatca::class);
    }
    public function SituationFinanciere()
    {
        return $this->hasOne(SituationFinanciere::class);
    }
    public function Actionnaire()
    {
        return $this->hasMany(Actionnariat::class);
    }
    public function BeneficiaireEffectif()
    {
        return $this->hasMany(BenificiaireEffectif::class);
    }
    public function Administrateur()
    {
        return $this->hasMany(Administrateur::class);
    }
    public function PersonnesHabilites()
    {
        return $this->hasMany(PersonnesHabilites::class);
    }
    public function ObjetRelation()
    {
        return $this->hasOne(ObjetRelation::class);
    }
    public function ProfilRisque()
    {
        return $this->hasOne(ProfilRisque::class);
    }

    /**
     * Centralized Risk Rating Update
     */
    public function updateRiskRating()
    {
    if (!method_exists($this, 'isCompleted') || !$this->isCompleted()) {
            $this->update([
            'risque' => '-',
            'note'   => 0,
        ]);
            return back()->with('error', 'Etablissement non complété');
        }
        
        $ratingData = $this->calculateRiskRating();
        if ($ratingData['client']['total_client'] == 0 || $ratingData['beneficiaire_effectif']['note'] == 0 ||$ratingData['administrateurs']['note'] == 0 ){
            $this->update([
            'risque' => '-',
            'note'   => 0,
        ]);
        };
        
        
        $this->update([
            'risque' => $ratingData['global']['scoring'],
            'note'   => $ratingData['global']['note_totale'],
        ]);

        return $ratingData;
    }

    /**
     * Calculate all risk factors
     */
    public function calculateRiskRating(): array
    {
        $this->loadMissing([
            'typologieClient.secteur',
            'typologieClient.segment_get',
            'typologieClient.paysEtrangerInfo',
            'infoGenerales.paysresidence',
            'infoGenerales.paysActiviteInfo',
            'SituationFinanciere',
            'BeneficiaireEffectif.ppeRelation',
            'BeneficiaireEffectif.lienPpeRelation',
            'Administrateur.ppeRelation',
            'Administrateur.lienPpeRelation',
            'PersonnesHabilites.ppeRelation',
            'PersonnesHabilites.lienPpeRelation',
        ]);

        /* ================= CLIENT RISKS ================= */
        $secteur_niveauRisque = $this->typologieClient?->secteur?->niveauRisque ?? 0;
        $segment_niveauRisque = $this->typologieClient?->segment_get?->niveauRisque ?? 0;
        $Pays_Residence_Client_niveauRisque = $this->infoGenerales?->paysresidence?->niveauRisque ?? 0;
        $Activite_regulee_niveauRisque = $this->infoGenerales?->regule ? 1 : 2;
        $pays_Activite_etranger_niveauRisque = $this->typologieClient?->paysEtrangerInfo?->niveauRisque ?? 0;
        $Origine_fonds_niveauRisque = $this->SituationFinanciere?->origineFonds ? 1 : 3;
        
        $client_noteRisque = 0;
        if ($segment_niveauRisque) $client_noteRisque += $segment_niveauRisque;
        if ($Pays_Residence_Client_niveauRisque) $client_noteRisque += $Pays_Residence_Client_niveauRisque;
        if ($secteur_niveauRisque) $client_noteRisque += $secteur_niveauRisque;
        $client_noteRisque += $Activite_regulee_niveauRisque;
        $client_noteRisque += $pays_Activite_etranger_niveauRisque;
        $client_noteRisque += $Origine_fonds_niveauRisque;
        if($secteur_niveauRisque == 0 || $segment_niveauRisque == 0 || $Pays_Residence_Client_niveauRisque == 0){
            $client_noteRisque = 0;
        }

        /* ================= OTHER RISKS ================= */
        $BeneficiaireEffectif_niveauRisque = $this->getBeneficiaireEffectifTotalRisque();
        $Actionnaires_niveauRisque         = $this->getActionnairesRisque();
        $Administrateur_niveauRisque       = $this->getAdministrateurRisque();
        $Personnes_Habilitee_niveauRisque  = $this->getPersonnesHabiliteeRisque();
        $ObjetRelation_niveauRisque        = $this->ObjetRelation?->mandataire_check ? 30 : 0;

        /* ================= TOTAL RATING ================= */
        $reting = $client_noteRisque
            + ($BeneficiaireEffectif_niveauRisque['note'] ?? 0)
            + ($Actionnaires_niveauRisque['note'] ?? 0)
            + ($Administrateur_niveauRisque['note'] ?? 0)
            + ($Personnes_Habilitee_niveauRisque['note'] ?? 0)
            + $ObjetRelation_niveauRisque;

        /* ================= SCORING ================= */
        $SCORING = match (true) {
            $reting < 33  => 'LR',
            $reting < 49  => 'MR',
            default       => 'HR',
        };

        return [
            'client' => [
                'segment'                 => $segment_niveauRisque,
                'pays_residence'           => $Pays_Residence_Client_niveauRisque,
                'secteur'                  => $secteur_niveauRisque,
                'activite_regulee'         => $Activite_regulee_niveauRisque,
                'pays_activite_etranger'   => $pays_Activite_etranger_niveauRisque,
                'origine_fonds'            => $Origine_fonds_niveauRisque,
                'total_client'             => $client_noteRisque,
            ],
            'beneficiaire_effectif' => $BeneficiaireEffectif_niveauRisque,
            'actionnaires'          => $Actionnaires_niveauRisque,
            'administrateurs'       => $Administrateur_niveauRisque,
            'personnes_habilitees'  => $Personnes_Habilitee_niveauRisque,
            'objet_relation'        => ['note' => $ObjetRelation_niveauRisque],
            'global' => [
                'note_totale' => $reting,
                'scoring'     => $SCORING,
            ],
        ];
    }

    private function getBeneficiaireEffectifTotalRisque(): array
    {
        $beneficiaire = $this->BeneficiaireEffectif->first();
        if (!$beneficiaire) return ['note' => 0, 'percentage' => 0, 'table' => null];

        $transparence = method_exists($beneficiaire, 'checkRisk') ? $beneficiaire->checkRisk() : ['note'=>0,'percentage'=>0,'table'=>null];
        if ($transparence['table']=='Cnasnu') return ['note' => 300, 'percentage' => $transparence['percentage'], 'table' => $transparence['table']];
        $hasPPE = $beneficiaire->ppeRelation?->libelle ?? $beneficiaire->lienPpeRelation?->libelle;
        $ppe_niveauRisque = $hasPPE ? 30 : 1;
        $nation_niveauRisque = $beneficiaire->nationalite?->niveauRisque ?? 0;

        return [
            'note' => ($transparence['note'] ?? 0) + $ppe_niveauRisque + $nation_niveauRisque,
            'percentage' => round($transparence['percentage'] ?? 0),
            'table' => $transparence['table'] ?? null,
        ];
    }

    private function getActionnairesRisque(): array
    {
        $actionnaire = $this->Actionnaire->first();
        if (!$actionnaire) return ['note' => 0, 'percentage' => 0, 'table' => null];

        $niveauRisque = method_exists($actionnaire, 'checkRisk') ? $actionnaire->checkRisk() : ['note' => 2, 'percentage' => 0, 'table' => null];
        return [
            'note' => $niveauRisque['note'] ?? 0,
            'percentage' => isset($niveauRisque['percentage']) ? round($niveauRisque['percentage']) : 0,
            'table' => $niveauRisque['table'] ?? null,
        ];
    }

    private function getAdministrateurRisque(): array
    {
        $admin = $this->Administrateur->first();
        if (!$admin) return ['note'=>1, 'percentage'=>0, 'table'=>null];

        $transparence = method_exists($admin, 'checkRisk') ? $admin->checkRisk() : ['note'=>0,'percentage'=>0,'table'=>null];
        if($transparence['table']=='Cnasnu') return ['note' => 300, 'percentage' => $transparence['percentage'], 'table' => $transparence['table']];
        $hasPPE = $admin->ppeRelation?->libelle ?? $admin->lienPpeRelation?->libelle;
        $ppe_niveauRisque = $hasPPE ? 30 : 1;
        $nation_niveauRisque = $admin->nationalite?->niveauRisque ?? 0;

        return [
            'note' => ($transparence['note'] ?? 0) + $ppe_niveauRisque + $nation_niveauRisque,
            'percentage' => round($transparence['percentage'] ?? 0),
            'table' => $transparence['table'] ?? null,
        ];
    }

    private function getPersonnesHabiliteeRisque(): array
    {
        $ph = $this->PersonnesHabilites->first();
        if (!$ph) return ['note'=>0,'percentage'=>0,'table'=>null];

        $transparence = method_exists($ph, 'checkRisk') ? $ph->checkRisk() : ['note'=>0,'percentage'=>0,'table'=>null];
        $hasPPE = $ph->ppeRelation?->libelle ?? $ph->lienPpeRelation?->libelle;
        $ppe_niveauRisque = $hasPPE ? 30 : 1;
        $nation_niveauRisque = $ph->nationalite?->niveauRisque ?? 0;

        return [
            'note' => ($transparence['note'] ?? 0) + $ppe_niveauRisque + $nation_niveauRisque,
            'percentage' => round($transparence['percentage'] ?? 0),
            'table' => $transparence['table'] ?? null,
        ];
    }
}
