<?php

define('APP_INIT', true);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once '../libs/req/conn_db.php';

// Récupération des données
$id = intval($_POST['id'] ?? 0);
$password = trim($_POST['password'] ?? '');

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID utilisateur invalide.']);
    exit;
}

if ($password === '') {
    echo json_encode(['success' => false, 'message' => 'Le mot de passe est requis.']);
    exit;
}

// Hash du mot de passe
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Mise à jour
$stmt = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?");
$ok = $stmt->execute([$hashed, $id]);

if ($ok) {
    echo json_encode(['success' => true, 'message' => 'Mot de passe mis à jour avec succès.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour.']);
}