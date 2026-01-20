<?php
namespace Layout;

class politiquePratique {
    public function render() {
        ?>
        
        <p>
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

            <div class="collapse" id="LBTFT">
              <div class="card card-body">
                Politique, pratique et procédure de LBC/FT
              </div>
            </div>
        </p>

        <?php
    }
}