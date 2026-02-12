

    const paysOptions = window.APP_DATA.pays.map(p => `<option value="${p.id}">${p.libelle}</option>`).join('');
    const ppeOptions  = window.APP_DATA.ppes.map(p => `<option value="${p.id}">${p.libelle}</option>`).join('');

   let adminCount = document.querySelectorAll('.administrateurRowInfos').length || 0;

// Ajouter un administrateur
function addAdministrateurRow() {
    adminCount++;
    const row = document.createElement('div');
    row.className = `row mt-3 administrateurRowInfos administrateurRow${adminCount}`;
    row.innerHTML = `
        <div class="col-md-2">
            <input type="text" class="form-control" placeholder="Nom" name="noms_administrateurs[]">
        </div>
        <div class="col-md-1">
            <input type="text" class="form-control" placeholder="Prénom" name="prenoms_administrateurs[]">
        </div>
        <div class="col-md-2">
            <select class="form-control pays" name="pays_administrateurs[]">
                ${paysOptions}
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" class="form-control" name="dates_naissance_administrateurs[]">
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control" placeholder="CIN / Passeport" name="cins_administrateurs[]">
        </div>
        <div class="col-md-2">
            <select class="form-control pays" name="nationalites_administrateurs[]">
                ${paysOptions}
            </select>
        </div>

        <!-- PPE -->
        <div class="col-md-1 d-flex flex-column align-items-start">
            <label>PPE</label>
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input adm_ppe" id="administrateur_ppe_id_${adminCount}" onchange="toggleAdminPPE(${adminCount})">
            </div>
        </div>
        <div class="col-md-2 d-flex flex-column align-items-start">
            <div class="hidden-select" id="administrateur_ppe_data_id_${adminCount}" style="display:none">
                <label>Libelle PPE</label>
                <select class="form-control ppes" name="ppes_administrateurs_input[]">
                    ${ppeOptions}
                </select>
            </div>
        </div>

        <!-- Lien PPE -->
        <div class="col-md-1 d-flex flex-column align-items-start">
            <label>Lien PPE</label>
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input adm_lien" id="administrateur_ppe_lien_id_${adminCount}" onchange="toggleAdminLienPPE(${adminCount})">
            </div>
        </div>
        <div class="col-md-2 d-flex flex-column align-items-start">
            <div class="hidden-select" id="administrateur_ppe_lien_data_id_${adminCount}" style="display:none">
                <label>Libelle Lien PPE</label>
                <select class="form-control ppes" name="ppes_lien_administrateurs_input[]">
                    ${ppeOptions}
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <input type="text" class="form-control" name="fonctions_administrateurs[]" placeholder="Fonction">
        </div>
        <div class="col-md-1">
            <input type="file" class="form-control" name="cin_administrateurs[]">
        </div>
        <div class="col-md-1">
            <input type="file" class="form-control" name="pvn_administrateurs[]">
        </div>
        <div class="col-md-1 text-center">
            <a href="#" class="text-danger" onclick="removeAdministrateur(${adminCount})">
                <i class="ti ti-trash"></i>
            </a>
        </div>
    `;
    document.querySelector('.administrateursRows').appendChild(row);
}

// Supprimer administrateur
function removeAdministrateur(index) {
    const row = document.querySelector(`.administrateurRow${index}`);
    if(row) row.remove();
}
function toggleAdminPPE(index) {

    let check  = document.getElementById('administrateur_ppe_id_' + index);
    let hidden = document.getElementById('hidden_admin_ppe_' + index);
    let block  = document.getElementById('administrateur_ppe_data_id_' + index);
    let label  = document.getElementById('label_admin_ppe_' + index);

    if (check.checked) {
        hidden.value = 1;
        block.style.display = 'block';
        label.innerText = 'Oui';
    } else {
        hidden.value = 0;
        block.style.display = 'none';
        label.innerText = 'Non';
    }
}

function toggleAdminLienPPE(index) {

    let check  = document.getElementById('administrateur_ppe_lien_id_' + index);
    let hidden = document.getElementById('hidden_admin_lien_' + index);
    let block  = document.getElementById('administrateur_ppe_lien_data_id_' + index);
    let label  = document.getElementById('label_admin_lien_' + index);

    if (check.checked) {
        hidden.value = 1;
        block.style.display = 'block';
        label.innerText = 'Oui';
    } else {
        hidden.value = 0;
        block.style.display = 'none';
        label.innerText = 'Non';
    }
}
