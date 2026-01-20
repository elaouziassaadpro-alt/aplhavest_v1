<div class="widget-content searchable-container list">
  <!-- --------------------- start benificiaire ---------------- -->
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
      </div>
    </div>
  </div>
  <!-- ---------------------
                  end benificiaire
              ---------------- -->

  <div class="card card-body">
    <div class="table-responsive">
      <table class="table search-table align-middle text-nowrap">
        <thead class="header-item">
          <th>
            <div class="n-chk align-self-center text-center">
              <div class="form-check">
                <input type="checkbox" class="form-check-input primary" id="benificiaire-check-all" />
                <label class="form-check-label" for="benificiaire-check-all"></label>
                <span class="new-control-indicator">Tous</span>
              </div>
            </div>
          </th>
          <th>Nom</th>
          <th>Prénom</th>
          <th>Etablissement</th>
          <th>Date de naissance</th>
          <th>Pays de naissance</th>
          <th>PPE</th>
          <th>Lien PPE</th>
          <th>Etat</th>
          <th>Modifier</th>
        </thead>
        <tbody>

          <?php


          $stmt = $pdo->query("SELECT b.id AS bID, b.nom AS bNom, b.prenom AS bPrenom, e.raisonSocial AS eRS, b.dateNaissance AS bNaissance, p.libelle AS bpNaissance, e.id AS eID, ppe.libelle AS bppeLibelle, lienppe.libelle AS blienppeLibelle FROM benificiaires b JOIN etablissement e ON b.idEtablissement = e.id LEFT JOIN pays p ON b.paysNaissance = p.id LEFT JOIN ppes ppe ON b.ppe = ppe.id LEFT JOIN ppes lienppe ON b.lienPPE = lienppe.id GROUP BY b.cinPasseport, b.nom, b.prenom");
          $benificiaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

          foreach ($benificiaires as $benificiaire) {

            $idEtablissement = $benificiaire['eID'];
            $bID = $benificiaire['bID'];
            $bNom = $benificiaire['bNom'];
            $bPrenom = $benificiaire['bPrenom'];
            $bNaissance = $benificiaire['bNaissance'];
            $bpNaissance = $benificiaire['bpNaissance'];
            $bppeLibelle = $benificiaire['bppeLibelle'];
            $blienppeLibelle = $benificiaire['blienppeLibelle'];
            $eRS = $benificiaire['eRS'];
            
            $details = '<a href="details_etablissement.php?idEtablissement=' . $idEtablissement . '" class="text-info center" style="margin-left:-15px;">'.$eRS.'</a>';
            $detailsBenificiaire = '<a href="details_benificiaire.php?idBenificiaire=' . $bID . '" class="text-info center" style="margin-left:-60px;"><i class="ti ti-edit fs-5"></i></a>';
          
            if($bNom != "" || $bPrenom != "")
            {
          ?>

          <!-- start row -->
          <tr class="search-items">
            <td>
              <div class="n-chk align-self-center text-center">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input benificiaire-chkbox primary benificiaire-check-all" id="checkbox1" />
                  <label class="form-check-label" for="checkbox1"></label>
                </div>
              </div>
            </td>
            <td>
              <span class="usr-email-addr"><?php echo $bNom; ?></span>
            </td>
            <td>
             <img src="../../dist/css/icons/flag-icon-css/flags/<?php echo 'ma'; ?>.svg" class="round-20" style="height: 15px !important;margin-top: -3px;margin-right: 5px;"> <span class="usr-email-addr"><?php echo $bPrenom; ?></span>
            </td>
            <td>
              <span class="usr-location"><?php echo $details; ?></span>
            </td>
            <td>
              <span class="usr-ph-no"><?php echo $bNaissance; ?></span>
            </td>
            <td>
              <span class="usr-ph-no"><?php echo $bpNaissance; ?></span>
            </td>
            <td>
              <span class="usr-ph-no disabled-btn"><?php if($bppeLibelle){echo $bppeLibelle;}else{echo "-";} ?></span>
            </td>
            <td>
              <span class="usr-ph-no disabled-btn"><?php if($blienppeLibelle){echo $blienppeLibelle;}else{echo "-";} ?></span>
            </td>
            <td>
              <span class="usr-ph-no disabled-btn"><span class="btn btn-success btn-sm w-100 disabled" aria-disabled="true" tabindex="-1">Actif</span></span>
            </td>
            <td>
              <div class="action-btn">

                <center><?php echo $detailsBenificiaire; ?></center>

              </div>
            </td>
          </tr>
          <!-- end row -->

          <?php
        }

        }
          ?>

        </tbody>
      </table>
    </div>
  </div>
</div>