<p>
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
        </p>