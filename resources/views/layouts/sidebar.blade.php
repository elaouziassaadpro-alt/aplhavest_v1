<aside class="left-sidebar">
        <!-- Sidebar scroll-->
        <div>
          <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="./index.html" class="text-nowrap logo-img">
              <img src="{{ asset('dist\images\logos\alphavest-logo.png') }}" class="dark-logo" width="180" alt="" />

            </a>
            <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
              <i class="ti ti-x fs-8 text-muted"></i>
            </div>
          </div>
          <!-- Sidebar navigation-->
          <nav class="sidebar-nav scroll-sidebar" data-simplebar>
            <ul id="sidebarnav">
              <!-- ============================= -->
              <!-- Accueil -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Home</span>
              </li>
              <!-- =================== -->
              <!-- Dashboard -->
              <!-- =================== -->
              <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
                  <span>
                    <i class="ti ti-aperture"></i>
                  </span>
                  <span class="hide-menu">Tableau de bord</span>
                </a>
              </li>
              <!-- ============================= -->
              <!-- Clients -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Clients</span>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('etablissements.index') }}" aria-expanded="false">
                  <span>
                    <i class="ti ti-building"></i>
                  </span>
                  <span class="hide-menu">Etablissements</span>
                </a>
              </li>

              <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('infogeneral.create') }}" aria-expanded="false">
                  <span>
                    <i class="ti ti-plus"></i>
                  </span>
                  <span class="hide-menu">Nouvel Etablissement</span>
                </a>
              </li>
              
              
              
                
              
              <!-- ============================= -->
              <!-- Détails -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Détails</span>
                </li>

                <li class="sidebar-item">
                  <a class="sidebar-link" href="{{ route('contacts.index') }}" aria-expanded="false">
                    <span><i class="ti ti-address-book"></i></span>
                    <span class="hide-menu">Contacts</span>
                  </a>
                </li>

                <li class="sidebar-item">
                  <a class="sidebar-link" href="{{ route('actionnariat.index') }}" aria-expanded="false">
                    <span><i class="ti ti-users"></i></span>
                    <span class="hide-menu">Actionnaires</span>
                  </a>
                </li>

                <li class="sidebar-item">
                  <a class="sidebar-link" href="{{ route('benificiaireeffectif.index') }}" aria-expanded="false">
                    <span><i class="ti ti-id-badge"></i></span>
                    <span class="hide-menu">Bénéficiaires effectifs</span>
                  </a>
                </li>

                <li class="sidebar-item">
                  <a class="sidebar-link" href="{{ route('administrateurs.index') }}" aria-expanded="false">
                    <span><i class="ti ti-briefcase"></i></span>
                    <span class="hide-menu">Administrateurs</span>
                  </a>
                </li>

                <li class="sidebar-item">
                  <a class="sidebar-link" href="{{ route('personneshabilites.index') }}" aria-expanded="false">
                    <span><i class="ti ti-shield"></i></span>
                    <span class="hide-menu">Personnes habilitées</span>
                  </a>
                </li>
                <li class="sidebar-item"> 
                  <a class="sidebar-link" href="{{ route('coordonneesbancaires.index') }}" aria-expanded="false">
                      <span><i class="ti ti-credit-card"></i></span>
                      <span class="hide-menu">Coordonnées Bancaires</span>
                  </a>
              </li>


              <!-- ============================= -->
              <!-- Icons -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">GESTION LISTES</span>
              </li>

              <li class="sidebar-item">
                <a class="sidebar-link" href="./liste-noire.html" aria-expanded="false">
                  <span class="rounded-3">
                    <i class="ti ti-ban"></i>
                  </span>
                  <span class="hide-menu">Liste noire</span>
                </a>
              </li>

              <li class="sidebar-item">
                <a class="sidebar-link" href="./importer-liste.html" aria-expanded="false">
                  <span class="rounded-3">
                    <i class="ti ti-upload"></i>
                  </span>
                  <span class="hide-menu">Importer une liste</span>
                </a>
              </li>
              <!-- =================== -->
              <!-- AUTH -->
              <!-- =================== -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">GESTION INTERNE</span>
              </li>

              <li class="sidebar-item">
                <a class="sidebar-link" href="./mon-profil.html" aria-expanded="false">
                  <span class="rounded-3">
                    <i class="ti ti-user-circle"></i>
                  </span>
                  <span class="hide-menu">Mon profil</span>
                </a>
              </li>

              <li class="sidebar-item">
                <a class="sidebar-link" href="./utilisateurs.html" aria-expanded="false">
                  <span class="rounded-3">
                    <i class="ti ti-users"></i>
                  </span>
                  <span class="hide-menu">Utilisateurs</span>
                </a>
              </li>

              <li class="sidebar-item">
                <a class="sidebar-link" href="./roles.html" aria-expanded="false">
                  <span class="rounded-3">
                    <i class="ti ti-lock"></i>
                  </span>
                  <span class="hide-menu">Rôles</span>
                </a>
              </li>
              
          <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
      </aside>