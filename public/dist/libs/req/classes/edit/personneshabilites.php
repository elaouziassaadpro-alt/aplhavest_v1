<?php
namespace Edit;

class personnesHabilites {
    public function render($habilitesl) {
        ?>
        
        <p>
              <a
                class="btn btn-light-info w-100 font-medium text-info"
                data-bs-toggle="collapse"
                href="#personnesHabilites"
                role="button"
                aria-expanded="false"
                aria-controls="personnesHabilites"
              >
                Personnes habilités à faire fonctionner le compte
              </a>

            <div class="collapse" id="personnesHabilites">
              <div class="card card-body">
                <div class="phabilitesRows">
                  <h5>Personnes habilités</h5>

                  <?php

                    $rowsCount = 0;

                    $nbrhabilites = count($habilitesl);

                    foreach ($habilitesl as $habilite)
                    {
                      $rowsCount++; 
                  ?>

                  <div class="row phabiliteRowInfos phabiliteRow<?php echo $rowsCount;?>">

                  <input type="text" name="ppe2_habilites[]" value="0" class="fichiers_caches" id="habilite_ppe2_id_<?php echo $rowsCount;?>">
                  <input type="text" name="lien2_habilites[]" value="0" class="fichiers_caches" id="habilite_lien2_id_<?php echo $rowsCount;?>">

                    <div class="col-md-2">
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Nom"
                        name="noms_habilites[]"
                        value="<?php echo $habilite['nom'];?>"
                      /> 
                    </div>
                    <div class="col-md-2">
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Prénom"
                        name="prenoms_habilites[]"
                        value="<?php echo $habilite['prenom'];?>"
                      /> 
                    </div>
                    <div class="col-md-2">
                      <input
                        type="text"
                        class="form-control"
                        placeholder="N° CIN / Passeport"
                        name="cin_habilites[]"
                        value="<?php echo $habilite['cinPasseport'];?>"
                      /> 
                    </div>
                    <div class="col-md-1">
                      <input type="file" 
                               id="cin_file_hab_` + rowsCount + `"
                               name="cin_habilites[]" 
                               style="display:none;" 
                               accept=".pdf,.jpg,.png" />

                        <label for="cin_file_hab_` + rowsCount + `" class="btn btn-primary w-100" data-bs-toggle="tooltip" data-bs-original-title="CIN">
                          <i class="ti ti-upload"></i>&nbsp; CIN
                        </label>
                    </div>
                    <div class="col-md-2">
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Fonction"
                        name="fonctions_habilites[]"
                        value="<?php echo $habilite['fonction'];?>"
                      /> 
                    </div>
                    <div class="col-md-2">
                      <input type="file" 
                               id="hab_file_hab_` + rowsCount + `"
                               name="hab_habilites[]" 
                               style="display:none;" 
                               accept=".pdf,.jpg,.png" />

                        <label for="hab_file_hab_` + rowsCount + `" class="btn btn-primary w-100" data-bs-toggle="tooltip" data-bs-original-title="Habilitation">
                          <i class="ti ti-upload"></i>&nbsp; Habilitation
                        </label>
                    </div>
                    <div class="col-md-1">
                      <a href="#" style="line-height: 3;" class="deletePHabiliteRow deletePHabiliteRow<?php echo $rowsCount;?>" data-rowID="<?php echo $rowsCount;?>"><center><i class="ti ti-trash w-100 h5"></i></center></a>
                    </div>

                    <div class="row">
                      <div class="col-md-1">
                        <div class="mb-6">
                          <div class="mb-4 bt-switch">
                            <label>PPE </label>
                            <input
                              type="checkbox"
                              data-checked="false"
                              data-on-color="primary"
                              data-off-color="default"
                              data-off-text="Non"
                              data-on-text="Oui"
                        class="smart-switch hab_ppe"
                        id="habilite_ppe_id_<?php echo $rowsCount;?>"
                          name="ppes_habilites_check[]"
                          data-target="habilite_ppe_data_id_<?php echo $rowsCount;?>"
                          <?php if($habilite['ppe2'] === 1){echo "checked"; }?>
                            />
                          </div>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="hidden-select" id="habilite_ppe_data_id_<?php echo $rowsCount;?>">

                        <label>Libelle PPE </label>
                        <select class="form-control ppes"
                          name="ppes_habilites_input[]">
                          <?php if($habilite['ppe'] != 10000){echo "<option value=" . $habilite['ppe'] . " selected>".$habilite['ppeLibelle']."</option>";}else{
                            echo '<option value="10000">PPE</option>';
                          }?>
                        </select>

                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="mb-6">
                          <div class="mb-4 bt-switch">
                            <label>Lien avec une personne PPE </label>
                            <input
                              type="checkbox"
                              data-checked="false"
                              data-on-color="primary"
                              data-off-color="default"
                              data-off-text="Non"
                              data-on-text="Oui"
                        class="smart-switch hab_lien"
                        id="habilite_ppe_lien_id_<?php echo $rowsCount;?>"
                          name="ppes_lien_habilites_check[]"
                          data-target="habilite_ppe_lien_data_id_<?php echo $rowsCount;?>"
                          <?php if($habilite['lien2'] === 1){echo "checked"; }?>
                            />
                          </div>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="hidden-select" id="habilite_ppe_lien_data_id_<?php echo $rowsCount;?>">

                        <label>Libelle PPE </label>
                        <select class="form-control ppes"
                          name="ppes_lien_habilites_input[]">
                          <?php if($habilite['lienPPE'] != 10000){echo "<option value=" . $habilite['lienPPE'] . " selected>".$habilite['ppelienlibelle']."</option>";}else{
                            echo '<option value="10000">PPE</option>';
                          }?>
                        </select>

                        </div>
                      </div>
                    </div>


                  </div>

                    <?php
                  }
                  ?>

                </div>        

                <div class="row addPHabiliteRow">
                  <div class="col-md-3">
                    <a href="#" class="btn btn-light-info" style="margin-top: 10px;" id="addPHabiliteRowBtn">Ajouter une personne habilité</a>
                  </div>
                </div>
              </div>
            </div>
        </p>

        <?php
    }
}