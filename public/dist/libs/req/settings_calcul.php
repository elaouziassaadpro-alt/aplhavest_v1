<?php

$note_globale = 0;

$risque_ajout_ppe  = 30;

function getLibellePPE($pdo, $id) {
    $stmt = $pdo->prepare("SELECT libelle FROM ppes WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchColumn() ?: "";
}

//majCalculPPE($pdo, $idEtablissement, $ppe, $lien, 'Personne habilitée', $risque_ajout_ppe);
function majCalculPPE($pdo, $idEtablissement, $ppe, $lienPPE, $source, $noteppe) {
    $ppe_valide = is_numeric($ppe) && $ppe > 0 && $ppe < 10000;
    $lienPPE_valide = is_numeric($lienPPE) && $lienPPE > 0 && $lienPPE < 10000;

    $note_ajout = 0;
    $risque_ajout = 0;

    $nbrppe = 0;

    $libellePPE = getLibellePPE($pdo, $ppe);
    $libelleLienPPE = getLibellePPE($pdo, $lienPPE);

    if ($ppe_valide) {
        $note_ajout += 1;
        $risque_ajout += $noteppe ;

        // Détail pour PPE
        $insert_detail = $pdo->prepare("INSERT INTO details_calcul (note, detailNote, idEtablissement) VALUES (?, ?, ?)");
        $insert_detail->execute([$noteppe, 'PPE ' . $source . ':'.$libellePPE, $idEtablissement]);

        $nbrppe = $nbrppe + 1;
    }

    if ($lienPPE_valide) {
        $note_ajout += 1;
        $risque_ajout += $noteppe ;

        // Détail pour Lien PPE
        $insert_detail = $pdo->prepare("INSERT INTO details_calcul (note, detailNote, idEtablissement) VALUES (?, ?, ?)");
        $insert_detail->execute([$noteppe, 'Lien PPE ' . $source . ':'.$libelleLienPPE, $idEtablissement]);
        $nbrppe = $nbrppe + 1;
    }

    if($risque_ajout != 0)
    {
    	$risque_ajout = $risque_ajout / $nbrppe;
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



function majCalculSegment($pdo, $idEtablissement, $segment_id) {
    // Récupération du segment
    $stmt = $pdo->prepare("SELECT niveauRisque, libelle FROM segments WHERE id = ?");
    $stmt->execute([$segment_id]);
    $segment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$segment) {
        return; // Aucun segment trouvé
    }

    $note_segment = (float)$segment['niveauRisque'];
    $libelle = $segment['libelle'];

    // Insertion dans details_calcul
    $stmtDetail = $pdo->prepare("
        INSERT INTO details_calcul (note, detailNote, idEtablissement, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    $stmtDetail->execute([
        $note_segment,
        'Segment : ' . $libelle,
        $idEtablissement
    ]);

    // Vérifie si une ligne existe déjà
    $stmtCheck = $pdo->prepare("SELECT note, niveauRisque FROM calcul_etablissement WHERE idEtablissement = ?");
    $stmtCheck->execute([$idEtablissement]);
    $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $nouveau_risque = $row['niveauRisque'] + $note_segment;

        $update = $pdo->prepare("
            UPDATE calcul_etablissement
            SET niveauRisque = ?
            WHERE idEtablissement = ?
        ");
        $update->execute([$nouveau_risque, $idEtablissement]);

    } else {
        // Si aucune ligne n'existe encore
        $insert = $pdo->prepare("
            INSERT INTO calcul_etablissement (idEtablissement, note, niveauRisque)
            VALUES (?, ?, ?)
        ");
        $insert->execute([$idEtablissement, 0, $note_segment]); // note = 0 ici, seul niveauRisque compte
    }
}

function majCalculSecteur($pdo, $idEtablissement, $secteur_id) {
    // Récupération du segment
    $stmt = $pdo->prepare("SELECT niveauRisque, libelle FROM secteurs WHERE id = ?");
    $stmt->execute([$secteur_id]);
    $secteur = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$secteur) {
        return; // Aucun segment trouvé
    }

    $note_secteur = (float)$secteur['niveauRisque'];
    $libelle = $secteur['libelle'];

    // Insertion dans details_calcul
    $stmtDetail = $pdo->prepare("
        INSERT INTO details_calcul (note, detailNote, idEtablissement, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    $stmtDetail->execute([
        $note_secteur,
        'Secteur : ' . $libelle,
        $idEtablissement
    ]);

    // Vérifie si une ligne existe déjà
    $stmtCheck = $pdo->prepare("SELECT note, niveauRisque FROM calcul_etablissement WHERE idEtablissement = ?");
    $stmtCheck->execute([$idEtablissement]);
    $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $nouveau_risque = $row['niveauRisque'] + $note_secteur;

        $update = $pdo->prepare("
            UPDATE calcul_etablissement
            SET niveauRisque = ?
            WHERE idEtablissement = ?
        ");
        $update->execute([$nouveau_risque, $idEtablissement]);

    } else {
        // Si aucune ligne n'existe encore
        $insert = $pdo->prepare("
            INSERT INTO calcul_etablissement (idEtablissement, note, niveauRisque)
            VALUES (?, ?, ?)
        ");
        $insert->execute([$idEtablissement, 0, $note_secteur]); // note = 0 ici, seul niveauRisque compte
    }
}


function majCalculRegulite($pdo, $idEtablissement, $regulite_check, $regulateur) {
    // Détermination de la note selon la régularité
    if ($regulite_check == 1) {
        $note_regulite = 1;
        $libelle = $regulateur;
    } else {
        $note_regulite = 2;
        $libelle = 'Non régulé';
    }

    // Insertion du détail dans details_calcul
    $stmtDetail = $pdo->prepare("
        INSERT INTO details_calcul (note, detailNote, idEtablissement, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    $stmtDetail->execute([
        $note_regulite,
        'Régulé : ' . $libelle,
        $idEtablissement
    ]);

    // Vérifie si une ligne existe déjà dans calcul_etablissement
    $stmtCheck = $pdo->prepare("SELECT note, niveauRisque FROM calcul_etablissement WHERE idEtablissement = ?");
    $stmtCheck->execute([$idEtablissement]);
    $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Ajout au risque global
        $nouveau_risque = $row['niveauRisque'] + $note_regulite;

        $update = $pdo->prepare("
            UPDATE calcul_etablissement
            SET niveauRisque = ?
            WHERE idEtablissement = ?
        ");
        $update->execute([$nouveau_risque, $idEtablissement]);
    } else {
        // Si aucune ligne n'existe encore
        $insert = $pdo->prepare("
            INSERT INTO calcul_etablissement (idEtablissement, note, niveauRisque)
            VALUES (?, ?, ?)
        ");
        $insert->execute([$idEtablissement, 0, $note_regulite]);
    }
}


function majCalculPaysResidenceFiscal($pdo, $idEtablissement, $paysrf_id) {
    // Récupération du pays de residence fiscal
    $stmt = $pdo->prepare("SELECT niveauRisque, libelle FROM pays WHERE id = ?");
    $stmt->execute([$paysrf_id]);
    $paysrf = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$paysrf) {
        return; // Aucun paysrf trouvé
    }

    $note_paysrf = (float)$paysrf['niveauRisque'];
    if($note_paysrf > 2)
    {
        $note_paysrf = $note_paysrf * 5;
    }
    $libelle = $paysrf['libelle'];

    // Insertion dans details_calcul
    $stmtDetail = $pdo->prepare("
        INSERT INTO details_calcul (note, detailNote, idEtablissement, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    $stmtDetail->execute([
        $note_paysrf,
        'Pays Résidence : ' . $libelle,
        $idEtablissement
    ]);

    // Vérifie si une ligne existe déjà
    $stmtCheck = $pdo->prepare("SELECT note, niveauRisque FROM calcul_etablissement WHERE idEtablissement = ?");
    $stmtCheck->execute([$idEtablissement]);
    $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $nouveau_risque = $row['niveauRisque'] + $note_paysrf;

        $update = $pdo->prepare("
            UPDATE calcul_etablissement
            SET niveauRisque = ?
            WHERE idEtablissement = ?
        ");
        $update->execute([$nouveau_risque, $idEtablissement]);

    } else {
        // Si aucune ligne n'existe encore
        $insert = $pdo->prepare("
            INSERT INTO calcul_etablissement (idEtablissement, note, niveauRisque)
            VALUES (?, ?, ?)
        ");
        $insert->execute([$idEtablissement, 0, $note_paysrf]); // note = 0 ici, seul niveauRisque compte
    }
}


function majCalculPaysEtranger($pdo, $idEtablissement, $pays_etranger, $activite_etranger_input) {
    // Par défaut : national
    $note_pays = 0;
    $libelle = 'National';

    // Si activité étrangère activée
    if ($pays_etranger == 1) {
        if (!empty($activite_etranger_input)) {
            // Récupération du risque du pays sélectionné
            $stmt = $pdo->prepare("SELECT niveauRisque, libelle FROM pays WHERE id = ?");
            $stmt->execute([$activite_etranger_input]);
            $pays = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($pays) {
                $note_pays = (float)$pays['niveauRisque'];
                $note_pays = $note_pays * 3;
                $libelle = $pays['libelle'];
            } else {
                // Si le pays n’existe pas dans la table
                $note_pays = 2;
                $libelle = 'Pays inconnu';
            }
        } else {
            // Aucun pays renseigné mais activité étrangère activée
            $note_pays = 2;
            $libelle = 'Pays non spécifié';
        }
    }

    // Insertion du détail dans details_calcul
    $stmtDetail = $pdo->prepare("
        INSERT INTO details_calcul (note, detailNote, idEtablissement, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    $stmtDetail->execute([
        $note_pays,
        'Pays d’activité : ' . $libelle,
        $idEtablissement
    ]);

    // Vérifie si une ligne existe déjà dans calcul_etablissement
    $stmtCheck = $pdo->prepare("SELECT note, niveauRisque FROM calcul_etablissement WHERE idEtablissement = ?");
    $stmtCheck->execute([$idEtablissement]);
    $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Mise à jour du niveau de risque total
        $nouveau_risque = $row['niveauRisque'] + $note_pays;

        $update = $pdo->prepare("
            UPDATE calcul_etablissement
            SET niveauRisque = ?
            WHERE idEtablissement = ?
        ");
        $update->execute([$nouveau_risque, $idEtablissement]);
    } else {
        // Première insertion
        $insert = $pdo->prepare("
            INSERT INTO calcul_etablissement (idEtablissement, note, niveauRisque)
            VALUES (?, ?, ?)
        ");
        $insert->execute([$idEtablissement, 0, $note_pays]);
    }
}


function majCalculOrigineFonds($pdo, $idEtablissement, $origine_fonds_input) {
    // Vérification de la variable d'origine des fonds
    if (!empty($origine_fonds_input) && trim($origine_fonds_input) !== '') {
        $note_origine = 1;
        $libelle = $origine_fonds_input;
    } else {
        $note_origine = 3;
        $libelle = 'Origine inconnu';
    }

    // Insertion du détail dans details_calcul
    $stmtDetail = $pdo->prepare("
        INSERT INTO details_calcul (note, detailNote, idEtablissement, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    $stmtDetail->execute([
        $note_origine,
        'Origine des fonds : ' . $libelle,
        $idEtablissement
    ]);

    // Vérifie si une ligne existe déjà dans calcul_etablissement
    $stmtCheck = $pdo->prepare("SELECT note, niveauRisque FROM calcul_etablissement WHERE idEtablissement = ?");
    $stmtCheck->execute([$idEtablissement]);
    $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Mise à jour du niveau de risque global
        $nouveau_risque = $row['niveauRisque'] + $note_origine;

        $update = $pdo->prepare("
            UPDATE calcul_etablissement
            SET niveauRisque = ?
            WHERE idEtablissement = ?
        ");
        $update->execute([$nouveau_risque, $idEtablissement]);
    } else {
        // Première insertion
        $insert = $pdo->prepare("
            INSERT INTO calcul_etablissement (idEtablissement, note, niveauRisque)
            VALUES (?, ?, ?)
        ");
        $insert->execute([$idEtablissement, 0, $note_origine]);
    }
}


function majCalculBeneficiaires($pdo, $idEtablissement, $noms, $prenoms, $cins, $cins_fichiers, $nationalites) {
    $nb = count($noms);
    $note_globale = 0;

    for ($i = 0; $i < $nb; $i++) {
        $nom = trim($noms[$i] ?? '');
        $prenom = trim($prenoms[$i] ?? '');
        $cin = trim($cins[$i] ?? '');
        $fichier = trim($cins_fichiers[$i] ?? '');
        $pays_id = (int)($nationalites[$i] ?? 0);

        // --- 1. Calcul de la note de complétude ---
        if ($nom && $prenom && $cin && $fichier) {
            $note = 1;
            $detail = "Bénéficiaire complet : $nom $prenom ($cin)";
        } elseif ($nom || $prenom || $cin) {
            $note = 2;
            $detail = "Bénéficiaire incomplet : $nom $prenom ($cin)";
        } else {
            $note = 3;
            $detail = "Aucun bénéficiaire renseigné";
        }

        // --- Insertion du détail de complétude ---
        $stmt = $pdo->prepare("
            INSERT INTO details_calcul (note, detailNote, idEtablissement, created_at)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->execute([$note, $detail, $idEtablissement]);
        $note_globale += $note;

        // --- 2. Calcul de la note de pays ---
        if ($pays_id > 0) {
            $stmtPays = $pdo->prepare("SELECT libelle, niveauRisque FROM pays WHERE id = ?");
            $stmtPays->execute([$pays_id]);
            $pays = $stmtPays->fetch(PDO::FETCH_ASSOC);

            if ($pays) {
                $note_pays = (float)$pays['niveauRisque'];
                if($note_pays > 2)
                {
                    $note_pays = $note_pays * 5;
                }
                $detail_pays = "Nationalité bénéficiaire : {$pays['libelle']}";

                $stmt = $pdo->prepare("
                    INSERT INTO details_calcul (note, detailNote, idEtablissement, created_at)
                    VALUES (?, ?, ?, NOW())
                ");
                $stmt->execute([$note_pays, $detail_pays, $idEtablissement]);
                $note_globale += $note_pays;
            }
        }
    }

    // --- Mise à jour ou insertion dans calcul_etablissement ---
    $stmtCheck = $pdo->prepare("SELECT niveauRisque FROM calcul_etablissement WHERE idEtablissement = ?");
    $stmtCheck->execute([$idEtablissement]);
    $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $nouveau_risque = $row['niveauRisque'] + $note_globale;
        $update = $pdo->prepare("UPDATE calcul_etablissement SET niveauRisque = ? WHERE idEtablissement = ?");
        $update->execute([$nouveau_risque, $idEtablissement]);
    } else {
        $insert = $pdo->prepare("INSERT INTO calcul_etablissement (idEtablissement, note, niveauRisque) VALUES (?, ?, ?)");
        $insert->execute([$idEtablissement, 0, $note_globale]);
    }
}


function majCalculAdministrateurs($pdo, $idEtablissement, $noms, $prenoms, $cins, $cins_fichiers, $nationalites) {
    $nb = count($noms);
    $note_globale = 0;

    for ($i = 0; $i < $nb; $i++) {
        $nom = trim($noms[$i] ?? '');
        $prenom = trim($prenoms[$i] ?? '');
        $cin = trim($cins[$i] ?? '');
        $fichier = trim($cins_fichiers[$i] ?? '');
        $pays_id = (int)($nationalites[$i] ?? 0);

        // --- Complétude ---
        if ($nom && $prenom && $cin && $fichier) {
            $note = 1;
            $detail = "Administrateur complet : $nom $prenom ($cin)";
        } elseif ($nom || $prenom || $cin) {
            $note = 2;
            $detail = "Administrateur incomplet : $nom $prenom ($cin)";
        } else {
            $note = 3;
            $detail = "Aucun administrateur renseigné";
        }

        $stmt = $pdo->prepare("INSERT INTO details_calcul (note, detailNote, idEtablissement, created_at)
                               VALUES (?, ?, ?, NOW())");
        $stmt->execute([$note, $detail, $idEtablissement]);
        $note_globale += $note;

        // --- Pays ---
        if ($pays_id > 0) {
            $stmtPays = $pdo->prepare("SELECT libelle, niveauRisque FROM pays WHERE id = ?");
            $stmtPays->execute([$pays_id]);
            $pays = $stmtPays->fetch(PDO::FETCH_ASSOC);

            if ($pays) {
                $note_pays = (float)$pays['niveauRisque'];
                if($note_pays > 2)
                {
                    $note_pays = $note_pays * 5;
                }
                $detail_pays = "Nationalité administrateur : {$pays['libelle']}";
                $stmt = $pdo->prepare("INSERT INTO details_calcul (note, detailNote, idEtablissement, created_at)
                                       VALUES (?, ?, ?, NOW())");
                $stmt->execute([$note_pays, $detail_pays, $idEtablissement]);
                $note_globale += $note_pays;
            }
        }
    }

    // --- Mise à jour calcul global ---
    $stmtCheck = $pdo->prepare("SELECT niveauRisque FROM calcul_etablissement WHERE idEtablissement = ?");
    $stmtCheck->execute([$idEtablissement]);
    $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $nouveau_risque = $row['niveauRisque'] + $note_globale;
        $update = $pdo->prepare("UPDATE calcul_etablissement SET niveauRisque = ? WHERE idEtablissement = ?");
        $update->execute([$nouveau_risque, $idEtablissement]);
    } else {
        $insert = $pdo->prepare("INSERT INTO calcul_etablissement (idEtablissement, note, niveauRisque) VALUES (?, ?, ?)");
        $insert->execute([$idEtablissement, 0, $note_globale]);
    }
}



function majCalculMandataire($pdo, $idEtablissement, $mandataire_check, $mandataire) {
    // Détermination de la note selon la régularité
    if ($mandataire_check == 1) {
        $note_mandataire = 30;
        $libelle = $mandataire;
    } else {
        $note_mandataire = 0;
        $libelle = 'Non mandataire';
    }

    // Insertion du détail dans details_calcul
    $stmtDetail = $pdo->prepare("
        INSERT INTO details_calcul (note, detailNote, idEtablissement, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    $stmtDetail->execute([
        $note_mandataire,
        'Mandataire : ' . $libelle,
        $idEtablissement
    ]);

    // Vérifie si une ligne existe déjà dans calcul_etablissement
    $stmtCheck = $pdo->prepare("SELECT note, niveauRisque FROM calcul_etablissement WHERE idEtablissement = ?");
    $stmtCheck->execute([$idEtablissement]);
    $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Ajout au risque global
        $nouveau_risque = $row['niveauRisque'] + $note_mandataire;

        $update = $pdo->prepare("
            UPDATE calcul_etablissement
            SET niveauRisque = ?
            WHERE idEtablissement = ?
        ");
        $update->execute([$nouveau_risque, $idEtablissement]);
    } else {
        // Si aucune ligne n'existe encore
        $insert = $pdo->prepare("
            INSERT INTO calcul_etablissement (idEtablissement, note, niveauRisque)
            VALUES (?, ?, ?)
        ");
        $insert->execute([$idEtablissement, 0, $note_mandataire]);
    }

    //echo $mandataire;
}



function translitterer_arabe($str) {
    $trans = [
        'ا'=>'a','أ'=>'a','إ'=>'i','آ'=>'a','ب'=>'b','ت'=>'t','ث'=>'th','ج'=>'j','ح'=>'h','خ'=>'kh',
        'د'=>'d','ذ'=>'dh','ر'=>'r','ز'=>'z','س'=>'s','ش'=>'ch','ص'=>'s','ض'=>'d','ط'=>'t','ظ'=>'z',
        'ع'=>'a','غ'=>'gh','ف'=>'f','ق'=>'k','ك'=>'k','ل'=>'l','م'=>'m','ن'=>'n','ه'=>'h','و'=>'w','ي'=>'y',
        'ى'=>'a','ء'=>'','ؤ'=>'w','ئ'=>'y','ة'=>'a'
    ];
    return strtolower(trim(strtr($str, $trans)));
}

function checkCorrespondance($pdo, $nomSaisi) {
    $nomSaisi = translitterer_arabe($nomSaisi);
    $table = 'cnasnu';
    $maxPourcentage = 0;
    $ligneCorrespondante = [
        'first_name' => '',
        'second_name' => '',
        'third_name' => '',
        'fourth_name' => ''
    ];

    $stmt = $pdo->query("SELECT first_name, second_name, third_name, fourth_name FROM $table");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $percentMaxLigne = 0;

        // Cherche le meilleur % pour cette ligne
        foreach (['first_name', 'second_name', 'third_name', 'fourth_name'] as $col) {
            $nomListe = translitterer_arabe($row[$col] ?? '');
            if ($nomListe !== '') {
                similar_text($nomSaisi, $nomListe, $percent);
                if ($percent > $percentMaxLigne) {
                    $percentMaxLigne = $percent;
                }
            }
        }

        // Si cette ligne est meilleure que toutes les précédentes
        if ($percentMaxLigne > $maxPourcentage) {
            $maxPourcentage = $percentMaxLigne;
            $ligneCorrespondante = [
                'first_name' => $row['first_name'],
                'second_name' => $row['second_name'],
                'third_name' => $row['third_name'],
                'fourth_name' => $row['fourth_name']
            ];
        }
    }

    return [
        'noms' => $ligneCorrespondante,
        'pourcentage' => round($maxPourcentage, 2)
    ];
}






