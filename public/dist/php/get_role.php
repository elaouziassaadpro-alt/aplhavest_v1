<?php
// get_role.php
require_once '../libs/req/conn_db.php';
header('Content-Type: application/json');

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if($id <= 0){
    echo json_encode(['error' => 'id invalide']);
    exit;
}

// Rôle
$stmt = $pdo->prepare("SELECT id, nom, description, actif, date_creation FROM roles WHERE id = ?");
$stmt->execute([$id]);
$role = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$role){
    echo json_encode(['error' => 'Rôle introuvable']);
    exit;
}

// Permissions
$stmt2 = $pdo->prepare("SELECT permission FROM roles_permissions WHERE id_role = ?");
$stmt2->execute([$id]);
$permissions = $stmt2->fetchAll(PDO::FETCH_COLUMN);

// Nombre d'utilisateurs (optionnel)
$stmt3 = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE role = ?");
$stmt3->execute([$id]);
$countUsers = $stmt3->fetchColumn();

$role['permissions'] = $permissions;
$role['nb_utilisateurs'] = intval($countUsers);

echo json_encode($role);
