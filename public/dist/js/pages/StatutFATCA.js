document.addEventListener('DOMContentLoaded', function() {

    // ===== US Entity Toggle =====
    const usCheckbox = document.getElementById('us_entity_id');
    const usLabel = document.getElementById('usEntityLabel');
    const dropzoneFATCA = document.getElementById('dropzone-fichierFATCA');

    function toggleUS() {
        if(usCheckbox.checked) {
            usLabel.textContent = 'Oui';
            dropzoneFATCA.style.display = 'block';
        } else {
            usLabel.textContent = 'Non';
            dropzoneFATCA.style.display = 'none';
        }
    }
    usCheckbox.addEventListener('change', toggleUS);
    toggleUS();

    // ===== GIIN Toggle =====
    const giinCheckbox = document.getElementById('giin_id');
    const giinLabel = document.getElementById('giinLabel');
    const giinData = document.getElementById('giin_data_id');
    const giinAutres = document.getElementById('giin_data_autres_id');

    function toggleGIIN() {
        if(giinCheckbox.checked) {
            giinLabel.textContent = 'Oui';
            giinData.style.display = 'flex';
            giinAutres.style.display = 'none';
        } else {
            giinLabel.textContent = 'Non';
            giinData.style.display = 'none';
            giinAutres.style.display = 'flex';
        }
    }
    giinCheckbox.addEventListener('change', toggleGIIN);
    toggleGIIN();

});


document.addEventListener('DOMContentLoaded', function() {
    const usCheckbox = document.getElementById('us_entity_id');
    const usLabel = document.getElementById('usEntityLabel');
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