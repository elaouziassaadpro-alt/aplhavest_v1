<?php
namespace Edit;

class situationFinanciere {
    public function render(
      $capitalSocial,
      $origineFonds,
      $chiffreAffaires,
      $resultatsNet,
      $holding,
      $paysOrigineFonds,
      $paysOrigineFondsLibelle) {
        ?>
        
        <p>

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
                    value="<?php echo $capitalSocial;?>"
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
                    value="<?php echo $origineFonds;?>"
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
                  >
                    <?php if($paysOrigineFonds != 10000){echo "<option value=" . $paysOrigineFonds . " selected>".$paysOrigineFondsLibelle."</option>";}else{
                      echo '<option value="10000">Choisissez une option</option>';
                    }?>
                  </select>
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
                    <input class="form-check-input" type="radio" name="chiffre_affaires_radio" id="caM1" value="< 5 M.DH" <?php if($chiffreAffaires === "< 5 M.DH"){echo "checked";}?>>
                    <label class="form-check-label" for="caM1">< 5 M.DH</label>
                  </div>

                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="chiffre_affaires_radio" id="caM2" value="5 M.DH < CA < 10 M.DH" <?php if($chiffreAffaires === "5 M.DH < CA < 10 M.DH"){echo "checked";}?>>
                    <label class="form-check-label" for="caM2">5 M.DH < CA < 10 M.DH</label>
                  </div>

                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="chiffre_affaires_radio" id="caM3" value="> 10 M.DH" <?php if($chiffreAffaires === "> 10 M.DH"){echo "checked";}?>>
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
                    value="<?php echo $resultatsNet;?>"
                  />
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-6">
                  <label>Appartient à un groupe <b>"holding"</b></label>
                  <br>
                  <div style="margin-top:10px;"></div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="groupe_holding_radio" id="holdingOui" value="1" <?php if($holding === 1){echo "checked";} ?>>
                    <label class="form-check-label" for="holdingOui">Oui</label>
                  </div>

                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="groupe_holding_radio" id="holdingNon" value="0" <?php if($holding === 0){echo "checked";} ?>>
                    <label class="form-check-label" for="holdingNon">Non</label>
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