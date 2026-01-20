<?php

define('APP_INIT', true);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once '../libs/req/conn_db.php';

$id = intval($_POST['id']);
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$email = $_POST['email'];
$login = $_POST['login'];
$role = intval($_POST['role']);

if(isset($_FILES['photo']['name']) && $_FILES['photo']['name'] != ""){
    $filename = time() . "_" . $_FILES['photo']['name'];
    move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/users_photos/".$filename);

    $stmt = $pdo->prepare("
        UPDATE utilisateurs
        SET nom=?, prenom=?, email=?, login=?, role=?, photo=?
        WHERE id=?");
    $stmt->execute([$nom, $prenom, $email, $login, $role, $filename, $id]);
} else {
    $stmt = $pdo->prepare("
        UPDATE utilisateurs
        SET nom=?, prenom=?, email=?, login=?, role=?
        WHERE id=?");
    $stmt->execute([$nom, $prenom, $email, $login, $role, $id]);
}

echo json_encode(["success"=>true, "message"=>"Utilisateur mis à jour"]);