<?php

namespace App\Traits;

use App\Models\Cnasnu;
use App\Models\Anrf;

trait NiveauRisqueTrait
{
    public function niveauRisqueAuto(): array
{
    $beneficiaireId = preg_replace('/\D/', '', $this->identite ?? '');

    $nom    = $this->nom_rs;
    $prenom = $this->prenom;

    $cnasnu = $this->Cnasnu($beneficiaireId, $nom, $prenom);
    $anrf   = $this->Anrf($beneficiaireId, $nom);

    // Facteur identité
    if (!$this->identite) {
        $identityRisk = 3;
    } elseif (!$this->cin_file) {
        $identityRisk = 2;
    } else {
        $identityRisk = 1;
    }

    return [
        'note'       => $cnasnu['note'] + $anrf['note'] + $identityRisk,
        'percentage' => max($cnasnu['percentage'], $anrf['percentage']),
        'table'      => $cnasnu['table'] ?? $anrf['table'] ?? '-',
    ];
}

protected function Cnasnu($beneficiaireId, $nom, $prenom): array
{
    if (!$beneficiaireId && !$nom) {
        return [
            'note' => 1,
            'percentage' => 0,
            'table' => null
        ];
    }

    /* =========================
       1️⃣ MATCH IDENTIFIANT
       ========================= */

    if ($beneficiaireId) {

        $matchById = Cnasnu::whereRaw(
            "REGEXP_REPLACE(dataID, '[^0-9]', '') = ?",
            [$beneficiaireId]
        )->first();

        if ($matchById) {
            return [
                'note'       => 30,
                'percentage' => 100,
                'table'      => 'Cnasnu'
            ];
        }
    }

    /* =========================
       2️⃣ FUZZY MATCH
       ========================= */

    $records = Cnasnu::select(
        'firstName',
        'secondName',
        'thirdName',
        'fourthName'
    )->get();

    $nomBenef     = $this->normalize($nom);
    $prenomBenef  = $this->normalize($prenom);

    foreach ($records as $record) {

        $scores = [];

        $first  = $this->normalize($record->firstName);
        $second = $this->normalize($record->secondName);
        $third  = $this->normalize($record->thirdName);
        $fourth = $this->normalize($record->fourthName);

        /* =========================
           SCENARIO 1 — Direct
        ========================== */

        similar_text($first,  $nomBenef,    $p1);
        similar_text($second, $prenomBenef, $p2);

        $scores[] = $p1;
        $scores[] = $p2;

        /* =========================
           SCENARIO 2 — Inversé
        ========================== */

        similar_text($first,  $prenomBenef, $p3);
        similar_text($second, $nomBenef,    $p4);

        $scores[] = $p3;
        $scores[] = $p4;

        /* =========================
           SCENARIO 3 — Anywhere
        ========================== */

        similar_text($third,  $prenomBenef, $p5);
        similar_text($fourth, $prenomBenef, $p6);

        $scores[] = $p5;
        $scores[] = $p6;

        similar_text($third,  $nomBenef, $p7);
        similar_text($fourth, $nomBenef, $p8);

        $scores[] = $p7;
        $scores[] = $p8;

        /* =========================
           SCENARIO 4 — FULL NAME 🔥
        ========================== */

        $fullNameRecord = $this->normalize("$first $second $third $fourth");
        $fullNameBenef  = $this->normalize("$nom $prenom");

        similar_text($fullNameRecord, $fullNameBenef, $globalScore);

        $scores[] = $globalScore;

        /* =========================
           FINAL DECISION
        ========================== */

        $bestScore = max($scores);

        if ($bestScore >= 70) {
            return [
                'note'       => 30,
                'percentage' => $bestScore,
                'table'      => 'Cnasnu'
            ];
        }
    }

    return [
        'note' => 1,
        'percentage' => 0,
        'table' => null
    ];
}



protected function Anrf($beneficiaireId, $nom): array
{
    if (!$beneficiaireId && !$nom) {
        return [
            'note' => 1,
            'percentage' => 0,
            'table' => null
        ];
    }

    // ✅ Normalisation
    $nomBenef = $this->normalize($nom);

    /* =========================
       1️⃣ MATCH IDENTIFIANT
       ========================= */
    if ($beneficiaireId) {

        $matchById = Anrf::whereRaw(
            "REGEXP_REPLACE(identifiant, '[^0-9]', '') = ?",
            [$beneficiaireId]
        )->first();

        if ($matchById) {
            return [
                'note'       => 3,
                'percentage' => 100,
                'table'      => 'Anrf'
            ];
        }
    }

    /* =========================
       2️⃣ MATCH NOM (FUZZY)
       ========================= */

    $records = Anrf::select('nom')->get();

    foreach ($records as $record) {

        $recordNom = $this->normalize($record->nom);

        similar_text($recordNom, $nomBenef, $percent);

        if ($percent >= 70) {
            return [
                'note'       => 3,
                'percentage' => $percent,
                'table'      => 'Anrf'
            ];
        }
    }

    /* =========================
       DEFAULT SAFE RETURN
       ========================= */
    return [
        'note' => 1,
        'percentage' => 0,
        'table' => null
    ];
}


    



    public function checkIdentity(): array
{
    $beneficiaireId = preg_replace('/\D/', '', $this->identite ?? '');

    $nom    = $this->nom_rs;
    $prenom = $this->prenom;

    $cnasnu = $this->Cnasnu($beneficiaireId, $nom, $prenom);
    $anrf   = $this->Anrf($beneficiaireId, $nom);

    return [
        'note'       => $cnasnu['note'] + $anrf['note'],
        'percentage' => max($cnasnu['percentage'], $anrf['percentage']),
        'table'      => $cnasnu['table'] ?? $anrf['table'] ?? '-',
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
