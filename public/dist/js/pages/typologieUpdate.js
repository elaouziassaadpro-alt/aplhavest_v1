document.addEventListener('DOMContentLoaded', () => {

    const editBtn = document.querySelector('.edit-section-btn');
    const saveBox = document.getElementById('save-section');
    const editables = document.querySelectorAll('.editable');

    const activiteCheck = document.getElementById('activite_etranger_check');
    const activiteLabel = document.getElementById('activite_etranger_label');
    const paysWrapper   = document.getElementById('pays_etranger_wrapper');

    const marcheCheck = document.getElementById('marche_check');
    const marcheLabel = document.getElementById('marche_label');
    const marcheWrapper = document.getElementById('marche_wrapper');

    // ✏️ Activer édition
    editBtn.addEventListener('click', () => {
        editables.forEach(el => el.disabled = false);
        saveBox.classList.remove('d-none');
    });

    // 🌍 Activité à l'étranger
    activiteCheck.addEventListener('change', () => {
        activiteLabel.textContent = activiteCheck.checked ? 'Oui' : 'Non';
        paysWrapper.style.display = activiteCheck.checked ? 'block' : 'none';
    });

    // 📈 Marché financier
    marcheCheck.addEventListener('change', () => {
        marcheLabel.textContent = marcheCheck.checked ? 'Oui' : 'Non';
        marcheWrapper.style.display = marcheCheck.checked ? 'block' : 'none';
    });

});

