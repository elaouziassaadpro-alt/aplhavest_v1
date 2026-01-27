$(document).ready(function() {

    // Add new row
    $('#addBenificiaireRowBtn').click(function(e) {
        e.preventDefault();

        let rowsCount = $(".benificiaireRowInfos").length + 1;
        if(rowsCount > 5) {
            alert("Vous ne pouvez ajouter que 5 Bénéficiaires !");
            return;
        }

        // Row HTML
        let contactRow = `
<div class="row benificiaireRowInfos benificiaireRow${rowsCount}">

    <input type="hidden" name="ppe2_benificiaires[]" value="0">
    <input type="hidden" name="lien2_benificiaires[]" value="0">

    <div class="col-md-2">
        <input type="text" class="form-control" placeholder="Nom / Raison sociale" name="noms_rs_benificiaires[]" />
    </div>
    <div class="col-md-2">
        <input type="text" class="form-control" placeholder="Prénom (p.physique)" name="prenoms_benificiaires[]" />
    </div>
    <div class="col-md-2">
        <select class="form-control pays" name="pays_naissance_benificiaires[]">
            <option value="10000">Pays de naissance</option>
        </select>
    </div>
    <div class="col-md-2">
        <input type="date" class="form-control" name="dates_naissance_benificiaires[]">
    </div>
    <div class="col-md-1">
        <input type="text" class="form-control" placeholder="CIN / Passeport" name="identite_benificiaires[]" />
    </div>
    <div class="col-md-2">
        <select class="form-control pays" name="nationalites_benificiaires[]">
            <option value="10000">Nationalité</option>
        </select>
    </div>
    <div class="col-md-1 text-center">
        <a href="#" class="deleteBenificiaireRow" data-rowid="${rowsCount}"><i class="ti ti-trash"></i></a>
    </div>
    <!-- ------------------------------------------------------------------------------------------------- -->
    <!-- PPE -->
    <div class="col-md-1 text-right">
        <label>PPE</label>
        <div class="form-check form-switch">
            <input class="form-check-input ben_ppe" type="checkbox" id="benificiaire_ppe_id_${rowsCount}" name="benificiaires_ppe_check[]">
            <label class="form-check-label"><span id="label_ppe_${rowsCount}">Non</span></label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="hidden-select" id="benificiaire_ppe_data_id_${rowsCount}" style="display:none;">
            <label>Libelle PPE</label>
            <select class="form-control ppes" name="benificiaires_ppe_input[]">
                <option value="10000">PPE</option>
            </select>
        </div>
    </div>

    <!-- Lien PPE -->
    <div class="col-md-2 text-right ">
        <label>Lien PPE</label>
        <div class="form-check form-switch">
            <input class="form-check-input ben_lien" type="checkbox" id="benificiaire_ppe_lien_id_${rowsCount}" name="benificiaires_ppe_lien_check[]">
            <label class="form-check-label"><span id="label_lien_${rowsCount}">Non</span></label>
        </div>
    </div>
    <div class="col-md-2">
        <div class="hidden-select" id="benificiaire_ppe_lien_data_id_${rowsCount}" style="display:none;">
            <label>Libelle PPE</label>
            <select class="form-control ppes" name="benificiaires_ppe_lien_input[]">
                <option value="10000">PPE</option>
            </select>
        </div>
    </div>

    <!-- ------------------------------------------------------------------------------------------------- -->

    <div class="col-md-2">
        <label>% du capital</label>
        <input type="text" class="form-control" placeholder="% du capital" name="benificiaires_pourcentage_capital[]" />
    </div>

    <div class="col-md-1">
    <label>CIN</label>
        <input type="file" id="cin_file_ben_${rowsCount}" name="cin_benificiaires[]" style="display:none;" accept=".pdf,.jpg,.png" />
        <label for="cin_file_ben_${rowsCount}" class="btn btn-primary w-100">CIN</label>
    </div>
    <hr class="mt-3 mb-3" />
</div>
`
;

         $(".benificiairesRows").append(contactRow);

        if (typeof getpays === "function") getpays();
        if (typeof getppes === "function") getppes();
    });

    // Delete row
    $(document).on('click', '.deleteBenificiaireRow', function(e) {
        e.preventDefault();
        $(this).closest('.benificiaireRowInfos').remove();
    });

    // **Dynamic toggle for PPE**
    $(document).on('change', '.ben_ppe', function() {
        let rowID = $(this).attr('id').split('_').pop();
        $(`#benificiaire_ppe_data_id_${rowID}`).toggle($(this).is(':checked'));
        $(`#label_ppe_${rowID}`).text($(this).is(':checked') ? 'Oui' : 'Non');
    });

    // **Dynamic toggle for Lien PPE**
    $(document).on('change', '.ben_lien', function() {
        let rowID = $(this).attr('id').split('_').pop();
        $(`#benificiaire_ppe_lien_data_id_${rowID}`).toggle($(this).is(':checked'));
        $(`#label_lien_${rowID}`).text($(this).is(':checked') ? 'Oui' : 'Non');
    });


    // Delete row
    $(document).on('click', '.deleteBenificiaireRow', function(e) {
        e.preventDefault();
        $(this).closest('.benificiaireRowInfos').remove();
    });

});
