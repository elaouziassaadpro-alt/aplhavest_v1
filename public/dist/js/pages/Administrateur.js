
function initCreatePageLogic() {

    if (typeof $ === 'undefined' || typeof window.APP_DATA === 'undefined') {
        return;
    }

    let ppesList = '';
    window.APP_DATA.ppes.forEach(ppe => {
        ppesList += `<li data-value="${ppe.id}">${ppe.libelle}</li>`;
    });

    let paysList = '';
    window.APP_DATA.pays.forEach(pay => {
        paysList += `<li data-value="${pay.id}">${pay.libelle}</li>`;
    });

    $('#addAdministrateurRowBtn').click(function(e){
        e.preventDefault();

        let rowsCount = $(".administrateurRowInfos").length + 1;
        if(rowsCount > 5) {
            alert("Vous ne pouvez ajouter que 5 Administrateurs ou dirigeants !");
            return;
        }

        let contactRow = `
        <div class="row mt-3  administrateurRowInfos administrateurRow${rowsCount}">


            <div class="col-md-2">
                <input type="text" class="form-control" placeholder="Nom" name="noms_administrateurs[]" required>
            </div>
            <div class="col-md-1">
                <input type="text" class="form-control" placeholder="Prénom" name="prenoms_administrateurs[]">
            </div>
            <div class="col-md-2">
                <div class="custom-select-wrapper">
                                <input type="text" class="form-control pays-search" 
                                    placeholder="Sélectionnez un pays">
                                <ul class="custom-dropdown">${paysList}</ul>
                                <input type="hidden" name="pays_administrateurs[]" required>
                            </div>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="dates_naissance_administrateurs[]">
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" placeholder="CIN / Passeport" name="cins_administrateurs[]">
            </div>
            <div class="col-md-2">
                <div class="custom-select-wrapper">
                                <input type="text" class="form-control pays-search" 
                                    placeholder="Sélectionnez une nationalité">
                                <ul class="custom-dropdown">${paysList}</ul>
                                <input type="hidden" name="nationalites_administrateurs[]" required>
                            </div>
            </div>
            <div class="col-md-1 text-center">
                <a href="#" class="deleteAdministrateurRow " data-rowid="${rowsCount}">
                    <i class="ti ti-trash h5"></i>
                </a>
            </div>

            <!-- PPE ------------------------------------------------------------------------------->
            <div class="col-md-1 d-flex flex-column align-items-start">
                <label for="administrateur_ppe_id_${rowsCount}">PPE</label>
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input adm_ppe" id="administrateur_ppe_id_${rowsCount}" name="ppes_administrateurs_check[]">
                    <label class="form-check-label" for="administrateur_ppe_id_${rowsCount}">
                        <span id="label_ppe_${rowsCount}">Non</span>
                    </label>
                </div>
                </div>
                <div class="col-md-2 d-flex flex-column align-items-start">
                <div class="hidden-select" id="administrateur_ppe_data_id_${rowsCount}" style="display:none;width: -webkit-fill-available;">
                    <label>Libelle PPE</label>
                    <div class="custom-select-wrapper">
                                <input type="text" class="form-control ppe-search" 
                                    placeholder="Sélectionnez une PPE">
                                <ul class="custom-dropdown">${ppesList}</ul>
                                <input type="hidden" name="ppes_administrateurs_input[]">
                            </div>
                </div>
            </div>
            
            <!-- Lien PPE -->
                <div class="col-md-1 d-flex flex-column align-items-start">
                    <div class="text-left mb-2">
                        <label for="administrateur_ppe_lien_id_${rowsCount}">Lien PPE</label>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input adm_lien" id="administrateur_ppe_lien_id_${rowsCount}" name="ppes_lien_administrateurs_check[]">
                            <label class="form-check-label" for="administrateur_ppe_lien_id_${rowsCount}">
                                <span id="label_lien_${rowsCount}">Non</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 d-flex flex-column align-items-start">
            <div class="hidden-select " id="administrateur_ppe_lien_data_id_${rowsCount}" style="display:none;width: -webkit-fill-available;">
                        <label>Libelle PPE</label>
                        <div class="custom-select-wrapper">
                                <input type="text" class="form-control ppe-search" 
                                    placeholder="Sélectionnez une PPE">
                                <ul class="custom-dropdown">${ppesList}</ul>
                                <input type="hidden" name="ppes_lien_administrateurs_input[]">
                            </div>
                    </div>
            </div>
            <!-- End PPE --------------------------------------------------------------------------->
            <div class="col-md-2">
                <label>Fonction</label>
                <input type="text" class="form-control" name="fonctions_administrateurs[]" placeholder="Fonction">
            </div>
            <div class="col-md-1">
                    <div class="mb-3">
                      <label style="width:100%;"></label>
                      <input type="file" 
                               id="cin_file_adm_` + rowsCount + `"
                               name="cin_administrateurs[]" 
                               style="display:none;" 
                               accept=".pdf,.jpg,.png" />

                        <label for="cin_file_adm_` + rowsCount + `" class="btn btn-primary w-100" data-bs-toggle="tooltip" data-bs-original-title="Joindre CIN">
                          <i class="ti ti-upload"></i>&nbsp; CIN
                        </label>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="mb-3">
                      <label style="width:100%;"></label>
                      <input type="file" 
                               id="pvn_file_adm_` + rowsCount + `"
                               name="pvn_administrateurs[]" 
                               style="display:none;" 
                               accept=".pdf,.jpg,.png" />

                        <label for="pvn_file_adm_` + rowsCount + `" class="btn btn-primary w-100" data-bs-toggle="tooltip" data-bs-original-title="Joindre CIN">
                          <i class="ti ti-upload"></i>&nbsp; PV nomination
                        </label>
                    </div>
                  </div>
                <hr class="mt-3 mb-3">
        </div>
  
        `;


        $(".administrateursRows").append(contactRow);
    });

    // Toggle PPE / Lien PPE
    $(document).on('change', '.adm_ppe', function() {
        let row = this.id.split('_').pop();
        $('#administrateur_ppe_data_id_' + row).toggle(this.checked);
        $('#label_ppe_' + row).text(this.checked ? 'Oui' : 'Non');
    });

    $(document).on('change', '.adm_lien', function() {
        let row = this.id.split('_').pop();
        $('#administrateur_ppe_lien_data_id_' + row).toggle(this.checked);
        $('#label_lien_' + row).text(this.checked ? 'Oui' : 'Non');
    });
    // SELECT DROPDOWN
    $(document).on('click', '.custom-dropdown li', function () {

        let wrapper = $(this).closest('.custom-select-wrapper');

        wrapper.find('input[type="text"]').val($(this).text());
        wrapper.find('input[type="hidden"]').val($(this).data('value'));

        wrapper.find('.custom-dropdown').hide();
    });

    // SHOW DROPDOWN + RESET FILTER
    $(document).on('focus', '.pays-search, .ppe-search', function () {

        let dropdown = $(this).siblings('.custom-dropdown');

        dropdown.find('li').show();
        dropdown.show();
    });

    // FILTER
    $(document).on('keyup', '.pays-search, .ppe-search', function () {

        let value = $(this).val().toLowerCase();
        let dropdown = $(this).siblings('.custom-dropdown');

        dropdown.find('li').each(function () {

            let text = $(this).text().toLowerCase();

            $(this).toggle(value === '' || text.startsWith(value));
        });
    });

    // CLICK OUTSIDE
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.custom-select-wrapper').length) {
            $('.custom-dropdown').hide();
        }
    });

    // Delete row (existing)
    $(document).on('click', '.deleteAdministrateurRow', function(e){
        e.preventDefault();
        $(this).closest('.administrateurRowInfos').remove();
    });
};
$(document).ready(function () {
    initCreatePageLogic();
});
    /* =========================
       🗑 DELETE SELECTION & LIST LOGIC (Merged)
    ========================== */
    const checkboxes = () => document.querySelectorAll('.admin-chkbox');

    // Check All
    $('#admin-check-all').on('change', function() {
        $('.admin-chkbox').prop('checked', this.checked);
        toggleBulkAction();
    });

    // Single Check
    $(document).on('change', '.admin-chkbox', function() {
        if ($('#admin-check-all').length) {
            $('#admin-check-all').prop('checked', $('.admin-chkbox:checked').length === $('.admin-chkbox').length);
        }
        toggleBulkAction();
    });

    function toggleBulkAction() {
        const anyChecked = $('.admin-chkbox:checked').length > 0;
        $('#bulk-action').toggle(anyChecked);
    }

    // Search
    $('#input-search').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Bulk Delete
    $(document).on('click', '#delete-selection', function(e) {
        e.preventDefault();
        const btn = $(this);

        if (btn.data('processing')) return;
        
        if (!confirm('Confirmer la suppression des administrateurs sélectionnés ?')) return;

        const selectedIds = $('.admin-chkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            alert('Veuillez sélectionner au moins un administrateur.');
            return;
        }

        // Get routes from window object
        const url = window.administrateurRoutes ? window.administrateurRoutes.bulkDelete : '';
        const token = window.administrateurRoutes ? window.administrateurRoutes.csrfToken : '';

        if (!url || !token) {
             alert('Erreur: Configuration missing.');
             return;
        }

        btn.data('processing', true);

        $.ajax({
            url: url,
            type: 'POST',
            data: JSON.stringify({ ids: selectedIds }),
            contentType: 'application/json',
            headers: { 'X-CSRF-TOKEN': token },
            success: function(data) {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            },
            error: function() {
                alert('Erreur lors de la suppression');
            },
            complete: function() {
                btn.data('processing', false);
            }
        });
    });

