<?php
namespace Layout;

class actionnaires {
    public function render() {
        ?>
        
        <p>
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

            <div class="collapse" id="actionnariat">
              <div class="card card-body">
                <div class="actionnairesRows">
                  <h5>Actionnaires</h5>
                </div>

                <div class="row addActionnaireRow">
                  <div class="col-md-2">
                    <a href="#" class="btn btn-light-info" style="margin-top: 10px;" id="addActionnaireRowBtn">Ajouter un actionnaire</a>
                  </div>
                </div>
              </div>
            </div>
        </p>

        <?php
    }
}