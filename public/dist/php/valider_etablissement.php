<?php

session_start();

require_once '../libs/req/conn_db.php';

if(isset($_POST['idetablissement']) && isset($_POST['action']))
{
	if (isset($_COOKIE['id_utilisateur'])) {
	    $id_utilisateur = $_COOKIE['id_utilisateur'];
	}
	else
	{
		$id_utilisateur = 0;
	}

	$idToValidate = $_POST['idetablissement'];

	try {

		if($idToValidate != "" && $_POST['action'] == 'validation')
		{
			// Valider l'etablissement (UPDATE etablissement SET validation = 1 WHERE id = ?) idtovalidate
			$validation = 1;
			$validationText = 'Validation';

			echo json_encode(['validation' => true, 'message' => "L'établissement a été validé !", 'titre' => 'Validation effectué', 'icone' => 'ti ti-check fs-10 text-success']);
		}
		else if($idToValidate != "" && $_POST['action'] == 'rejet')
		{
			$validation = 2;
			$validationText = 'Rejet';
			// Rejeter l'etablissement (UPDATE etablissement SET validation = 2 WHERE id = ?) idtovalidate
			echo json_encode(['rejet' => true, 'message' => "L'établissement a été rejeté !", 'titre' => 'Rejet effectué', 'icone' => 'ti ti-check fs-10 text-success']);
		}


	    $pdo->beginTransaction();

	    // Mise à jour dans la table etablissement
	    $updateEtab = $pdo->prepare("
	        UPDATE etablissement
	        SET validation = ?
	        WHERE id = ?
	    ");
	    $updateEtab->execute([$validation, $idToValidate]);

	    // Mise à jour dans la table details_validation
	    $updateDetails = $pdo->prepare("
	        INSERT INTO details_validation(idEtablissement, niveauValidation, majPar, type, niveauRisque)
	        VALUES(?,?,?,?,?)
	    ");
	    $updateDetails->execute([$idToValidate, $validation, $id_utilisateur, $validationText, 'HR']);

	    $pdo->commit();

	    //echo json_encode(['success' => true, 'message' => 'Validation mise à jour avec succès.']);

	} catch (Exception $e) {
	    $pdo->rollBack();
	    //echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
	}



	$update = $pdo->prepare("
	    UPDATE etablissement
	    SET validation = ?
	    WHERE id = ?
	");
	
	$update->execute([$validation, $idToValidate]);

	// STMTMAJ : Maj par : utilisateur, date et heure maintenant
}
else
{
	echo json_encode(['validation' => false, 'rejet' => false, 'message' => "L'établissement n'a pas été validé ni rejeté !", 'titre' => 'Erreur de mise à jour', 'icone' => 'ti ti-check fs-10 text-success']);
}




?>