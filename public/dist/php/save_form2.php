<?php

define('APP_INIT', true);
$retour = "";

function majCalculPPE($pdo, $idEtablissement, $ppe, $lienPPE, $source = 'Bénéficiaire') {
    $ppe_valide = is_numeric($ppe) && $ppe > 0 && $ppe < 10000;
    $lienPPE_valide = is_numeric($lienPPE) && $lienPPE > 0 && $lienPPE < 10000;

    $note_ajout = 0;
    $risque_ajout = 0;

    if ($ppe_valide) {
        $note_ajout += 1;
        $risque_ajout += 100;

        // Détail pour PPE
        $insert_detail = $pdo->prepare("INSERT INTO details_calcul (note, detailNote, idEtablissement) VALUES (?, ?, ?)");
        $insert_detail->execute([100, 'PPE ' . $source, $idEtablissement]);
    }

    if ($lienPPE_valide) {
        $note_ajout += 1;
        $risque_ajout += 100;

        // Détail pour Lien PPE
        $insert_detail = $pdo->prepare("INSERT INTO details_calcul (note, detailNote, idEtablissement) VALUES (?, ?, ?)");
        $insert_detail->execute([100, 'Lien PPE ' . $source, $idEtablissement]);
    }

    // Vérifie si une ligne existe dans calcul_etablissement
    $stmt = $pdo->prepare("SELECT note, niveauRisque FROM calcul_etablissement WHERE idEtablissement = ?");
    $stmt->execute([$idEtablissement]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $nouvelle_note = $row['note'] + $note_ajout;
        $nouveau_risque = $row['niveauRisque'] + $risque_ajout;

        $update = $pdo->prepare("UPDATE calcul_etablissement SET note = ?, niveauRisque = ? WHERE idEtablissement = ?");
        $update->execute([$nouvelle_note, $nouveau_risque, $idEtablissement]);
    } else {
        $insert = $pdo->prepare("INSERT INTO calcul_etablissement (idEtablissement, note, niveauRisque) VALUES (?, ?, ?)");
        $insert->execute([$idEtablissement, $note_ajout, $risque_ajout]);
    }
}




$data = json_decode(file_get_contents('php://input'), true);

// Connexion PDO
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$retour = "<ul class='text-danger'>";

function checkVide(&$retour, $condition, $message)
{
    if ($condition) {
        $retour .= "<li>$message</li>";
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

checkVide($retour, empty($data['raison_social_input']), "Raison sociale");
//checkVide($retour, empty($data['capital_social_input']), "Capital social"); // ecraser

checkVide($retour, empty($data['forme_juridique_input']), "Forme juridique");
//checkVide($retour, empty($data['ice_input']), "ICE");// ecraser
//checkVide($retour, empty($data['rc_input']), "RC");// ecraser
//checkVide($retour, empty($data['if_input']), "IF");// ecraser
checkVide($retour, empty($data['lieu_activite_input']), "Lieu d'activité");
checkVide($retour, empty($data['residence_fiscale_input']), "Lieu de résidence fiscale");
checkVide($retour, empty($data['secteur_activite_input']), "Secteur d'activité");
checkVide($retour, empty($data['segment_input']), "Segment");
//checkVide($retour, empty($data['origine_fonds_input']), "Origine des fonds");// ecraser
checkVide($retour, empty($data['pays_provenance_fonds_input']), "Pays de provenance des fonds");// ecraser
//checkVide($retour, empty($data['resultat_net_input']), "Résultat net");// ecraser
checkVide($retour, empty($data['objet_relation[]']), "Objet de la relation");// ecraser

if($retour == "<ul class='text-danger'>")
{
    foreach ($data as $key => $value) {
        // Remplace les caractères non valides pour nom de variable
        $varName = preg_replace('/[^a-zA-Z0-9_]/', '_', $key);
        // Crée la variable dynamiquement
        $$varName = $value;
    }

    $objet_relation = $data['objet_relation[]'];

    if(empty($data['capital_social_input']))
    {
        $capital_social_input = 0;
    }

    checkFK($pdo, 'formes_juridiques', $forme_juridique_input, "La forme juridique n'existe pas.", $retour);
    checkFK($pdo, 'pays', $lieu_activite_input, "Le lieu d'activité n'existe pas.", $retour);
    checkFK($pdo, 'pays', $residence_fiscale_input, "Le lieu de résidence fiscale n'existe pas.", $retour);
    checkFK($pdo, 'secteurs', $secteur_activite_input, "Le secteur d'activité n'existe pas.", $retour);
    checkFK($pdo, 'segments', $segment_input, "Le segment n'existe pas.", $retour);
    checkFK($pdo, 'pays', $pays_provenance_fonds_input, "Le pays de provenance n'existe pas.", $retour);

}

$dateajout = date('Y-m-d H:i:s');

$usentityPath = $data['usentity'] ?? "";

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
nomRegulateur,
telephone,
email,
siteweb,
secteurActivite,
segment,
paysEtranger,
publicEpargne,
usEntity,
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
mandataire,
dateFinMandat,
partPortfeuille,
instruments,
niveauRisqueTolere,
anneesInvestissement,
transactions2annees,
ajouteLe,
validation)

VALUES (
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?,
?
)");
if($retour != "<ul class='text-danger'>")
{
  echo "Merci de vérifier les champs :";
}
else
{
  $stmt->execute([

    $data['raison_social_input'],
    $capital_social_input,
    $forme_juridique_input,
    $data['date_immatriculation_input'],
    $ice_input,
    $rc_input,
    $if_input,
    $data['siege_social_input'],
    $lieu_activite_input,
    $residence_fiscale_input,
    $data['autorite_regulation_input'],
    $data['telephone_input'],
    $data['email_input'],
    $data['site_web_input'],
    $secteur_activite_input,
    $segment_input,
    $data['activite_etranger_input'],
    $data['sur_marche_financier_input'],
    $usentityPath,
    $data['giin_input'],
    $data['giin_autres_input'],
    $data['financier_capital_social_input'],
    $data['origine_fonds_input'],
    $pays_provenance_fonds_input,
    $data['chiffre_affaires_radio'],
    $resultat_net_input,
    $data['groupe_holding_radio'],
    $data['relation_affaire_radio'],
    $data['horizon_placement_radio'],
    json_encode($objet_relation),
    $data['mandataire_input'],
    $data['mandataire_fin_mandat_date'],
    $data['departement_gestion_input'],
    json_encode($data['instruments_souhaites_input[]']),
    $niveau_risque_tolere_radio,
    $annees_investissement_produits_finaniers,
    $transactions_courant_2_annees,
    $dateajout,
    0
  ]);

  $idEtablissement = $pdo->lastInsertId(); // FK pour les autres tables

  // ➤ 2. Insert dans `contacts`
  if (isset($data['noms_contacts[]'])) {
    if(is_array($data['noms_contacts[]']))
    {
      foreach ($data['noms_contacts[]'] as $i => $nom) {
        $email = $data['emails_contacts[]'][$i] ?? '';
        $tel = $data['telephones_contacts[]'][$i] ?? '';
        $fonction = $data['fonctions_contacts[]'][$i] ?? '';

        $stmt = $pdo->prepare("INSERT INTO contacts (nom, email, telephone, fonction, idEtablissement) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $email, $tel, $fonction, $idEtablissement]);
      }
    }
    else
    {
        $nom = $data['noms_contacts[]'] ?? '';
        $email = $data['emails_contacts[]'] ?? '';
        $tel = $data['telephones_contacts[]'] ?? '';
        $fonction = $data['fonctions_contacts[]'] ?? '';

        $stmt = $pdo->prepare("INSERT INTO contacts (nom, email, telephone, fonction, idEtablissement) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $email, $tel, $fonction, $idEtablissement]);
    }
  }

  // ➤ 3. Insert dans `comptes_bancaires`
  if (isset($data['noms_banques[]'])) {
    if(is_array($data['noms_banques[]']))
    {
      foreach ($data['noms_banques[]'] as $i => $banque) {
        $agence = $data['agences_banques[]'][$i] ?? '';
        $ville = $data['villes_banques[]'][$i] ?? '';
        $rib = $data['ribs_banques[]'][$i] ?? '';

        $stmt = $pdo->prepare("INSERT INTO comptes_bancaires (banque, agence, ville, rib, idEtablissement) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$banque, $agence, $ville, $rib, $idEtablissement]);
      }
    }
    else
    {
        $banque = $data['noms_banques[]'] ?? '';
        $agence = $data['agences_banques[]'] ?? '';
        $ville = $data['villes_banques[]'] ?? '';
        $rib = $data['ribs_banques[]'] ?? '';

        $stmt = $pdo->prepare("INSERT INTO comptes_bancaires (banque, agence, ville, rib, idEtablissement) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$banque, $agence, $ville, $rib, $idEtablissement]);
    }
  }

  // ➤ 4. Insert dans `actionnaires`
  if (isset($data['noms_rs_actionnaires[]'])) {
    if(is_array($data['noms_rs_actionnaires[]']))
    {
      foreach ($data['noms_rs_actionnaires[]'] as $i => $nom) {
        $identite = $data['identite_actionnaires[]'][$i] ?? '';
        $nbr_titres = $data['nombre_titres_actionnaires[]'][$i] ?? '';
        $pourcentage = $data['pourcentage_capital_actionnaires[]'][$i] ?? '';

        $stmt = $pdo->prepare("INSERT INTO actionnaires (nom, cinRC, nombreTitres, capital, idEtablissement) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $identite, $nbr_titres, $pourcentage, $idEtablissement]);
      }
    }
    else
    {
        $nom = $data['noms_rs_actionnaires[]'] ?? '';
        $identite = $data['identite_actionnaires[]'] ?? '';
        $nbr_titres = $data['nombre_titres_actionnaires[]'] ?? '';
        $pourcentage = $data['pourcentage_capital_actionnaires[]'] ?? '';

        $stmt = $pdo->prepare("INSERT INTO actionnaires (nom, cinRC, nombreTitres, capital, idEtablissement) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $identite, $nbr_titres, $pourcentage, $idEtablissement]);
    }
  }


  // ➤ 5. Insert dans `benificiaires`
  if (isset($data['noms_rs_benificiaires[]'])) {
    if(is_array($data['noms_rs_benificiaires[]']))
    {
      foreach ($data['noms_rs_benificiaires[]'] as $i => $nom) {
        $pays_naissance_benificiaire = $data['pays_naissance_benificiaires[]'][$i] ?? '';
        $date_naissance_benificiaire = $data['dates_naissance_benificiaires[]'][$i] ?? '';
        $identite_benificiaires = $data['identite_benificiaires[]'][$i] ?? '';
        $nationalites_benificiaires = $data['nationalites_benificiaires[]'][$i] ?? '';
        $benificiaires_ppe_input = $data['benificiaires_ppe_input[]'][$i] ?? '';
        $benificiaires_ppe_lien_input = $data['benificiaires_ppe_lien_input[]'][$i] ?? '';
        $benificiaires_pourcentage_capital = $data['benificiaires_pourcentage_capital[]'][$i] ?? '';

        $stmt = $pdo->prepare("INSERT INTO benificiaires (nom, paysNaissance, dateNaissance, cinPasseport, nationalite, ppe, lienPPE, capital, idEtablissement) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $pays_naissance_benificiaire, $date_naissance_benificiaire, $identite_benificiaires, $nationalites_benificiaires, $benificiaires_ppe_input, $benificiaires_ppe_lien_input, $benificiaires_pourcentage_capital, $idEtablissement]);

        majCalculPPE($pdo, $idEtablissement, $benificiaires_ppe_input, $benificiaires_ppe_lien_input, 'Bénéficiaire');

      }
    }
    else
    {
        $nom = $data['noms_rs_benificiaires[]'] ?? '';
        $pays_naissance_benificiaire = $data['pays_naissance_benificiaires[]'] ?? '';
        $date_naissance_benificiaire = $data['dates_naissance_benificiaires[]'] ?? '';
        $identite_benificiaires = $data['identite_benificiaires[]'] ?? '';
        $nationalites_benificiaires = $data['nationalites_benificiaires[]'] ?? '';
        $benificiaires_ppe_input = $data['benificiaires_ppe_input[]'] ?? '';
        $benificiaires_ppe_lien_input = $data['benificiaires_ppe_lien_input[]'] ?? '';
        $benificiaires_pourcentage_capital = $data['benificiaires_pourcentage_capital[]'] ?? '';

        $stmt = $pdo->prepare("INSERT INTO benificiaires (nom, paysNaissance, dateNaissance, cinPasseport, nationalite, ppe, lienPPE, capital, idEtablissement) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $pays_naissance_benificiaire, $date_naissance_benificiaire, $identite_benificiaires, $nationalites_benificiaires, $benificiaires_ppe_input, $benificiaires_ppe_lien_input, $benificiaires_pourcentage_capital, $idEtablissement]);

        majCalculPPE($pdo, $idEtablissement, $benificiaires_ppe_input, $benificiaires_ppe_lien_input, 'Bénéficiaire');

    }
  }


  // ➤ 6. Insert dans `administrateurs`
  if (isset($data['noms_administrateurs[]'])) {
    
    if(is_array($data['noms_administrateurs[]']))
    {
      foreach ($data['noms_administrateurs[]'] as $i => $nom) {
        $pays_naissance_administrateur = $data['pays_administrateurs[]'][$i] ?? '';
        $date_naissance_administrateur = $data['dates_naissance_administrateurs[]'][$i] ?? '';
        $identite_administrateurs = $data['cins_administrateurs[]'][$i] ?? '';
        $nationalites_administrateurs = $data['nationalites_administrateurs[]'][$i] ?? '';
        $administrateurs_ppe_input = $data['ppes_administrateurs_input[]'][$i] ?? '';
        $administrateurs_ppe_lien_input = $data['ppes_lien_administrateurs_input[]'][$i] ?? '';
        $fonctions_administrateurs = $data['fonctions_administrateurs[]'][$i] ?? '';

        $stmt = $pdo->prepare("INSERT INTO administrateurs (nom, paysNaissance, dateNaissance, cinPasseport, nationalite, ppe, lienPPE, fonction, idEtablissement) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $pays_naissance_administrateur, $date_naissance_administrateur, $identite_administrateurs, $nationalites_administrateurs, $administrateurs_ppe_input, $administrateurs_ppe_lien_input, $fonctions_administrateurs, $idEtablissement]);

        majCalculPPE($pdo, $idEtablissement, $administrateurs_ppe_input, $benificiaires_ppe_lien_input, 'Administrateur');

      }
    }
    else
    {
        $nom = $data['noms_administrateurs[]'] ?? '';
        $pays_naissance_administrateur = $data['pays_administrateurs[]'] ?? '';
        $date_naissance_administrateur = $data['dates_naissance_administrateurs[]'] ?? '';
        $identite_administrateurs = $data['cins_administrateurs[]'] ?? '';
        $nationalites_administrateurs = $data['nationalites_administrateurs[]'] ?? '';
        $administrateurs_ppe_input = $data['ppes_administrateurs_input[]'] ?? '';
        $administrateurs_ppe_lien_input = $data['ppes_lien_administrateurs_input[]'] ?? '';
        $fonctions_administrateurs = $data['fonctions_administrateurs[]'] ?? '';

        $stmt = $pdo->prepare("INSERT INTO administrateurs (nom, paysNaissance, dateNaissance, cinPasseport, nationalite, ppe, lienPPE, fonction, idEtablissement) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $pays_naissance_administrateur, $date_naissance_administrateur, $identite_administrateurs, $nationalites_administrateurs, $administrateurs_ppe_input, $administrateurs_ppe_lien_input, $fonctions_administrateurs, $idEtablissement]);

        majCalculPPE($pdo, $idEtablissement, $administrateurs_ppe_input, $benificiaires_ppe_lien_input, 'Administrateur');
    }
  }


  // ➤ 7. Insert dans `habilites`
  if (isset($data['noms_habilites[]'])) {
    if(is_array($data['noms_habilites[]']))
    {
      foreach ($data['noms_habilites[]'] as $i => $nom) {
        $identite = $data['cins_habilites[]'][$i] ?? '';
        $fonction = $data['fonctions_habilites[]'][$i] ?? '';

        $stmt = $pdo->prepare("INSERT INTO habilites (nom, cinPasseport, fonction, idEtablissement) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $identite, $fonction, $idEtablissement]);
      }
    }
    else
    {
        $nom = $data['noms_habilites[]'] ?? '';
        $identite = $data['cins_habilites[]'] ?? '';
        $fonction = $data['fonctions_habilites[]'] ?? '';

        $stmt = $pdo->prepare("INSERT INTO habilites (nom, cinPasseport, fonction, idEtablissement) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $identite, $fonction, $idEtablissement]);
    }
  }

}



if($retour == "<ul class='text-danger'>")
{
    $retour = "<ul class='text-success'>Enregistrement effectué !";
}
else
{
    //$retour = "<ul class='text-danger'>Erreur !";
}

$retour .= "</ul>";

echo $retour;

?>