/**
 * Professional Etablissement Update UI Logic
 * Handles section-based editing and state management.
 */

document.addEventListener('DOMContentLoaded', function() {
     // SELECT DROPDOWN
    $(document).on('click', '.custom-dropdown li', function () {

        let wrapper = $(this).closest('.custom-select-wrapper');

        wrapper.find('input[type="text"]').val($(this).text());
        wrapper.find('input[type="hidden"]').val($(this).data('value'));

        wrapper.find('.custom-dropdown').hide();
    });

    // SHOW DROPDOWN + RESET FILTER
    $(document).on('focus', '.pays-search, .ppe-search, .banque-search, .ville-search', function () {

        let dropdown = $(this).siblings('.custom-dropdown');

        dropdown.find('li').show();
        dropdown.show();
    });

    // FILTER
    $(document).on('keyup', '.pays-search, .ppe-search, .banque-search, .ville-search', function () {

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
    
    
    const editBtns = document.querySelectorAll('.edit-section-btn');

    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Find the closest section container (usually the accordion-collapse)
            const section = this.closest('.accordion-collapse');
            if (!section) return;

            const isLocked = section.classList.contains('locked-mode');

            if (isLocked) {
                unlockSection(section, this);
            } else {
                lockSection(section, this);
            }
        });
    });

    function unlockSection(section, btn) {
        section.classList.remove('locked-mode');
        section.classList.add('editing-mode');
        
        // Enable all inputs, selects, textareas
        const inputs = section.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.disabled = false;
        });

        // Update Icon to 'X' (Cancel)
        const icon = btn.querySelector('i');
        if (icon) {
            icon.classList.remove('ti-pencil');
            icon.classList.add('ti-x');
            icon.style.transform = 'rotate(90deg)';
        }
        
        // Change button color to danger (Red)
        btn.classList.remove('btn-light-info');
        btn.classList.remove('delete-btn');
        btn.classList.add('btn-light-danger');
        btn.setAttribute('title', 'Annuler les modifications');

        const dropzone = section.querySelector('.dropzone');
        if (dropzone) dropzone.style.pointerEvents = 'auto';
    }

    function lockSection(section, btn) {
        section.classList.add('locked-mode');
        section.classList.remove('editing-mode');

        // Disable all inputs, selects, textareas
        const inputs = section.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.disabled = true;
        });

        const icon = btn.querySelector('i');
        if (icon) {
            icon.classList.remove('ti-x');
            icon.classList.add('ti-pencil');
            icon.style.transform = 'rotate(0deg)';
        }

        btn.classList.remove('btn-light-danger');
        btn.classList.add('btn-light-info');
        btn.setAttribute('title', 'Modifier cette section');

        const dropzone = section.querySelector('.dropzone');
        if (dropzone) dropzone.style.pointerEvents = 'none';
    }

    /**
     * Shared toggle logic for switches (Oui/Non)
     */
    function toggleSwitch(checkboxId, labelId, targetSelector = null) {
        const checkbox = document.getElementById(checkboxId);
        const label = document.getElementById(labelId);
        if (!checkbox || !label) return;

        const update = () => {
            label.textContent = checkbox.checked ? 'Oui' : 'Non';
            if (targetSelector) {
                const targets = document.querySelectorAll(targetSelector);
                targets.forEach(t => {
                    if (checkbox.checked) {
                        t.classList.remove('d-none');
                        if (t.tagName === 'DIV' && !t.classList.contains('col-4')) t.style.display = 'flex'; 
                        else t.style.display = 'block';
                    } else {
                        t.classList.add('d-none');
                        t.style.display = 'none';
                    }
                });
            }
        };

        checkbox.addEventListener('change', update);
        update();
    }

    // Initialize static switches
    toggleSwitch('autorite_regulation_check', 'autoriteLabel', '#autoriteInputWrapper');
    toggleSwitch('Societe_gestion_check', 'Societe_gestionLabel', '#societeGestionFiles');
    toggleSwitch('activite_etranger_check', 'activite_etranger_label', '#pays_etranger_wrapper');
    toggleSwitch('marche_check', 'marche_label', '#marche_wrapper');
    toggleSwitch('mandataire_id', 'mandataireLabel', '.mandat-hide');
    toggleSwitch('departement_gestion_id', 'labelcheckbox', '#departement_gestion_data_container');
    toggleSwitch('us_entity_id', 'us_entity_label', '#fichier_usEntity_input');
    toggleSwitch('giin_id', 'giinLabel', '#giin_data_id');

    /**
     * Helper for dynamic row switches (PPE, etc.)
     */
    function initDynamicToggles(containerSelector, checkboxClass, labelPrefix, wrapperPrefix) {
        const container = document.querySelector(containerSelector);
        if (!container) return;

        container.addEventListener('change', (e) => {
            if (e.target.classList.contains(checkboxClass)) {
                const id = e.target.id.split('_').pop();
                const label = document.getElementById(labelPrefix + id);
                const wrapper = document.getElementById(wrapperPrefix + id);
                
                if (label) label.textContent = e.target.checked ? 'Oui' : 'Non';
                if (wrapper) {
                    wrapper.style.display = e.target.checked ? 'block' : 'none';
                    if (wrapper.classList.contains('d-flex')) {
                         wrapper.style.display = e.target.checked ? 'flex' : 'none';
                    }
                }
            }
        });
    }

    // Initialize dynamic row toggles
    initDynamicToggles('.beneficiairesRows', 'ben_ppe', 'label_ppe_', 'ppe_block_');
    initDynamicToggles('.beneficiairesRows', 'ben_lien', 'label_lien_', 'ppe_lien_block_');
    initDynamicToggles('.administrateursRows', 'adm_ppe', 'label_ppe_', 'administrateur_ppe_data_id_');
    initDynamicToggles('.administrateursRows', 'adm_lien', 'label_lien_', 'administrateur_ppe_lien_data_id_');
    initDynamicToggles('.personneHabiliteRows', 'form-check-input', 'label_ppe_', 'habilite_ppe_data_id_');
});

/**
 * PPE Toggle functions moved from show.blade.php
 */
function togglePPE(index) {
    let check = document.getElementById('ppe_' + index);
    let hidden = document.getElementById('hidden_ppe_' + index);
    let block = document.getElementById('ppe_block_' + index);
    let label = document.getElementById('label_ppe_' + index);
    
    if (check && check.checked) {
        if (hidden) hidden.value = 1;
        if (block) block.style.display = 'block';
        if (label) label.innerText = 'Oui';
    } else {
        if (hidden) hidden.value = 0;
        if (block) block.style.display = 'none';
        if (label) label.innerText = 'Non';
    }
}

function toggleLienPPE(index) {
    let check = document.getElementById('lien_ppe_' + index);
    let hidden = document.getElementById('hidden_lien_' + index);
    let block = document.getElementById('ppe_lien_block_' + index);
    let label = document.getElementById('label_lien_' + index);
    
    if (check && check.checked) {
        if (hidden) hidden.value = 1;
        if (block) block.style.display = 'block';
        if (label) label.innerText = 'Oui';
    } else {
        if (hidden) hidden.value = 0;
        if (block) block.style.display = 'none';
        if (label) label.innerText = 'Non';
    }
}
function ENTITY(){
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

    if (usCheckbox) {
        usCheckbox.addEventListener('change', toggleUS);
        toggleUS();
    }

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

    if (giinCheckbox) {
        giinCheckbox.addEventListener('change', toggleGIIN);
        toggleGIIN();
    }
}
function dropzone() {
    const usCheckbox = document.getElementById('us_entity_id');
    const usLabel = document.getElementById('us_entity_label');
    const dropzoneFATCA = document.getElementById('dropzone-fichierFATCA');
    const fatcaInput = document.getElementById('fatca_hidden_input');

    if (!usCheckbox || !dropzoneFATCA || !fatcaInput) return;

    // Toggle affichage dropzone
    function toggleUS() {
        if(usCheckbox.checked) {
            if (usLabel) usLabel.textContent = 'Oui';
            dropzoneFATCA.style.display = 'block';
        } else {
            if (usLabel) usLabel.textContent = 'Non';
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
            const p = dropzoneFATCA.querySelector('p');
            if (p) p.textContent = fatcaInput.files[0].name;
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
            const p = dropzoneFATCA.querySelector('p');
            if (p) p.textContent = fatcaInput.files[0].name;
        }
        dropzoneFATCA.style.backgroundColor = '#fff';
    });
}



function toggleHabilitePPE(index) {
    let check = document.getElementById('habilite_ppe_id_' + index);
    let hidden = document.getElementById('hidden_habilite_ppe_' + index);
    let block = document.getElementById('habilite_ppe_data_id_' + index);
    let label = document.getElementById('label_habilite_ppe_' + index);
    
    if (check && check.checked) {
        if (hidden) hidden.value = 1;
        if (block) block.style.display = 'block';
        if (label) label.innerText = 'Oui';
    } else {
        if (hidden) hidden.value = 0;
        if (block) block.style.display = 'none';
        if (label) label.innerText = 'Non';
    }
}

function toggleHabiliteLienPPE(index) {
    let check = document.getElementById('habilite_ppe_lien_id_' + index);
    let hidden = document.getElementById('hidden_habilite_lien_' + index);
    let block = document.getElementById('habilite_ppe_lien_data_id_' + index);
    let label = document.getElementById('label_habilite_lien_' + index);
    
    if (check && check.checked) {
        if (hidden) hidden.value = 1;
        if (block) block.style.display = 'block';
        if (label) label.innerText = 'Oui';
    } else {
        if (hidden) hidden.value = 0;
        if (block) block.style.display = 'none';
        if (label) label.innerText = 'Non';
    }
}
function removeActionnaire(index) {
    $('.actionnaireRow' + index).remove();
}
function benificiaireeffectifDestroy(index) {
    $('.benificiaireRow' + index).remove();
}

function removeHabilite(index) {
    $('.phabiliteRow' + index).remove();
}
function supprimercontact(button) {
    button.closest('.row').remove();
}
// SELECT DROPDOWN
   

// add contact form
let contactCount = 0;  // Global counter for unique rows
const MAX_CONTACTS = 5;

function ajoutercontact() {

    let html = `
        <div class="row align-items-end">

            <div class="col-md-2 mb-3">
                <label>Nom</label>
                <input type="text" class="form-control" name="noms_contacts[]">
            </div>

            <div class="col-md-2 mb-3">
                <label>Prénom</label>
                <input type="text" class="form-control" name="prenoms_contacts[]">
            </div>

            <div class="col-md-3 mb-3">
                <label>Email</label>
                <input type="email" class="form-control" name="emails_contacts[]">
            </div>
            <div class="col-md-2 mb-3">
                <label>Fonction</label>
                <input type="text" class="form-control" name="fonctions_contacts[]">
            </div>

            <div class="col-md-2 mb-3">
                <label>Téléphone</label>
                <input type="text" class="form-control" name="telephones_contacts[]">
            </div>

            <div class="col-md-1 mb-3">
                <button type="button" class="btn btn-danger btn-sm"
                        onclick="supprimercontact(this)">
                    ✖
                </button>
            </div>

        </div>
    `;

    document.querySelector('.contactsRows').insertAdjacentHTML('beforeend', html);
}

document.addEventListener('DOMContentLoaded', function() {
    ENTITY();
    dropzone();
});
