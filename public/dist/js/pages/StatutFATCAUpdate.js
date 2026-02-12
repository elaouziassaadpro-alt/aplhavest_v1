document.addEventListener('DOMContentLoaded', function () {

    /* ================= US ENTITY ================= */

    const usCheckbox  = document.getElementById('us_entity_id');
    const usLabel     = document.getElementById('us_entity_label');
    const usFileInput = document.getElementById('fichier_usEntity_input');

    function toggleUS() {
        if (!usCheckbox || !usLabel || !usFileInput) return;

        if (usCheckbox.checked) {
            usLabel.textContent = 'Oui';
            usFileInput.style.display = 'block';
        } else {
            usLabel.textContent = 'Non';
            usFileInput.style.display = 'none';
            usFileInput.value = ''; // reset fichier si décoché
        }
    }

    usCheckbox?.addEventListener('change', toggleUS);
    toggleUS();

    /* ================= GIIN ================= */

    const giinCheckbox   = document.getElementById('giin_id');
    const giinLabel      = document.getElementById('giin_label');
    const giinData       = document.getElementById('giin_data_id');
    const giinAutresInput = document.querySelector('input[name="giin_label_Autres"]');
    const giinAutresBox   = giinAutresInput?.closest('.flex-grow-1');

    function toggleGIIN() {
        if (!giinCheckbox || !giinLabel) return;

        if (giinCheckbox.checked) {
            giinLabel.textContent = 'Oui';

            if (giinData) giinData.style.display = 'block';
            if (giinAutresBox) giinAutresBox.style.display = 'none';
        } else {
            giinLabel.textContent = 'Non';

            if (giinData) giinData.style.display = 'none';
            if (giinAutresBox) giinAutresBox.style.display = 'block';
        }
    }

    giinCheckbox?.addEventListener('change', toggleGIIN);
    toggleGIIN();

});



document.addEventListener('DOMContentLoaded', function() {
    const usCheckbox = document.getElementById('us_entity_id');
    const usLabel = document.getElementById('us_entity_label');
    const dropzoneFATCA = document.getElementById('dropzone-fichierFATCA');
    const fatcaInput = document.getElementById('fatca_hidden_input');

    // Toggle affichage dropzone
    function toggleUS() {
        if(usCheckbox.checked) {
            usLabel.textContent = 'Oui';
            dropzoneFATCA.style.display = 'block';
        } else {
            usLabel.textContent = 'Non';
            dropzoneFATCA.style.display = 'none';
            fatcaInput.value = ''; // reset
        }
    }
    usCheckbox.addEventListener('change', toggleUS);
    toggleUS();

    // Quand on clique sur le dropzone, ouvre le file input
    dropzoneFATCA.addEventListener('click', () => {
        fatcaInput.click();
    });

    // Afficher le nom du fichier sélectionné
    fatcaInput.addEventListener('change', () => {
        if(fatcaInput.files.length > 0){
            dropzoneFATCA.querySelector('p').textContent = fatcaInput.files[0].name;
        }
    });

    // Optionnel : support du drag & drop
    dropzoneFATCA.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzoneFATCA.style.backgroundColor = '#f0f0f0';
    });

    dropzoneFATCA.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropzoneFATCA.style.backgroundColor = '#fff';
    });

    dropzoneFATCA.addEventListener('drop', (e) => {
        e.preventDefault();
        fatcaInput.files = e.dataTransfer.files;
        if(fatcaInput.files.length > 0){
            dropzoneFATCA.querySelector('p').textContent = fatcaInput.files[0].name;
        }
        dropzoneFATCA.style.backgroundColor = '#fff';
    });
});