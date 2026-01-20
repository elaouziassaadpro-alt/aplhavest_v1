<?php

define('APP_INIT', true);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once '../libs/req/conn_db.php';

$response = ["status" => "error"];

try {
    $nom      = $_POST['nom'];
    $prenom   = $_POST['prenom'];
    $email    = $_POST['email'];
    $login    = $_POST['login'];
    $role     = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Vérifier unicité email/login côté serveur
    $check = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? OR login = ?");
    $check->execute([$email, $login]);

    if ($check->rowCount() > 0) {
        $response["message"] = "Email ou login existe déjà";
        echo json_encode($response);
        exit;
    }

    // Upload photo
    $photoName = null;

    if (!empty($_FILES['photo']['name'])) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photoName = time() . "_" . rand(1000,9999) . "." . $ext;

        $dir = "../../uploads/users/";
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        move_uploaded_file($_FILES['photo']['tmp_name'], $dir . $photoName);
    }

    // Insert
    $sql = $pdo->prepare("
        INSERT INTO utilisateurs (nom, prenom, email, login, mot_de_passe, role, photo)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $sql->execute([$nom, $prenom, $email, $login, $password, $role, $photoName]);

    $response = [
        "status" => "success",
        "message" => "Utilisateur ajouté"
    ];

} catch (Exception $e) {
    $response["message"] = $e->getMessage();
}

echo json_encode($response);