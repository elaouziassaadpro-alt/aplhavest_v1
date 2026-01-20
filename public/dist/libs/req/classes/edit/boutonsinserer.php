<?php
namespace Edit;

class boutonsInserer {
    public function render() {
        ?>
        
        <p>
            <div class="buttonsSaveForm" style="position: fixed;z-index: 9999;bottom: -2px;right: 1.5em;">
              

                  <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group mb-2" role="group" aria-label="First group">
                      <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" title="Réinitialiser" id="clear_form" disabled>
                        <i class="ti ti-eraser fs-7"></i>
                      </button>
                      <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" title="Imprimer" id="print_form" disabled>
                        <i class="ti ti-printer fs-7"></i>
                      </button>
                      <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" title="Modifier" id="edit_form">
                        <i class="ti ti-edit fs-7"></i>
                      </button>
                      <button type="button" type="submit" class="btn btn-secondary" data-bs-toggle="tooltip" title="Enregistrer" id="save_form">
                        <i class="ti ti-device-floppy fs-7"></i>
                      </button>
                    </div>
                  </div>

            </div>
        </p>

        <?php
    }
}