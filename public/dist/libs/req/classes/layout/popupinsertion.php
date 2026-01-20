<?php
namespace Layout;

class popupInsertion {
    public function render() {
        ?>
        
        <div class="modal fade" id="codes_erreurs_modal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-md">
            <div class="modal-content modal-filled">
              <div class="modal-body p-4 text-center">
                <i id="erreurs_icon" class="ti fs-7"></i>
                <h4 class="mt-2" id="erreurs_titre"></h4>
                <p class="mt-3" id="erreurs_content"></p>
                <button type="button" class="btn btn-outline-dark my-2 btnmodal" data-bs-dismiss="modal">
                  D'accord
                </button>
              </div>
            </div>
          </div>
        </div>


        <?php
    }
}