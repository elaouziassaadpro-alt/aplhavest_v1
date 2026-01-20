<?php

// Déconnexion
if (isset($_SESSION['id_utilisateur'])) {
    $stmt = $pdo->prepare("
        SELECT id 
        FROM sessions 
        WHERE id_utilisateur = ? AND actif = 1 
        ORDER BY date_connexion DESC 
        LIMIT 1
    ");
    $stmt->execute([$_SESSION['id_utilisateur']]);
    $last_session = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($last_session) {
        $stmt = $pdo->prepare("UPDATE sessions SET date_deconnexion = NOW(), actif = 0 WHERE id = ?");
        $stmt->execute([$last_session['id']]);
    }

    session_unset();
    session_destroy();
}

header('Location: ../main/login.php');

?>