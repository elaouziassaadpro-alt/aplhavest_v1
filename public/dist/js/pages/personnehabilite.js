/**
 * JavaScript for Personnes Habilitées
 * Handles both the Create/Edit form (jQuery) and the Index page (Vanilla JS)
 */

// =========================================================================
// CREATE / EDIT PAGE LOGIC (jQuery)
// =========================================================================
function initCreatePageLogic() {

    if (typeof $ === 'undefined' || typeof window.APP_DATA === 'undefined') {
        return;
    }

    let ppesList = '';
    window.APP_DATA.ppes.forEach(ppe => {
        ppesList += `<li data-value="${ppe.id}">${ppe.libelle}</li>`;
    });

    let paysList = '';
    if (window.APP_DATA.pays) {
        window.APP_DATA.pays.forEach(p => {
            paysList += `<li data-value="${p.id}">${p.libelle}</li>`;
        });
    }

    $('#addPHabiliteRowBtn').click(function (e) {

        e.preventDefault();

        let rowsCount = $(".phabiliteRowInfos").length + 1;

        if (rowsCount > 5) {
            alert("Maximum 5 personnes habilitées");
            return;
        }

        let row = `
        <div class="row mt-3 phabiliteRowInfos">

            <div class="col-md-2">
                <label>Nom </label>
                <input type="text" class="form-control" placeholder="Nom" name="noms_habilites[]">
            </div>
            <div class="col-md-2">
                <label>Prénom</label>
                <input type="text" class="form-control" placeholder="Prénom" name="prenoms_habilites[]">
            </div>
            <div class="col-md-2">
                <label>CIN</label>
                <input type="text" class="form-control" placeholder="N° CIN / Passeport" name="cin_habilites[]">
            </div>
            <div class="col-md-1 mt-4">
                <input type="file" id="cin_file_hab_${rowsCount}" name="cin_habilites_file[]" style="display:none;" accept=".pdf,.jpg,.png">
                <label for="cin_file_hab_${rowsCount}" class="btn btn-primary w-100"><i class="ti ti-upload"></i>&nbsp; CIN</label>
            </div>
            <div class="col-md-2">
                <div class="custom-select-wrapper">
                    <label>Nationalité</label>
                    <input type="text" class="form-control pays-search" placeholder="Chercher...">
                    <ul class="custom-dropdown">${paysList}</ul>
                    <input type="hidden" name="nationalites_habilites[]">
                </div>
            </div>
            
            <div class="col-md-2">
                <label>Fonction</label>
                <input type="text" class="form-control" placeholder="Fonction" name="fonctions_habilites[]">
            </div>
            <div class="col-md-1 mt-4">
                <a href="#" class="deletePHabiliteRow " data-rowid="${rowsCount}"><i class="ti ti-trash h5"></i></a>
            </div>
            <div class="col-md-2 mt-4">

                <input type="file" id="hab_file_hab_${rowsCount}" name="hab_habilites[]" style="display:none;" accept=".pdf,.jpg,.png">
                <label for="hab_file_hab_${rowsCount}" class="btn btn-primary w-100"><i class="ti ti-upload"></i>&nbsp; Habilitation</label>
            </div>

            <!-- PPE -->
            <div class="col-md-1">
                <label>PPE</label>
                <div class="form-check form-switch">
                <input type="hidden" name="ppes_habilites_check[]" value="0" id="habilite_ppe2_id_${rowsCount}">
                    <input type="checkbox" class="form-check-input adm_ppe" id="habilite_ppe_id_${rowsCount}">
                    <label><span id="label_ppe_${rowsCount}">Non</span></label>
                </div>
            </div>

            <div class="col-md-2" id="habilite_ppe_data_id_${rowsCount}" style="display:none;">
                <div class="custom-select-wrapper">
                    <label>Libellé PPE</label>
                    <input type="text" class="form-control ppe-search" placeholder="Libellé PPE">
                    <ul class="custom-dropdown">${ppesList}</ul>
                    <input type="hidden" name="ppes_habilites_input[]">
                </div>
            </div>

            <!-- LIEN PPE -->
            <div class="col-md-1">
                <label>Lien PPE</label>
                <div class="form-check form-switch">
            <input type="hidden" name="ppes_lien_habilites_check[]" value="0" id="habilite_lien2_id_${rowsCount}">

                    <input type="checkbox" class="form-check-input adm_lien" id="habilite_ppe_lien_id_${rowsCount}">
                    <label><span id="label_lien_${rowsCount}">Non</span></label>
                </div>
            </div>

            <div class="col-md-2" id="habilite_ppe_lien_data_id_${rowsCount}" style="display:none;">
                <div class="custom-select-wrapper">
                    <label>Type lien PPE</label>
                    <input type="text" class="form-control ppe-search" placeholder="Type lien PPE">
                    <ul class="custom-dropdown">${ppesList}</ul>
                    <input type="hidden" name="ppes_lien_habilites_input[]">
                </div>
            </div>
            <hr class="mt-3">
        </div>`;

        $(".personneHabiliteRows").append(row);
    });

    // ✅ TOGGLE PPE
    $(document).on('change', '.adm_ppe', function () {

        let row = this.id.split('_').pop();

        $('#habilite_ppe_data_id_' + row).toggle(this.checked);
        $('#label_ppe_' + row).text(this.checked ? 'Oui' : 'Non');

        // ✅ IMPORTANT
        $('#habilite_ppe2_id_' + row).val(this.checked ? 1 : 0);
    });

    // ✅ TOGGLE LIEN PPE
    $(document).on('change', '.adm_lien', function () {

        let row = this.id.split('_').pop();

        $('#habilite_ppe_lien_data_id_' + row).toggle(this.checked);
        $('#label_lien_' + row).text(this.checked ? 'Oui' : 'Non');

        // ✅ IMPORTANT
        $('#habilite_lien2_id_' + row).val(this.checked ? 1 : 0);
    });

    // DELETE ROW
    $(document).on('click', '.deletePHabiliteRow', function (e) {
        e.preventDefault();
        $(this).closest('.phabiliteRowInfos').remove();
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

// =========================================================================
// INDEX PAGE LOGIC (Vanilla JS)
// =========================================================================
var PersonneHabiliteIndex = {
    init: function () {
        if (!document.getElementById('check-all') && !document.getElementById('input-search')) {
            return;
        }
        this.initEvents();
    },
    initEvents: function () {
        const searchInput = document.getElementById('input-search');
        const checkAll = document.getElementById('check-all');
        const bulkAction = document.getElementById('bulk-action');
        const deleteBtn = document.getElementById('delete-selection');
        const checkboxes = () => document.querySelectorAll('.personne-chkbox');
        const rows = document.querySelectorAll('tbody tr');

        // SEARCH
        if (searchInput) {
            searchInput.addEventListener('keyup', function () {
                const value = this.value.toLowerCase();
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(value) ? '' : 'none';
                });
            });
        }

        // CHECK ALL
        if (checkAll) {
            checkAll.addEventListener('change', function () {
                checkboxes().forEach(cb => cb.checked = this.checked);
                toggleBulkAction();
            });
        }

        // SINGLE CHECK (Delegated)
        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('personne-chkbox')) {
                if (checkAll) {
                    checkAll.checked = Array.from(checkboxes()).every(cb => cb.checked);
                }
                toggleBulkAction();
            }
        });

        function toggleBulkAction() {
            const hasChecked = Array.from(checkboxes()).some(cb => cb.checked);
            if (bulkAction) {
                bulkAction.style.display = hasChecked ? 'block' : 'none';
            }
        }

        // DELETE MULTIPLE
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function () {
                const selectedIds = Array.from(checkboxes())
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                if (selectedIds.length === 0) {
                    alert('Veuillez sélectionner au moins une personne.');
                    return;
                }

                if (!confirm('Confirmer la suppression des personnes sélectionnées ?')) return;

                fetch(window.personnesRoutes.bulkDelete, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.personnesRoutes.csrfToken
                    },
                    body: JSON.stringify({ ids: selectedIds })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Erreur lors de la suppression');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Erreur lors de la suppression');
                });
            });
        }

        // DELETE SINGLE (Delegated)
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('delete-single')) {
                const id = e.target.dataset.id;
                if (!confirm('Confirmer la suppression ?')) return;

                fetch(`${window.personnesRoutes.destroy}/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': window.personnesRoutes.csrfToken }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) location.reload();
                    else alert(data.message || 'Erreur lors de la suppression');
                })
                .catch(err => {
                    console.error(err);
                    alert('Erreur lors de la suppression');
                });
            }
        });
    }
};

// =========================================================================
// INITIALIZATION
// =========================================================================
document.addEventListener('DOMContentLoaded', function () {
    PersonneHabiliteIndex.init();
});
