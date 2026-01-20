<?php

if (isset($_COOKIE['id_utilisateur'])) {
    $id_utilisateur = $_COOKIE['id_utilisateur'];
  }

?>

<!--  Header Start -->
<header class="app-header"> 
  <nav class="navbar navbar-expand-lg navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link sidebartoggler nav-icon-hover ms-n3" id="headerCollapse" href="javascript:void(0)">
          <i class="ti ti-menu-2"></i>
        </a>
      </li>
      <li class="nav-item d-none d-lg-block">
        <a class="nav-link nav-icon-hover" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">
          <i class="ti ti-search"></i>
        </a>
      </li>
    </ul>
    <ul class="navbar-nav quick-links d-none d-lg-flex">
      <li class="nav-item dropdown-hover d-none d-lg-block">
        <a class="nav-link" href="#">Echanges</a>
      </li>
      <li class="nav-item dropdown-hover d-none d-lg-block">
        <a class="nav-link" href="#">Calendrier</a>
      </li>
      <li class="nav-item dropdown-hover d-none d-lg-block">
        <a class="nav-link" href="#">Historiques</a>
      </li>
      <li class="nav-item dropdown-hover d-none d-lg-block">
        <a class="nav-link text-danger" href="#">
        <?php 

        $ip = $_SERVER['REMOTE_ADDR'];
        echo "Adresse IP : " . $ip;


        $vnom = 'Système';
        $vprenom = 'Auto';

        $vdateValidation = '- -';
        

        $vtype = '-';
        $vniveauRisque = '';

         ?>
       </a>
      </li>
      <?php

        if(isset($_GET['idEtablissement']))
        {
          if($_GET['idEtablissement'] != '')
          {


      ?>
      <li class="nav-item">
        <p class="link-disabled" href="#" style="margin-top:20%;">
        <?php 

        if(isset($_GET['idEtablissement']))
        {
          if(!empty($_GET['idEtablissement']))
          {
            $etablissement = $_GET['idEtablissement'];
            $stmt = $pdo->prepare("SELECT *, e.validation AS vEtat FROM etablissement e LEFT JOIN details_validation v ON e.id = v.idEtablissement LEFT JOIN utilisateurs u ON v.majPar = u.id WHERE e.id = ? LIMIT 1;");

            $stmt->execute([$etablissement]);

            $validateur = $stmt->fetch(PDO::FETCH_ASSOC);

            $vEtat = 0;

            if ($validateur) {
                $vnom = $validateur['nom'];
                $vprenom = $validateur['prenom'];

                $vdateValidation = $validateur['dateValidation'];
                

                $vtype = $validateur['type'];
                $vniveauRisque = $validateur['niveauRisque'];

                $vEtat = $validateur['vEtat'];
            } else {
                //echo "Validé par le système";
                $vEtat = 1;
            }

          }
        }

        if($vdateValidation != "")
        {
          $vdateValidation = trim($vdateValidation);
          list($date, $heure) = explode(' ', $vdateValidation);
        }
        else
        {
          $date = " ";
          $heure = " ";
        }

        // Si validé automatiquement afficher LR
        if($vEtat == 0)
          {
            echo "<span class='btn btn-success btn-md recevoirdata' style='margin-top:-27px; margin-left:5px;' data-recevoir='recevoir".$etablissement."' data-rejet='0' data-validation='".$etablissement."'>Valider</span> &nbsp; <span class='btn btn-danger btn-md recevoirdata' style='margin-top:-27px; data-recevoir='recevoir".$etablissement."' margin-left:5px;' data-validation='0' data-rejet='".$etablissement."'>Rejeter</span>";
          }
        else if($vEtat == 1)
          {
            if($vniveauRisque == "HR")
            {
              echo "<span class='btn bg-light-danger btn-md text-danger w-100'  style='margin-top:2px; margin-left:5px;'>".$vniveauRisque." Validé</span>"; 
            }
            else if($vniveauRisque == "MR")
            {
              echo "<span class='btn bg-light-warning btn-md text-warning w-100'  style='margin-top:2px; margin-left:5px;'>".$vniveauRisque." Validé</span>"; 
            }
            else
            {
              echo "<span class='btn bg-light-success btn-md text-success w-100'  style='margin-top:2px; margin-left:5px;'>".$vniveauRisque." Validé</span>"; 
            }
          }
        else if($vEtat == 2)
          {
            if($vniveauRisque == "HR")
            {
              echo "<span class='btn bg-light-danger btn-md text-danger w-100'  style='margin-top:2px; margin-left:5px;'>".$vniveauRisque." Rejeté</span>"; 
            }
            else if($vniveauRisque == "MR")
            {
              echo "<span class='btn bg-light-warning btn-md text-warning w-100'  style='margin-top:2px; margin-left:5px;'>".$vniveauRisque." Rejeté</span>"; 
            }
            else
            {
              echo "<span class='btn bg-light-success btn-md text-success w-100'  style='margin-top:2px; margin-left:5px;'>".$vniveauRisque." Rejeté</span>"; 
            }
          }
        else
          {
            if($vniveauRisque == "HR")
            {
              echo "<span class='btn bg-light-danger btn-md text-danger w-100'  style='margin-top:2px; margin-left:5px;'>".$vniveauRisque." Rejeté</span>"; 
            }
            else if($vniveauRisque == "MR")
            {
              echo "<span class='btn bg-light-warning btn-md text-warning w-100'  style='margin-top:2px; margin-left:5px;'>".$vniveauRisque." Rejeté</span>"; 
            }
            else
            {
              echo "<span class='btn bg-light-success btn-md text-success w-100'  style='margin-top:2px; margin-left:5px;'>".$vniveauRisque." Rejeté</span>"; 
            } 
          }

         ?>
       </p>
      </li>
      <li class="nav-item">
        <p class="link-disabled" style="margin-top:7%;margin-left: 20px;">
        <?php 

        if($vnom != "" && $vprenom != "")
        {
          echo "<span id=''><b>par</b> : <span id='recevoirnom'>" . $vnom . " " . $vprenom . "</span>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<b>le</b> : <span id='recevoirdate'>" . $date . " à : ".$heure."</span></span>";
        }
        else
        {
          echo "<span id='parid'><b>par</b> : <span id='recevoirnom'>" . $vnom . " " . $vprenom . "</span>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<b>le</b> : <span id='recevoirdate'>" . $date . " à : ".$heure."</span></span>";
        }

         ?>
       </p>
      </li>

      <?php
        }
      }

      ?>
    </ul>
    <div class="d-block d-lg-none">
      <img src="../../dist/images/logos/alphavest-logo.png" class="dark-logo" width="180" alt="" />
      <img src="../../dist/images/logos/alphavest-logo.png" class="light-logo"  width="180" alt="" />
    </div>
    <button class="navbar-toggler p-0 border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="p-2">
        <i class="ti ti-dots fs-7"></i>
      </span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <div class="d-flex align-items-center justify-content-between">
        <a href="javascript:void(0)" class="nav-link d-flex d-lg-none align-items-center justify-content-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar" aria-controls="offcanvasWithBothOptions">
          <i class="ti ti-align-justified fs-7"></i>
        </a>
        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
          <li class="nav-item dropdown">
            <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="../../dist/images/svgs/icon-flag-fr.svg" alt="" class="rounded-circle object-fit-cover round-20">
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
              <div class="message-body" data-simplebar>
                <a href="javascript:void(0)" class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item active">
                  <div class="position-relative">
                    <img src="../../dist/images/svgs/icon-flag-fr.svg" alt="" class="rounded-circle object-fit-cover round-20">
                  </div>
                  <p class="mb-0 fs-3">français (FR)</p>
                </a>
                <a href="javascript:void(0)" class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">
                  <div class="position-relative">
                    <img src="../../dist/images/svgs/icon-flag-en.svg" alt="" class="rounded-circle object-fit-cover round-20">
                  </div>
                  <p class="mb-0 fs-3">English (EN)</p>
                </a>
                
                <a href="javascript:void(0)" class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">
                  <div class="position-relative">
                    <img src="../../dist/images/svgs/icon-flag-sa.svg" alt="" class="rounded-circle object-fit-cover round-20">
                  </div>
                  <p class="mb-0 fs-3">عربي  (AR)</p>
                </a>
              </div>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="showNotifications"
               data-bs-toggle="dropdown" aria-expanded="false">
              <i class="ti ti-bell-ringing"></i>
              <div class="notification bg-primary rounded-circle" style="display:none;"></div>
            </a>
            <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
              <div class="d-flex align-items-center justify-content-between py-3 px-7">
                <h5 class="mb-0 fs-5 fw-semibold">Notifications</h5>
                <span class="badge bg-primary rounded-4 px-3 py-1 lh-sm">0 nouveaux</span>
              </div>
              <div class="message-body notification-body" data-simplebar>
                <!-- Notifications chargées ici -->
              </div>
              <div class="py-6 px-7 mb-1">
                <button class="btn btn-outline-primary w-100">Voir toutes</button>
              </div>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
              <div class="d-flex align-items-center">
                <div class="user-profile-img">
                  <img src="../../dist/images/profile/user-1.jpg" class="rounded-circle user-profil-header" width="35" height="35" alt="" />
                </div>
              </div>
            </a>
            <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop1">
              <div class="profile-dropdown position-relative" data-simplebar>
                <div class="py-3 px-7 pb-0">
                  <h5 class="mb-0 fs-5 fw-semibold">Mon Profil</h5>
                </div>
                <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                  <img src="../../dist/images/profile/user-1.jpg" class="rounded-circle" width="80" height="80" alt="" />
                  <div class="ms-3">
                    <h5 class="mb-1 fs-3">BAKRI Amine</h5>
                    <span class="mb-1 d-block text-dark">Infra & sécurité</span>
                    <p class="mb-0 d-flex text-dark align-items-center gap-2">
                      <i class="ti ti-mail fs-4"></i> a.bakri@alphavest.ma
                    </p>
                  </div>
                </div>
                <div class="message-body">
                  <a href="./page-user-profile.html" class="py-8 px-7 mt-8 d-flex align-items-center">
                    <span class="d-flex align-items-center justify-content-center bg-light rounded-1 p-6">
                      <img src="../../dist/images/svgs/icon-account.svg" alt="" width="24" height="24">
                    </span>
                    <div class="w-75 d-inline-block v-middle ps-3">
                      <h6 class="mb-1 bg-hover-primary fw-semibold"> Mon profil </h6>
                      <span class="d-block text-dark">Parametres du compte</span>
                    </div>
                  </a>
                  <a href="./logout.php" class="py-8 px-7 d-flex align-items-center">
                    <div class="w-75 d-inline-block v-middle ps-3">
                      <h6 class="mb-1 bg-hover-primary fw-semibold text-danger">Se déconnecter</h6>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>
<!--  Header End -->