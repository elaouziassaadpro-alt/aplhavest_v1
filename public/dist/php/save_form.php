<?php

try
{
    require_once '../libs/req/conn_db.php';
    require '../libs/req/settings_calcul.php';



    define('APP_INIT', true);

    $retour = "<ul class='text-danger'>";

    $message = "";

    function checkVide(&$retour, $condition, $message)
    {
        if ($condition) {
            //$retour .= "<li>$message</li>";
            return true;
        }
        return false;
    }

    function checkFK(PDO $pdo, string $table, $id, string $message, &$retour) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `$table` WHERE id = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() == 0) {
            $retour .= "<li>$message</li>";
            return false;
        }
        return true;
    }

    // Infos générales
    checkVide($retour, empty($raison_social_input = $_POST['raison_social_input'] ?? 0), "Raison sociale");
    if($raison_social_input == null){$raison_social_input = " ";$message = "Raison sociale"; $retour .= "<li>$message</li>";}

    checkVide($retour, empty($capital_social_input = $_POST['capital_social_input'] ?? null), "Capital social");
    if($capital_social_input == null){$capital_social_input = 0;}

    checkVide($retour, empty($forme_juridique_input = $_POST['forme_juridique_input'] ?? null), "Forme juridique");
    if($forme_juridique_input == null){$forme_juridique_input = 10000;}

    checkVide($retour, empty($date_immatriculation_input = $_POST['date_immatriculation_input'] ?? null), "Date d'immatriculation");
    if($date_immatriculation_input == null){$date_immatriculation_input = "1900-01-01";}

    checkVide($retour, empty($ice_input = $_POST['ice_input'] ?? null), "ICE");
    if($ice_input == null){$ice_input = 0;}

    checkVide($retour, empty($rc_input = $_POST['rc_input'] ?? null), "RC");
    if($rc_input == null){$rc_input = 0;}

    checkVide($retour, empty($if_input = $_POST['if_input'] ?? null), "IF");
    if($if_input == null){$if_input = 0;}

    checkVide($retour, empty($siege_social_input = $_POST['siege_social_input'] ?? null), "Siège social");

    checkVide($retour, empty($lieu_activite_input = $_POST['lieu_activite_input'] ?? null), "Pays d'activité");
    if($lieu_activite_input == null){$lieu_activite_input = 10000;}


    checkVide($retour, empty($residence_fiscale_input = $_POST['residence_fiscale_input'] ?? null), "Pays de résidence fiscale");
    if($residence_fiscale_input == null){$residence_fiscale_input = 10000;}


    checkVide($retour, empty($autorite_regulation_input = $_POST['autorite_regulation_input'] ?? null), "Autorité de régulation");

    checkVide($retour, empty($telephone_input = $_POST['telephone_input'] ?? null), "Téléphone de l'établissement");

    checkVide($retour, empty($email_input = $_POST['email_input'] ?? null), "Email de l'établissement");

    checkVide($retour, empty($site_web_input = $_POST['site_web_input'] ?? null), "Site web de l'établissement");

    $societe_de_gestion_check = $_POST['societe_de_gestion_check2'];
    $autorite_regulation_check = $_POST['autorite_regulation_check2'];
    $activite_etranger_check = $_POST['activite_etranger_check2'];
    $sur_marche_financier_check = $_POST['sur_marche_financier_check2'];
    $us_entity_check = $_POST['us_entity_check2'];
    $giin_check = $_POST['giin2'];
    $mandataire_check = $_POST['mandataire2'];
    $dep_gestion_check = $_POST['dep_gestion2'];



    // Typologie client
    checkVide($retour, empty($secteur_activite_input = $_POST['secteur_activite_input'] ?? null), "Secteur");
    checkVide($retour, empty($segment_input = $_POST['segment_input'] ?? null), "Segment");
    checkVide($retour, empty($activite_etranger_input = $_POST['activite_etranger_input'] ?? null), "Activité à l'étranger");
    checkVide($retour, empty($sur_marche_financier_input = $_POST['sur_marche_financier_input'] ?? null), "Marché financier");


    // Statut FATCA
    checkVide($retour, empty($usentityPath = $_POST['usentity'] ?? null), "Fichier FATCA");
    if($usentityPath == null){$usentityPath = "/";}

    checkVide($retour, empty($giin_input = $_POST['giin_inputs'] ?? null), "GIIN code");
    checkVide($retour, empty($giin_autres_input = $_POST['giin_autres_input'] ?? null), "GIIN précisions");


    // Situation financière
    checkVide($retour, empty($financier_capital_social_input = $_POST['financier_capital_social_input'] ?? null), "Capital social financement");
    checkVide($retour, empty($origine_fonds_input = $_POST['origine_fonds_input'] ?? null), "Origine des fonds");
    checkVide($retour, empty($pays_provenance_fonds_input = $_POST['pays_provenance_fonds_input'] ?? null), "Pays de provenance des fonds");
    checkVide($retour, empty($chiffre_affaires_radio = $_POST['chiffre_affaires_radio'] ?? null), "Chiffre d'affaires");
    checkVide($retour, empty($resultat_net_input = $_POST['resultat_net_input'] ?? null), "Résultats NET");
    checkVide($retour, empty($groupe_holding_radio = $_POST['groupe_holding_radio'] ?? null), "Groupe ou holding");



    checkVide($retour, empty($relation_affaire_radio = $_POST['relation_affaire_radio[]'] ?? null), "Fréquence des opérations");
    if($relation_affaire_radio == null){$relation_affaire_radio = "";}

    checkVide($retour, empty($horizon_placement_radio = $_POST['horizon_placement_radio[]'] ?? null), "Horizon de placement");
    if($horizon_placement_radio == null){$horizon_placement_radio = "";}

    checkVide($retour, empty($objet_relation = $_POST['objet_relation[]'] ?? null), "Objet de la relation");
    if($objet_relation == null){$objet_relation = "";}

    checkVide($retour, empty($mandataire_input = $_POST['mandataire_input[]'] ?? null), "Géré par un mandataire");
    if($mandataire_input == null){$mandataire_input = "";}

    checkVide($retour, empty($mandataire_fin_mandat_date = $_POST['mandataire_fin_mandat_date'] ?? null), "Date fin de mandat");


    // Profil risque
    checkVide($retour, empty($departement_gestion_input = $_POST['departement_gestion_input'] ?? null), "Département en charge de la gestion des placements");
    checkVide($retour, empty($instruments_souhaites_input = $_POST['instruments_souhaites_input[]'] ?? null), "Instruments financiers souhaités");
    if($instruments_souhaites_input == null){$instruments_souhaites_input = "";}

    checkVide($retour, empty($niveau_risque_tolere_radio = $_POST['niveau_risque_tolere_radio[]'] ?? null), "Niveau de risque toléré");
    if($niveau_risque_tolere_radio == null){$niveau_risque_tolere_radio = "0";}

    checkVide($retour, empty($annees_investissement_produits_finaniers = $_POST['annees_investissement_produits_finaniers'] ?? null), "Années d'investissement");
    checkVide($retour, empty($transactions_courant_2_annees = $_POST['transactions_courant_2_annees'] ?? null), "Transactions en moyenne réalisés");




    checkFK($pdo, 'formes_juridiques', $forme_juridique_input = $_POST['forme_juridique_input'] ?? null, "La forme juridique n'existe pas.", $retour);
    checkFK($pdo, 'pays', $lieu_activite_input = $_POST['lieu_activite_input'] ?? null, "Le lieu d'activité n'existe pas.", $retour);
    checkFK($pdo, 'pays', $residence_fiscale_input = $_POST['residence_fiscale_input'] ?? null, "Le lieu de résidence fiscale n'existe pas.", $retour);
    checkFK($pdo, 'secteurs', $secteur_activite_input = $_POST['secteur_activite_input'] ?? null, "Le secteur d'activité n'existe pas.", $retour);
    checkFK($pdo, 'segments', $segment_input = $_POST['segment_input'] ?? null, "Le segment n'existe pas.", $retour);
    checkFK($pdo, 'pays', $pays_provenance_fonds_input = $_POST['pays_provenance_fonds_input'] ?? null, "Le pays de provenance n'existe pas.", $retour);

    $capital_social_input = $capital_social_input ?? 0;

    if (!empty($capital_social_input) || $capital_social_input != 0)
    {
        $capital_social_input = preg_replace('/[^\d]/', '', $capital_social_input);
    }
    else
    {
        $capital_social_input = 0;
    }


    $financier_capital_social_input = $financier_capital_social_input ?? 0;

    if (!empty($financier_capital_social_input) || $financier_capital_social_input != 0)
    {
        $financier_capital_social_input = preg_replace('/[^\d]/', '', $financier_capital_social_input);
    }
    else
    {
        $financier_capital_social_input = 0;
    }

    $resultat_net_input = $resultat_net_input ?? 0;

    if (!empty($resultat_net_input) || $resultat_net_input != 0)
    {
        $resultat_net_input = preg_replace('/[^\d]/', '', $resultat_net_input);
    }
    else
    {
        $resultat_net_input = 0;
    }

    


    $dateajout = date('Y-m-d H:i:s');

    $retour .= "</ul>";

    // ➤ 1. Insert dans `etablissement`
    $stmt = $pdo->prepare("INSERT INTO etablissement (

    raisonSocial,
    capitalSocialPrimaire,
    formeJuridique,
    dateImmatriculation,
    ice,
    rc,
    ifiscal,
    siegeSocial,
    paysActivite,
    paysResidence,
    regule,
    nomRegulateur,
    societeGestion,
    telephone,
    email,
    siteweb,
    secteurActivite,
    segment,
    activiteEtranger,
    paysEtranger,
    publicEpargne2,
    publicEpargne,
    usEntity,
    usEntity2,
    giin2,
    giin,
    giinAutres,
    capitalSocial,
    origineFonds,
    paysOrigineFonds,
    chiffreAffaires,
    resultatsNET,
    holding,
    frequenceOperations,
    horizonPlacement,
    objetRelation,
    mandataire2,
    mandataire,
    dateFinMandat,
    depGestion,
    partPortfeuille,
    instruments,
    niveauRisqueTolere,
    anneesInvestissement,
    transactions2annees,
    ajouteLe,
    validation)

    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");


    $lieu_activite_input = $_POST['lieu_activite_input'];

    $stmt->execute([

      $raison_social_input,
      $capital_social_input,
      $forme_juridique_input,
      $date_immatriculation_input,
      $ice_input,
      $rc_input,
      $if_input,
      $siege_social_input,
      $lieu_activite_input,
      $residence_fiscale_input,
      $autorite_regulation_check,
      $autorite_regulation_input,
      $societe_de_gestion_check,
      $telephone_input,
      $email_input,
      $site_web_input,
      $secteur_activite_input,
      $segment_input,
      $activite_etranger_check,
      $activite_etranger_input,
      $sur_marche_financier_check,
      $sur_marche_financier_input,
      $usentityPath,
      $us_entity_check,
      $giin_check,
      $giin_input,
      $giin_autres_input,
      $financier_capital_social_input,
      $origine_fonds_input,
      $pays_provenance_fonds_input,
      $chiffre_affaires_radio,
      $resultat_net_input,
      $groupe_holding_radio,
      $relation_affaire_radio,
      $horizon_placement_radio,
      $objet_relation,
      $mandataire_check,
      $mandataire_input,
      $mandataire_fin_mandat_date,
      $dep_gestion_check,
      $departement_gestion_input,
      $instruments_souhaites_input,
      $niveau_risque_tolere_radio,
      $annees_investissement_produits_finaniers,
      $transactions_courant_2_annees,
      $dateajout,
      0
    ]);


    $idEtablissement = $pdo->lastInsertId();

    

    // Création du dossier de l'établissement si inexistant
    $uploadDir = __DIR__ . '/../../dist/php/uploads/etablissements/' . $idEtablissement . '/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Liste des fichiers à gérer
    $fichiersAccepter = ['ice','status','rc','agrement','etat_synthese','fatca', 'ni_file', 'fs_file', 'rg_file', 'mandat_file'];
    $fichiersUploades = [];

    foreach($fichiersAccepter as $type){
        if(isset($_FILES['fichiers']['name'][$type]) && $_FILES['fichiers']['error'][$type] === UPLOAD_ERR_OK){
            $nomTemporaire = $_FILES['fichiers']['tmp_name'][$type];
            $nomFichier = uniqid($type.'_') . '_' . basename($_FILES['fichiers']['name'][$type]);
            $cheminDestination = $uploadDir . $nomFichier;

            if(move_uploaded_file($nomTemporaire, $cheminDestination)){
                $fichiersUploades[$type] = 'uploads/etablissements/' . $idEtablissement . '/' . $nomFichier;
            }
        }
    }

    // Update de la table etablissement avec tous les fichiers
    $sql = "UPDATE etablissement SET 
        fichier_ice = :ice,
        fichier_status = :status,
        fichier_rc = :rc,
        fichier_agrement = :agrement,
        fichier_ni_file = :ni_file,
        fichier_fs_file = :fs_file,
        fichier_rg_file = :rg_file,
        fichier_etat_synthese = :etat_synthese,
        fichier_mandat_file = :mandat_file,
        fichier_usEntity = :fatca
    WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'ice'           => $fichiersUploades['ice'] ?? null,
        'status'        => $fichiersUploades['status'] ?? null,
        'rc'            => $fichiersUploades['rc'] ?? null,
        'agrement'      => $fichiersUploades['agrement'] ?? null,
        'ni_file'      => $fichiersUploades['ni_file'] ?? null,
        'fs_file'      => $fichiersUploades['fs_file'] ?? null,
        'rg_file'      => $fichiersUploades['rg_file'] ?? null,
        'etat_synthese' => $fichiersUploades['etat_synthese'] ?? null,
        'mandat_file' => $fichiersUploades['mandat_file'] ?? null,
        'fatca'         => $fichiersUploades['fatca'] ?? null,
        'id'            => $idEtablissement
    ]);

    //echo json_encode(['status'=>'success', 'fichiers'=>$fichiersUploades]);


    // ➤ 2. Insert dans `contacts`
    if (!empty($_POST['noms_contacts'])) {
        foreach ($_POST['noms_contacts'] as $i => $nom) {
            $email = $_POST['emails_contacts'][$i] ?? '';
            $prenom = $_POST['prenoms_contacts'][$i] ?? '';
            $tel = $_POST['telephones_contacts'][$i] ?? '';
            $fonction = $_POST['fonctions_contacts'][$i] ?? '';

            $stmt = $pdo->prepare("INSERT INTO contacts (nom, prenom, email, telephone, fonction, idEtablissement) VALUES (?, ?, ?, ?, ?,?)");
            $stmt->execute([$nom, $prenom, $email, $tel, $fonction, $idEtablissement]);
        }
    }

    // ➤ 3. Insert dans `comptes_bancaires`
    if (!empty($_POST['noms_banques']) || !empty($_POST['ribs_banques'])) {
        foreach ($_POST['noms_banques'] as $i => $nom) {
            $agence = $_POST['agences_banques'][$i] ?? '';
            $ville = $_POST['villes_banques'][$i] ?? 10000;
            $rib = $_POST['ribs_banques'][$i] ?? '';

            $stmt = $pdo->prepare("INSERT INTO comptes_bancaires (banque, agence, ville, rib, idEtablissement) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $agence, $ville, $rib, $idEtablissement]);
        }
    }


    if (!empty($_POST['noms_rs_actionnaires'])) {
        foreach ($_POST['noms_rs_actionnaires'] as $i => $nom) {
            $prenom = $_POST['prenoms_actionnaires'][$i] ?? '';
            $identite = $_POST['identite_actionnaires'][$i] ?? '';
            $nbr_titres = $_POST['nombre_titres_actionnaires'][$i] ?? '';
            $pourcentage = $_POST['pourcentage_capital_actionnaires'][$i] ?? '';

            $stmt = $pdo->prepare("INSERT INTO actionnaires (nom, prenom, cinRC, nombreTitres, capital, idEtablissement) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $identite, $nbr_titres, $pourcentage, $idEtablissement]);
        }
    }

    if (!empty($_POST['noms_rs_benificiaires'])) {
        foreach ($_POST['noms_rs_benificiaires'] as $i => $nom) {
            $pays = $_POST['pays_naissance_benificiaires'][$i] ?? '';
            $prenom = $_POST['prenoms_benificiaires'][$i] ?? '';
            $date = $_POST['dates_naissance_benificiaires'][$i] ?? '';
            $identite = $_POST['identite_benificiaires'][$i] ?? '';
            $nationalite = $_POST['nationalites_benificiaires'][$i] ?? '';
            $ppe = $_POST['benificiaires_ppe_input'][$i] ?? '';
            $lien = $_POST['benificiaires_ppe_lien_input'][$i] ?? '';
            $pourcentage = $_POST['benificiaires_pourcentage_capital'][$i] ?? '';

            $ppe2 = $_POST['ppe2_benificiaires'][$i] ?? 0;
            $lien2 = $_POST['lien2_benificiaires'][$i] ?? 0;

            $cin_benificiaires = null;

            if (isset($_FILES['cin_benificiaires']['name'][$i]) && $_FILES['cin_benificiaires']['error'][$i] === UPLOAD_ERR_OK) 
            {
                $nomTemporaire = $_FILES['cin_benificiaires']['tmp_name'][$i];
                $nomOriginal = basename($_FILES['cin_benificiaires']['name'][$i]);
                $extension = pathinfo($nomOriginal, PATHINFO_EXTENSION);

                // 🔒 Nom unique et clair
                $nomFichier = 'benificiaire' . ($i + 1) . '_cin_' . uniqid() . '.' . $extension;

                $cheminDestination = $uploadDir . $nomFichier;

                if (move_uploaded_file($nomTemporaire, $cheminDestination)) {
                    $cin_benificiaires = 'uploads/etablissements/' . $idEtablissement . '/' . $nomFichier;
                }

                //echo "CIN_BENIFICIAIREEEEE = " . $cin_benificiaires;
            }

            $stmt = $pdo->prepare("INSERT INTO benificiaires (
                nom, prenom, paysNaissance, dateNaissance, cinPasseport,
                nationalite, ppe, lienPPE, capital, idEtablissement, fichier_cin_file,
                ppe2, lien2
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->execute([
                        $nom, $prenom, $pays, $date, $identite, $nationalite,
                        $ppe, $lien, $pourcentage, $idEtablissement, $cin_benificiaires,
                        $ppe2, $lien2
                    ]);

            majCalculPPE($pdo, $idEtablissement, $ppe, $lien, 'Bénéficiaire', $risque_ajout_ppe);
        }
    }

    if (!empty($_POST['noms_administrateurs'])) {
        foreach ($_POST['noms_administrateurs'] as $i => $nom) {
            $prenom = $_POST['prenoms_administrateurs'][$i] ?? '';
            $pays = $_POST['pays_administrateurs'][$i] ?? '';
            $date = $_POST['dates_naissance_administrateurs'][$i] ?? '';
            $identite = $_POST['cins_administrateurs'][$i] ?? '';
            $nationalite = $_POST['nationalites_administrateurs'][$i] ?? '';
            $ppe = $_POST['ppes_administrateurs_input'][$i] ?? '';
            $lien = $_POST['ppes_lien_administrateurs_input'][$i] ?? '';
            $fonction = $_POST['fonctions_administrateurs'][$i] ?? '';

            $ppe2 = $_POST['ppe2_administrateurs'][$i] ?? 0;
            $lien2 = $_POST['lien2_administrateurs'][$i] ?? 0;

            if (isset($_FILES['cin_administrateurs']['name'][$i]) && $_FILES['cin_administrateurs']['error'][$i] === UPLOAD_ERR_OK) 
            {
                $nomTemporaire = $_FILES['cin_administrateurs']['tmp_name'][$i];
                $nomOriginal = basename($_FILES['cin_administrateurs']['name'][$i]);
                $extension = pathinfo($nomOriginal, PATHINFO_EXTENSION);

                // 🔒 Nom unique et clair
                $nomFichier = 'administrateur' . ($i + 1) . '_cin_' . uniqid() . '.' . $extension;

                $cheminDestination = $uploadDir . $nomFichier;

                if (move_uploaded_file($nomTemporaire, $cheminDestination)) {
                    $cin_administrateurs = 'uploads/etablissements/' . $idEtablissement . '/' . $nomFichier;
                }
            }
            else
            {
                $cin_administrateurs = null;
            }

            if (isset($_FILES['pvn_administrateurs']['name'][$i]) && $_FILES['pvn_administrateurs']['error'][$i] === UPLOAD_ERR_OK) 
            {
                $nomTemporaire = $_FILES['pvn_administrateurs']['tmp_name'][$i];
                $nomOriginal = basename($_FILES['pvn_administrateurs']['name'][$i]);
                $extension = pathinfo($nomOriginal, PATHINFO_EXTENSION);

                // 🔒 Nom unique et clair
                $nomFichier = 'administrateur' . ($i + 1) . '_pvnomination_' . uniqid() . '.' . $extension;

                $cheminDestination = $uploadDir . $nomFichier;

                if (move_uploaded_file($nomTemporaire, $cheminDestination)) {
                    $pvn_administrateurs = 'uploads/etablissements/' . $idEtablissement . '/' . $nomFichier;
                }

            }
            else
            {
                $pvn_administrateurs = null;
            }

            $stmt = $pdo->prepare("INSERT INTO administrateurs (nom, prenom, paysNaissance, dateNaissance, cinPasseport, nationalite, ppe, lienPPE, fonction, idEtablissement, fichier_cin_file, fichier_pvnomination_file, ppe2, lien2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $pays, $date, $identite, $nationalite, $ppe, $lien, $fonction, $idEtablissement, $cin_administrateurs, $pvn_administrateurs, $ppe2, $lien2]);

            majCalculPPE($pdo, $idEtablissement, $ppe, $lien, 'Administrateur', $risque_ajout_ppe);
        }
    }

    if (!empty($_POST['noms_habilites'])) {
        foreach ($_POST['noms_habilites'] as $i => $nom) {
            $prenom = $_POST['prenoms_habilites'][$i] ?? '';
            $identite = $_POST['cins_habilites'][$i] ?? '';
            $fonction = $_POST['fonctions_habilites'][$i] ?? '';
            $ppe = $_POST['ppes_habilites_input'][$i] ?? '';
            $lien = $_POST['ppes_lien_habilites_input'][$i] ?? '';

            $ppe2 = $_POST['ppe2_habilites'][$i] ?? 0;
            $lien2 = $_POST['lien2_habilites'][$i] ?? 0;

            $cin_habilites = null;

            if (isset($_FILES['cin_habilites']['name'][$i]) && $_FILES['cin_habilites']['error'][$i] === UPLOAD_ERR_OK) 
            {
                $nomTemporaire = $_FILES['cin_habilites']['tmp_name'][$i];
                $nomOriginal = basename($_FILES['cin_habilites']['name'][$i]);
                $extension = pathinfo($nomOriginal, PATHINFO_EXTENSION);

                // 🔒 Nom unique et clair
                $nomFichier = 'habilite' . ($i + 1) . '_cin_' . uniqid() . '.' . $extension;

                $cheminDestination = $uploadDir . $nomFichier;

                if (move_uploaded_file($nomTemporaire, $cheminDestination)) {
                    $cin_habilites = 'uploads/etablissements/' . $idEtablissement . '/' . $nomFichier;
                }
            }

            if (isset($_FILES['hab_habilites']['name'][$i]) && $_FILES['hab_habilites']['error'][$i] === UPLOAD_ERR_OK) 
            {
                $nomTemporaire = $_FILES['hab_habilites']['tmp_name'][$i];
                $nomOriginal = basename($_FILES['hab_habilites']['name'][$i]);
                $extension = pathinfo($nomOriginal, PATHINFO_EXTENSION);

                // 🔒 Nom unique et clair
                $nomFichier = 'habilite' . ($i + 1) . '_habilitation_' . uniqid() . '.' . $extension;

                $cheminDestination = $uploadDir . $nomFichier;

                if (move_uploaded_file($nomTemporaire, $cheminDestination)) {
                    $hab_habilites = 'uploads/etablissements/' . $idEtablissement . '/' . $nomFichier;
                }

            }
            else
            {
                $hab_habilites = null;
            }

            $stmt = $pdo->prepare("INSERT INTO habilites (nom, prenom, cinPasseport, fonction, idEtablissement, ppe, lienPPE, fichier_cin_file, fichier_habilitation_file, ppe2, lien2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $identite, $fonction, $idEtablissement, $ppe, $lien, $cin_habilites, $hab_habilites, $ppe2, $lien2]);

            majCalculPPE($pdo, $idEtablissement, $ppe, $lien, 'Personne habilitée', $risque_ajout_ppe);
        }
    }


    if ($segment_input && is_numeric($segment_input)) {
        majCalculSegment($pdo, $idEtablissement, (int)$segment_input);
    }

    if ($secteur_activite_input && is_numeric($secteur_activite_input)) {
        majCalculSecteur($pdo, $idEtablissement, (int)$secteur_activite_input);
    }

    if ($residence_fiscale_input && is_numeric($residence_fiscale_input)) {
        majCalculPaysResidenceFiscal($pdo, $idEtablissement, (int)$residence_fiscale_input);
    }


    if(!empty($_POST['autorite_regulation_check']))
    {
        $regulite_check = 1;
        if(!empty($autorite_regulation_input) && $autorite_regulation_input != "")
        {
            $regulateur = $autorite_regulation_input;
        }
        else
        {
            $regulateur = "Inconnu";
        }
    }
    else
    {
        $regulite_check = 0;
        $regulateur = "Non régulier";
    }

    majCalculRegulite($pdo, $idEtablissement, $regulite_check, $regulateur);


    if(!empty($_POST['activite_etranger_check']))
    {
        $activite_etranger = 1;
        if(!empty($activite_etranger_input) && $activite_etranger_input != "")
        {
            $pays_etranger = $activite_etranger_input;
        }
        else
        {
            $pays_etranger = "10000";
        }
    }
    else
    {
        $activite_etranger = 0;
        $pays_etranger = "0";
    }

    majCalculPaysEtranger($pdo, $idEtablissement, $activite_etranger, $pays_etranger);

    majCalculOrigineFonds($pdo, $idEtablissement, $origine_fonds_input);

    majCalculBeneficiaires(
        $pdo,
        $idEtablissement,
        $_POST['noms_rs_benificiaires'] ?? [],
        $_POST['prenoms_benificiaires'] ?? [],
        $_POST['identite_benificiaires'] ?? [],
        $_POST['cin_benificiaires'] ?? [],
        $_POST['nationalites_benificiaires'] ?? []
    );

    majCalculAdministrateurs(
        $pdo,
        $idEtablissement,
        $_POST['noms_administrateurs'] ?? [],
        $_POST['prenoms_administrateurs'] ?? [],
        $_POST['identite_administrateurs'] ?? [],
        $_POST['cin_administrateurs'] ?? [],
        $_POST['nationalites_administrateurs'] ?? []
    );


    if(!empty($_POST['mandataire_check']))
    {
        $mandataire_check = 1;

        $mandataire_input = $_POST['mandataire_input'];

        if(!empty($mandataire_input) && $mandataire_input != "")
        {
            $mandataire = $mandataire_input;
        }
        else
        {
            $mandataire = "Inconnu";
        }
    }
    else
    {
        $mandataire_check = 0;
        $mandataire = "Non mandataire";
    }

    majCalculMandataire($pdo, $idEtablissement, $mandataire_check, $mandataire);

    //$result = checkCorrespondance($pdo, "ALI CHAUDHRY");

    //echo "Noms trouvés :<br>";
    //echo implode(" ", $result['noms']) . "<br>";
    //echo "Correspondance : " . $result['pourcentage'] . "%";

    echo json_encode(['success' => true, 'idEtablissement' => $idEtablissement, 'message' => "L'établissement a bien été ajouté !", 'titre' => 'Enregistrement effectué', 'icone' => 'ti ti-check fs-10 text-success', 'check' => 'true']);
    exit;
}
catch (Exception $e)
{
    echo json_encode(['success' => false, 'message' => $e->getMessage(), 'titre' => 'Etablissement non enregistré', 'icone' => 'ti ti-server fs-10 text-danger']);
    exit;
}
?>