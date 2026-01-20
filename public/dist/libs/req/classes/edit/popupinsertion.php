<?php
namespace Edit;

class popupInsertion {
    public function render() {
        ?>
        
        <div id="codes_erreurs" class="card-danger">
          <div
            class="modal fade"
            id="codes_erreurs_modal"
            tabindex="-1"
            aria-labelledby="vertical-center-modal"
            aria-hidden="true"
          >
            <div class="modal-dialog modal-md">
              <div
                class="modal-content modal-filled bg-light-danger"
              >
                <div class="modal-body p-4">
                  <div class="text-center text-danger">
                    <i class="ti ti-hexagon-letter-x fs-7"></i>

                    <h4 class="mt-2" id="erreurs_titre"></h4>
                    <p class="mt-3" id="erreurs_content"></p>

                    <button
                      type="button"
                      class="btn btn-outline-dark my-2"
                      data-bs-dismiss="modal"
                    >
                      D'accord
                    </button>
                  </div>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
          </div>
        </div>

        <?php
    }
}