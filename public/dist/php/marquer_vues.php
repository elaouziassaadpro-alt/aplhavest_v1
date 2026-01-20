<?php

define('APP_INIT', true);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once '../libs/req/conn_db.php';

session_start();

$id = (int) $_POST['id'];

if (isset($_COOKIE['id_utilisateur'])) {
    $idUtilisateur = $_COOKIE['id_utilisateur'];
  }
  else
  {
    $idUtilisateur = 1;
  }

$update = $pdo->prepare("
    UPDATE notifications 
    SET statut = 'lu', date_derniere_execution = NOW()
    WHERE idUtilisateur = ? AND statut = 'non_lu' AND id = ?
");
$update->execute([$idUtilisateur, $id]);

echo json_encode(['success' => true]);

?>
