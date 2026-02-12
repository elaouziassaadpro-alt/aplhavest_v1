const searchInput = document.getElementById('input-search');

if (searchInput) {
    searchInput.addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('.search-items');

        rows.forEach(row => {
            const nameCell = row.querySelector('td:nth-child(2)'); // 2nd column = Name
            if (nameCell) {
                const nameText = nameCell.textContent.toLowerCase();
                row.style.display = nameText.includes(searchValue) ? '' : 'none';
            }
        });
    });
}
document.addEventListener('DOMContentLoaded', function () {

    axios.defaults.headers.common['X-CSRF-TOKEN'] =
        document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const checkboxes   = document.querySelectorAll('.contact-chkbox');
    const validateBtn  = document.getElementById('validate-selection');
    const rejectBtn    = document.getElementById('reject-selection');
    const deleteBtn    = document.getElementById('delete-selection');
    const actionBtns   = document.querySelectorAll('.action-btn.show-btn');

    function toggleButtons() {
        const anyChecked = [...checkboxes].some(cb => cb.checked);
        actionBtns.forEach(btn => btn.style.display = anyChecked ? 'inline-flex' : 'none');
    }

    checkboxes.forEach(cb => cb.addEventListener('change', toggleButtons));

    function updateValidation(status) {
        const ids = [...checkboxes]
            .filter(cb => cb.checked)
            .map(cb => cb.dataset.etablissementId);

        if (!ids.length) return;

        axios.post(window.routes.updateValidation, { ids, validation: status })
            .then(() => {
                ids.forEach(id => {
                    const row = document.querySelector(`.contact-chkbox[data-etablissement-id="${id}"]`).closest('tr');
                    const span = row.querySelector('td:nth-child(7) span');

                    if (status === 'valide') {
                        span.textContent = 'valide';
                        span.className = 'text-success';
                    } else if (status === 'rejete') {
                        span.textContent = 'rejeté';
                        span.className = 'text-danger';
                    } else {
                        span.textContent = 'en attente';
                        span.className = 'text-warning';
                    }
                });

                checkboxes.forEach(cb => cb.checked = false);
                toggleButtons();
            })
            .catch(err => console.error('Erreur validation :', err));
    }

    validateBtn.addEventListener('click', () => updateValidation('valide'));
    rejectBtn.addEventListener('click',   () => updateValidation('rejete'));

    // Delete multiple
    if (deleteBtn) {
        deleteBtn.addEventListener('click', () => {
            const ids = [...checkboxes]
                .filter(cb => cb.checked)
                .map(cb => cb.dataset.etablissementId);

            if (!ids.length) return;

            if (!confirm('Voulez-vous vraiment supprimer les établissements sélectionnés ?')) return;

            axios.post(window.routes.deleteMultiple, { ids })
                .then(res => {
                    ids.forEach(id => {
                        const row = document.querySelector(`.contact-chkbox[data-etablissement-id="${id}"]`).closest('tr');
                        row.remove();
                    });

                    checkboxes.forEach(cb => cb.checked = false);
                    toggleButtons();

                    Swal.fire('Supprimé !', res.data.message, 'success');
                })
                .catch(err => {
                    Swal.fire('Erreur', err.response.data.message || 'Une erreur est survenue', 'error');
                    console.error('Erreur suppression :', err);
                });
        });
    }

});
