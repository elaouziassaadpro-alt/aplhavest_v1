<?php

define('APP_INIT', true);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once '../libs/req/conn_db.php';

$login = $_POST['login'];

$stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE login = ?");
$stmt->execute([$login]);

echo json_encode([
    "exists" => $stmt->rowCount() > 0
]);
?>
