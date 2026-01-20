<?php


define('APP_INIT', true);


$fermetureSESSION = 17280 * 60; // 6 minutes en secondes

session_save_path(__DIR__ . '/tmp_sessions');
if (!is_dir(session_save_path())) mkdir(session_save_path(), 0777, true);
ini_set('session.gc_maxlifetime', $fermetureSESSION); // 6 minutes
ini_set('session.cookie_lifetime', $fermetureSESSION);
session_start();




// **** Chemin fichiers required
$req_path = "../../dist/libs/req/";

require_once 'conn_db.php';


$seuils_risque = [
    [
        'seuil' => 33,
        'noteRisque' => 'LR',
        'noteType' => 'success',
    ],
    [
        'seuil' => 50,
        'noteRisque' => 'MR',
        'noteType' => 'warning',
    ],
    [
        'seuil' => 500,
        'noteRisque' => 'HR',
        'noteType' => 'danger',
    ],
    [
        'seuil' => 1000,
        'noteRisque' => 'A SURVEILLER',
        'noteType' => 'danger',
    ],
    [
        'seuil' => PHP_INT_MAX, 
        'noteRisque' => 'INTERDIT',
        'noteType' => 'danger',
    ],
];


// **** Insertion nouvel utilisateur
function insertNewUser($login, $email_utilisateur, $mot_de_passe, $role, $nom_utilisateur)
{
    global $pdo; // Rendre $pdo accessible dans la fonction
    // Hasher le mot de passe
    $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO utilisateurs (login, email, mot_de_passe, role, nom) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$login, $email_utilisateur, $hash, $role, $nom_utilisateur]);

    echo $role . " inséré avec succès.";
}

$login = 'WIDAD';
$email_utilisateur = 'w.ouardi@alphavest.ma';
$mot_de_passe = 'Widad@av.kyc';
$role = 'Directeur général';
$nom_utilisateur = 'Widad OUARDI';

// insertNewUser($login, $email_utilisateur, $mot_de_passe, $role, $nom_utilisateur);
// **** Fin insertion nouvel utilisateur


// **** Modification mot de passe utilisateur
function updateUserPassword($id, $mot_de_passe)
{
    global $pdo;
    $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?");
    $stmt->execute([$hash, $id]);

    echo "Mot de passe mis à jour avec succès pour l'utilisateur ID $id.";
}


//updateUserPassword(2, 'Nabila@av.kyc');
// **** Fin modification mot de passe utilisateur





// **** Durée d'inactivité en secondes (30 minutes = 3000)
define('SESSION_TIMEOUT', $fermetureSESSION);

// **** Pages publiques sans mot de passe
$public_pages = ['login.php', 'logout.php'];

// **** Nom de la page courante
$current_page = basename($_SERVER['PHP_SELF']);

// **** Test de session
//echo $_SESSION['id_utilisateur'];

// **** Si la page a besoin de mot de passe :
if (!in_array($current_page, $public_pages)) {

    if (!isset($_SESSION['id_utilisateur'])) {
        header('Location: ../main/login.php?msg=not_connected');
        exit;
    }

    // Vérifier session active et timeout
    $stmt = $pdo->prepare("
        SELECT * 
        FROM sessions 
        WHERE id_utilisateur = ? 
          AND session_id = ? 
          AND actif = 1
        ORDER BY date_connexion DESC
        LIMIT 1
    ");
    $stmt->execute([$_SESSION['id_utilisateur'], session_id()]);
    $session = $stmt->fetch();

    setcookie('id_utilisateur', $_SESSION['id_utilisateur'], time() + 12*24*60*60, '/', '', false, true);

    if (!$session) {
        session_unset();
        session_destroy();
        header('Location: ../main/login.php?msg=not_connected');
        exit;
    }

    // Timeout d’inactivité
    if (isset($_SESSION['derniere_activite']) && (time() - $_SESSION['derniere_activite'] > SESSION_TIMEOUT)) {
        $stmt = $pdo->prepare("UPDATE sessions SET date_deconnexion = NOW(), actif = 0 WHERE id = ?");
        $stmt->execute([$session['id']]);

        session_unset();
        session_destroy();
        header('Location: ../main/login.php?msg=session_expired');
        exit;
    }

    $_SESSION['derniere_activite'] = time();
}

//$id_utilisateur = $_SESSION['id_utilisateur'];




?>


<script>
    const SESSION_TIMEOUT = 2880 * 60 * 1000; // 48 heures en millisecondes
    const WARNING_BEFORE = 60 * 60 * 1000; // 60 minute avant la fin

    let warningTimeout = setTimeout(showWarningPopup, SESSION_TIMEOUT - WARNING_BEFORE);

    function showWarningPopup() {
        const stay = confirm("Votre session va expirer dans 1 minute. Voulez-vous rester connecté ?");

        if (stay) {
            fetch("../main/keep_alive.php", { method: "POST" })
                .then(res => {
                    if (res.ok) {
                        clearTimeout(warningTimeout);
                        warningTimeout = setTimeout(showWarningPopup, SESSION_TIMEOUT - WARNING_BEFORE);
                    } else {
                        window.location.href = "../main/logout.php";
                    }
                })
                .catch(() => window.location.href = "../main/logout.php");
        } else {
            window.location.href = "../main/logout.php";
        }
    }
</script>



<?php
// /app/pack/dist/lib/req/settings.php

// Point de base : dossier actuel
define('BASE_PATH', __DIR__); // = /app/pack/dist/lib/req

// Autoloader
spl_autoload_register(function ($class) {
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = BASE_PATH . '/classes/' . $classPath . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        echo "<pre>❌ Classe non trouvée : $class\nFichier attendu : $file</pre>";
    }
});