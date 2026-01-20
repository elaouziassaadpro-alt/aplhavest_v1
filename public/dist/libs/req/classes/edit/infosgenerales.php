<?php
namespace Edit;

class infosGenerales {
    public function render(
      $nom_rs,
      $capitalSocialPrimaire,
      $forme_juridique_id,
      $forme_juridique,
      $dateImmatriculation,
      $ice,
      $rc,
      $ifiscal,
      $siegeSocial,
      $lieuActivite,
      $lieuActiviteid,
      $paysResidence,
      $paysResidenceid,
      $nomRegulateur,
      $regule,
      $telephone,
      $email,
      $siteweb,
      $societeGestion,
      $contacts
    ) {
        ?>
        <p class="button-group">
            <a
              class="btn btn-light-info w-100 font-medium text-info"
              data-bs-toggle="collapse"
              href="#informationsGenerales"
              role="button"
              aria-expanded=""
              aria-controls="informationsGenerales"
            >
              Informations générales
            </a>

            <div class="collapse show" id="informationsGenerales" >
              <div class="card card-body">
                
                <div class="row">
                  <div class="col-md-4">
                    <div class="mb-3">
                      <label>Raison sociale</label>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Dénomination"
                        name="raison_social_input"
                        value="<?php echo $nom_rs;?>"
                      />
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="mb-3">
                      <label>Capital social</label>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Capital social"
                        name="capital_social_input"
                        value="<?php echo $capitalSocialPrimaire;?>"
                      />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="mb-3">
                      <label class="mr-sm-2" for="inlineFormCustomSelect"
                        >Forme juridique</label
                      >
                      <select
                        class="form-select mr-sm-2 select2 w-100" style="width: 100%;"
                        id="formes_juridiques_id"
                        name="forme_juridique_input"
                      >
                      <?php if($forme_juridique_id != 10000){echo "<option value=" . $forme_juridique_id . " selected>".$forme_juridique."</option>";}else{
                        echo '<option value="10000">Choisissez une option</option>';
                      }?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="mb-3">
                      <label class="mr-sm-2" for="inlineFormCustomSelect"
                        >Date d'immatriculation
                      </label>
                      <div class="form-group">
                        <input type="date" class="form-control" value="<?php echo $dateImmatriculation;?>" name="date_immatriculation_input">
                      </div>
                    </div>
                  </div>


                </div>

                <div class="row">
                  <div class="col-md-2">
                    <div class="mb-3">
                      <label>ICE</label>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Identifiant commun de l'entreprise"
                        name="ice_input"
                        value="<?php echo $ice;?>"
                      />
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="mb-3">
                      <label style="width:100%;"></label>
                      <label for="ice" class="btn btn-primary" style="width:100%;"><i class="ti ti-upload"></i>&nbsp; ICE</label>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="mb-3">
                      <label style="width:100%;"></label>
                      <label for="status" class="btn btn-primary" style="width:100%;"><i class="ti ti-upload"></i>&nbsp; Status</label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="mb-3">
                      <label>RC</label>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Numéro du registre de commerce"
                        name="rc_input"
                        value="<?php echo $rc;?>"
                      />
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="mb-3">
                      <label style="width:100%;"></label>
                      <label for="rc" class="btn btn-primary" style="width:100%;"><i class="ti ti-upload"></i>&nbsp; RC</label>
                    </div>
                  </div>
                  
                  <div class="col-md-3">
                    <div class="mb-3">
                      <label>IF</label>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Identifiant fiscal"
                        name="if_input"
                        value="<?php echo $ifiscal;?>"
                      />
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <div class="mb-3">
                      <label>Siège social</label>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Adresse du siège social"
                        name="siege_social_input"
                        value="<?php echo $siegeSocial;?>"
                      />
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="mb-3">
                      <label>Lieu d'activité</label>
                      <select
                        class="form-select mr-sm-2 select2 w-100" style="width: 100%;"
                        id="lieu_activite_id"
                        name="lieu_activite_input"
                      >
                        <?php if($lieuActiviteid != 10000){echo "<option value=" . $lieuActiviteid . " selected>".$lieuActivite."</option>";}else{
                          echo '<option value="10000">Choisissez une option</option>';
                        }?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="mb-3">
                      <label>Pays de résidence fiscal</label>
                      <select
                        class="form-select mr-sm-2 select2 w-100" style="width: 100%;"
                        id="residence_fiscale_id"
                        name="residence_fiscale_input"
                      >
                        <?php if($lieuActiviteid != 10000){echo "<option value=" . $lieuActiviteid . " selected>".$lieuActivite."</option>";}else{
                          echo '<option value="10000">Choisissez une option</option>';
                        }?>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <label style="margin-bottom:-20px ;">Régulé par une autorité de régulation</label>
                  <div class="col-md-1">
                    <div class="mb-6">
                      <label> </label>
                      <div class="mb-4 bt-switch" id="autorite_regulation_check_id">
                        <input
                          type="checkbox"
                          data-checked="yes"
                          data-on-color="primary"
                          data-off-color="default"
                          data-off-text="Non"
                          data-on-text="Oui"
                          name="autorite_regulation_check"
                          <?php if($regule === 1){echo "checked"; }?>
                        />
                      </div>

                      <style>
                        .bootstrap-switch-null.bootstrap-switch-undefined.bootstrap-switch-undefined.bootstrap-switch-undefined.bootstrap-switch-undefined.bootstrap-switch-undefined.bootstrap-switch.bootstrap-switch-wrapper.bootstrap-switch-focused.bootstrap-switch-animate.bootstrap-switch-off.bootstrap-switch-on .bootstrap-switch-container {
                            margin-left: 0px;width: 119.484px;
                        }

                        .bootstrap-switch-null.bootstrap-switch-undefined.bootstrap-switch-undefined.bootstrap-switch-undefined.bootstrap-switch-undefined.bootstrap-switch-undefined.bootstrap-switch.bootstrap-switch-wrapper.bootstrap-switch-focused.bootstrap-switch-animate .bootstrap-switch-container
                        {
                          margin-left: -46.90px;
                        }

                        
                      </style>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="mb-3">
                      <label> </label>
                      <div class="mb-3">
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Nom du régulateur"
                          id="autorite_regulation_data_id"
                          style="display: none;"
                          name="autorite_regulation_input"
                          value="<?php echo $nomRegulateur;?>"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="mb-3">
                      <label>Téléphone</label>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Téléphone du client"
                        name="telephone_input"
                        value="<?php echo $telephone;?>"
                      />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="mb-3">
                      <label>E-mail</label>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="E-mail du client"
                        name="email_input"
                        value="<?php echo $email;?>"
                      />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="mb-3">
                      <label>Site web</label>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Adresse web du client"
                        name="site_web_input"
                        value="<?php echo $siteweb;?>"
                      />
                    </div>
                  </div>
                </div>

                <div class="row">
                  <label style="margin-bottom:-20px ;">Société de gestion</label>
                  <div class="col-md-1">
                    <div class="mb-6">
                      <label> </label>
                      <div class="mb-4 bt-switch" id="societe_de_gestion_check_id">
                        <input
                          type="checkbox"
                          data-checked="false"
                          data-on-color="primary"
                          data-off-color="default"
                          data-off-text="Non"
                          data-on-text="Oui"
                          name="societe_de_gestion_check"
                          value="<?php echo $societeGestion;?>"
                          <?php if($societeGestion === 1){echo "checked";}?>
                        />
                      </div>
                    </div>
                  </div>

                  <?php if($societeGestion === 1){echo '<style>.societe_de_gestion_check_hide {display:block;}</style>';}?>
                  <div class="col-md-2">
                    <div class="mb-3">
                      <label> </label>
                      <div class="mb-3 societe_de_gestion_check_hide">
                        <label for="agrement" class="btn btn-primary" style="width:100%;"><i class="ti ti-upload"></i>&nbsp; Agrément</label>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="mb-3">
                      <label> </label>
                      <div class="mb-3 societe_de_gestion_check_hide">
                        <label for="ni_fs_rg" class="btn btn-primary" style="width:100%;"><i class="ti ti-upload"></i>&nbsp; NI / FS / RG</label>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="mb-3">
                      <label> </label>
                      <div class="mb-3 societe_de_gestion_check_hide">
                        <label for="loi_ep" class="btn btn-primary" style="width:100%;"><i class="ti ti-upload"></i>&nbsp; Loi pour les EP</label>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="mb-3">
                      <label> </label>
                      <div class="mb-3 societe_de_gestion_check_hide">
                        <label for="etat_synthese" class="btn btn-primary" style="width:100%;"><i class="ti ti-upload"></i>&nbsp; Etats de synthèse</label>
                      </div>
                    </div>
                  </div>
                </div>

                

                <div class="contactsRows">
                  <h5>Contacts</h5>

                  <?php


                  $indexcontact = 1;

                  $nbrlignes = count($contacts);

                  foreach ($contacts as $contact)
                  {
                    echo '
                        <div class="row contactRowInfos contactRow' . $indexcontact . '">

                          <div class="col-md-2">
                            <input type="text" class="form-control" placeholder="Nom"
                                   name="noms_contacts[]" value="' . htmlspecialchars($contact['nom']) . '" />
                          </div>

                          <div class="col-md-2">
                            <input type="text" class="form-control" placeholder="Prénom"
                                   name="prenoms_contacts[]" value="' . htmlspecialchars($contact['prenom']) . '" />
                          </div>

                          <div class="col-md-2">
                            <input type="text" class="form-control" placeholder="Fonction"
                                   name="fonctions_contacts[]" value="' . htmlspecialchars($contact['fonction']) . '" />
                          </div>

                          <div class="col-md-2">
                            <input type="text" class="form-control" placeholder="Téléphone"
                                   name="telephones_contacts[]" value="' . htmlspecialchars($contact['telephone']) . '" />
                          </div>

                          <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="Email"
                                   name="emails_contacts[]" value="' . htmlspecialchars($contact['email']) . '" />
                          </div>

                          <div class="col-md-1">
                            <a href="#" style="line-height: 3;';
                            if($indexcontact < $nbrlignes)
                            {
                              echo 'display: none;';
                            }
                            echo '" class="deleteContactRow deleteContactRow' . $indexcontact . '" data-rowid="' . $indexcontact . '">
                              <center><i class="ti ti-trash w-100 h5"></i></center>
                            </a>
                          </div>
                        </div>
                        ';

                        $indexcontact++;
                  }

                  ?>
                </div>



                <div class="row addContactRow">
                  <div class="col-md-2">
                    <a href="#" class="btn btn-light-info" style="margin-top: 10px;" id="addContactRowBtn">Ajouter un contact</a>
                  </div>
                </div>

              </div>
            </div>
        </p>

        <?php
    }
}