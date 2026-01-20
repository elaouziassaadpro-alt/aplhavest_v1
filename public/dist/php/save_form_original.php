<?php

define('APP_INIT', true);

$retour = "";

$data = json_decode(file_get_contents('php://input'), true);

// Connexion PDO
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if(!isset($data['autorite_regulation_check']) || ($data['autorite_regulation_check'] != "" && !is_null($data['autorite_regulation_check'])))
{
  $data['autorite_regulation_input'] = $data['autorite_regulation_input'];
}
else
{
  $data['autorite_regulation_input'] = "-";
  $retour .= "<br>";
  $retour .= "<li>Nom du régulateur</li>";
}

if(!isset($data['pays_provenance_fonds_input']) || is_null($data['pays_provenance_fonds_input']))
{
  $pays_provenance_fonds_input = "";
}
else
{
  $pays_provenance_fonds_input = $data['pays_provenance_fonds_input'];
  $retour .= "<br>";
  $retour .= "<li>Pays de provenance des fonds</li>";
}

//json_encode($data['objet_relation[]']),

if(!isset($data['objet_relation[]']) || is_null($data['objet_relation[]']))
{
  $objet_relation = "";
}
else
{
  $objet_relation = $data['objet_relation[]'];
  $retour .= "<br>";
  $retour .= "<li>Objet de la relation d'affaire</li>";
}

//$data['niveau_risque_tolere_radio'],

if(!isset($data['niveau_risque_tolere_radio']) || is_null($data['niveau_risque_tolere_radio']))
{
  $niveau_risque_tolere_radio = "";
}
else
{
  $niveau_risque_tolere_radio = $data['niveau_risque_tolere_radio'];
  $retour .= "<br>";
  $retour .= "<li>Le niveau de risque toléré</li>";
}

//annees_investissement_produits_finaniers

if(!isset($data['annees_investissement_produits_finaniers']) || is_null($data['annees_investissement_produits_finaniers']))
{
  $annees_investissement_produits_finaniers = "";
}
else
{
  $annees_investissement_produits_finaniers = $data['annees_investissement_produits_finaniers'];
  $retour .= "<br>";
  $retour .= "<li>Années d'investissement dans les produits financiers</li>";
}

//transactions_courant_2_annees

if(!isset($data['transactions_courant_2_annees']) || is_null($data['transactions_courant_2_annees']))
{
  $transactions_courant_2_annees = "";
}
else
{
  $transactions_courant_2_annees = $data['transactions_courant_2_annees'];
  $retour .= "<br>";
  $retour .= "<li>Transactions en moyenne réalisés sur le marché courant 2 dernières années</li>";
}

//$data['capital_social_input'],

if(!isset($data['capital_social_input']) || is_null($data['capital_social_input']) || $data['capital_social_input'] == "")
{
  $capital_social_input = 0;
}
else
{
  $capital_social_input = $data['capital_social_input'];
  $retour .= "<br>";
  $retour .= "<li>Situation financière : Capital social</li>";
}

if(!isset($data['forme_juridique_input']) || is_null($data['forme_juridique_input']) || $data['forme_juridique_input'] == "0")
{
  $forme_juridique_input = 0;
  $retour .= "<br>";
  $retour .= "<li>Forme juridique</li>";
}
else
{
  $forme_juridique_input = $data['forme_juridique_input'];
}

if(!isset($data['secteur_activite_input']) || is_null($data['secteur_activite_input']) || $data['secteur_activite_input'] == "0")
{
  $secteur_activite_input = 0;
  $retour .= "<br>";
  $retour .= "<li>Secteur d'activité</li>";
}
else
{
  $secteur_activite_input = $data['secteur_activite_input'];
}

if(!isset($data['segment_input']) || is_null($data['segment_input']) || $data['segment_input'] == "0")
{
  $segment_input = 0;
  $retour .= "<br>";
  $retour .= "<li>Segment</li>";
}
else
{
  $segment_input = $data['segment_input'];
}

if(!isset($data['ice_input']) || is_null($data['ice_input']) || $data['ice_input'] == "")
{
  $ice_input = 0;
}
else
{
  $ice_input = $data['ice_input'];
  $retour .= "<br>";
  $retour .= "<li>ICE invalid</li>";
}

if(!isset($data['rc_input']) || is_null($data['rc_input']) || $data['rc_input'] == "")
{
  $rc_input = 0;
}
else
{
  $rc_input = $data['rc_input'];
  $retour .= "<br>";
  $retour .= "<li>RC invalid</li>";
}

if(!isset($data['if_input']) || is_null($data['if_input']) || $data['if_input'] == "")
{
  $if_input = 0;
}
else
{
  $if_input = $data['if_input'];
  $retour .= "<br>";
  $retour .= "<li>IF invalid</li>";
}

//$lieu_activite_input,
//  $residence_fiscale_input,

if(!isset($data['lieu_activite_input']) || is_null($data['lieu_activite_input']) || $data['lieu_activite_input'] == "Choisissez une option")
{
  $lieu_activite_input = 0;
}
else
{
  $lieu_activite_input = $data['lieu_activite_input'];
  $retour .= "<br>";
  $retour .= "<li>Pays d'activité</li>";
}

if(!isset($data['residence_fiscale_input']) || is_null($data['residence_fiscale_input']) || $data['residence_fiscale_input'] == "Choisissez une option")
{
  $residence_fiscale_input = 0;
}
else
{
  $residence_fiscale_input = $data['residence_fiscale_input'];
  $retour .= "<br>";
  $retour .= "<li>Pays de résidence fiscale</li>";
}

if(!isset($data['activite_etranger_input']) || is_null($data['activite_etranger_input']) || $data['activite_etranger_input'] == "Choisissez une option")
{
  $activite_etranger_input = 0;
}
else
{
  $activite_etranger_input = $data['activite_etranger_input'];
  $retour .= "<br>";
  $retour .= "<li>Pays de résidence fiscale</li>";
}


if(!isset($data['resultat_net_input']) || is_null($data['resultat_net_input']) || $data['resultat_net_input'] == "")
{
  $resultat_net_input = 0;
}
else
{
  $resultat_net_input = $data['resultat_net_input'];
  $retour .= "<br>";
  $retour .= "<li>Résultat net (exercice écoulé)</li>";
}

$dateajout = date('Y-m-d H:i:s');

$usentityPath = $data['usentity'] ?? "";

// Vérification de la checkbox
if (!empty($data['us_entity_check']) && $data['us_entity_check'] == "on") {
    // Checkbox cochée → il doit y avoir un fichier
    if (empty($usentityPath)) {
        $retour .= "<li>Vous devez fournir un fichier US Entity.</li>";
    }
} else {
    // Checkbox décochée → le champ reste vide
    $usentityPath = "";
}




function checkFK(PDO $pdo, string $table, $id, string $messageErreur) {
    $sql = "SELECT COUNT(*) FROM `$table` WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->fetchColumn() == 0) {
        return "<li>$messageErreur</li>";
    }

    return "";
}

$retour = "<ul><span class='text-dark'>Merci de vérifier les champs :</span> ";

if (empty($data['raison_social_input'])) {  $retour .= "<li>Raison sociale.</li>";}
if (empty($data['capital_social_input'])) {  $retour .= "<li>Capital social.</li>";}
if (empty($data['ice_input'])) {  $retour .= "<li>ICE.</li>";}

$retour .= checkFK($pdo, 'formes_juridiques', $forme_juridique_input, "Forme juridique.");
$retour .= checkFK($pdo, 'pays', $lieu_activite_input, "Lieu d'activité.");
$retour .= checkFK($pdo, 'pays', $residence_fiscale_input, "Pays de résidence.");
$retour .= checkFK($pdo, 'secteurs', $secteur_activite_input, "Secteur d'activité.");
$retour .= checkFK($pdo, 'segments', $segment_input, "Segment.");
$retour .= checkFK($pdo, 'pays', $pays_provenance_fonds_input, "Pays de provenance des fonds.");
$retour .= checkFK($pdo, 'pays', $activite_etranger_input, "Pays etranger d'activité.");

$retour .= "</ul>";

if (strlen($retour) > strlen("<ul></ul>")) {
    echo $retour;
    exit;
}

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
if($retour == "")
{
  echo "Une erreur est survenue, merci de vérifier les champs !";
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
    $activite_etranger_input,
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
      }
    }
    else
    {
        $nom = $data['noms_rs_benificiaires[]'] ?? '';
        $pays_naissance_benificiaire = $data['pays_administrateurs[]'] ?? '';
        $date_naissance_administrateur = $data['dates_naissance_administrateurs[]'] ?? '';
        $identite_administrateurs = $data['cins_administrateurs[]'] ?? '';
        $nationalites_administrateurs = $data['nationalites_administrateurs[]'] ?? '';
        $administrateurs_ppe_input = $data['ppes_administrateurs_input[]'] ?? '';
        $administrateurs_ppe_lien_input = $data['ppes_lien_administrateurs_input[]'] ?? '';
        $fonctions_administrateurs = $data['fonctions_administrateurs[]'] ?? '';

        $stmt = $pdo->prepare("INSERT INTO administrateurs (nom, paysNaissance, dateNaissance, cinPasseport, nationalite, ppe, lienPPE, fonction, idEtablissement) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $pays_naissance_administrateur, $date_naissance_administrateur, $identite_administrateurs, $nationalites_administrateurs, $administrateurs_ppe_input, $administrateurs_ppe_lien_input, $fonctions_administrateurs, $idEtablissement]);
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





?>