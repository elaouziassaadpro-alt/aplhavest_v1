/**
 * JavaScript for Benificiaire Effectif
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
    window.APP_DATA.pays.forEach(pay => {
        paysList += `<li data-value="${pay.id}">${pay.libelle}</li>`;
    });

    // ADD ROW
    $('#addBenificiaireRowBtn').on('click', function (e) {

        e.preventDefault();

        let rowsCount = $('.benificiaireRowInfos').length + 1;

        if (rowsCount > 5) {
            alert("Vous ne pouvez ajouter que 5 Bénéficiaires Effectifs !");
            return;
        }

        let contactRow = `
                    <div class="row benificiaireRowInfos mb-3">

                        <div class="col-md-2">
                            <label>Nom / Raison sociale</label>
                            <input type="text" class="form-control" 
                                name="noms_rs_benificiaires[]" 
                                placeholder="Nom / Raison sociale">
                        </div>

                        <div class="col-md-2">
                            <label>Prénom</label>
                            <input type="text" class="form-control" 
                                name="prenoms_benificiaires[]" 
                                placeholder="Prénom">
                        </div>

                        <div class="col-md-2">
                            <label>Pays de naissance</label>
                            <div class="custom-select-wrapper">
                                <input type="text" class="form-control pays-search" 
                                    placeholder="Sélectionnez un pays">
                                <ul class="custom-dropdown">${paysList}</ul>
                                <input type="hidden" name="pays_naissance_benificiaires[]" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label>Date de naissance</label>
                            <input type="date" class="form-control" 
                                name="dates_naissance_benificiaires[]">
                        </div>

                        <div class="col-md-1">
                            <label>CIN</label>
                            <input type="text" class="form-control" 
                                name="identite_benificiaires[]" 
                                placeholder="CIN / Passeport">
                        </div>

                        <div class="col-md-2">
                            <label>Nationalité</label>
                            <div class="custom-select-wrapper">
                                <input type="text" class="form-control pays-search" 
                                    placeholder="Sélectionnez une nationalité">
                                <ul class="custom-dropdown">${paysList}</ul>
                                <input type="hidden" name="nationalites_benificiaires[]" required>
                            </div>
                        </div>

                        <div class="col-md-1 text-center mt-4">
                            <a href="#" class="deleteBenificiaireRow text-danger">
                                <i class="ti ti-trash h5"></i>
                            </a>
                        </div>

                        <!-- PPE -->
                        <div class="col-md-1">
                        <label>PPE</label>
                        <div class="form-check form-switch">
                            <!-- Hidden input pour envoyer la valeur au serveur -->
                            <input type="hidden" id="hidden_ben_ppe_${rowsCount}" name="benificiaires_ppe_check[]" value="0">
                            
                            <!-- Checkbox visible -->
                            <input class="form-check-input ben_ppe" 
                                type="checkbox" 
                                id="ben_ppe_${rowsCount}" 
                                onchange="togglePPE(${rowsCount})">

                            <label class="form-check-label">
                                <span id="label_ppe_${rowsCount}">Non</span>
                            </label>
                        </div>
                    </div>
                    
                        <div class="col-md-3" style="display:none;" id="ppe_block_${rowsCount}">
                            <label>Libellé PPE</label>
                            <div class="custom-select-wrapper">
                                <input type="text" class="form-control ppe-search" 
                                    placeholder="Sélectionnez une PPE">
                                <ul class="custom-dropdown">${ppesList}</ul>
                                <input type="hidden" name="benificiaires_ppe_input[]">
                            </div>
                        </div>

                        <!-- LIEN PPE -->
                        <div class="col-md-1">
                            <label>Lien PPE</label>
                            <div class="form-check form-switch">
                            <input type="hidden" id="hidden_ben_ppe_lien_${rowsCount}" name="benificiaires_ppe_lien_check[]">

                                <input class="form-check-input ben_lien" 
                                    type="checkbox" 
                                    id="ben_lien_${rowsCount}">

                                <label class="form-check-label">
                                    <span id="label_lien_${rowsCount}">Non</span>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-3" style="display:none;" id="ppe_lien_block_${rowsCount}">
                            <label>Type Lien PPE</label>
                            <div class="custom-select-wrapper">
                                <input type="text" class="form-control ppe-search" 
                                    placeholder="Sélectionnez un type de lien">
                                <ul class="custom-dropdown">${ppesList}</ul>
                                <input type="hidden" name="benificiaires_ppe_lien_input[]">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label>% du capital</label>
                            <input type="text" class="form-control" 
                                name="benificiaires_pourcentage_capital[]" 
                                placeholder="% du capital">
                        </div>

                        <div class="col-md-1 mt-4">
                            <input type="file" 
                                id="cin_file_adm_${rowsCount}"
                                name="cin_Beneficiaires_Effectif[]" 
                                style="display:none;" />

                            <label for="cin_file_adm_${rowsCount}" class="btn btn-primary">
                                <i class="ti ti-upload"></i> CIN
                            </label>
                        </div>

                        <hr class="mt-3">
                    </div>`;


        $('.benificiairesRows').append(contactRow);
    });

        // TOGGLE PPE
        $(document).on('change', '.ben_ppe', function () {
            let row = this.id.split('_').pop();

            // Bloc PPE
            let ppeBlock = $('#ppe_block_' + row);
            let hiddenInput = $('#hidden_ben_ppe_' + row);
            let label = $('#label_ppe_' + row);

            // Afficher ou cacher le bloc PPE
            ppeBlock.toggle(this.checked);

            // Changer le label Oui / Non
            label.text(this.checked ? 'Oui' : 'Non');

            // Mettre à jour la valeur hidden
            hiddenInput.val(this.checked ? 1 : 0);
        });

        // TOGGLE LIEN PPE
        $(document).on('change', '.ben_lien', function () {
            let row = this.id.split('_').pop();

            // Bloc Lien PPE
            let lienBlock = $('#ppe_lien_block_' + row);
            let hiddenInput = $('#hidden_ben_ppe_lien_' + row);
            let label = $('#label_lien_' + row);

            // Afficher ou cacher le bloc Lien PPE
            lienBlock.toggle(this.checked);

            // Changer le label Oui / Non
            label.text(this.checked ? 'Oui' : 'Non');

            // Mettre à jour la valeur hidden
            hiddenInput.val(this.checked ? 1 : 0);
        });


    // DELETE ROW
    $(document).on('click', '.deleteBenificiaireRow', function (e) {
        e.preventDefault();
        $(this).closest('.benificiaireRowInfos').remove();
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
}


// =========================================================================
// INDEX PAGE LOGIC (Vanilla JS)
// =========================================================================
var BenificiaireEffectifIndex = {
    init: function () {
        if (!document.getElementById('be-check-all') && !document.getElementById('input-search-be')) {
            return;
        }
        this.initEvents();
    },
    initEvents: function () {
        const searchInput = document.getElementById('input-search-be');
        const checkAll = document.getElementById('be-check-all');
        const checkboxes = () => document.querySelectorAll('.be-chkbox');
        const bulkAction = document.getElementById('bulk-action-be');
        const deleteBtn = document.getElementById('delete-selection-be');
        const rows = document.querySelectorAll('tbody tr');

        // Search logic
        if (searchInput) {
            searchInput.addEventListener('keyup', function () {
                const value = this.value.toLowerCase();
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(value) ? '' : 'none';
                });
            });
        }

        // Check All logic
        if (checkAll) {
            checkAll.addEventListener('change', function () {
                checkboxes().forEach(cb => cb.checked = this.checked);
                toggleBulkAction();
            });
        }

        // Single checkbox logic (delegated)
        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('be-chkbox')) {
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

        // Bulk Delete logic
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function () {
                const selectedIds = Array.from(checkboxes())
                    .filter(c => c.checked)
                    .map(c => c.value);

                if (selectedIds.length === 0) {
                    alert('Veuillez sélectionner au moins un bénéficiaire.');
                    return;
                }

                if (!confirm('Confirmer la suppression des bénéficiaires sélectionnés ?')) return;

                fetch(window.benificiaireRoutes.bulkDelete, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.benificiaireRoutes.csrfToken
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
    }
};

// =========================================================================
// INITIALIZATION
// =========================================================================
document.addEventListener('DOMContentLoaded', function () {
    initCreatePageLogic();
    BenificiaireEffectifIndex.init();
});