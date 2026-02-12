/**
 * JavaScript for Coordonnées Bancaires
 * Handles both the Create/Edit form (jQuery) and the Index page (Vanilla JS)
 */

// =========================================================================
// CREATE / EDIT PAGE LOGIC (jQuery)
// =========================================================================
function initCreatePageLogic() {
    if (typeof $ === 'undefined' || typeof window.banquesData === 'undefined') {
        return;
    }

    $(document).ready(function () {
        $('#addCBancairesRowBtn').on('click', function (e) {
            e.preventDefault();
            let rowsCount = $('.CBancairesRowInfos').length + 1;

            if (rowsCount > 5) {
                alert("Vous ne pouvez ajouter que 5 Comptes Bancaires !");
                return;
            }

            let banquesList = '';
            window.banquesData.forEach(banque => {
                banquesList += `<li data-value="${banque.id}">${banque.nom}</li>`;
            });
            let villesList = '';
            window.villesData.forEach(ville => {
                villesList += `<li data-value="${ville.id}">${ville.libelle}</li>`;
            });

            let CBancairesRow = `
                <div class="row CBancairesRowInfos CBancairesRow${rowsCount}">
                    <div class="col-md-3 mb-3">
                        <div class="custom-select-wrapper">
                            <input type="text" class="form-control banque-search" placeholder="Sélectionnez une banque">
                            <ul class="custom-dropdown">${banquesList}</ul>
                            <input type="hidden" name="banque_id[]" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="Agence" name="agences_banque[]"/>
                    </div>
                    <div class="col-md-2">
                        <div class="custom-select-wrapper">
                            <input type="text" class="form-control banque-search" placeholder="Sélectionnez une ville">
                            <ul class="custom-dropdown">${villesList}</ul>
                            <input type="hidden" name="ville_id[]" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="RIB" name="rib_banque[]"/>
                    </div>
                    <div class="col-md-1 text-center">
                        <a href="#" class="deleteCBancairesRow" data-rowid="${rowsCount}" style="line-height: 3;"><i class="ti ti-trash h5"></i></a>
                    </div>
                </div>`;

            $('.CBancairesRows').append(CBancairesRow);
        });

        // Delete row
            $(document).on('click', '.deleteCBancairesRow', function (e) {
                e.preventDefault();
                $(this).closest('.CBancairesRowInfos').remove();
            });

            // Select item from dropdown
            $(document).on('click', '.custom-dropdown li', function () {
                let wrapper = $(this).closest('.custom-select-wrapper');

                wrapper.find('.banque-search').val($(this).text());
                wrapper.find('input[type="hidden"]').val($(this).data('value'));
                wrapper.find('.custom-dropdown').hide();
            });

            // Show dropdown on focus
            $(document).on('focus', '.banque-search', function () {
                $(this).siblings('.custom-dropdown').show();
            });

            // Filter by first letters
            $(document).on('keyup', '.banque-search', function () {

                let value = $(this).val().toLowerCase();
                let dropdown = $(this).siblings('.custom-dropdown');

                dropdown.find('li').each(function () {

                    let text = $(this).text().toLowerCase();

                    if (value === '' || text.startsWith(value)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }

                });

            });

            // Hide dropdown when clicking outside
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.custom-select-wrapper').length) {
                    $('.custom-dropdown').hide();
                }
            });

    });
}

// =========================================================================
// INDEX PAGE LOGIC (Vanilla JS)
// =========================================================================
var CoordonneesBancairesIndex = {
    init: function () {
        if (!document.getElementById('coordonnees-check-all') && !document.getElementById('input-search')) {
            return;
        }
        this.initEvents();
    },
    initEvents: function () {
        const searchInput = document.getElementById('input-search');
        const checkAll = document.getElementById('coordonnees-check-all');
        const checkboxes = () => document.querySelectorAll('.coordonnees-chkbox');
        const bulkAction = document.getElementById('bulk-action');
        const deleteBtn = document.getElementById('delete-selection');
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
            if (e.target.classList.contains('coordonnees-chkbox')) {
                if (checkAll) {
                    checkAll.checked = Array.from(checkboxes()).every(cb => cb.checked);
                }
                toggleBulkAction();
            }
        });

        function toggleBulkAction() {
            const hasChecked = Array.from(checkboxes()).some(cb => cb.checked);
            if (bulkAction) {
                bulkAction.style.display = hasChecked ? 'inline-block' : 'none';
            }
        }

        // DELETE MULTIPLE
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function () {
                const selectedIds = Array.from(checkboxes())
                    .filter(c => c.checked)
                    .map(c => c.value);

                if (selectedIds.length === 0) {
                    alert('Veuillez sélectionner au moins une coordonnée.');
                    return;
                }

                if (!confirm('Confirmer la suppression des coordonnées bancaires sélectionnées ?')) return;

                fetch(window.coordonneesRoutes.bulkDelete, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.coordonneesRoutes.csrfToken
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
    CoordonneesBancairesIndex.init();
});
