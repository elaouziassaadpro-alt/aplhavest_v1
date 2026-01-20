<?php

define('APP_INIT', true);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once '../libs/req/conn_db.php';

session_start();

//$idUtilisateur = $_SESSION['id_utilisateur']; // adapte selon ta variable de session

//$idUtilisateur = 1; // adapte selon ta variable de session

if (isset($_COOKIE['id_utilisateur'])) {
    $idUtilisateur = $_COOKIE['id_utilisateur'];
  }

$stmt = $pdo->prepare("
    SELECT n.id, n.titre, n.message, n.type, n.statut, n.date_creation , e.raisonSocial
    FROM notifications n JOIN etablissement e ON e.id = n.idEtablissement 
    WHERE n.idUtilisateur = ? AND n.actif = 1 AND (n.statut = 'non_lu' OR DATE(n.date_derniere_execution) = DATE_SUB(CURDATE(), INTERVAL 1 DAY ))
    ORDER BY n.date_creation DESC
    LIMIT 10
");
$stmt->execute([$idUtilisateur]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

$count_non_vues = $pdo->prepare("
    SELECT COUNT(*) FROM notifications 
    WHERE idUtilisateur = ? AND actif = 1 AND statut = 'non_lu'
");
$count_non_vues->execute([$idUtilisateur]);
$nb_non_vues = $count_non_vues->fetchColumn();

echo json_encode([
    'non_vues' => $nb_non_vues,
    'notifications' => $notifications
]);
?>
