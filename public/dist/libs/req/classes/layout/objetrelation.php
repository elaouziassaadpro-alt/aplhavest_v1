<?php
namespace Layout;

class objetRelation {
    public function render() {
        ?>
        
        <p>
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

            <div class="collapse" id="objetNatureRelationAffaire">
              <div class="card card-body">
                <div class="row">
                  <div class="col-md-7">
                    <h5>Fréquence des opérations :</h5>
                    <div class="row">
                      <div class="mb-6">
                        <div style="margin-top:10px;"></div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="relation_affaire_radio" id="natureRelation1" value="Quotidienne">
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
                          <input class="form-check-input" type="radio" name="horizon_placement_radio" id="horizonPlacement1" value="< 1 an">
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
                          data="false"
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
                                        <input type="date" class="form-control" value="1900-01-01" placeholder="Date" name="mandataire_fin_mandat_date">
                                    </div>
                      </div>
                    </div>

                    <div class="mb-3 row" style="margin-top:-30px">
                      <div class="mb-4 col-4">
                        <label for="mandat_file" class="btn btn-primary mandat-hide" style="width:100%;"><i class="ti ti-upload"></i>&nbsp; Mandat pouvoir</label>
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