<?php

namespace App\Traits;

use App\Models\Cnasnu;
use App\Models\Anrf;
use App\Models\ANRF_PP;

trait NiveauRisqueTrait
{
    public function niveauRisqueAuto(): array
    {
        $beneficiaireId = preg_replace('/\D/', '', $this->identite ?? '');

        $nom    = $this->nom_rs ?? $this->nom;
        $prenom = $this->prenom;

        $cnasnu = $this->Cnasnu($beneficiaireId, $nom, $prenom);
        $anrf   = $this->Anrf($beneficiaireId, $nom, $prenom);

        // ================= IDENTITÉ =================
        if (!$this->identite) {
            $identityRisk = 3;
        } elseif (!$this->cin_file) {
            $identityRisk = 2;
        } else {
            $identityRisk = 1;
        }

        // ================= PPE =================
        $ppeniveaurisque = 1;

        if ($this->ppe || $this->lien_ppe) {
            $ppeniveaurisque = 30;
        }

        // ================= NATIONALITÉ =================
        $nationaliteniveaurisque = 1;

        if ($this->nationalite) {
            $nationaliteniveaurisque = $this->nationalite->niveauRisque;
        }

        // ================= FINAL CALCULATION =================
        $cnasnu_note = $cnasnu['note'];
        
        // If it's a Cnasnu match, calculate based on entity importance
        if ($cnasnu['table'] === 'Cnasnu') {
            $className = get_class($this);
            // Administrators, Beneficiaries, and General Info are high importance (300)
            if (str_contains($className, 'Administrateur') || 
                str_contains($className, 'BenificiaireEffectif') || 
                str_contains($className, 'InfoGeneral')) {
                $cnasnu_note = 300;
            } else {
                // Shareholders and Authorized Persons are lower importance (30)
                $cnasnu_note = 30;
            }
        }

        $total_note = $cnasnu_note
                    + $anrf['note']
                    + $identityRisk
                    + $ppeniveaurisque
                    + $nationaliteniveaurisque;

        return [
            'note'              => $total_note,
            'cnasnu_note'       => $cnasnu_note,
            'anrf_note'         => $anrf['note'],
            'identite_note'     => $identityRisk,
            'ppe_note'          => $ppeniveaurisque,
            'nationalite_note'  => $nationaliteniveaurisque,
            'percentage'        => max($cnasnu['percentage'], $anrf['percentage']),
            'table'             => $cnasnu['table'] ?? $anrf['table'] ?? '-',
            'match_id' => $cnasnu['match_id'] ?? $anrf['match_id'] ?? null,
        ];
    }


protected function Cnasnu($identite, $nom, $prenom): array
{
    if (!$identite && !$nom) {
        return [
            'note' => 1,
            'percentage' => 0,
            'table' => null,
            'match_id' => null
        ];
    }

    $riskNote = 30; // Default low risk note
    $className = get_class($this);
    if (str_contains($className, 'Administrateur') || 
        str_contains($className, 'BenificiaireEffectif') || 
        str_contains($className, 'InfoGeneral')) {
        $riskNote = 300;
    }

    /* =========================
       1️⃣ MATCH IDENTIFIANT (Exact ID Match)
       ========================= */

    if ($identite) {
        // Use regex directly in query for cleaner match
        $matchById = Cnasnu::whereRaw(
            "REGEXP_REPLACE(documentNumber, '[^0-9]', '') = ?",
            [$identite]
        )->first();

        if ($matchById) {
            return [
                'note'       => $riskNote,
                'percentage' => 100,
                'table'      => 'Cnasnu',
                'match_id'   => $matchById->id
            ];
        }
    }

    /* =========================
       2️⃣ FUZZY MATCH (Best Match Search)
       ========================= */

    // Normalize inputs once
    $nomBenef     = $this->normalize($nom);
    $prenomBenef  = $this->normalize($prenom);
    $fullNameBenef = $this->normalize("$prenom $nom");
    $fullNameBenefInv = $this->normalize("$nom $prenom");

    // Fetch only necessary columns to minimize memory usage
    $records = Cnasnu::select('id', 'firstName', 'secondName', 'thirdName', 'fourthName')->get();

    $bestMatch = null;
    $highestScore = 0;

    foreach ($records as $record) {
        // ── Normalize all name parts ─────────────────────────────────────────
        $first  = $this->normalize($record->firstName);
        $second = $this->normalize($record->secondName);
        $third  = $this->normalize($record->thirdName);
        $fourth = $this->normalize($record->fourthName);

        // Full name as stored in the list (all 4 parts concatenated)
        $recordFull    = trim("$first $second $third $fourth");
        $recordFull    = preg_replace('/\s+/', ' ', $recordFull);

        $scores = [];

        // ── Scenario 1 : Full name match (weight 1.0 → max 100 %) ───────────
        // Compare the complete list name against the beneficiary name in
        // both natural order (prenom nom) and reversed (nom prenom).
        similar_text($recordFull, $fullNameBenef,    $s1a); $scores[] = $s1a;
        similar_text($recordFull, $fullNameBenefInv, $s1b); $scores[] = $s1b;

        // ── Scenario 2 : Component match with name-swap handling ─────────────
        // Each combination produces ONE combined score so it can reach 100 %.
        // Weights: last-name (family name) counts more (0.6) than first name (0.4).

        // Direct   → record.first ≈ prenom,  record.second ≈ nom
        similar_text($first,  $prenomBenef, $d1);
        similar_text($second, $nomBenef,    $d2);
        $scores[] = $d1 * 0.4 + $d2 * 0.6;   // max = 100

        // Swapped  → record.first ≈ nom,     record.second ≈ prenom
        similar_text($first,  $nomBenef,    $sw1);
        similar_text($second, $prenomBenef, $sw2);
        $scores[] = $sw1 * 0.6 + $sw2 * 0.4; // max = 100

        // With third/fourth filling in as "nom" (some records have 3-4 parts)
        similar_text($third,  $nomBenef,    $t1);
        similar_text($fourth, $nomBenef,    $t2);
        $scores[] = $d1  * 0.4 + $t1 * 0.6; // first≈prenom, third≈nom
        $scores[] = $d1  * 0.4 + $t2 * 0.6; // first≈prenom, fourth≈nom

        // ── Scenario 3 : Partial 2-name pairs (weight 0.9) ──────────────────
        // All C(4,2) = 6 unique pairs — no duplicates.
        // Useful when the beneficiary has fewer names than the list entry.
        $pairs = [
            ["$first $second",  $fullNameBenef],
            ["$second $third",  $fullNameBenef],
            ["$third $fourth",  $fullNameBenef],
            ["$first $third",   $fullNameBenef],
            ["$first $fourth",  $fullNameBenef],
            ["$second $fourth", $fullNameBenef],
        ];
        foreach ($pairs as [$pair, $target]) {
            similar_text($pair, $target, $pScore);
            $scores[] = $pScore * 0.9;
        }

        // ── Best score for this record ───────────────────────────────────────
        $currentMax = max($scores);

        if ($currentMax > $highestScore) {
            $highestScore = $currentMax;
            $bestMatch    = $record;
        }
    }

    // Determine final result based on the best match found
    if ($highestScore >= 70) { // Threshold for fuzzy match
        return [
            'note'       => $riskNote,
            'percentage' => round($highestScore, 2),
            'table'      => 'Cnasnu',
            'match_id'   => $bestMatch->id
        ];
    }

    return [
        'note' => 1,
        'percentage' => 0,
        'table' => null,
        'match_id' => null
    ];
}



protected function Anrf($identite, $nom, $prenom): array
{
    if (!$identite && !$nom) {
        return [
            'note' => 1,
            'percentage' => 0,
            'table' => null,
            'match_id' => null
        ];
    }

    $riskNote = 3; // Default note for Anrf

    /* =========================
       1️⃣ MATCH IDENTIFIANT (Exact ID Match)
       ========================= */
    if ($identite) {
        // Check ANRF table
        $matchAnrf = Anrf::whereRaw(
            "REGEXP_REPLACE(identifiant, '[^0-9]', '') = ?",
            [$identite]
        )->first();
        
        if ($matchAnrf) {
            return [
                'note'       => $riskNote,
                'percentage' => 100,
                'table'      => 'Anrf',
                'match_id'   => $matchAnrf->id
            ];
        }

        // Check ANRF_PP table
        $matchAnrfPP = ANRF_PP::whereRaw(
            "REGEXP_REPLACE(identifiant, '[^0-9]', '') = ?",
            [$identite]
        )->first();

        if ($matchAnrfPP) {
            return [
                'note'       => $riskNote,
                'percentage' => 100,
                'table'      => 'ANRF_PP',
                'match_id'   => $matchAnrfPP->id
            ];
        }
    }

    /* =========================
       2️⃣ FUZZY MATCH (Best Match Search)
       ========================= */

    // Normalize inputs once
    $fullNameBenef    = $this->normalize("$nom $prenom");
    $fullNameBenefInv = $this->normalize("$prenom $nom");
    $nomBenef         = $this->normalize($nom);
    $prenomBenef      = $this->normalize($prenom);

    $bestMatch = null;
    $highestScore = 0;
    $bestTable = null;

    // --- Search in Anrf ---
    $anrfRecords = Anrf::select('id', 'nom')->get();
    foreach ($anrfRecords as $record) {
        $recordNom = $this->normalize($record->nom);
        $scores = [];
        
        similar_text($recordNom, $fullNameBenef, $s1);
        $scores[] = $s1;
        similar_text($recordNom, $fullNameBenefInv, $sInv);
        $scores[] = $sInv;
        similar_text($recordNom, $nomBenef, $s2);
        $scores[] = $s2 * 0.9;
        similar_text($recordNom, $prenomBenef, $s3);
        $scores[] = $s3 * 0.9;
        
        $currentMax = max($scores);
        if ($currentMax > $highestScore) {
            $highestScore = $currentMax;
            $bestMatch = $record;
            $bestTable = 'Anrf';
        }
    }

    // --- Search in ANRF_PP ---
    $anrfPpRecords = ANRF_PP::select('id', 'nom', 'prenom')->get();
    foreach ($anrfPpRecords as $record) {
        $recordNom    = $this->normalize($record->nom);
        $recordPrenom = $this->normalize($record->prenom);
        $recordFull   = trim("$recordNom $recordPrenom");
        $recordFullInv = trim("$recordPrenom $recordNom");

        $scores = [];
        
        similar_text($recordFull, $fullNameBenef, $s1);
        $scores[] = $s1;
        similar_text($recordFull, $fullNameBenefInv, $sInv);
        $scores[] = $sInv;
        similar_text($recordFullInv, $fullNameBenef, $sInv2);
        $scores[] = $sInv2;

        // Partial component match
        similar_text($recordNom, $nomBenef, $sn);
        similar_text($recordPrenom, $prenomBenef, $sp);
        $scores[] = ($sn * 0.6 + $sp * 0.4);

        similar_text($recordNom, $prenomBenef, $snp);
        similar_text($recordPrenom, $nomBenef, $spn);
        $scores[] = ($snp * 0.4 + $spn * 0.6);
        
        $currentMax = max($scores);
        if ($currentMax > $highestScore) {
            $highestScore = $currentMax;
            $bestMatch = $record;
            $bestTable = 'ANRF_PP';
        }
    }

    if ($highestScore >= 80) { // Threshold
        return [
            'note'       => $riskNote,
            'percentage' => round($highestScore, 2),
            'table'      => $bestTable,
            'match_id'   => $bestMatch->id
        ];
    }

    /* =========================
       DEFAULT SAFE RETURN
       ========================= */
    return [
        'note' => 1,
        'percentage' => 0,
        'table' => null,
        'match_id' => null
    ];
}


    public function checkIdentity(): array
{
    $identite = preg_replace('/\D/', '', $this->identite ?? '');

    if (empty($identite)) {
        $identite = preg_replace('/\D/', '', $this->rc_input ?? '');
    }

    $nom    = $this->nom_rs ?? $this->nom;
    $prenom = $this->prenom;

    $cnasnu = $this->Cnasnu($identite, $nom, $prenom);
    $anrf   = $this->Anrf($identite, $nom, $prenom);

    $cnasnu_note = $cnasnu['note'] ?? 1;
    if (($cnasnu['table'] ?? '') === 'Cnasnu') {
        $className = get_class($this);
        $cnasnu_note = (str_contains($className, 'Administrateur') || 
                       str_contains($className, 'BenificiaireEffectif') || 
                       str_contains($className, 'InfoGeneral')) ? 300 : 30;
    }

    return [
        'note'       => $cnasnu_note + ($anrf['note'] ?? 1),
        'percentage' => max($cnasnu['percentage'] ?? 0, $anrf['percentage'] ?? 0),
        'table'      => $cnasnu['table'] ?? $anrf['table'] ?? '-',
        'match_id' => $cnasnu['match_id'] ?? $anrf['match_id'] ?? null
    ];
}

/**
 * Helper method to find a match in a list of records based on ID and fuzzy name.
 */
private function findMatchInList(string $beneficiaireId, string $fullNameBenef, iterable $records, string $idField, array $nameFields, int $riskNote, string $tableName): ?array
{
    foreach ($records as $record) {
        // Normalize record ID
        $recordId = preg_replace('/\D/', '', $record->{$idField} ?? '');

        // 1. Exact ID match (if ID is provided)
        if (!empty($beneficiaireId) && !empty($recordId) && $recordId === $beneficiaireId) {
            return ['note' => $riskNote, 'percentage' => 100, 'table' => $tableName];
        }

        // 2. Fuzzy name similarity
        $fullNameRecord = trim(implode(' ', array_map(fn($f) => $record->{$f} ?? '', $nameFields)));

        if (!empty($fullNameBenef) && !empty($fullNameRecord)) {
            similar_text(
                mb_strtolower($fullNameRecord, 'UTF-8'),
                mb_strtolower($fullNameBenef, 'UTF-8'),
                $percent
            );

            if ($percent >= 70) { // threshold: 70%
                return ['note' => $riskNote, 'percentage' => round($percent), 'table' => $tableName];
            }
        }
    }

    return null;
}
private function normalize(?string $value): string
{
    return mb_strtolower(
        trim(preg_replace('/\s+/', ' ', $value ?? '')),
        'UTF-8'
    );
}


}
