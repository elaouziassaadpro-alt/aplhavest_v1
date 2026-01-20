<?php
namespace Layout;

class coordonneesbancaires {
    public function render() {
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