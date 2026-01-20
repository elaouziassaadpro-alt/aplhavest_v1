<?php
// roles_actions.php
require_once 'conn_db.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

try {
    if ($action === 'save') {

        // -------------------------------
        // 🔹 Variables reçues depuis le form
        // -------------------------------
        $id = isset($_POST['id']) && $_POST['id'] !== '' ? intval($_POST['id']) : 0;
        $nom = trim($_POST['nom'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $actif = isset($_POST['actif']) ? 1 : 0;

        // Permissions cochées
        $permissions = $_POST['permissions'] ?? [];

        // Utilisateurs cochés ❗
        $users = $_POST['users'] ?? [];

        if ($nom === '') {
            echo json_encode(['success' => false, 'message' => 'Le nom du rôle est requis.']);
            exit;
        }

        // -------------------------------
        // 🔹 INSERT ou UPDATE du rôle
        // -------------------------------
        if ($id > 0) {
            // UPDATE
            $stmt = $pdo->prepare("UPDATE roles SET nom = ?, description = ?, actif = ? WHERE id = ?");
            $ok = $stmt->execute([$nom, $description, $actif, $id]);
        } else {
            // INSERT
            $stmt = $pdo->prepare("INSERT INTO roles (nom, description, actif) VALUES (?, ?, ?)");
            $ok = $stmt->execute([$nom, $description, $actif]);
            if ($ok) {
                $id = $pdo->lastInsertId();
            }
        }

        if (!$ok) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l’enregistrement du rôle.']);
            exit;
        }

        // -------------------------------
        // 🔹 Mise à jour des permissions
        // -------------------------------
        $pdo->prepare("DELETE FROM roles_permissions WHERE id_role = ?")->execute([$id]);

        if (!empty($permissions)) {
            $stmtPerm = $pdo->prepare(
                "INSERT INTO roles_permissions (id_role, permission, valeur) VALUES (?, ?, 1)"
            );

            foreach ($permissions as $perm) {
                $perm = preg_replace('/[^a-z0-9_\-\.]/i', '', $perm);
                if ($perm === '') continue;
                $stmtPerm->execute([$id, $perm]);
            }
        }

        // -------------------------------
        // 🔥 GESTION DES UTILISATEURS DU RÔLE
        // -------------------------------
        // Réinitialiser les utilisateurs qui avaient ce rôle
        $reset = $pdo->prepare("UPDATE utilisateurs SET role = NULL WHERE role = ?");
        $reset->execute([$id]);

        // Assigner les utilisateurs cochés dans le formulaire
        if (!empty($users)) {
            $stmtUser = $pdo->prepare("UPDATE utilisateurs SET role = ? WHERE id = ?");
            foreach ($users as $uid) {
                $uid = intval($uid);
                $stmtUser->execute([$id, $uid]);
            }
        }

        echo json_encode([
            'success' => true,
            'message' => ($id > 0 ? 'Rôle enregistré.' : 'Rôle ajouté.')
        ]);
        exit;
    }

    // -----------------------------------
    // 🔹 SUPPRESSION RÔLE
    // -----------------------------------
    if ($action === 'delete') {

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID invalide']);
            exit;
        }

        // Vérifier s'il y a des utilisateurs liés
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE role = ?");
        $stmt->execute([$id]);
        $c = $stmt->fetchColumn();

        if ($c > 0) {
            if($c == 1)
            {
                echo json_encode([
                    'success' => false,
                    'message' => "Impossible de supprimer : ce rôle est attribué à $c utilisateur."
                ]);
            }
            else
            {
                echo json_encode([
                    'success' => false,
                    'message' => "Impossible de supprimer : ce rôle est attribué à $c utilisateurs."
                ]);
            }
            exit;
        }

        // Supprimer rôle
        $stmt = $pdo->prepare("DELETE FROM roles WHERE id = ?");
        $ok = $stmt->execute([$id]);

        echo json_encode([
            'success' => $ok,
            'message' => $ok ? 'Rôle supprimé.' : 'Erreur suppression.'
        ]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Action inconnue']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Exception: '.$e->getMessage()]);
}
