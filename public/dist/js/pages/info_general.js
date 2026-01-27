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
    setupCustomDropdown('formeInput', 'formeDropdown', 'formeSelect', window.dropdownData.formejuridiques);
setupCustomDropdown('formeInputpayact', 'formeDropdownpayact', 'formeSelectpayact', window.dropdownData.pays);
setupCustomDropdown('formeInputpayresidence', 'formeDropdownpayresidence', 'formeSelectpayresidence', window.dropdownData.pays);

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
// obligation de champs custom dropdowns
document.addEventListener('DOMContentLoaded', () => {
    // Select the form
    const form = document.querySelector('form');

    // Listen for the form submission
    form.addEventListener('submit', function(e) {
        // Get the values of your custom dropdowns (hidden inputs store selected values)
        const formeJuridique = document.getElementById('formeSelect').value.trim(); // Forme juridique
        const paysActivite = document.getElementById('formeSelectpay').value.trim(); // Lieu d'activité
        const paysResidence = document.getElementById('formeSelectpayresidence').value.trim(); // Pays de résidence fiscale

        // Array to store error messages
        let errors = [];

        // Check each field, if empty, push an error message
        if (!formeJuridique) {
            errors.push("Veuillez sélectionner une Forme juridique."); // Error for Forme juridique
        }
        if (!paysActivite) {
            errors.push("Veuillez sélectionner un Lieu d'activité."); // Error for Lieu d'activité
        }
        if (!paysResidence) {
            errors.push("Veuillez sélectionner un Pays de résidence fiscale."); // Error for Pays de résidence
        }

        // If there are any errors
        if (errors.length > 0) {
            e.preventDefault(); // Stop form submission
            alert(errors.join("\n")); // Show all errors in an alert (one per line)
        }
    });
});

// ------------------------------------------------
// Show selected file names for file inputs

const checkbox = document.getElementById('autorite_regulation_check');
const wrapper = document.querySelector('.autoriteInputWrapper');
const labels = document.getElementById('autoriteLabel');

checkbox.addEventListener('change', () => {
    if (checkbox.checked) {
        labels.textContent = 'Oui';
        wrapper.style.display = 'block';
        wrapper.style.opacity = 0;
        setTimeout(() => wrapper.style.opacity = 1, 10); // fade in
    } else {
        labels.textContent = 'Non';
        wrapper.style.opacity = 0;
        setTimeout(() => wrapper.style.display = 'none', 300); // fade out
    }
});
// ------------------------------------------------

document.addEventListener('DOMContentLoaded', function () {

    const checkbox = document.getElementById('Societe_gestion_check');
    const label = document.getElementById('Societe_gestionLabel');
    const filesWrapper = document.getElementById('societeGestionFiles');

    checkbox.addEventListener('change', function () {
        if (this.checked) {
            label.textContent = 'Oui';

            filesWrapper.style.display = 'flex';
            setTimeout(() => {
                filesWrapper.style.opacity = '1';
            }, 10);

        } else {
            label.textContent = 'Non';

            filesWrapper.style.opacity = '0';
            setTimeout(() => {
                filesWrapper.style.display = 'none';
            }, 300);
        }
    });

});
// add contact form
$(document).ready(function(){
          $('#addContactRowBtn').click(function(e){
            e.preventDefault();
            var rowsCount = $(".contactRowInfos").length;
            rowsCount++;
            var contactRow = `

              <div class="row contactRowInfos contactRow`+rowsCount+`">

                <div class="col-md-2">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Nom"
                    name="noms_contacts[]"
                  /> 
                </div>
                <div class="col-md-2">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Prénom"
                    name="prenoms_contacts[]"
                  /> 
                </div>
                <div class="col-md-2">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Fonction"
                    name="fonctions_contacts[]"
                  /> 
                </div>
                <div class="col-md-2">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Téléphone"
                    name="telephones_contacts[]"
                  /> 
                </div>
                <div class="col-md-3">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Email"
                    name="emails_contacts[]"
                  /> 
                </div>
                <div class="col-md-1">
                  <a href="#" style="line-height: 3;" class="deleteContactRow deleteContactRow`+rowsCount+`" data-rowID="`+rowsCount+`"><center><i class="ti ti-trash w-100 h5"></i></center></a>
                </div>
              </div>

            `;



            if(rowsCount <= 5)
            {
              $(".contactsRows").append(contactRow);
              phonemask();
              emailmask();
              $(".deleteContactRow.deleteContactRow"+(rowsCount-1)).hide();
            }
            else
            {
              alert("Vous ne pouvez ajouter que 5 Contacts !");
            }
          });

          $(document).on('click', '.deleteContactRow', function(x){
            x.preventDefault();

            var rowID = $(this).data('rowid');

            if (rowID !== 0 && rowID <= 5) {
                $('.contactRow' + rowID).remove();
                $(".deleteContactRow.deleteContactRow"+(rowID-1)).show();
              }
          });
        });
// ------------------------------------------------
document.getElementById('autorite_regulation_check').addEventListener('change', function () {
    document.getElementById('autoriteLabel').textContent = this.checked ? 'Oui' : 'Non';
});

const check = document.getElementById('autorite_regulation_check');
const label = document.getElementById('autoriteLabel');
const inputWrapper = document.getElementById('autoriteInputWrapper');

check.addEventListener('change', function () {
    if (this.checked) {
        label.textContent = 'Oui';
        inputWrapper.classList.remove('d-none');
    } else {
        label.textContent = 'Non';
        inputWrapper.classList.add('d-none');
    }
});