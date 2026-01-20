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
          <th>Fonction</th>
          <th>Etat</th>
          <th>Modifier</th>
        </thead>
        <tbody>

          <?php


          $stmt = $pdo->query("SELECT a.id AS aID, a.nom AS aNom, a.prenom AS aPrenom, e.raisonSocial AS eRS, a.dateNaissance AS aNaissance, p.libelle AS apNaissance, e.id AS eID, ppe.libelle AS appeLibelle, lienppe.libelle AS alienppeLibelle, a.fonction AS afonction FROM administrateurs a JOIN etablissement e ON a.idEtablissement = e.id LEFT JOIN pays p ON a.paysNaissance = p.id LEFT JOIN ppes ppe ON a.ppe = ppe.id LEFT JOIN ppes lienppe ON a.lienPPE = lienppe.id GROUP BY a.cinPasseport, a.nom, a.prenom");
          $administrateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

          foreach ($administrateurs as $administrateur) {

            $idEtablissement = $administrateur['eID'];
            $aID = $administrateur['aID'];
            $aNom = $administrateur['aNom'];
            $aPrenom = $administrateur['aPrenom'];
            $aNaissance = $administrateur['aNaissance'];
            $apNaissance = $administrateur['apNaissance'];
            $appeLibelle = $administrateur['appeLibelle'];
            $alienppeLibelle = $administrateur['alienppeLibelle'];
            $afonction = $administrateur['afonction'];
            $eRS = $administrateur['eRS'];
            
            $details = '<a href="details_etablissement.php?idEtablissement=' . $idEtablissement . '" class="text-info center" style="margin-left:-15px;">'.$eRS.'</a>';
            $detailsBenificiaire = '<a href="details_benificiaire.php?idBenificiaire=' . $aID . '" class="text-info center" style="margin-left:-60px;"><i class="ti ti-edit fs-5"></i></a>';
          
            if($aNom != "" || $aPrenom != "")
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
              <span class="usr-email-addr"><?php echo $aNom; ?></span>
            </td>
            <td>
             <img src="../../dist/css/icons/flag-icon-css/flags/<?php echo 'ma'; ?>.svg" class="round-20" style="height: 15px !important;margin-top: -3px;margin-right: 5px;"> <span class="usr-email-addr"><?php echo $aPrenom; ?></span>
            </td>
            <td>
              <span class="usr-location"><?php echo $details; ?></span>
            </td>
            <td>
              <span class="usr-ph-no"><?php echo $aNaissance; ?></span>
            </td>
            <td>
              <span class="usr-ph-no"><?php echo $apNaissance; ?></span>
            </td>
            <td>
              <span class="usr-ph-no disabled-btn"><?php if($appeLibelle){echo $appeLibelle;}else{echo "-";} ?></span>
            </td>
            <td>
              <span class="usr-ph-no disabled-btn"><?php if($alienppeLibelle){echo $alienppeLibelle;}else{echo "-";} ?></span>
            </td>
            <td>
              <span class="usr-ph-no disabled-btn"><?php if($afonction){echo $afonction;}else{echo "-";} ?></span>
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