<?php
namespace Edit;

class administrateurs {
    public function render($administrateursl) {
        ?>
        
        <p>
              <a
                class="btn btn-light-info w-100 font-medium text-info"
                data-bs-toggle="collapse"
                href="#administrateursDirigeants"
                role="button"
                aria-expanded="false"
                aria-controls="administrateursDirigeants"
              >
                Administrateurs / Dirigeants
              </a>

            <div class="collapse" id="administrateursDirigeants">
              <div class="card card-body">
               
                <div class="administrateursRows">
                  <h5>Administrateurs / Dirigeants</h5>

                  <?php

                  $rowsCount = 0;

                  $nbradministrateurs = count($administrateursl);

                  foreach ($administrateursl as $administrateur)
                  {
                    $rowsCount++; 

                    ?>

                  <div class="row administrateurRowInfos administrateurRow<?php echo $rowsCount;?>">

                  <input type="text" name="ppe2_administrateurs[]" value="0" class="fichiers_caches" id="administrateur_ppe2_id_<?php echo $rowsCount;?>">
                  <input type="text" name="lien2_administrateurs[]" value="0" class="fichiers_caches" id="administrateur_lien2_id_<?php echo $rowsCount;?>">

                    <div class="col-md-2">
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Nom"
                        name="noms_administrateurs[]"
                        value="<?php echo $administrateur['nom'];?>"
                      /> 
                    </div>
                    <div class="col-md-1">
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Prénom"
                        name="prenoms_administrateurs[]"
                        value="<?php echo $administrateur['prenom'];?>"
                      /> 
                    </div>
                    <div class="col-md-2">
                      <select class="form-control pays"
                        name="pays_administrateurs[]">
                        
                        <?php if($administrateur['paysNaissance'] != 10000){echo "<option value=" . $administrateur['paysNaissance'] . " selected>".$administrateur['libelleNaissance']."</option>";}else{
                          echo '<option value="10000">Choisissez une option</option>';
                        }?>
                      </select>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                                      <input type="date" class="form-control"
                        name="dates_naissance_administrateurs[]"
                        value="<?php echo $administrateur['dateNaissance'];?>"
                        >
                                  </div> 
                    </div>
                    <div class="col-md-2">
                      <input
                        type="text"
                        class="form-control"
                        placeholder="CIN / Passeport"
                        name="cins_administrateurs[]"
                        value="<?php echo $administrateur['cinPasseport'];?>"
                      /> 
                    </div>
                    <div class="col-md-2">
                      <select class="form-control pays"
                        name="nationalites_administrateurs[]">
                        <?php if($administrateur['nationalite'] != 10000){echo "<option value=" . $administrateur['nationalite'] . " selected>".$administrateur['libelleNationalite']."</option>";}else{
                          echo '<option value="10000">Nationalité</option>';
                        }?>
                      </select>
                    </div>

                    <div class="col-md-1">
                      <a href="#" style="line-height: 3;" class="deleteAdministrateurRow deleteAdministrateurRow<?php echo $rowsCount;?>" data-rowID="<?php echo $rowsCount;?>"><center><i class="ti ti-trash w-100 h5"></i></center></a>
                    </div>

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
                      class="smart-switch adm_ppe"
                      id="administrateur_ppe_id_<?php echo $rowsCount;?>"
                        name="ppes_administrateurs_check[]"
                        data-target="administrateur_ppe_data_id_<?php echo $rowsCount;?>"
                        <?php if($administrateur['ppe2'] === 1){echo "checked"; }?>
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="hidden-select" id="administrateur_ppe_data_id_<?php echo $rowsCount;?>">

                      <label>Libelle PPE </label>
                      <select class="form-control ppes"
                        name="ppes_administrateurs_input[]">
                        <?php if($administrateur['ppe'] != 10000){echo "<option value=" . $administrateur['ppe'] . " selected>".$administrateur['ppeLibelle']."</option>";}else{
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
                      class="smart-switch adm_lien"
                      id="administrateur_ppe_lien_id_<?php echo $rowsCount;?>"
                        name="ppes_lien_administrateurs_check[]"
                        data-target="administrateur_ppe_lien_data_id_<?php echo $rowsCount;?>"
                        <?php if($administrateur['lien2'] === 1){echo "checked"; }?>
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="hidden-select" id="administrateur_ppe_lien_data_id_<?php echo $rowsCount;?>">

                      <label>Libelle PPE </label>
                      <select class="form-control ppes"
                        name="ppes_lien_administrateurs_input[]">
                        <?php if($administrateur['lienPPE'] != 10000){echo "<option value=" . $administrateur['lienPPE'] . " selected>".$administrateur['ppelienlibelle']."</option>";}else{
                          echo '<option value="10000">PPE</option>';
                        }?>
                      </select>

                      </div>
                    </div>
                    <div class="col-md-2">
                      <label>Fonction </label>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Fonction"
                        name="fonctions_administrateurs[]"
                        value="<?php echo $administrateur['fonction'];?>"
                      /> 
                    </div>
                      <div class="col-md-1">
                        <div class="mb-3">
                          <label style="width:100%;"></label>
                          <input type="file" 
                                   id="cin_file_adm_` + rowsCount + `"
                                   name="cin_administrateurs[]" 
                                   style="display:none;" 
                                   accept=".pdf,.jpg,.png" />

                            <label for="cin_file_adm_` + rowsCount + `" class="btn btn-primary w-100" data-bs-toggle="tooltip" data-bs-original-title="Joindre CIN">
                              <i class="ti ti-upload"></i>&nbsp; CIN
                            </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="mb-3">
                          <label style="width:100%;"></label>
                          <input type="file" 
                                   id="pvn_file_adm_` + rowsCount + `"
                                   name="pvn_administrateurs[]" 
                                   style="display:none;" 
                                   accept=".pdf,.jpg,.png" />

                            <label for="pvn_file_adm_` + rowsCount + `" class="btn btn-primary w-100" data-bs-toggle="tooltip" data-bs-original-title="Joindre CIN">
                              <i class="ti ti-upload"></i>&nbsp; PV nomination
                            </label>
                        </div>
                      </div>
                    </div>
                      <?php
                    }
                    ?>

                </div>        

                <div class="row addAdministrateurRow">
                  <div class="col-md-3">
                    <a href="#" class="btn btn-light-info" style="margin-top: 10px;" id="addAdministrateurRowBtn">Ajouter un Administrateur ou Dirigeant</a>
                  </div>
                  
                </div>


              </div>
            </div>
        </p>

        <?php
    }
}