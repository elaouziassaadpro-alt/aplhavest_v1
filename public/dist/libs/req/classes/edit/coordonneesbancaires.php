<?php
namespace Edit;

class coordonneesbancaires {
    public function render($cBancaires) {
        ?>
        
          <p>
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

          

          <div class="collapse" id="coordonneesBancaires">
            <div class="card card-body">

              <div class="CBancairesRows">
                <?php

                $rowsCount = 0;

                $nbrbanques = count($cBancaires);

                foreach ($cBancaires as $cBancaire)
                {
                  $rowsCount++; 
                  ?>

                    <div class="row CBancairesRowInfos CBancairesRow<?php echo $rowsCount;?>">

                      <div class="col-md-3">
                        <select class="form-control banques" name="noms_banques[]">
                          <option value="<?php echo $cBancaire['idb'];?>"><?php echo $cBancaire['nomBanque'];?></option>
                        </select>
                      </div>
                      <div class="col-md-3">
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Agence"
                          name="agences_banques[]"
                          value="<?php echo $cBancaire['agence']; ?>"
                        /> 
                      </div>
                      <div class="col-md-2">
                        <select class="form-control villes_banques" name="villes_banques[]">
                          <option value="<?php echo $cBancaire['idVBanque'];?>"><?php echo $cBancaire['nomVBanque']; ?></option>
                        </select>
                      </div>
                      <div class="col-md-3">
                        <input
                          type="text"
                          class="form-control"
                          placeholder="RIB"
                          name="ribs_banques[]"
                          value="<?php echo $cBancaire['rib']; ?>"
                        /> 
                      </div>
                      <div class="col-md-1">
                        <a href="#" style="line-height: 3;<?php if($rowsCount < $nbrbanques){ echo "display:none;"; }?>" class="deleteCBancairesRow deleteCBancairesRow<?php echo $rowsCount;?>" data-rowID="<?php echo $rowsCount;?>"><center><i class="ti ti-trash w-100 h5"></i></center></a>
                      </div>
                    </div>

                  <?php
                }

                ?>
              </div>


              <div class="row addCBancairesRow">
                <div class="col-md-2">
                  <a href="#" class="btn btn-light-info" style="margin-top: 10px;" id="addCBancairesRowBtn">Ajouter un compte</a>
                </div>
              </div>

            </div>
          </div>
        </p>

        <?php
    }
}