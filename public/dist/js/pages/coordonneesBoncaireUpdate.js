    let CBancairesCount = $('.CBancairesRowInfos').length || 0;
    const maxRows = 5;

    function createCBancaireRow(banqueId = '', villeId = '', agence = '', rib = '') {
        CBancairesCount++;
        let banquesList = banquesData.map(b => `<option value="${b.id}" ${b.id == banqueId ? 'selected' : ''}>${b.nom}</option>`).join('');
        let villesList  = villesData.map(v => `<option value="${v.id}" ${v.id == villeId ? 'selected' : ''}>${v.libelle}</option>`).join('');

        return `
        <div class="row CBancairesRowInfos CBancairesRow${CBancairesCount} mb-2 align-items-center">
            <div class="col-md-3 mb-3">
                <label>Banque</label>
                <select name="banque_id[]" class="form-select" required>
                    <option value="">Sélectionnez une banque</option>
                    ${banquesList}
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label>Agence</label>
                <input type="text" class="form-control" placeholder="Agence" name="agences_banque[]" value="${agence}">
            </div>
            <div class="col-md-2 mb-3">
                <label>Ville</label>
                <select name="ville_id[]" class="form-select" required>
                    <option value="">Sélectionnez une ville</option>
                    ${villesList}
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label>RIB</label>
                <input type="text" class="form-control" placeholder="RIB" name="rib_banque[]" value="${rib}">
            </div>
            <div class="col-md-1 text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeCBancaire(${CBancairesCount})" title="Supprimer cette coordonnée bancaire">
                    <i class="ti ti-trash me-1"></i> Supprimer
                </button>
            </div>
        </div>`;
    }

    function addCBancaireRow() {
        if ($('.CBancairesRowInfos').length >= maxRows) {
            alert(`Vous ne pouvez ajouter que ${maxRows} comptes bancaires !`);
            return;
        }
        $('.CBancairesRows').append(createCBancaireRow());
    }

    
