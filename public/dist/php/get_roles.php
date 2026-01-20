<?php
// get_role.php
require_once '../libs/req/conn_db.php';
//header('Content-Type: application/json');


$action = $_GET['action'] ?? '';

// ----------------------------------------------------------------------
// 1. Récupérer la liste de TOUS les rôles (pour formulaire d'utilisateur)
//    URL : get_roles.php?action=list
// ----------------------------------------------------------------------
if ($action === "list") {
    $stmt = $pdo->query("SELECT id, nom FROM roles ORDER BY nom");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

// ----------------------------------------------------------------------
// 2. Récupérer les permissions d’un rôle choisi
//    URL : POST idRole
// ----------------------------------------------------------------------
if ($action === "permissions") {

    if (!isset($_POST['idRole'])) {
        echo json_encode(["error" => "idRole manquant"]);
        exit;
    }

    $idRole = $_POST['idRole'];

    $stmt = $pdo->prepare("SELECT * FROM permissions WHERE role_id = ?");
    $stmt->execute([$idRole]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

// ----------------------------------------------------------------------
// Si aucune action valide
// ----------------------------------------------------------------------
echo json_encode(["error" => "Action invalide"]);