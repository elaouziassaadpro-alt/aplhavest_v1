<?php
define('APP_INIT', true);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$search = isset($_GET['q']) ? $_GET['q'] : '';

require_once '../libs/req/conn_db.php';

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($search != '') {
        $stmt = $pdo->prepare("SELECT id, libelle FROM segments 
                               WHERE libelle LIKE :search
                               ORDER BY libelle ASC LIMIT 50");
        $stmt->execute(['search' => "%$search%"]);
    } else {
        $stmt = $pdo->query("SELECT id, libelle FROM segments WHERE id < 10000 ORDER BY libelle ASC LIMIT 50");
    }

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($rows, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
