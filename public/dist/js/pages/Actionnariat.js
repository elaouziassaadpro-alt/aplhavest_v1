document.addEventListener('DOMContentLoaded', function () {

    let maxRows = 5;

    document.getElementById('addActionnaireRowBtn').addEventListener('click', function (e) {
        e.preventDefault();

        let rows = document.querySelectorAll('.actionnaireRowInfos');
        let rowsCount = rows.length + 1;

        if (rowsCount > maxRows) {
            alert("Vous ne pouvez ajouter que 5 Actionnaires !");
            return;
        }

        let row = `
        <div class="row actionnaireRowInfos actionnaireRow${rowsCount} mt-2">
            <div class="col-md-2">
                <input type="text" class="form-control" name="noms_rs_actionnaires[]" placeholder="Nom / Raison sociale">
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" name="prenoms_actionnaires[]" placeholder="Prénom (p.physique)">
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" name="identite_actionnaires[]" placeholder="N° d'identité / RC">
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="nombre_titres_actionnaires[]" placeholder="Nombre de titres">
            </div>
            <div class="col-md-3">
                <input type="number" step="0.01" class="form-control" name="pourcentage_capital_actionnaires[]" placeholder="% Capital">
            </div>
            <div class="col-md-1 text-center">
                <a href="#" class="deleteActionnaireRow" data-row="${rowsCount}">
                    <i class="ti ti-trash text-danger"></i>
                </a>
            </div>
        </div>
        `;

        document.querySelector('.actionnairesRows').insertAdjacentHTML('beforeend', row);

        // cacher l’icône delete précédente
        if (rowsCount > 1) {
            document.querySelector(`.actionnaireRow${rowsCount - 1} .deleteActionnaireRow`)
                .style.display = 'none';
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.deleteActionnaireRow')) {
            e.preventDefault();
            let btn = e.target.closest('.deleteActionnaireRow');
            let rowId = btn.dataset.row;

            document.querySelector('.actionnaireRow' + rowId).remove();

            if (rowId > 1) {
                document.querySelector(`.actionnaireRow${rowId - 1} .deleteActionnaireRow`)
                    .style.display = 'inline';
            }
        }
    });

});
