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
      SELECT 
          nom, 
          prenom, 
          email, 
          role, 
          photo 
      FROM utilisateurs 
      WHERE id = ?
  ");

  $stmt->execute([$idUtilisateur]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
      echo json_encode(['success' => false, 'message' => 'Utilisateur introuvable']);
      exit;
  }

  if (empty($user['photo'])) {
      $user['photo'] = '../../dist/images/profile/user-default.svg';
  }

  echo json_encode([
      'success' => true,
      'user' => $user
  ]);
?>
