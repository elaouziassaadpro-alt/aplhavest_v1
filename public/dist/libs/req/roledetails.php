<div class="widget-content searchable-container list">
  <!-- --------------------- start Contact ---------------- -->
  

  <?php

  use Layout\popupinsertion;
  $popupinsertion = new popupinsertion();
  $popupinsertion->render();

  ?>

  <?php
  require_once 'conn_db.php';

  // 🔹 Récupérer tous les rôles
  $stmt = $pdo->query("SELECT * FROM roles ORDER BY id ASC");
  $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <button class="btn btn-primary" id="btnAddRole">
            <i class="bi bi-plus-circle"></i> Ajouter un rôle
          </button>
          <br>
          <br>
          <br>
        </div>

        <table class="table search-table align-middle text-nowrap">
          <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Nom</th>
                  <th>Description</th>
                  <th>Date création</th>
                  <th>Actif</th>
                  <th>Nb utilisateurs</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($roles as $r): 
                  // compter les utilisateurs liés au rôle (table utilisateurs.col role)
                  $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE role = ?");
                  $stmtCount->execute([$r['id']]);
                  $countUsers = $stmtCount->fetchColumn();
                ?>
                <tr>
                  <td><?= $r['id'] ?></td>
                  <td><?= htmlspecialchars($r['nom']) ?></td>
                  <td><?= nl2br(htmlspecialchars($r['description'])) ?></td>
                  <td><?= $r['date_creation'] ?></td>
                  <td><?= $r['actif'] ? 'Oui' : 'Non' ?></td>
                  <td><?= $countUsers ?></td>
                  <td>
                    <button class="btn btn-sm btn-secondary btn-edit" data-id="<?= $r['id'] ?>"><i class="ti ti-edit"></i> Modifier</button>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $r['id'] ?>"><i class="ti ti-trash"></i> Supprimer</button>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
        </table>

      </div>

      <!-- Modal unique pour Ajouter / Modifier -->
      <div class="modal fade" id="modalRole" tabindex="-1">
        <div class="modal-dialog modal-lg">
          <form id="formRole" class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalTitle">Ajouter un rôle</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id" id="roleId" value="">
              <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" id="roleNom" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" id="roleDesc" class="form-control" rows="2"></textarea>
              </div>
              <div class="mb-3 form-check">
                <input type="checkbox" name="actif" id="roleActif" class="form-check-input" checked>
                <label class="form-check-label" for="roleActif">Actif</label>
              </div>

              <hr>
              <h6>Permissions</h6>

              <!-- Ici je reprends la structure de ton tableau original en convertissant chaque case en checkbox name="permissions[]" -->
              <div class="table-responsive">
                <table class="table align-middle text-nowrap">
                  <thead>
                    
                  </thead>
                  <tbody>
                    <!-- Etablissements (5) -->
                    <tr>
                      <td>Etablissements</td>
                      <td>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" name="permissions[]" value="etablissement_creer" id="etablissement-creer">
                          <label class="form-check-label" for="etablissement-creer">Créer</label>
                        </div>
                      </td>
                      <td>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" name="permissions[]" value="etablissement_modifier" id="etablissement-modifier">
                          <label class="form-check-label" for="etablissement-modifier">Modifier</label>
                        </div>
                      </td>
                      <td>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" name="permissions[]" value="etablissement_consulter" id="etablissement-consulter">
                          <label class="form-check-label" for="etablissement-consulter">Consulter</label>
                        </div>
                      </td>
                      <td>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" name="permissions[]" value="etablissement_valider" id="etablissement-valider">
                          <label class="form-check-label" for="etablissement-valider">Valider</label>
                        </div>
                      </td>
                      <td>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" name="permissions[]" value="etablissement_supprimer" id="etablissement-supprimer">
                          <label class="form-check-label" for="etablissement-supprimer">Supprimer</label>
                        </div>
                      </td>
                    </tr>

                    <!-- Utilisateurs (4) -->
                    <tr>
                      <td>Utilisateurs</td>
                      <td>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" name="permissions[]" value="utilisateur_creer" id="utilisateur-creer">
                          <label class="form-check-label" for="utilisateur-creer">Créer</label>
                        </div>
                      </td>
                      <td>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" name="permissions[]" value="utilisateur_modifier" id="utilisateur-modifier">
                          <label class="form-check-label" for="utilisateur-modifier">Modifier</label>
                        </div>
                      </td>
                      <td>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" name="permissions[]" value="utilisateur_consulter" id="utilisateur-consulter">
                          <label class="form-check-label" for="utilisateur-consulter">Consulter</label>
                        </div>
                      </td>
                      <td>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" name="permissions[]" value="utilisateur_supprimer" id="utilisateur-supprimer">
                          <label class="form-check-label" for="utilisateur-supprimer">Supprimer</label>
                        </div>
                      </td>
                      <td>
                        
                      </td>
                    </tr>

                  </tbody>
                </table>

                <hr class="my-3">

                <h5 class="mb-2">Utilisateurs assignés à ce rôle</h5>

                <div class="border rounded p-3" style="max-height: 250px; overflow-y: auto;">
                    <div id="usersList">
                        <!-- Les utilisateurs seront chargés en AJAX ici -->
                        Chargement...
                    </div>
                </div>
              </div>
              <!-- end permissions table -->

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              <button type="submit" class="btn btn-primary" id="saveRoleBtn">Enregistrer</button>
            </div>
          </form>
        </div>
      </div>


</div>