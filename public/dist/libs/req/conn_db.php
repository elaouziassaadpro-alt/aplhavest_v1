<?php

// Vérifie si le fichier est inclus et non appelé directement
if (!defined('APP_INIT') && !(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')) {
    http_response_code(403);
    exit('<div class="Title">Accès interdit !</div><p>Vous n\'êtes pas autorisé à consulter ce fichier.</p>');
}

// **** Connexion à la base de données
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=kycapp3;charset=utf8mb4',
        'root',
        '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    );
} catch (PDOException $e) {
    die("Erreur PDO : " . $e->getMessage());
}


