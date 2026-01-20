<?php
require_once '../libs/req/conn_db.php';

$role_id = $_POST['role_id'] ?? null;

// Tous les users
$users = $pdo->query("SELECT id, nom, prenom FROM utilisateurs ORDER BY nom ASC")
             ->fetchAll(PDO::FETCH_ASSOC);

// Users ayant déjà ce rôle
$assigned = [];
if ($role_id) {
    $q = $pdo->prepare("SELECT id FROM utilisateurs WHERE role = ?");
    $q->execute([$role_id]);
    $assigned = array_column($q->fetchAll(PDO::FETCH_ASSOC), 'id');
}

// Génération HTML
foreach ($users as $u) {
    $checked = in_array($u['id'], $assigned) ? 'checked' : '';
    echo "
    <div class='form-check'>
        <input type='checkbox' class='form-check-input' name='users[]' value='{$u['id']}' $checked>
        <label class='form-check-label'>{$u['nom']} {$u['prenom']}</label>
    </div>";
}
