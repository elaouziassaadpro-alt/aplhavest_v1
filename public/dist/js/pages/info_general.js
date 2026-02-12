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
    
    setupCustomDropdown('formeInput', 'formeDropdown', 'formeSelect', window.dropdownData.formejuridiques);
    setupCustomDropdown('formeInputpayact', 'formeDropdownpayact', 'formeSelectpayact', window.dropdownData.pays);
    setupCustomDropdown('formeInputpayresidence', 'formeDropdownpayresidence', 'formeSelectpayresidence', window.dropdownData.pays);

});



// ------------------------------------------------


// ------------------------------------------------
// Show selected file names for file inputs

document.addEventListener('DOMContentLoaded', () => {
    const checkbox = document.getElementById('autorite_regulation_check');
    const wrapper = document.getElementById('autoriteInputWrapper');
    const label = document.getElementById('autoriteLabel');

    // Initial state (important for edit mode)
    if (checkbox.checked) {
        label.textContent = 'Oui';
        wrapper.classList.remove('d-none');
    }

    checkbox.addEventListener('change', () => {
        if (checkbox.checked) {
            label.textContent = 'Oui';
            wrapper.classList.remove('d-none');
        } else {
            label.textContent = 'Non';
            wrapper.classList.add('d-none');
        }
    });
});
// ------------------------------------------------

document.addEventListener('DOMContentLoaded', () => {

    const checkbox = document.getElementById('Societe_gestion_check');
    const label = document.getElementById('Societe_gestionLabel');
    const filesWrapper = document.getElementById('societeGestionFiles');

    // Initial state (edit mode / reload)
    if (checkbox.checked) {
        label.textContent = 'Oui';
        filesWrapper.classList.remove('d-none');
    }

    checkbox.addEventListener('change', () => {
        if (checkbox.checked) {
            label.textContent = 'Oui';
            filesWrapper.classList.remove('d-none');
        } else {
            label.textContent = 'Non';
            filesWrapper.classList.add('d-none');
        }
    });

});
// add contact form
let contactCount = 0;  // Global counter for unique rows
const MAX_CONTACTS = 5;

function ajoutercontact() {
    // Count current rows
    const currentRows = document.querySelectorAll('.contactRowInfos').length;

    if (currentRows >= MAX_CONTACTS) {
        alert("Vous ne pouvez ajouter que 5 contacts !");
        return;
    }

    contactCount++; // unique ID for this row

    // Create a new div for the contact row
    const div = document.createElement('div');
    div.className = `row contactRowInfos contactRow${contactCount} mb-2`;
    div.innerHTML = `
        <div class="col-md-2">
            <input type="text" class="form-control" placeholder="Nom" name="noms_contacts[]">
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control" placeholder="Prénom" name="prenoms_contacts[]">
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control" placeholder="Fonction" name="fonctions_contacts[]">
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control" placeholder="Téléphone" name="telephones_contacts[]">
        </div>
        <div class="col-md-3">
            <input type="email" class="form-control" placeholder="Email" name="emails_contacts[]">
        </div>
        <div class="col-md-1">
            <a href="#" onclick="supprimercontact(${contactCount})" class="text-danger">
                <center><i class="ti ti-trash w-100 h5"></i></center>
            </a>
        </div>
    `;

    document.querySelector('.contactsRows').appendChild(div);
}

// Function to delete a row
function supprimercontact(id) {
    const row = document.querySelector(`.contactRow${id}`);
    if (row) {
        row.remove();
    }
}
// ------------------------------------------------
