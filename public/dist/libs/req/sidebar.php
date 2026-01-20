<!-- Sidebar Start -->
<aside class="left-sidebar">
  <!-- Sidebar scroll-->
  <div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <a href="./" class="text-nowrap logo-img">
        <img src="../../dist/images/logos/alphavest-logo.png" class="dark-logo" width="180" alt="" />
        <img src="../../dist/images/logos/alphavest-logo.png" class="light-logo"  width="180" alt="" />
      </a>
      <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="ti ti-x fs-8 text-muted"></i>
      </div>
    </div>
    <!-- Sidebar navigation-->
    <nav class="sidebar-nav scroll-sidebar" data-simplebar>
      <ul id="sidebarnav">
        <!-- ============================= -->
        <!-- Home -->
        <!-- ============================= -->
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Accueil</span>
        </li>
        <!-- =================== -->
        <!-- Dashboard -->
        <!-- =================== -->
        <li class="sidebar-item">
          <a class="sidebar-link" href="./" aria-expanded="false">
            <span>
              <i class="ti ti-aperture"></i>
            </span>
            <span class="hide-menu">Tableau de bord</span>
          </a>
        </li>
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Clients</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="./etablissements.php" aria-expanded="false">
            <span>
              <i class="ti ti-building"></i>
            </span>
            <span class="hide-menu">Etablissements</span>
          </a>
          <?php

            if(isset($_GET['idEtablissement']))
            {
              $stmtnometab = $pdo->prepare("SELECT raisonSocial,id FROM etablissement WHERE id = ?");
              $stmtnometab->execute([$_GET['idEtablissement']]);
              $row = $stmtnometab->fetch(PDO::FETCH_ASSOC);

              echo '

                <ul aria-expanded="false" class="collapse first-level in">
                  <li class="sidebar-item active">
                    <a href="./details_etablissement.php?idEtablissement='.$row['id'].'" class="sidebar-link active">
                      <div class="round-16 d-flex align-items-center justify-content-center">
                        <i class="ti ti-circle"></i>
                      </div>
                      <span class="hide-menu">'.$row['raisonSocial'].'</span>
                    </a>
                  </li>
                </ul>              

              ';
            }

          ?>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="./nouvel_etablissement.php" aria-expanded="false">
            <span>
              <i class="ti ti-plus"></i>
            </span>
            <span class="hide-menu">Nouvel Etablissement</span>
          </a>
        </li>
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Détails</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="./contacts.php" aria-expanded="false">
            <span>
              <i class="ti ti-address-book"></i>
            </span>
            <span class="hide-menu">Contacts</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="./actionnaires.php" aria-expanded="false">
            <span>
              <i class="ti ti-user-dollar"></i>
            </span>
            <span class="hide-menu">Actionnaires</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="./benificiaires.php" aria-expanded="false">
            <span>
              <i class="ti ti-user-bitcoin"></i>
            </span>
            <span class="hide-menu">Bénificiaires effectifs</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="./administrateurs.php" aria-expanded="false">
            <span>
              <i class="ti ti-password-user"></i>
            </span>
            <span class="hide-menu">Administrateurs</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="./habilites.php" aria-expanded="false">
            <span>
              <i class="ti ti-writing"></i>
            </span>
            <span class="hide-menu">Personnes habilitées</span>
          </a>
        </li>
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Liste noire</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="./importer_liste.php" aria-expanded="false">
            <span>
              <i class="ti ti-playlist-add"></i>
            </span>
            <span class="hide-menu">Importer une liste</span>
          </a>
        </li>

        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Gestion interne</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="./mon_profil.php" aria-expanded="false">
            <span>
              <i class="ti ti-user-circle"></i>
            </span>
            <span class="hide-menu">Mon profil</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="./utilisateurs.php" aria-expanded="false">
            <span>
              <i class="ti ti-users"></i>
            </span>
            <span class="hide-menu">Utilisateurs</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="./roles.php" aria-expanded="false">
            <span>
              <i class="ti ti-user-check"></i>
            </span>
            <span class="hide-menu">Rôles</span>
          </a>
        </li>


      </ul>
    </nav>
    <!-- End Sidebar navigation -->
  </div>
  <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->