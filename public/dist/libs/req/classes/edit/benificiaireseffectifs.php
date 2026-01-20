<?php
namespace Edit;

class benificiaireseffectifs {
    public function render($benificiairesl) {
        ?>
        
        <p>
              <a
                class="btn btn-light-info w-100 font-medium text-info"
                data-bs-toggle="collapse"
                href="#benificiaireEffectif"
                role="button"
                aria-expanded="false"
                aria-controls="benificiaireEffectif"
              >
                Bénificiaire effectif
              </a>

            <div class="collapse" id="benificiaireEffectif">
              <div class="card card-body">
                
                <div class="benificiairesRows">
                  <h5>Bénificiaires</h5>
                  <?php

                    $rowsCount = 0;

                    $nbrbenificiaires = count($benificiairesl);

                    foreach ($benificiairesl as $benificiaire)
                    {
                      $rowsCount++; 
                  ?>

                  <div class="row benificiaireRowInfos benificiaireRow<?php echo $rowsCount;?>">

                    <input type="text" name="ppe2_benificiaires[]" value="0" class="fichiers_caches" id="benificiaire_ppe2_id_<?php echo $rowsCount;?>">
                    <input type="text" name="lien2_benificiaires[]" value="0" class="fichiers_caches" id="benificiaire_lien2_id_<?php echo $rowsCount;?>">

                    <div class="col-md-2">
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Nom / Raison sociale"
                        name="noms_rs_benificiaires[]"
                        value="<?php echo $benificiaire['nom'];?>"
                      /> 
                    </div>
                    <div class="col-md-2">
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Prénom (p.physique)"
                        name="prenoms_benificiaires[]"
                        value="<?php echo $benificiaire['prenom'];?>"
                      /> 
                    </div>
                    <div class="col-md-2">
                      <select class="form-control pays" name="pays_naissance_benificiaires[]">
                        <?php if($benificiaire['paysNaissance'] != 10000){echo "<option value=" . $benificiaire['paysNaissance'] . " selected>".$benificiaire['libelleNaissance']."</option>";}else{
                          echo '<option value="10000">Choisissez une option</option>';
                        }?>
                      </select>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                                      <input type="date" class="form-control" name="dates_naissance_benificiaires[]" value="<?php echo $benificiaire['dateNaissance'];?>">
                                  </div> 
                    </div>
                    <div class="col-md-1">
                      <input
                        type="text"
                        class="form-control"
                        placeholder="CIN / Passeport"
                        name="identite_benificiaires[]"
                         value="<?php echo $benificiaire['cinPasseport'];?>"
                      /> 
                    </div>
                    <div class="col-md-2">
                      <select class="form-control pays" name="nationalites_benificiaires[]">
                        <?php if($benificiaire['nationalite'] != 10000){echo "<option value=" . $benificiaire['nationalite'] . " selected>".$benificiaire['libelleNationalite']."</option>";}else{
                          echo '<option value="10000">Choisissez une option</option>';
                        }?>
                      </select>
                    </div>

                    <div class="col-md-1">
                      <a href="#" style="line-height: 3;" class="deleteBenificiaireRow deleteBenificiaireRow<?php echo $rowsCount;?>" data-rowID="<?php echo $rowsCount;?>"><center><i class="ti ti-trash w-100 h5"></i></center></a>
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
                      class="smart-switch ben_ppe"
                      id="benificiaire_ppe_id_<?php echo $rowsCount;?>"
                      name="benificiaires_ppe_check[]"
                      data-target="benificiaire_ppe_data_id_<?php echo $rowsCount;?>"
                      <?php if($benificiaire['ppe2'] === 1){echo "checked"; }?>
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="hidden-select" id="benificiaire_ppe_data_id_<?php echo $rowsCount;?>">

                      <label>Libelle PPE </label>
                      <select class="form-control ppes" name="benificiaires_ppe_input[]">
                        <?php if($benificiaire['ppe'] != 10000){echo "<option value=" . $benificiaire['ppe'] . " selected>".$benificiaire['ppeLibelle']."</option>";}else{
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
                      class="smart-switch ben_lien"
                      id="benificiaire_ppe_lien_id_<?php echo $rowsCount;?>"
                      name="benificiaires_ppe_lien_check[]"
                      data-target="benificiaire_ppe_lien_data_id_<?php echo $rowsCount;?>"
                      <?php if($benificiaire['lien2'] === 1){echo "checked"; }?>
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="hidden-select" id="benificiaire_ppe_lien_data_id_<?php echo $rowsCount;?>">

                      <label>Libelle PPE </label>
                      <select class="form-control ppes" name="benificiaires_ppe_lien_input[]">
                        <?php if($benificiaire['lienPPE'] != 10000){echo "<option value=" . $benificiaire['lienPPE'] . " selected>".$benificiaire['ppelienlibelle']."</option>";}else{
                          echo '<option value="10000">PPE</option>';
                        }?>
                      </select>

                      </div>
                    </div>
                    <div class="col-md-2">
                      <label>% du capital</label>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="% du capital"
                       name="benificiaires_pourcentage_capital[]"
                       value="<?php echo $benificiaire['capital'];?>"
                      /> 
                    </div>
                      <div class="col-md-1">
                      <label></label>
                        <input type="file" 
                                 id="cin_file_ben_<?php echo $rowsCount;?>"
                                 name="cin_benificiaires[]" 
                                 style="display:none;" 
                                 accept=".pdf,.jpg,.png" />

                          <label for="cin_file_ben_<?php echo $rowsCount;?>" class="btn btn-primary w-100" data-bs-toggle="tooltip" data-bs-original-title="Joindre CIN">
                            <i class="ti ti-upload"></i>&nbsp; CIN
                          </label>
                      </div>



                </div>

                  <?php
                }
                ?>

                </div>        

                <div class="row addBenificiaireRow">
                  <div class="col-md-2">
                    <a href="#" class="btn btn-light-info" style="margin-top: 10px;" id="addBenificiaireRowBtn">Ajouter un bénificiaire</a>
                  </div>
                </div>


              </div>
            </div>
        </p>



        <?php
    }
}