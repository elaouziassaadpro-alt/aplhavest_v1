document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.edit-section-btn'); // Tous les boutons d'édition
    editButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            // On récupère le parent section de ce bouton
            const parentSection = btn.closest('.accordion-collapse, .section-wrapper');

            if (!parentSection) return;

            // --- Activer tous les inputs text, textarea et select ---
            parentSection.querySelectorAll('input:not([type="checkbox"]):not([type="radio"]), textarea, select').forEach(el => {
                el.disabled = false;
                el.style.pointerEvents = 'auto';
                el.style.color = '#707070';
                el.style.setProperty('background-color', '#fff', 'important');
            });

            // --- Activer les fichiers ---
            parentSection.querySelectorAll('input[type="file"]').forEach(el => {
                el.disabled = false;
                el.style.pointerEvents = 'auto';
                el.style.backgroundColor = '#5d87ff'; // bleu pour file
            });

           parentSection.querySelectorAll('.btn.btn-success, .add').forEach(el => {
                el.disabled = false;
                el.style.pointerEvents = 'auto';
                el.classList.remove('update');
            });
            parentSection.querySelectorAll('a').forEach(el => {
                el.disabled = false;
                el.style.pointerEvents = 'auto';
                el.classList.remove('destroy');
            });


            // --- Activer les checkboxes et radios ---
            parentSection.querySelectorAll('input[type="checkbox"], input[type="radio"]').forEach(el => {
                    el.disabled = false;             // activer l'input
                    el.style.pointerEvents = 'auto'; // activer pointer
                    el.style.cursor = 'default'; 
                    el.style.accentColor = '#5d87ff';
                      // curseur normal

                    
                });
                parentSection.querySelectorAll('.btn.btn-primary,.btn.btn-primary:hover').forEach(el => {
                el.style.backgroundColor = '#5d87ff'; // bleu pour file
            });
            parentSection.querySelectorAll('#dropzone-fichierFATCA').forEach(el => {
                el.style.pointerEvents = 'auto';
            });


            // Optionnel : changer le style du bouton pour indiquer le mode édition
            btn.classList.remove('btn-secondary');
            btn.classList.add('btn-primary');
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    setupToggle('Societe_gestion_check', 'societeGestionFiles', 'Societe_gestionLabel');
    setupToggle('autorite_regulation_check', 'autoriteInputWrapper', 'autoriteLabel');
});

function setupToggle(checkboxId, wrapperId, labelId) {
    const checkboxEl = document.getElementById(checkboxId);
    const wrapperEl = document.getElementById(wrapperId);
    const labelEl = document.getElementById(labelId);

    if (!checkboxEl || !wrapperEl || !labelEl) return; // sécurité

    // Initial display selon état initial
    wrapperEl.style.display = checkboxEl.checked ? 'block' : 'none';
    labelEl.textContent = checkboxEl.checked ? 'Oui' : 'Non';

    // Toggle champ visibilité
    checkboxEl.addEventListener('change', function () {
        if (checkboxEl.checked) {
            wrapperEl.style.display = 'flex';
            labelEl.textContent = 'Oui';
        } else {
            wrapperEl.style.display = 'none';
            labelEl.textContent = 'Non';
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {

    function toggleByCheckbox(checkboxId, targetId, labelId) {
        const checkbox = document.getElementById(checkboxId);
        const target   = document.getElementById(targetId);
        const label    = document.getElementById(labelId);

        if (!checkbox || !target || !label) return;

        function update() {
            if (checkbox.checked) {
                target.style.display = 'block';
                label.textContent = 'Oui';
            } else {
                target.style.display = 'none';
                label.textContent = 'Non';
            }
        }

        // état initial
        update();

        // changement
        checkbox.addEventListener('change', update);
    }

    toggleByCheckbox(
        'us_entity_id',
        'dropzone-fichierFATCA',
        'us_entity_label'
    );

    toggleByCheckbox(
        'giin_id',
        'giin_data_id',
        'giin_label'
    );
    toggleByCheckbox(
        'departement_gestion_id',
        'departement_gestion_data_container',
        'labelcheckbox'
    );

});


