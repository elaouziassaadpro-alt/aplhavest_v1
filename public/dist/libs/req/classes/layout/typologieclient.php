<?php
namespace Layout;

class typologieClient {
    public function render() {
        ?>
        
          <p>
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

            <div class="collapse" id="typologieClient">
              <div class="card card-body">
                <div class="row">
                  <div class="col-md-2">
                    <div class="mb-3">
                      <label>Secteur d'activité</label>
                      <select class="form-control" id="secteur_dactivite_id" style="width: 100%;" name="secteur_activite_input">
                        <option value="10000">Selectionnez une activité</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="mb-3">
                      <label>Segment</label>
                      <select class="form-control" id="segments_id" style="width: 100%;" name="segment_input">
                        <option value="10000">Selectionnez un segment </option>
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
                          <option value="10000">Pays</option>
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
        </p>

        <?php
    }
}