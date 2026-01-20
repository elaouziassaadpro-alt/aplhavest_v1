<?php
namespace Layout;

class benificiaireseffectifs {
    public function render() {
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