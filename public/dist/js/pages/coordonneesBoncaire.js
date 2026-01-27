// add cordonnees bancaires selector
$(document).ready(function () {

    $('#addCBancairesRowBtn').on('click', function (e) {
        e.preventDefault();

        let rowsCount = $('.CBancairesRowInfos').length + 1;

        if (rowsCount > 5) {
            alert("Vous ne pouvez ajouter que 5 Comptes Bancaires !");
            return;
        }

        let banquesList = '';
        banquesData.forEach(banque => {
            banquesList += `<li data-value="${banque.id}">${banque.nom}</li>`;
        });
        let villesList = '';
        villesData.forEach(ville => {
            villesList += `<li data-value="${ville.id}">${ville.libelle}</li>`;
        });

        let CBancairesRow = `
        <div class="row CBancairesRowInfos CBancairesRow${rowsCount}">
        

            <div class="col-md-3 mb-3">
                <div class="custom-select-wrapper">
                    <input
                        type="text"
                        class="form-control banque-search"
                        placeholder="Sélectionnez une banque"
                    >
                    <ul class="custom-dropdown">
                        ${banquesList}
                    </ul>
                    <input
                        type="hidden"
                        name="banque_id[]"
                        required
                    >
                </div>
            </div>
            <div class="col-md-3">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Agence"
                    name="agences_banque[]"
                />
            </div>

            <div class="col-md-2">
                <div class="custom-select-wrapper">
                    <input
                        type="text"
                        class="form-control banque-search"
                        placeholder="Sélectionnez une ville"
                    >
                    <ul class="custom-dropdown">
                        ${villesList}
                    </ul>
                    <input
                        type="hidden"
                        name="ville_id[]"
                        required
                    >
                </div>
            </div>

            <div class="col-md-3">
                <input
                    type="text"
                    class="form-control"
                    placeholder="RIB"
                    name="rib_banque[]"
                />
            </div>

            <div class="col-md-1 text-center">
                <a
                    href="#"
                    class="deleteCBancairesRow deleteCBancairesRow${rowsCount}"
                    data-rowid="${rowsCount}"
                    style="line-height: 3;"
                >
                    <i class="ti ti-trash h5"></i>
                </a>
            </div>
        </div>
        `;

        $('.CBancairesRows').append(CBancairesRow);

        $('.deleteCBancairesRow' + (rowsCount - 1)).hide();
    });

    // DELETE ROW
    $(document).on('click', '.deleteCBancairesRow', function (e) {
        e.preventDefault();

        let rowID = $(this).data('rowid');
        $('.CBancairesRow' + rowID).remove();

        if (rowID > 1) {
            $('.deleteCBancairesRow' + (rowID - 1)).show();
        }
    });

    // BANQUE SELECT
    $(document).on('click', '.custom-dropdown li', function () {
        let wrapper = $(this).closest('.custom-select-wrapper');

        wrapper.find('.banque-search').val($(this).text());
        wrapper.find('input[type="hidden"]').val($(this).data('value'));
        wrapper.find('.custom-dropdown').hide();
    });

    $(document).on('focus', '.banque-search', function () {
        $(this).siblings('.custom-dropdown').show();
    });

});
//---------------------------------------------------------------------------

