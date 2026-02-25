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
        'validation_AK',
        'risque',
        'note',
        'created_by',
        'validation_AK_by',
        'validation_CI_by',
        'validation_AK_date',
        'validation_CI_date',
        'validation_CI',

           ];
    public function isCompleted(): bool
{
    return collect([
        'infoGenerales',
        'typologieClient',
        'BeneficiaireEffectif',
        'Administrateur',


    ])->every(fn ($relation) => method_exists($this, $relation) && $this->$relation()->exists());
}
        public function infoGenerales()
    {
        return $this->hasOne(InfoGeneral::class);   
    }

    public function opcFiles()
    {
        return $this->hasMany(opc_files::class);
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
        $raison_social_niveauRisque = $this->infoGenerales?->note ?? 0;
        $secteur_niveauRisque = $this->typologieClient?->secteur?->niveauRisque ?? 0;
        $segment_niveauRisque = $this->typologieClient?->segment_get?->niveauRisque ?? 0;
        $Pays_Residence_Client_niveauRisque = $this->infoGenerales?->paysresidence?->niveauRisque ?? 0;
        $Activite_regulee_niveauRisque = $this->infoGenerales?->regule ? 2 : 1;
        $pays_Activite_etranger_niveauRisque = $this->typologieClient?->paysEtrangerInfo?->niveauRisque ?? 0;
        $Origine_fonds_niveauRisque = $this->SituationFinanciere?->origineFonds ? 1 : 3;
        
        $client_noteRisque = 0;
        if ($raison_social_niveauRisque) $client_noteRisque += $raison_social_niveauRisque;
        if ($segment_niveauRisque) $client_noteRisque += $segment_niveauRisque;
        if ($Pays_Residence_Client_niveauRisque) $client_noteRisque += $Pays_Residence_Client_niveauRisque;
        if ($secteur_niveauRisque) $client_noteRisque += $secteur_niveauRisque;
        $client_noteRisque += $Activite_regulee_niveauRisque;
        $client_noteRisque += $pays_Activite_etranger_niveauRisque;
        $client_noteRisque += $Origine_fonds_niveauRisque;
        if($secteur_niveauRisque == 0 || $segment_niveauRisque == 0 || !$Pays_Residence_Client_niveauRisque){
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

        $this->update([
            'risque' => $SCORING,
            'note'       => $reting,
        ]);

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
        $maxNote = 0;
        $count = $this->BeneficiaireEffectif->count();
        if (!$count) return ['note' => 0, 'percentage' => 0, 'table' => null, 'match_id' => null];

        foreach ($this->BeneficiaireEffectif as $beneficiaire) {
            $note = $beneficiaire->note ?? 0;
            if ($note > $maxNote) {
                $maxNote = $note;
            }
        }

        return ['note' => $maxNote, 'percentage' => 0, 'table' => null, 'match_id' => null];
    }

    private function getActionnairesRisque(): array
    {
        $totalNote = 0;
        $count = $this->Actionnaire->count();
        if (!$count) return ['note' => 0, 'percentage' => 0, 'table' => null, 'match_id' => null];

        foreach ($this->Actionnaire as $actionnaire) {
            $totalNote += $actionnaire->note ?? 0;
        }

        return ['note' => $totalNote / $count, 'percentage' => 0, 'table' => null, 'match_id' => null];
    }

    private function getAdministrateurRisque(): array
    {
        $maxNote = 0;
        $count = $this->Administrateur->count();
        if (!$count) return ['note' => 1, 'percentage' => 0, 'table' => null, 'match_id' => null];

        foreach ($this->Administrateur as $admin) {
            $note = $admin->note ?? 0;
            if ($note > $maxNote) {
                $maxNote = $note;
            }
        }

        return ['note' => $maxNote, 'percentage' => 0, 'table' => null, 'match_id' => null];
    }

    private function getPersonnesHabiliteeRisque(): array
    {
        $totalNote = 0;
        $count = $this->PersonnesHabilites->count();
        if (!$count) return ['note' => 0, 'percentage' => 0, 'table' => null, 'match_id' => null];

        foreach ($this->PersonnesHabilites as $ph) {
            $totalNote += $ph->note ?? 0;
        }

        return ['note' => $totalNote / $count, 'percentage' => 0, 'table' => null, 'match_id' => null];
    }

    /* ================= AUDIT RELATIONSHIPS ================= */

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function validatorAK()
    {
        return $this->belongsTo(User::class, 'validation_AK_by');
    }

    public function validatorCI()
    {
        return $this->belongsTo(User::class, 'validation_CI_by');
    }
}
