<?php
namespace Layout;

class statutfatca {
    public function render() {
        ?>
        
        <p>
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
                        <input name="fichiers[fatca]" type="file" id="fatca_hidden_input" class="fichiers_caches" />
                        <!-- Zone de drop, avec un ID spécifique -->
                        <div id="dropzone-fichierFATCA" class="dropzone us_entity_data_id" style="display:none;">
                          <div class="fallback">
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
                          name="giin_inputs"
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

        <?php
    }
}