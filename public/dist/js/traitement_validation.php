<?php
require_once '../libs/req/conn_db.php';
header('Content-Type: application/json');

try {
    $source       = $_POST['source'] ?? ''; // cnasnu, anrf, anrf_physiques
    $table        = $_POST['table'] ?? '';  // actionnaires, administrateurs, etc.
    $tableId      = (int)($_POST['table_id'] ?? 0);
    $targetId     = $_POST['target_id'] ?? null;

    if (!$table || !$tableId) {
        throw new Exception("Données incomplètes reçues");
    }

    // -----------------------------
    // 1️⃣ Trouver ou créer l'établissement
    // -----------------------------
    $etabId = null;
    if ($table === 'etablissement') {
        $etabId = $tableId;
    } else {
        $stmt = $pdo->prepare("SELECT idEtablissement FROM `$table` WHERE id = ? LIMIT 1");
        $stmt->execute([$tableId]);
        $etabId = $stmt->fetchColumn();
    }

    // Si établissement inexistant : création automatique
    if (!$etabId) {
        $pdo->prepare("INSERT INTO etablissement (nom, statut) VALUES (?, 'Nouveau')")
            ->execute(["Établissement (lié à $table #$tableId)"]);
        $etabId = $pdo->lastInsertId();
        $redirect = "details_etablissement.php?idEtablissement=".$etabId;
    } else {
        $redirect = "validation_listes.php";
    }

    // -----------------------------
    // 2️⃣ Préparation du détail de calcul
    // -----------------------------
    $sourceNom = ucfirst(strtolower(str_replace('_', ' ', $source)));
    $detail = strtoupper($source) . ":Présent dans la liste ($sourceNom)";
    $note = ($source === 'cnasnu') ? 1000 : 500; // CNASNU → 1000, ANRF → 500

    // -----------------------------
    // 3️⃣ Vérifier doublon avant insertion
    // -----------------------------
    $check = $pdo->prepare("SELECT COUNT(*) FROM details_calcul WHERE idEtablissement = ? AND detailNote = ?");
    $check->execute([$etabId, $detail]);
    $exists = $check->fetchColumn() > 0;

    if (!$exists) {
        // Insertion du détail de calcul
        $insert = $pdo->prepare("
            INSERT INTO details_calcul (note, detailNote, idEtablissement)
            VALUES (?, ?, ?)
        ");
        $insert->execute([$note, $detail, $etabId]);

        // Mise à jour du niveau de risque
        $updateRisk = $pdo->prepare("
            UPDATE calcul_etablissement
            SET niveauRisque = niveauRisque + ?
            WHERE idEtablissement = ?
        ");
        $updateRisk->execute([$note, $etabId]);
    }

    // -----------------------------
    // 4️⃣ Gestion du statut et notification
    // -----------------------------
    if ($source === 'cnasnu') {
        // 🔴 CNASNU = Interdit
        $pdo->prepare("UPDATE etablissement SET statut = 'Interdit' WHERE id = ?")->execute([$etabId]);
        $pdo->prepare("UPDATE details_validation SET niveauValidation = 2, type = 'Rejet', niveauRisque = 'INTERDIT' WHERE idEtablissement = ?")->execute([$etabId]);
        $message = "<p style='font-size:30px;'>Classé (CNASNU)<br><span style='font-size:20px;font-weight:500'>L'établissement a été ajouté à la liste des interdits.</span></p>";


    } elseif (in_array($source, ['anrf', 'anrf_physiques'])) {
        // 🟠 ANRF = Suivi
        $pdo->prepare("UPDATE etablissement SET statut = 'Suivi' WHERE id = ?")->execute([$etabId]);

        // Vérifier si notification existe déjà
        $notifExists = $pdo->prepare("
            SELECT COUNT(*) FROM notifications 
            WHERE idEtablissement = ? AND type = 'suivi' AND actif = 1
        ");
        $notifExists->execute([$etabId]);

        if ($notifExists->fetchColumn() == 0) {
            $insertNotif = $pdo->prepare("
                INSERT INTO notifications (
                    idEtablissement, type, titre, message, declenchement, date_creation, actif
                ) VALUES (
                    ?, 'suivi', 'Suivi quotidien', 
                    'L’établissement est toujours présent dans la liste ANRF.', 
                    '09:00:00', NOW(), 1
                )
            ");
            $insertNotif->execute([$etabId]);
        }

        $message = "<p style='font-size:30px;'>Classé (ANRF)<br><span style='font-size:20px;font-weight:500'>Une notification quotidienne a été programmée.</span></p>";

    } else {
        $message = "Aucune action spécifique appliquée.";
    }

    // -----------------------------
    // 5️⃣ Vérifier s’il y a des correspondances liées
    // -----------------------------
    $tables = ['administrateurs','actionnaires','benificiaires','habilites'];
    $found = false;

    foreach ($tables as $tbl) {
        $sql = "SELECT 1 FROM details_calcul dc
                JOIN $tbl t ON dc.idEtablissement = t.idEtablissement
                WHERE dc.idEtablissement = ? LIMIT 1";
        $check = $pdo->prepare($sql);
        $check->execute([$etabId]);
        if ($check->fetchColumn()) {
            $found = true;
            break;
        }
    }

    // -----------------------------
    // 6️⃣ Redirection
    // -----------------------------
    if ($found) {
        $redirect = "validation_listes.php";
    } else {
        $redirect = "details_etablissement.php?idEtablissement=".$etabId;
    }

    echo json_encode([
        'status' => 'success',
        'message' => $message,
        'redirect' => $redirect
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
