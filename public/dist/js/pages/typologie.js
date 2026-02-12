document.addEventListener('DOMContentLoaded', function() {

    function setupCustomDropdown(inputId, dropdownId, hiddenId, items=[]) {
        const input = document.getElementById(inputId);
        const dropdown = document.getElementById(dropdownId);
        const hidden = document.getElementById(hiddenId);

        if (!input || !dropdown || !hidden) return;

        // Populate dropdown if items are provided
        if (items.length) {
            dropdown.innerHTML = '';
            items.forEach(item => {
                const li = document.createElement('li');
                li.dataset.value = item.id;
                li.textContent = item.libelle;
                dropdown.appendChild(li);
            });
        }

        input.addEventListener('focus', () => dropdown.style.display = 'block');

        input.addEventListener('input', () => {
            const filter = input.value.toLowerCase();
            dropdown.querySelectorAll('li').forEach(li => {
                li.style.display = li.textContent.toLowerCase().includes(filter) ? 'block' : 'none';
            });
        });

        dropdown.querySelectorAll('li').forEach(li => {
            li.addEventListener('click', () => {
                input.value = li.textContent;
                hidden.value = li.dataset.value;
                dropdown.style.display = 'none';
            });
        });

        document.addEventListener('click', e => {
            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    }

    // Initialize dropdowns with data from Blade
    setupCustomDropdown('formeInputsecteur', 'formeDropdownsecteur', 'formeSelectsecteur', window.dropdownData.secteurs);
    setupCustomDropdown('formeInputsegment', 'formeDropdownsegment', 'formeSelectsegment', window.dropdownData.segments);
    setupCustomDropdown('formeInputpays', 'formeDropdownpays', 'formeSelectpays', window.dropdownData.pays);

    // Show/hide "Précisez" input
    const marcheCheck = document.getElementById('sur_marche_financier_id');
    const marcheInput = document.getElementById('sur_marche_financier_data_id');
    if (marcheCheck && marcheInput) {
        marcheCheck.addEventListener('change', () => {
            marcheInput.style.display = marcheCheck.checked ? 'block' : 'none';
        });
    }

});
// ------------------------------------------------

document.addEventListener('DOMContentLoaded', function () {

    function toggleSwitchWithInput({
        checkboxId,
        labelId,
        wrapperId,
        resetOnDisable = true
    }) {
        const checkbox = document.getElementById(checkboxId);
        const label = document.getElementById(labelId);
        const wrapper = document.getElementById(wrapperId);

        if (!checkbox || !label || !wrapper) return;

        function toggle() {
            if (checkbox.checked) {
                label.textContent = 'Oui';
                wrapper.classList.remove('d-none');
            } else {
                label.textContent = 'Non';
                wrapper.classList.add('d-none');

                if (resetOnDisable) {
                    wrapper.querySelectorAll('input').forEach(input => {
                        input.value = '';
                    });
                }
            }
        }

        // Init on load
        toggle();

        // Listen change
        checkbox.addEventListener('change', toggle);
    }

    /* ================= INIT ================= */

    // Activité à l'étranger
    toggleSwitchWithInput({
        checkboxId: 'activite_etranger_check',
        labelId: 'activite_etranger_label',
        wrapperId: 'pays_etranger_wrapper'
    });

    // Marché financier / appel public
    toggleSwitchWithInput({
        checkboxId: 'sur_marche_financier_check',
        labelId: 'marcheFinancierLabel',
        wrapperId: 'marcheFinancierInputWrapper'
    });

});

