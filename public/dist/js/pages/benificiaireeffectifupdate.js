function removeCBancaire(index) {
    const row = document.querySelector(`.CBancairesRow${index}`);
    if (row) row.remove();
}


// Toggle PPE
function togglePPE(index) {
    const block = document.getElementById(`ppe_block_${index}`);
    const checkbox = document.getElementById(`ppe_${index}`);
    if(block && checkbox) {
        block.style.display = checkbox.checked ? 'block' : 'none';
    }
}

// Toggle Lien PPE
function toggleLienPPE(index) {
    const block = document.getElementById(`ppe_lien_block_${index}`);
    const checkbox = document.getElementById(`lien_ppe_${index}`);
    if(block && checkbox) {
        block.style.display = checkbox.checked ? 'block' : 'none';
    }
}
// index ----------------------------------------------
document.addEventListener('DOMContentLoaded', function () {
    const searchInput   = document.getElementById('input-search-be');
    const checkAll      = document.getElementById('be-check-all');
    const checkboxes    = () => document.querySelectorAll('.be-chkbox');
    const bulkAction    = document.getElementById('bulk-action-be');
    const deleteBtn     = document.getElementById('delete-selection-be');
    const rows          = document.querySelectorAll('tbody tr');

    // 🔍 Search
    searchInput.addEventListener('keyup', function () {
        const value = this.value.toLowerCase();
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    });

    // ☑ Check all
    checkAll.addEventListener('change', function () {
        checkboxes().forEach(cb => cb.checked = this.checked);
        toggleBulkAction();
    });

    // ☑ Single check
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('be-chkbox')) {
            checkAll.checked = [...checkboxes()].every(cb => cb.checked);
            toggleBulkAction();
        }
    });

    // 👁 Show/hide bulk action
    function toggleBulkAction() {
        const checked = [...checkboxes()].some(cb => cb.checked);
        bulkAction.style.display = checked ? 'block' : 'none';
    }

    // 🗑 Bulk delete
    deleteBtn.addEventListener('click', function () {
        if (!confirm('Confirmer la suppression des bénéficiaires sélectionnés ?')) return;

        const selectedIds = Array.from(checkboxes())
            .filter(c => c.checked)
            .map(c => c.value);

        if (selectedIds.length === 0) {
            alert('Veuillez sélectionner au moins un bénéficiaire.');
            return;
        }

        fetch("{{ route('beneficiaire.bulkDelete') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
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