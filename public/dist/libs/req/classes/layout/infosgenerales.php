<?php


namespace Layout;

class infosGenerales {
    public function render() {
        ?>
        <p class="button-group">
            <a
              class="btn btn-light-info w-100 font-medium text-info"
              data-bs-toggle="collapse"
              href="#informationsGenerales"
              role="button"
              aria-expanded="false"
              aria-controls="informationsGenerales"
            >
              Informations générales
            </a>

            <div class="collapse" id="informationsGenerales">
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
                        <option value="10000" selected>Choisissez une option</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="mb-3">
                      <label class="mr-sm-2" for="inlineFormCustomSelect"
                        >Date d'immatriculation
                      </label>
                      <div class="form-group">
                        <input type="date" class="form-control" value="2025-05-13" name="date_immatriculation_input">
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
                        value="0"
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
                        value="0"
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
                        value="0"
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
                        <option value="10000" selected>Choisissez une option</option>
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
                        <option value="10000" selected>Choisissez une option</option>
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
                          data-checked="false"
                          data-on-color="primary"
                          data-off-color="default"
                          data-off-text="Non"
                          data-on-text="Oui"
                          name="autorite_regulation_check"
                        />
                      </div>
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
                        placeholder="05 22 22 22 22"
                        name="telephone_input"
                      />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="mb-3">
                      <label>E-mail</label>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="email@duclient.com"
                        name="email_input"
                      />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="mb-3">
                      <label>Site web</label>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="www.site.com"
                        name="site_web_input"
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
                        />
                      </div>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="mb-3">
                      <label> </label>
                      <div class="mb-3 societe_de_gestion_check_hide">
                        <label for="agrement" class="btn btn-primary" style="width:100%;"><i class="ti ti-upload"></i>&nbsp; Agrément</label>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="mb-3">
                      <label> </label>
                      <div class="mb-3 societe_de_gestion_check_hide">
                        <label for="ni_file" class="btn btn-primary" style="width:100%;"><i class="ti ti-upload"></i>&nbsp; NI</label>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="mb-3">
                      <label> </label>
                      <div class="mb-3 societe_de_gestion_check_hide">
                        <label for="fs_file" class="btn btn-primary" style="width:100%;"><i class="ti ti-upload"></i>&nbsp;FS</label>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="mb-3">
                      <label> </label>
                      <div class="mb-3 societe_de_gestion_check_hide">
                        <label for="rg_file" class="btn btn-primary" style="width:100%;"><i class="ti ti-upload"></i>&nbsp;RG</label>
                      </div>
                    </div>
                  </div>

                </div>
                

                <div class="contactsRows">
                  <h5>Contacts</h5>
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