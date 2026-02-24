
    document.addEventListener('DOMContentLoaded', function () {

    const searchInput   = document.getElementById('input-search');
    const checkAll      = document.getElementById('users-check-all');
    const checkboxes    = () => document.querySelectorAll('.user-chkbox');
    const bulkAction    = document.getElementById('bulk-action');
    const deleteBtn     = document.getElementById('delete-selection');
    const rows          = document.querySelectorAll('tbody tr');

    /* =========================
       🔍 SEARCH BAR
    ========================== */
    searchInput.addEventListener('keyup', function () {
        const value = this.value.toLowerCase();
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    });

    /* =========================
       ☑ CHECK ALL
    ========================== */
    checkAll.addEventListener('change', function () {
        checkboxes().forEach(cb => cb.checked = this.checked);
        toggleBulkAction();
    });

    /* =========================
       ☑ SINGLE CHECK
    ========================== */
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('user-chkbox')) {
            checkAll.checked = [...checkboxes()].every(cb => cb.checked);
            toggleBulkAction();
        }
    });

    /* =========================
       👁 SHOW / HIDE DELETE BUTTON
    ========================== */
    function toggleBulkAction() {
        const checked = [...checkboxes()].some(cb => cb.checked);
        bulkAction.style.display = checked ? 'block' : 'none';
    }

    /* =========================
       🗑 DELETE SELECTION (AJAX)
    ========================== */
    deleteBtn.addEventListener('click', function () {

        const selectedIds = Array.from(checkboxes())
            .filter(c => c.checked)
            .map(c => c.value);

        if (selectedIds.length === 0) {
            alert('Veuillez sélectionner au moins un utilisateur.');
            return;
        }

    fetch(window.actionnariatRoutes.bulkDelete, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.actionnariatRoutes.csrfToken
            },
            body: JSON.stringify({ ids: selectedIds })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(err => alert('Erreur lors de la suppression'));
    });
    });

function selectAvatar(el) {
                        document.querySelectorAll('.avatar-label').forEach(l => {
                            l.classList.remove('border-primary', 'border-2');
                        });
                        el.classList.add('border-primary', 'border-2');
                        el.querySelector('input').checked = true;
                    }