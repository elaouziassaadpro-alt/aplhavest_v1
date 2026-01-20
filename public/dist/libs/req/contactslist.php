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
      </div>
    </div>
  </div>
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
          <th>Nom</th>
          <th>Fonction</th>
          <th>Etablissement</th>
          <th>Email</th>
          <th>Téléphone</th>
          <th>Etat</th>
          <th>Modifier</th>
        </thead>
        <tbody>

          <?php


          $stmt = $pdo->query("SELECT c.id AS cID, c.nom AS cNom, c.fonction AS cFonction, c.email AS cEmail, c.telephone AS cTelephone, e.raisonSocial AS eRS, e.id AS eID FROM contacts c JOIN etablissement e ON c.idEtablissement = e.id");
          $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

          foreach ($contacts as $contact) {

            $idEtablissement = $contact['eID'];
            $cID = $contact['cID'];
            $cNom = $contact['cNom'];
            $cFonction = $contact['cFonction'];
            $cEmail = $contact['cEmail'];
            $cTelephone = $contact['cTelephone'];
            $eRS = $contact['eRS'];
            
            $details = '<a href="details_etablissement.php?idEtablissement=' . $idEtablissement . '" class="text-info center" style="margin-left:-15px;">'.$eRS.'</a>';
            $detailsContact = '<a href="details_contact.php?idContact=' . $cID . '" class="text-info center" style="margin-left:-60px;"><i class="ti ti-edit fs-5"></i></a>';
          
            if($cEmail != "" || $cTelephone != "")
            {
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
              <span class="usr-email-addr"><?php echo $cNom; ?></span>
            </td>
            <td>
             <img src="../../dist/css/icons/flag-icon-css/flags/<?php echo $paysIso; ?>.svg" class="round-20" style="height: 15px !important;margin-top: -3px;margin-right: 5px;"> <span class="usr-email-addr"><?php echo $cFonction; ?></span>
            </td>
            <td>
              <span class="usr-location"><?php echo $details; ?></span>
            </td>
            <td>
              <span class="usr-ph-no text-success"><?php echo $cEmail; ?></span>
            </td>
            <td>
              <span class="usr-ph-no text-success"><?php echo $cTelephone; ?></span>
            </td>
            <td>
              <span class="usr-ph-no disabled-btn"><span class="btn btn-success btn-sm w-100 disabled" aria-disabled="true" tabindex="-1">Actif</span></span>
            </td>
            <td>
              <div class="action-btn">

                <center><?php echo $detailsContact; ?></center>

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