<div class="card w-100">
  <div class="card-body">
    <form id="formulaire_global" >
      <div class="form-body">
    <div class="mb-4">
      <h5 class="mb-0">Etablissement</h5>
    </div>

    <div id="codes_erreurs" class="card-danger">
      <div
        class="modal fade"
        id="codes_erreurs_modal"
        tabindex="-1"
        aria-labelledby="vertical-center-modal"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-md">
          <div
            class="modal-content modal-filled bg-light-danger"
          >
            <div class="modal-body p-4">
              <div class="text-center text-danger">
                <i class="ti ti-hexagon-letter-x fs-7"></i>

                <h4 class="mt-2" id="erreurs_titre"></h4>
                <p class="mt-3" id="erreurs_content"></p>

                <button
                  type="button"
                  class="btn btn-outline-dark my-2"
                  data-bs-dismiss="modal"
                >
                  D'accord
                </button>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
      </div>
    </div>

    <style>
      #erreurs_content ul li
      {
        text-align: left;
        line-height: 2.5;
      }

      #erreurs_content ul span.text-dark
      {
        opacity: 0.8;
        line-height: 5;
      }
    </style>

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
                  value="<?php echo $nom;?>"
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
                  value="<?php echo $capitalSocialPrimaire; ?>"
                />
              </div>
            </div>
            <div class="col-md-3">
              <div class="mb-3">
                <label class="mr-sm-2" for="inlineFormCustomSelect"
                  >Forme juridique</label
                >
                <br>
                <select
                  class="form-select mr-sm-2 select2 w-100" style="width: 100%;"
                  id="formes_juridiques_id"
                  name="forme_juridique_input"
                >
                  <option value="<?php echo $forme_juridique_id; ?>" selected><?php echo !empty($forme_juridique) ? $forme_juridique : 'Choisissez une forme'; ?></option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="mb-3">
                <label class="mr-sm-2" for="inlineFormCustomSelect"
                  >Date d'immatriculation</label
                >
                <div class="form-group">
                                <input type="date" class="form-control" value="<?php echo $dateImmatriculation; ?>" name="date_immatriculation_input">
                            </div>
              </div>
            </div>


          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="mb-3">
                <label>ICE</label>
                <input
                  type="text"
                  class="form-control"
                  placeholder="Identifiant commun de l'entreprise"
                  name="ice_input"
                  value="<?php echo $ice; ?>"
                />
              </div>
            </div>
            <div class="col-md-5">
              <div class="mb-3">
                <label>RC</label>
                <input
                  type="text"
                  class="form-control"
                  placeholder="Numéro du registre de commerce"
                  name="rc_input"
                  value="<?php echo $rc; ?>"
                />
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
                  value="<?php echo $ifiscal; ?>"
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
                  value="<?php echo $siegeSocial; ?>"
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
                  <option selected><?php echo $lieuActivite; ?></option>
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
                  <option selected><?php echo $paysResidence; ?></option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <label style="margin-bottom:-20px ;">Régulé par une autorité de régulation</label>
            <div class="col-md-1">
              <div class="mb-6  bt-switch">
                <label> </label>
                <div class="mb-4" id="autorite_regulation_check_id">
                  <input
                    type="checkbox"
                    data-on-color="primary"
                    data-off-color="default"
                    data-off-text="Non"
                    data-on-text="Oui"
                    name="inputchecked"
                    checked="checked"
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
                    value="<?php echo $nomRegulateur; ?>"
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
                  value="<?php echo $telephone; ?>"
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
                  value="<?php echo $email; ?>"
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
                  value="<?php echo $siteweb; ?>"
                />
              </div>
            </div>
          </div>

          <div class="contactsRows">
            <h5>Contacts</h5>
            <?php
            $contactIndex = 1;
            foreach ($contacts as $contact) {
                $rowClass = 'contactRow' . $contactIndex;
            ?>
            <div class="row contactRowInfos <?= $rowClass ?>">

              <div class="col-md-3">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Nom et prénom"
                  name="noms_contacts[]"
                  value="<?= htmlspecialchars($contact['nom']) ?>"
                />
              </div>

              <div class="col-md-3">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Fonction"
                  name="fonctions_contacts[]"
                  value="<?= htmlspecialchars($contact['fonction']) ?>"
                />
              </div>

              <div class="col-md-2">
                <input
                  type="text"
                  class="form-control phone-inputmask"
                  placeholder="Téléphone"
                  name="telephones_contacts[]"
                  value="<?= htmlspecialchars($contact['telephone']) ?>"
                />
              </div>

              <div class="col-md-3">
                <input
                  type="text"
                  class="form-control email-inputmask"
                  placeholder="Email"
                  name="emails_contacts[]"
                  value="<?= htmlspecialchars($contact['email']) ?>"
                />
              </div>

              <div class="col-md-1">
                <a href="#" style="line-height: 3;" class="deleteContactRow <?= 'deleteContactRow' . $contactIndex ?>" data-rowID="<?= $contactIndex ?>">
                  <center><i class="ti ti-trash w-100 h5"></i></center>
                </a>
              </div>

            </div>
            <?php
            $contactIndex++;
            }
            ?>

          </div>

          <div class="row addContactRow">
            <div class="col-md-2">
              <a href="#" class="btn btn-light-info btnadd" style="margin-top: 10px;" id="addContactRowBtn">Ajouter un contact</a>
            </div>
          </div>

        </div>
      </div>


      <a
        class="btn btn-light-info w-100 font-medium text-info"
        data-bs-toggle="collapse"
        href="#coordonneesBancaires"
        role="button"
        aria-expanded="false"
        aria-controls="coordonneesBancaires"
      >
        Coordonnées bancaires
      </a>

    </p>

    <div class="collapse" id="coordonneesBancaires">
      <div class="card card-body">

        <div class="CBancairesRows">
          <?php
            $cBancaireIndex = 1;
            foreach ($cBancaires as $cBancaire) {
                $rowClass = 'cBancaireRow' . $cBancaireIndex;
            ?>

            <div class="row CBancairesRowInfos <?= $rowClass ?>">

              <div class="col-md-3">
                <select class="form-control banques" name="noms_banques[]">
                  <option value="<?= htmlspecialchars($cBancaire['idBanque']) ?>"><?= htmlspecialchars($cBancaire['nomBanque']) ?></option>
                </select>
              </div>
              <div class="col-md-3">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Agence"
                  name="agences_banques[]"
                  value="<?= htmlspecialchars($cBancaire['agence']) ?>"
                /> 
              </div>
              <div class="col-md-2">
                <select class="form-control villes_banques" name="villes_banques[]">
                  <option value="<?= htmlspecialchars($cBancaire['idVBanque']) ?>"><?= htmlspecialchars($cBancaire['nomVBanque']) ?></option>
                </select>
              </div>
              <div class="col-md-3">
                <input
                  type="text"
                  class="form-control"
                  placeholder="RIB"
                  name="ribs_banques[]"
                  value="<?= htmlspecialchars($cBancaire['rib']) ?>"
                /> 
              </div>
              <div class="col-md-1">
                <a href="#" style="line-height: 3;" class="deleteCBancairesRow <?= 'deleteCBancairesRow' . $cBancaireIndex ?>" data-rowID="<?= $cBancaireIndex ?>"><center><i class="ti ti-trash w-100 h5"></i></center></a>
              </div>
            </div>

            <?php
            $cBancaireIndex++;
            }
            ?>
        </div>


        <div class="row addCBancairesRow">
          <div class="col-md-2">
            <a href="#" class="btn btn-light-info btnadd" style="margin-top: 10px;" id="addCBancairesRowBtn">Ajouter un compte</a>
          </div>
        </div>

      </div>
    </div>

      <a
        class="btn btn-light-info w-100 font-medium text-info"
        data-bs-toggle="collapse"
        href="#typologieClient"
        role="button"
        aria-expanded="false"
        aria-controls="typologieClient"
      >
        Typologie client
      </a>

    </p>

    <div class="collapse" id="typologieClient">
      <div class="card card-body">
        <div class="row">
          <div class="col-md-2">
            <div class="mb-3">
              <label>Secteur d'activité</label>
              <select class="form-control" id="secteur_dactivite_id" style="width: 100%;" name="secteur_activite_input">
                <option value="<?php echo $scID; ?>"><?php echo $scLibelle; ?></option>
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="mb-3">
              <label>Segment</label>
              <select class="form-control" id="segments_id" style="width: 100%;" name="segment_input">
                <option value="<?php echo $smID; ?>"><?php echo $smLibelle; ?></option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3 row">
              <label>Activité à l'étranger</label>
              <div class="mb-4 bt-switch col-3">
                <label> </label>
                <input
                  type="checkbox"
                  data-checked="false"
                  data-on-color="primary"
                  data-off-color="default"
                  data-off-text="Non"
                  data-on-text="Oui"
                  id="activite_etranger_id"
                  name="activite_etranger_check"
                />
              </div>
              <div class="mb-3 col-9">
                <select class="form-control pays hidden-select" id="activite_etranger_data_id" name="activite_etranger_input">
                  <option>Pays</option>
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3 row">
              <label>Sur un marché financier ou fait-il appel public à l'épargne ?</label>
              <div class="mb-4 bt-switch col-3">
                <label> </label>
                <input
                  type="checkbox"
                  data-checked="false"
                  data-on-color="primary"
                  data-off-color="default"
                  data-off-text="Non"
                  data-on-text="Oui"
                  id="sur_marche_financier_id"
                  name="sur_marche_financier_check"
                />
              </div>
              <div class="mb-3 col-9">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Précisez"
                  id="sur_marche_financier_data_id"
                  style="display: none;"
                  name="sur_marche_financier_input"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

      <a
        class="btn btn-light-info w-100 font-medium text-info"
        data-bs-toggle="collapse"
        href="#statutFatca"
        role="button"
        aria-expanded="false"
        aria-controls="statutFatca"
      >
        Statut FATCA
      </a>

    </p>

    <div class="collapse" id="statutFatca">
      <div class="card card-body">
        <div class="row">
          <div class="col-md-6">
            <h5>Votre établissement est-il considéré comme <b>"US Entity"</b> ?</h5>
            <div class="row">
              <div class="col-md-1">
                <div class="mb-6">
                  <label> </label>
                  <div class="mb-4 bt-switch">
                    <label> </label>
                    <input
                      type="checkbox"
                      data-checked="false"
                      data-on-color="primary"
                      data-off-color="default"
                      data-off-text="Non"
                      data-on-text="Oui"
                      id="us_entity_id"
                      name="us_entity_check"
                    />
                  </div>
                </div>
              </div>
              <div class="col-md-10">
                  <div class="card-body">
                    <!-- Zone de drop, avec un ID spécifique -->
                    <div id="dropzone-fichierFATCA" class="dropzone us_entity_data_id" style="display:none;">
                      <div class="fallback">
                        <input name="FATCA_fichier[]" type="file" multiple />
                        <input type="hidden" name="usentity" id="usentity_path" />
                      </div>
                    </div>
                  </div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <h5>Votre Etablissement est une <b>"Participating Financial Institution"</b> ?</h5>
            <div class="row">
              <div class="col-md-2">
                <div class="mb-6">
                  <label> </label>
                  <div class="mb-4 bt-switch">
                    <label> </label>
                    <input
                      type="checkbox"
                      data-checked="false"
                      data-on-color="primary"
                      data-off-color="default"
                      data-off-text="Non"
                      data-on-text="Oui"
                      id="giin_id"
                      name="giin_check"
                    />
                  </div>
                </div>
              </div>
              <div class="col-md-9 row" id="giin_data_id" style="display:none;">
                <div class="col-md-3">
                  <div class="mb-3">
                    <label> <br><br></label>
                    <label>GIIN : </label>
                  </div>
                </div>
                <div class="col-md-9">
                  <div class="mb-3">
                    <label> </label>
                    <input
                      type="text"
                      class="form-control giin-inputmask"
                      placeholder="Global Intermediary Identification Number"
                      name="giin_input"
                    />
                  </div>
                </div>
              </div>

              <div class="col-md-9 row" id="giin_data_autres_id">
                <div class="col-md-3">
                  <div class="mb-3">
                    <label> <br><br></label>
                    <label>Autres : </label>
                  </div>
                </div>
                <div class="col-md-9">
                  <div class="mb-3">
                    <label> </label>
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Précisions"
                      name="giin_autres_input"
                    />
                  </div>
                </div>
              </div>
              
            </div>

          </div>
        </div>
      </div>
    </div>

    <style>
      #dropzone-fichierFATCA
      {
        min-height: 90px;
        border: 2px dotted rgba(0, 0, 0, .1);
        background: #fff;
        padding: 0px 0px 0px 0px;
        border-radius: 10px;
      }
    </style>

      <a
        class="btn btn-light-info w-100 font-medium text-info"
        data-bs-toggle="collapse"
        href="#situationFinanciere"
        role="button"
        aria-expanded="false"
        aria-controls="situationFinanciere"
      >
        Situation financière et patrimoniale
      </a>

    </p>

    <div class="collapse" id="situationFinanciere">
      <div class="card card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="mb-6">
              <label>Capital social</label>
              <input
                type="text"
                class="form-control"
                placeholder=""
                name="financier_capital_social_input"
                value="<?php echo $capitalSocial; ?>"
              />
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <label> Origine des fonds</label>
              <input
                type="text"
                class="form-control"
                placeholder=""
                name="origine_fonds_input"
                value="<?php echo $origineFonds; ?>"
              />
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <label>Pays de provenance des fonds</label>
              <select
                type="text"
                class="form-control pays select2"
                placeholder=""
                name="pays_provenance_fonds_input"
                id="pays_provenance_fonds_input"
              ></select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <div class="mb-6">
              <label>Chiffre d'affaires (exercice écoulé)</label>
              <br>
              <div style="margin-top:10px;"></div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="chiffre_affaires_radio" id="caM1" value="< 5 M.DH" checked>
                <label class="form-check-label" for="caM1">< 5 M.DH</label>
              </div>

              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="chiffre_affaires_radio" id="caM2" value="5 M.DH < CA < 10 M.DH">
                <label class="form-check-label" for="caM2">5 M.DH < CA < 10 M.DH</label>
              </div>

              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="chiffre_affaires_radio" id="caM3" value="> 10 M.DH">
                <label class="form-check-label" for="caM3">> 10 M.DH</label>
              </div>

            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <label> Résultat net (exercice écoulé)</label>
              <input
                type="text"
                class="form-control"
                placeholder=""
                name="resultat_net_input"
                value="<?php echo $resultatsNet; ?>"
              />
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-6">
              <label>Appartient à un groupe <b>"holding"</b></label>
              <br>
              <div style="margin-top:10px;"></div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="groupe_holding_radio" id="holdingOui" value="1" <?php if($holding == 1){ echo "checked";} ?> >
                <label class="form-check-label" for="holdingOui">Oui</label>
              </div>

              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="groupe_holding_radio" id="holdingNon" value="0" <?php if($holding == 0){ echo "checked";} ?>>
                <label class="form-check-label" for="holdingNon">Non</label>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>

      <a
        class="btn btn-light-info w-100 font-medium text-info"
        data-bs-toggle="collapse"
        href="#actionnariat"
        role="button"
        aria-expanded="false"
        aria-controls="actionnariat"
      >
        Actionnariat
      </a>

    </p>

    <div class="collapse" id="actionnariat">
      <div class="card card-body">
        <div class="actionnairesRows">
          <h5>Actionnaires</h5>
        </div>

        <div class="row addActionnaireRow">
          <div class="col-md-2">
            <a href="#" class="btn btn-light-info btnadd" style="margin-top: 10px;" id="addActionnaireRowBtn">Ajouter un actionnaire</a>
          </div>
        </div>
      </div>
    </div>

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

    </p>

    <div class="collapse" id="benificiaireEffectif">
      <div class="card card-body">
        
        <div class="benificiairesRows">
          <h5>Bénificiaires</h5>
        </div>        

        <div class="row addBenificiaireRow">
          <div class="col-md-2">
            <a href="#" class="btn btn-light-info btnadd" style="margin-top: 10px;" id="addBenificiaireRowBtn">Ajouter un bénificiaire</a>
          </div>
        </div>


      </div>
    </div>

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

    </p>

    <div class="collapse" id="administrateursDirigeants">
      <div class="card card-body">
       
        <div class="administrateursRows">
          <h5>Administrateurs / Dirigeants</h5>
        </div>        

        <div class="row addAdministrateurRow">
          <div class="col-md-3">
            <a href="#" class="btn btn-light-info btnadd" style="margin-top: 10px;" id="addAdministrateurRowBtn">Ajouter un Administrateur ou Dirigeant</a>
          </div>
        </div>

      </div>
    </div>

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

    </p>

    <div class="collapse" id="personnesHabilites">
      <div class="card card-body">
        <div class="phabilitesRows">
          <h5>Personnes habilités</h5>
        </div>        

        <div class="row addPHabiliteRow">
          <div class="col-md-3">
            <a href="#" class="btn btn-light-info btnadd" style="margin-top: 10px;" id="addPHabiliteRowBtn">Ajouter une personne habilité</a>
          </div>
        </div>
      </div>
    </div>

      <a
        class="btn btn-light-info w-100 font-medium text-info"
        data-bs-toggle="collapse"
        href="#objetNatureRelationAffaire"
        role="button"
        aria-expanded="false"
        aria-controls="objetNatureRelationAffaire"
      >
        Objet et nature de la relation d'affaire
      </a>

    </p>

    <div class="collapse" id="objetNatureRelationAffaire">
      <div class="card card-body">
        <div class="row">
          <div class="col-md-7">
            <h5>Fréquence des opérations :</h5>
            <div class="row">
              <div class="mb-6">
                <div style="margin-top:10px;"></div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="relation_affaire_radio" id="natureRelation1" value="Quotidienne" checked>
                  <label class="form-check-label" for="natureRelation1">Quotidienne</label>
                </div>

                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="relation_affaire_radio" id="natureRelation2" value="Hebdomadaire">
                  <label class="form-check-label" for="natureRelation2">Hebdomadaire</label>
                </div>

                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="relation_affaire_radio" id="natureRelation3" value="Mensuelle">
                  <label class="form-check-label" for="natureRelation3">Mensuelle</label>
                </div>

                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="relation_affaire_radio" id="natureRelation4" value="Trimestrielle">
                  <label class="form-check-label" for="natureRelation4">Trimestrielle</label>
                </div>

                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="relation_affaire_radio" id="natureRelation5" value="Annuelle">
                  <label class="form-check-label" for="natureRelation5">Annuelle</label>
                </div>

                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="relation_affaire_radio" id="natureRelation6" value="Ponctuelle">
                  <label class="form-check-label" for="natureRelation6">Ponctuelle</label>
                </div>

              </div>
            </div>
          </div>

          <div class="col-md-5">
            <h5>Horizon de placement :</h5>
            <div class="row">
              <div class="mb-6">
                <div style="margin-top:10px;"></div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="horizon_placement_radio" id="horizonPlacement1" value="< 1 an" checked>
                  <label class="form-check-label" for="horizonPlacement1">< 1 an</label>
                </div>

                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="horizon_placement_radio" id="horizonPlacement2" value="Entre 1 et 3 ans">
                  <label class="form-check-label" for="horizonPlacement2">Entre 1 et 3 ans</label>
                </div>

                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="horizon_placement_radio" id="horizonPlacement3" value="Entre 3 et 5 ans">
                  <label class="form-check-label" for="horizonPlacement3">Entre 3 et 5 ans</label>
                </div>

                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="horizon_placement_radio" id="horizonPlacement4" value="< 5 ans">
                  <label class="form-check-label" for="horizonPlacement4">< 5 ans</label>
                </div>

              </div>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-7">
            <h5>Objet de la relation d'affaire :</h5>
            <div class="row">
              <div class="mb-6">

                <div style="margin-top:10px;"></div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input objetrelationcheck" type="checkbox" id="objetRelation1" name="objet_relation[]" value="Assurer des revenus réccurents">
                  <label class="form-check-label" for="objetRelation1">Assurer des revenus réccurents</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input objetrelationcheck" type="checkbox" id="objetRelation2" name="objet_relation[]" value="Profits à moyen et court terme">
                  <label class="form-check-label" for="objetRelation2">Profits à moyen et court terme</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input objetrelationcheck" type="checkbox" id="objetRelation3" name="objet_relation[]" value="Gestion de la trésorerie">
                  <label class="form-check-label" for="objetRelation3">Gestion de la trésorerie</label>
                </div>

              </div>
            </div>
          </div>

          <div class="col-md-5">
            <h5>Compte géré par mandataire :</h5>
            <div class="mb-3 row">
              <div class="mb-4 bt-switch col-2">
                <label> </label>
                <input
                  type="checkbox"
                  data-checked="false"
                  data-on-color="primary"
                  data-off-color="default"
                  data-off-text="Non"
                  data-on-text="Oui"
                  id="mandataire_id"
                  name="mandataire_check"
                />
              </div>
              <div class="mb-3 col-4">
                <input
                  type="text"
                  class="form-control mandat-hide"
                  placeholder="Description"
                  name="mandataire_input"
                />
              </div>
              <div class="mb-3 col-2">
                <label class="mandat-hide">Date fin de mandat</label>
              </div>
              <div class="mb-3 col-4">
                <div class="form-group mandat-hide">
                                <input type="date" class="form-control" value="" placeholder="Date" name="mandataire_fin_mandat_date">
                            </div>
              </div>
            </div>
            
          </div>
        </div>

      </div>
    </div>

      <a
        class="btn btn-light-info w-100 font-medium text-info"
        data-bs-toggle="collapse"
        href="#profilRisque"
        role="button"
        aria-expanded="false"
        aria-controls="profilRisque"
      >
        Profil risque
      </a>

    </p>

    <div class="collapse" id="profilRisque">
      <div class="card card-body">
        <div class="row">
          <div class="col-md-6">
            <h5>Département en charge de la gestion des placements / investissements</h5>
            <div class="mb-3 row">
              <div class="mb-4 bt-switch col-4">
                <label> </label>
                <input
                  type="checkbox"
                  data-checked="false"
                  data-on-color="primary"
                  data-off-color="default"
                  data-off-text="Non"
                  data-on-text="Oui"
                  id="departement_gestion_id"
                  name="departement_en_charge_check"
                />
              </div>
              <div class="mb-3 col-8">
                <select class="form-control hidden-select" id="departement_gestion_data_id" name="departement_gestion_input">
                  <option>La part du portfeuille de valeurs mobilières</option>
                  <option>Inférieur à 5%</option>
                  <option>Entre 5% et 10%</option>
                  <option>Entre 10% et 25%</option>
                  <option>Entre 25% et 50%</option>
                  <option>Supérieure à 50%</option>
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <h5>Les instruments financiers souhaités</h5>
            <div class="mb-3 row">
              <div class="mb-4 col-12">
                <div class="mb-6">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="instruments_souhaites_input[]" id="instrumentsSouhaites1" value="OPCVM Monétaires">
                    <label class="form-check-label" for="instrumentsSouhaites1">OPCVM Monétaires</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="instruments_souhaites_input[]" id="instrumentsSouhaites2" value="OPCVM Obligataires">
                    <label class="form-check-label" for="instrumentsSouhaites2">OPCVM Obligataires</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="instruments_souhaites_input[]" id="instrumentsSouhaites3" value="OPCVM Actions">
                    <label class="form-check-label" for="instrumentsSouhaites3">OPCVM Actions</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="instruments_souhaites_input[]" id="instrumentsSouhaites4" value="OPCVM Diversifiés">
                    <label class="form-check-label" for="instrumentsSouhaites4">OPCVM Diversifiés</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="instruments_souhaites_input[]" id="instrumentsSouhaites5" value="Bons de Trésor">
                    <label class="form-check-label" for="instrumentsSouhaites5">Bons de Trésor</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="instruments_souhaites_input[]" id="instrumentsSouhaites6" value="Titres de dette privé">
                    <label class="form-check-label" for="instrumentsSouhaites6">Titres de dette privé</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="instruments_souhaites_input[]" id="instrumentsSouhaites7" value="Actions">
                    <label class="form-check-label" for="instrumentsSouhaites7">Actions</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Autres"
                      style="display: inline-block;"
                      name="instruments_souhaites_input[]"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>


        </div>

        <div class="row">
          <div class="col-md-6">
            <h5>Le niveau de risque toléré</h5>
            <div class="mb-3 row">
              <div class="mb-4 col-12">
                <div class="mb-6">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="nrTolere1" value="Faible" name="niveau_risque_tolere_radio">
                    <label class="form-check-label" for="nrTolere1">Faible (Stratégie défensive)</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="nrTolere2" value="Moyen" name="niveau_risque_tolere_radio">
                    <label class="form-check-label" for="nrTolere2">Moyen (Stratégie équilibrée)</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="nrTolere3" value="Elevé" name="niveau_risque_tolere_radio">
                    <label class="form-check-label" for="nrTolere3">Elevé (Stratégie agressive)</label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <h5>Années d'investissement dans les produits financiers</h5>
            <div class="mb-3 row">
              <div class="mb-4 col-12">
                <div class="mb-6">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="anneesProduitsFinanciers1" value="Jamais" name="annees_investissement_produits_finaniers">
                    <label class="form-check-label" for="anneesProduitsFinanciers1">Jamais</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="anneesProduitsFinanciers2" value="Jusqu’à 1 an" name="annees_investissement_produits_finaniers">
                    <label class="form-check-label" for="anneesProduitsFinanciers2">Jusqu’à 1 an</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="anneesProduitsFinanciers3" value="Entre 1 et 5 ans" name="annees_investissement_produits_finaniers">
                    <label class="form-check-label" for="anneesProduitsFinanciers3">Entre 1 et 5 ans</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="anneesProduitsFinanciers4" value="Plus que 5 ans" name="annees_investissement_produits_finaniers">
                    <label class="form-check-label" for="anneesProduitsFinanciers4">Plus que 5 ans</label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <h5>Transactions en moyenne réalisés sur le marché courant 2 dernières années</h5>
            <div class="mb-3 row">
              <div class="mb-4 col-12">
                <div class="mb-6">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="transactions2annees1" value="Aucune" name="transactions_courant_2_annees">
                    <label class="form-check-label" for="transactions2annees1">Aucune</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="transactions2annees2" value="Moins de 30" name="transactions_courant_2_annees">
                    <label class="form-check-label" for="transactions2annees2">Moins de 30</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="transactions2annees3" value="Plus de 30" name="transactions_courant_2_annees">
                    <label class="form-check-label" for="transactions2annees3">Plus de 30</label>
                  </div>
                </div>
              </div>
            </div>
          </div>


        </div>


      </div>
    </div>

      <a
        class="btn btn-light-info w-100 font-medium text-info"
        data-bs-toggle="collapse"
        href="#LBTFT"
        role="button"
        aria-expanded="false"
        aria-controls="LBTFT"

        style="display: none;"
      >
        Politique, pratique et procédure de LBC/FT
      </a>

    </p>

    <div class="collapse" id="LBTFT">
      <div class="card card-body">
        Politique, pratique et procédure de LBC/FT
      </div>
    </div>

    <div class="buttonsSaveForm" style="position: fixed;z-index: 9999;bottom: -2px;right: 1.5em;">
      

          <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
            <div class="btn-group mb-2" role="group" aria-label="First group">
              <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" title="Imprimer" disabled>
                <i class="ti ti-printer fs-7"></i>
              </button>
              <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" title="Supprimer">
                <i class="ti ti-trash fs-7"></i>
              </button>
              <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" title="Modifier" id="edit_form">
                <i class="ti ti-edit fs-7"></i>
              </button>
              <button type="button" type="submit" class="btn btn-secondary" data-bs-toggle="tooltip" title="Enregistrer" id="save_form">
                <i class="ti ti-device-floppy fs-7"></i>
              </button>
            </div>
          </div>

    </div>


  </div>
  </form>
  </div>
</div>


<style>
  .contactRowInfos, .CBancairesRowInfos 
  {
    margin-top: 10px !important;
  }
</style>