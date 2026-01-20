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
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAddUser">
          Ajouter un utilisateur
        </button>
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
          <th>Rôle</th>
          <th>E-mail</th>
          <th>Nom d'utilisateur</th>
          <th>Mot de passe</th>
          <th>Modifier</th>
        </thead>
        <tbody>

          <?php


          $stmt = $pdo->query("SELECT u.id, u.level, u.nom, u.prenom, u.login, u.photo, u.email, u.mot_de_passe, r.nom AS rolenom FROM utilisateurs u JOIN roles r ON u.role = r.id");
          $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

          foreach ($utilisateurs as $utilisateur) {

            $idUtilisateur = $utilisateur['id'];
            $nomUtilisateur = $utilisateur['nom'];
            $prenomUtilisateur = $utilisateur['prenom'];
            $roleUtilisateur = $utilisateur['rolenom'];
            $emailUtilisateur = $utilisateur['email'];
            $loginUtilisateur = $utilisateur['login'];
            $levelUtilisateur = $utilisateur['level'];
            
            $details = $roleUtilisateur;
            $detailsUtilisateur = '<a href="details_utilisateur.php?idUtilisateur=' . $idUtilisateur . '" class="text-info center" style="margin-left:-60px;"><i class="ti ti-edit fs-5"></i></a>';
          
            if($nomUtilisateur != "" || $prenomUtilisateur != "")
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
              <span class="usr-email-addr"><?php echo $nomUtilisateur; ?></span>
            </td>
            <td>
             <span class="usr-email-addr"><?php echo $prenomUtilisateur; ?></span>
            </td>
            <td>
              <span class="usr-location"><?php echo $details; ?></span>
            </td>
            <td>
              <span class="usr-ph-no"><?php echo $emailUtilisateur; ?></span>
            </td>
            <td>
              <span class="usr-ph-no"><?php echo $loginUtilisateur; ?></span>
            </td>
            <td>
              <a href="#" class="text-info ms-2 btn-change-pass"
                   data-id="<?php echo $idUtilisateur; ?>">
                    Réinitialiser
                </a>
            </td>
            <td>
              <button class="btn btn-primary btn-sm btn-edit-user"
                      data-id="<?php echo $idUtilisateur; ?>">
                      <i class="ti ti-edit fs-5"></i>
                  
              </button>
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

  <!-- Modal Modifier Utilisateur -->
  <div class="modal fade" id="modalEditUser" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Modifier utilisateur</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form id="formEditUser" enctype="multipart/form-data">

          <div class="modal-body">

            <input type="hidden" name="id" id="editUserId">

            <div class="row">
              <div class="col-md-6">
                <label>Nom</label>
                <input type="text" class="form-control" name="nom" id="editNom">
              </div>
              <div class="col-md-6">
                <label>Prénom</label>
                <input type="text" class="form-control" name="prenom" id="editPrenom">
              </div>

              <div class="col-md-6 mt-3">
                <label>Email</label>
                <input type="email" class="form-control" name="email" id="editEmail">
              </div>

              <div class="col-md-6 mt-3">
                <label>Login</label>
                <input type="text" class="form-control" name="login" id="editLogin">
              </div>

              <div class="col-md-6 mt-3">
                <label>Rôle</label>
                <select class="form-control" name="role" id="editRole">
                  <option value="">Sélectionnez</option>
                  <?php
                    $r = $pdo->query("SELECT * FROM roles ORDER BY nom");
                    while($role = $r->fetch()){
                      echo '<option value="'.$role['id'].'">'.$role['nom'].'</option>';
                    }
                  ?>
                </select>
              </div>

              <div class="col-md-6 mt-3 row">
                <label>Photo</label>
                <div class="col-md-4">
                  <div id="previewPhoto" class="mt-2" style="display:none;">
                      <img id="imgUtilisateur" src="" alt="Photo utilisateur"
                           style="width:120px;height:120px;object-fit:cover;border-radius:8px;border:1px solid #ddd;">
                    </div>
                </div>
                <div class="col-md-8">
                  <input type="file" class="form-control" name="photo">
                </div>

                
              </div>

            </div>

          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Enregistrer</button>
          </div>

        </form>

      </div>
    </div>
  </div>


  <div class="modal fade" id="modalChangePass" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Changer le mot de passe</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form id="formChangePass">
          <div class="modal-body">

            <input type="hidden" name="id" id="passUserId">

            <label>Nouveau mot de passe</label>
            <input type="password" class="form-control" name="password" required>

          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
          </div>
        </form>

      </div>
    </div>
  </div>


  <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAddUser">
    Ajouter un utilisateur
  </button>

  <div class="modal fade" id="modalAddUser" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Ajouter un utilisateur</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form id="formAddUser" enctype="multipart/form-data">
          <div class="modal-body row">

            <div class="col-md-6">
              <label>Nom</label>
              <input type="text" name="nom" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label>Prénom</label>
              <input type="text" name="prenom" class="form-control" required>
            </div>

            <div class="col-md-6 mt-3">
              <label>Email</label>
              <input type="email" name="email" id="email" class="form-control" required>
              <small id="emailError" class="text-danger"></small>
            </div>

            <div class="col-md-6 mt-3">
              <label>Login</label>
              <input type="text" name="login" id="login" class="form-control" required>
              <small id="loginError" class="text-danger"></small>
            </div>

            <div class="col-md-6 mt-3">
              <label>Rôle</label>
              <select name="role" id="rolesList" class="form-control" required></select>
            </div>

            <div class="col-md-6 mt-3">
              <label>Mot de passe</label>
              <input type="password" name="password" class="form-control" required>
            </div>

            <div class="col-md-6 mt-3">
              <label>Photo</label>
              <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
              <img id="preview" class="img-thumbnail mt-2" style="display:none;width:100px;">
            </div>

          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Enregistrer</button>
          </div>
        </form>

      </div>
    </div>
  </div>





</div>