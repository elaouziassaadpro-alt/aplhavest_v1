<div class="widget-content searchable-container list">
  <!-- --------------------- start Contact ---------------- -->
  <div class="card card-body boutons_header">
    <div class="row">
      <div class="col-md-4 col-xl-3">
        <form class="position-relative">
          <input type="text" class="form-control product-search ps-5" id="input-search" placeholder="Recherche rapide..." />
          <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
        </form>
      </div>
      <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
        <div class="action-btn show-btn" style="display: none">
          <a href="javascript:void(0)" class="btn-light-success btn me-2 text-success d-flex align-items-center font-medium">
            <i class="ti ti-check text-success me-1 fs-5"></i> Valider la selection 
          </a>
        </div>
        <div class="action-btn show-btn" style="display: none">
          <a href="javascript:void(0)" class="btn-light-danger btn me-2 text-danger d-flex align-items-center font-medium">
            <i class="ti ti-x text-danger me-1 fs-5"></i> Rejeter la selection
          </a>
        </div>
        <a href="nouvel_etablissement.php" class="btn btn-info d-flex align-items-center">
          <i class="ti ti-users text-white me-1 fs-5"></i> Ajouter un etablissement
        </a>
      </div>
    </div>
  </div>

  <?php

  use Layout\popupinsertion;
  $popupinsertion = new popupinsertion();
  $popupinsertion->render();

  ?>
  <!-- ---------------------
                  end Contact
              ---------------- -->

  <div class="card card-body">
    <div class="table-responsive">
      <table class="table search-table align-middle text-nowrap">
        <thead class="header-item">
          <th>
            <div class="n-chk align-self-center text-center">
              <div class="form-check">
                <input type="checkbox" class="form-check-input primary" id="contact-check-all" />
                <label class="form-check-label" for="contact-check-all"></label>
                <span class="new-control-indicator">Tous</span>
              </div>
            </div>
          </th>
          <th>Dénomination</th>
          <th>Pays d'origine</th>
          <th>Secteur / Activité</th>
          <th>Risque</th>
          <th>Note</th>
          <th>Etat</th>
          <th>Détails</th>
        </thead>
        <tbody>

          <?php
          


          $stmt = $pdo->query("SELECT *,e.id as eid, p.libelle AS paysResidence, p.iso AS paysResidenceIso, s.libelle AS slibelle FROM etablissement e JOIN pays p ON e.paysResidence = p.id JOIN secteurs s ON e.secteurActivite = s.id");
          $etablissements = $stmt->fetchAll(PDO::FETCH_ASSOC);

          foreach ($etablissements as $etab) {

            $idEtablissement = $etab['eid'];
            $nom = $etab['raisonSocial'];
            $pays = $etab['paysResidence'];
            $paysIso = $etab['paysResidenceIso'];
            $secteur = $etab['slibelle'];
            $risque = "LR";
            $note = "20";
            $etat = $etab['validation'];
            $details = '<a href="details_etablissement.php?idEtablissement=' . $idEtablissement . '" class="text-info center"><i class="ti ti-arrow-right fs-5"></i></a>';


            $stmtCalcul = $pdo->prepare("SELECT SUM(niveauRisque) AS noteGlobale FROM calcul_etablissement WHERE idEtablissement = ?");
            $stmtCalcul->execute([$idEtablissement]);
            $calculs = $stmtCalcul->fetchAll(PDO::FETCH_ASSOC);
          
            foreach ($calculs as $calcul) {
                $noteGlobale = $calcul['noteGlobale'] ?? 0;

                // Valeurs par défaut si jamais aucun seuil ne correspond
                $noteRisque = 'N/A';
                $noteType = 'secondary';

                foreach ($seuils_risque as $seuil) {
                    if ($noteGlobale < $seuil['seuil']) {
                        $noteRisque = $seuil['noteRisque'];
                        $noteType = $seuil['noteType'];
                        break; // on s'arrête dès qu'on trouve le bon seuil
                    }
                }
            }

            if($noteGlobale < 34)
            {
                $validation = 1;
                $text = "success";

                $pdo->beginTransaction();



                $update = $pdo->prepare("
                    UPDATE etablissement
                    SET validation = ?
                    WHERE id = ?
                ");
                
                $update->execute([$validation, $idEtablissement]);

                $check = $pdo->prepare("
                        SELECT COUNT(*) FROM details_validation WHERE idEtablissement = ?
                    ");
                    $check->execute([$idEtablissement]);
                    $exists = $check->fetchColumn();

                if($exists == 0)
                {
                  $insert = $pdo->prepare("
                          INSERT INTO details_validation (idEtablissement, niveauValidation, majPar, type, niveauRisque)
                          VALUES (?, ?, ?, ?, ?)
                      ");

                  $insert->execute([
                          $idEtablissement,
                          $validation,
                          $id_utilisateur, // par ex. utilisateur connecté
                          'Validation',
                          'LR'
                      ]);
                }

                $pdo->commit();

                $etat = 1;
            }
            else if($noteGlobale < 49)
            {
                $validation = 1;
                $text = "warning";

                $pdo->beginTransaction();



                $update = $pdo->prepare("
                    UPDATE etablissement
                    SET validation = ?
                    WHERE id = ?
                ");
                
                $update->execute([$validation, $idEtablissement]);

                $check = $pdo->prepare("
                        SELECT COUNT(*) FROM details_validation WHERE idEtablissement = ?
                    ");
                    $check->execute([$idEtablissement]);
                    $exists = $check->fetchColumn();



                if($exists == 0)
                {
                  $insert = $pdo->prepare("
                          INSERT INTO details_validation (idEtablissement, niveauValidation, majPar, type, niveauRisque)
                          VALUES (?, ?, ?, ?, ?)
                      ");

                  $insert->execute([
                          $idEtablissement,
                          $validation,
                          $id_utilisateur, // par ex. utilisateur connecté
                          'Validation',
                          'MR'
                      ]);
                }

                $pdo->commit();

                $etat = 1;
            }
            else
            {
              if($etat == 0)
              {
                $validation = 0;
                $text = "warning";

                $update = $pdo->prepare("
                    UPDATE etablissement
                    SET validation = ?
                    WHERE id = ?
                ");
                
                $update->execute([$validation, $idEtablissement]);

                $etat = 0;
              }
              else if($etat == 2)
              {
                $validation = 2;
                $text = "danger";

                $update = $pdo->prepare("
                    UPDATE etablissement
                    SET validation = ?
                    WHERE id = ?
                ");
                
                $update->execute([$validation, $idEtablissement]);

                $etat = 2;
              }
              else
              {
                $validation = 1;
                $text = "danger";

                $update = $pdo->prepare("
                    UPDATE etablissement
                    SET validation = ?
                    WHERE id = ?
                ");
                
                $update->execute([$validation, $idEtablissement]);

                $etat = 1;
              }
            }


          ?>

          <!-- start row -->
          <tr class="search-items">
            <td>
              <div class="n-chk align-self-center text-center">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input contact-chkbox primary contact-check-all" id="checkbox1" />
                  <label class="form-check-label" for="checkbox1"></label>
                </div>
              </div>
            </td>
            <td>
              <span class="usr-email-addr"><?php echo $nom; ?></span>
            </td>
            <td>
             <img src="../../dist/css/icons/flag-icon-css/flags/<?php echo $paysIso; ?>.svg" class="round-20" style="height: 15px !important;margin-top: -3px;margin-right: 5px;"> <span class="usr-email-addr"><?php echo $pays; ?></span>
            </td>
            <td>
              <span class="usr-location"><?php echo $secteur; ?></span>
            </td>
            <td>
              <span class="usr-ph-no text-<?php echo $noteType; ?>"><?php echo $noteRisque; ?></span>
            </td>
            <td>
              <span class="usr-ph-no text-<?php echo $noteType; ?>"><?php echo $noteGlobale; ?></span>
            </td>
            <td>
              <span class="usr-ph-no">
                <?php

                  if($etat == 0)
                    {
                      echo "<span class='btn btn-success btn-sm w-50 actionetablissement' data-rejet='0' data-validation='".$idEtablissement."'>Valider</span> &nbsp; <span class='btn btn-danger btn-sm w-50 actionetablissement' data-validation='0' data-rejet='".$idEtablissement."'>Rejeter</span>";
                    }
                  else if($etat == 1)
                    {
                      echo "<span class='btn bg-light-".$text." btn-sm text-".$text." w-100' >Validé</span>"; 
                    }
                  else if($etat == 2)
                    {
                      echo "<span class='btn bg-light-".$text." btn-sm text-".$text." w-100' >Rejeté</span>"; 
                    }
                  else
                    {
                      echo "<span class='btn bg-light-danger btn-sm text-danger w-100' >Rejeté</span>"; 
                    }

                ?>
              </span>
            </td>
            <td>
              <div class="action-btn">

                <?php echo $details; ?>

              </div>
            </td>
          </tr>
          <!-- end row -->

          <?php

        }
          ?>

        </tbody>
      </table>
    </div>
  </div>
</div>