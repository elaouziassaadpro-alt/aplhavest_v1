// contact add row
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
// Coordonnées Bancaires

$(document).ready(function(){
          $('#addCBancairesRowBtn').click(function(e){
            e.preventDefault();
            var rowsCount = $(".CBancairesRowInfos").length;
            rowsCount++;

            var CBancairesRow = `

              <div class="row CBancairesRowInfos CBancairesRow`+rowsCount+`">

                <div class="col-md-3">
                  <select class="form-control banques" name="noms_banques[]">
                    <option value="10000">Selectionnez une banque</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Agence"
                    name="agences_banques[]"
                  /> 
                </div>
                <div class="col-md-2">
                  <select class="form-control villes_banques" name="villes_banques[]">
                    <option value="10000">Selectionnez une ville</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="RIB"
                    name="ribs_banques[]"
                  /> 
                </div>
                <div class="col-md-1">
                  <a href="#" style="line-height: 3;" class="deleteCBancairesRow deleteCBancairesRow`+rowsCount+`" data-rowID="`+rowsCount+`"><center><i class="ti ti-trash w-100 h5"></i></center></a>
                </div>
              </div>

            `;

            if(rowsCount <= 5)
            {
              $(".CBancairesRows").append(CBancairesRow);

              // GET BANQUES
              $('.banques:last').select2({
                ajax: {
                  url: '../../../package/dist/php/get_banques.php',
                  dataType: 'json',
                  delay: 250,
                  data: function (params) {
                    return {
                      q: params.term // la recherche saisie par l'utilisateur
                    };
                  },
                  processResults: function (data) {
                    if(data.error) {
                      alert(data.error);
                      return { results: [] };
                    }
                    return {
                      results: data.map(function(item) {
                        return {
                          id: item.id,
                          text: item.nom
                        };
                      })
                    };
                  },
                  cache: true
                },
                placeholder: 'Sélectionnez un élément',
                minimumInputLength: 0
              });

              // GET VILLES BANQUES
              $('.villes_banques:last').select2({
                ajax: {
                  url: '../../../package/dist/php/get_villes.php',
                  dataType: 'json',
                  delay: 250,
                  data: function (params) {
                    return {
                      q: params.term // la recherche saisie par l'utilisateur
                    };
                  },
                  processResults: function (data) {
                    if(data.error) {
                      alert(data.error);
                      return { results: [] };
                    }
                    return {
                      results: data.map(function(item) {
                        return {
                          id: item.id,
                          text: item.libelle
                        };
                      })
                    };
                  },
                  cache: true
                },
                placeholder: 'Sélectionnez un élément',
                minimumInputLength: 0
              });

              $(".deleteCBancairesRow.deleteCBancairesRow"+(rowsCount-1)).hide();
            }
            else
            {
              alert("Vous ne pouvez ajouter que 5 Comptes Bancaires !");
            }
          });

          $(document).on('click', '.deleteCBancairesRow', function(x){
            x.preventDefault();

            var rowID = $(this).data('rowid');

            if (rowID !== 0 && rowID <= 5) {
                $('.CBancairesRow' + rowID).remove();
                $(".deleteCBancairesRow.deleteCBancairesRow"+(rowID-1)).show();
              }
          });
        });

        // Actionnaires

$(document).ready(function(){
          $('#addActionnaireRowBtn').click(function(e){
            e.preventDefault();
            var rowsCount = $(".actionnaireRowInfos").length;
            rowsCount++;
            var contactRow = `

              <div class="row actionnaireRowInfos actionnaireRow`+rowsCount+`">

                <div class="col-md-2">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Nom / Raison sociale"
                    name="noms_rs_actionnaires[]"
                  /> 
                </div>
                <div class="col-md-2">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Prénom (p.physique)"
                    name="prenoms_actionnaires[]"
                  /> 
                </div>
                <div class="col-md-2">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="N° d'identité / N° du RC"
                    name="identite_actionnaires[]"
                  /> 
                </div>
                <div class="col-md-2">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Nombre de titres"
                    name="nombre_titres_actionnaires[]"
                  /> 
                </div>
                <div class="col-md-3">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="% Capital ou droit de vote"
                    name="pourcentage_capital_actionnaires[]"
                  /> 
                </div>
                <div class="col-md-1">
                  <a href="#" style="line-height: 3;" class="deleteActionnaireRow deleteActionnaireRow`+rowsCount+`" data-rowID="`+rowsCount+`"><center><i class="ti ti-trash w-100 h5"></i></center></a>
                </div>
              </div>

            `;

            if(rowsCount <= 5)
            {
              $(".actionnairesRows").append(contactRow);
              $(".deleteActionnaireRow.deleteActionnaireRow"+(rowsCount-1)).hide();
            }
            else
            {
              alert("Vous ne pouvez ajouter que 5 Actionnaires !");
            }
          });

          $(document).on('click', '.deleteActionnaireRow', function(x){
            x.preventDefault();

            var rowID = $(this).data('rowid');

            if (rowID !== 0 && rowID <= 5) {
                $('.actionnaireRow' + rowID).remove();
                $(".deleteActionnaireRow.deleteActionnaireRow"+(rowID-1)).show();
              }
          });
        });
