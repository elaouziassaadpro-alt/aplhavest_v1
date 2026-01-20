$(function () {
  "use strict";

  // Feather Icon Init Js
  // feather.replace();

  // $(".preloader").fadeOut();

  // =================================
  // Tooltip
  // =================================
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // =================================
  // Popover
  // =================================
  var popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="popover"]')
  );
  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });

  // increment & decrement
  $(".minus,.add").on("click", function () {
    var $qty = $(this).closest("div").find(".qty"),
      currentVal = parseInt($qty.val()),
      isAdd = $(this).hasClass("add");
    !isNaN(currentVal) &&
      $qty.val(
        isAdd ? ++currentVal : currentVal > 0 ? --currentVal : currentVal
      );
  });

  // ==============================================================
  // Collapsable cards
  // ==============================================================
  $('a[data-action="collapse"]').on("click", function (e) {
    e.preventDefault();
    $(this)
      .closest(".card")
      .find('[data-action="collapse"] i')
      .toggleClass("ti-minus ti-plus");
    $(this).closest(".card").children(".card-body").collapse("toggle");
  });
  // Toggle fullscreen
  $('a[data-action="expand"]').on("click", function (e) {
    e.preventDefault();
    $(this)
      .closest(".card")
      .find('[data-action="expand"] i')
      .toggleClass("ti-arrows-maximize ti-arrows-maximize");
    $(this).closest(".card").toggleClass("card-fullscreen");
  });
  // Close Card
  $('a[data-action="close"]').on("click", function () {
    $(this).closest(".card").removeClass().slideUp("fast");
  });

  // fixed header
  $(window).scroll(function () {
    if ($(window).scrollTop() >= 60) {
      $(".app-header").addClass("fixed-header");
    } else {
      $(".app-header").removeClass("fixed-header");
    }
  });

  // Checkout
  $(function () {
    $(".billing-address").click(function () {
      $(".billing-address-content").hide();
    });
    $(".billing-address").click(function () {
      $(".payment-method-list").show();
    });
  });
});

/*change layout boxed/full */
$(".full-width").click(function () {
  $(".container-fluid").addClass("mw-100");
  $(".full-width i").addClass("text-primary");
  $(".boxed-width i").removeClass("text-primary");
});
$(".boxed-width").click(function () {
  $(".container-fluid").removeClass("mw-100");
  $(".full-width i").removeClass("text-primary");
  $(".boxed-width i").addClass("text-primary");
});

/*Dark/Light theme*/
$(".light-logo").hide();
$(".dark-theme").click(function () {
  $("nav.navbar-light").addClass("navbar-dark");
  $(".dark-theme i").addClass("text-primary");
  $(".light-theme i").removeClass("text-primary");
  $(".light-logo").show();
  $(".dark-logo").hide();
});
$(".light-theme").click(function () {
  $("nav.navbar-light").removeClass("navbar-dark");
  $(".dark-theme i").removeClass("text-primary");
  $(".light-theme i").addClass("text-primary");
  $(".light-logo").hide();
  $(".dark-logo").show();
});

/*Card border/shadow*/
$(".cardborder").click(function () {
  $("body").addClass("cardwithborder");
  $(".cardshadow i").addClass("text-dark");
  $(".cardborder i").addClass("text-primary");
});
$(".cardshadow").click(function () {
  $("body").removeClass("cardwithborder");
  $(".cardborder i").removeClass("text-primary");
  $(".cardshadow i").removeClass("text-dark");
});

$(".change-colors li a").click(function () {
  $(".change-colors li a").removeClass("active-theme");
  $(this).addClass("active-theme");
});

/*Theme color change*/
function toggleTheme(value) {
  $(".preloader").show();
  var sheets = document.getElementById("themeColors");
  sheets.href = value;
  $(".preloader").fadeOut();
}
$(".preloader").fadeOut();


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



// Cnasnu check actionnaires


function setupAutocomplete(input) {
    let suggestionsDiv = document.createElement('div');
    suggestionsDiv.classList.add('suggestions');
    suggestionsDiv.style.cssText = 'border:1px solid #ccc; display:none; position:absolute; background:#fff; z-index:1000;';
    input.parentNode.appendChild(suggestionsDiv);

    input.addEventListener('input', function() {
        const value = this.value.trim();
        if (value.length < 1) {
            suggestionsDiv.style.display = 'none';
            return;
        }

        fetch('check_correspondance_ajax.php?nom=' + encodeURIComponent(value))
            .then(res => res.json())
            .then(data => {
                suggestionsDiv.innerHTML = '';
                if (data.length === 0) {
                    suggestionsDiv.style.display = 'none';
                    return;
                }

                data.forEach(item => {
                    const div = document.createElement('div');
                    div.style.padding = '5px';
                    div.style.cursor = 'pointer';
                    div.style.borderBottom = '1px solid #eee';
                    div.innerHTML = `
                        ${item.noms.first_name}, ${item.noms.second_name}, ${item.noms.third_name}, ${item.noms.fourth_name} 
                        - <strong>${item.pourcentage}%</strong>
                    `;
                    div.addEventListener('click', () => {
                        input.value = item.noms.first_name; // tu peux mettre la colonne que tu veux
                        suggestionsDiv.style.display = 'none';
                    });
                    suggestionsDiv.appendChild(div);
                });

                suggestionsDiv.style.display = 'block';
            });
    });

    document.addEventListener('click', (e) => {
        if (!suggestionsDiv.contains(e.target) && e.target !== input) {
            suggestionsDiv.style.display = 'none';
        }
    });
}

// Initialisation pour tous les inputs existants
document.querySelectorAll('.actionnaireRowInfos input[name="noms_rs_actionnaires[]"]').forEach(input => setupAutocomplete(input));

// Si tu ajoutes des lignes dynamiques, rappelle setupAutocomplete sur le nouvel input
function addActionnaireRow() {
    const container = document.querySelector('.actionnairesRows');
    const rowsCount = container.querySelectorAll('.actionnaireRowInfos').length + 1;

    const newRow = document.createElement('div');
    newRow.className = 'row actionnaireRowInfos actionnaireRow' + rowsCount;
    newRow.innerHTML = `
        <div class="col-md-2">
            <input type="text" class="form-control" placeholder="Nom / Raison sociale" name="noms_rs_actionnaires[]">
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control" placeholder="Prénom (p.physique)" name="prenoms_actionnaires[]">
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control" placeholder="N° d'identité / N° du RC" name="identite_actionnaires[]">
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control" placeholder="Nombre de titres" name="nombre_titres_actionnaires[]">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" placeholder="% Capital ou droit de vote" name="pourcentage_capital_actionnaires[]">
        </div>
        <div class="col-md-1">
            <a href="#" style="line-height: 3;" class="deleteActionnaireRow deleteActionnaireRow${rowsCount}" data-rowid="${rowsCount}">
                <center><i class="ti ti-trash w-100 h5"></i></center>
            </a>
        </div>
    `;

    container.appendChild(newRow);

    // Active autocomplete sur le nouvel input
    setupAutocomplete(newRow.querySelector('input[name="noms_rs_actionnaires[]"]'));
}




// Bénificiaires

$(document).ready(function () {
  // Activer tous les tooltips Bootstrap
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Mise à jour du tooltip avec le nom du fichier
  $(document).on('change', 'input[type="file"]', function () {
    const fileName = $(this).val().split('\\').pop(); // Nom du fichier
    const label = $("label[for='" + $(this).attr('id') + "']");


    // Mettre à jour le tooltip
    label.attr('data-bs-original-title', fileName || "Joindre CIN");

    // Forcer l'actualisation du tooltip
    const tooltipInstance = bootstrap.Tooltip.getInstance(label[0]);
    if (tooltipInstance) {
      tooltipInstance.setContent({ '.tooltip-inner': fileName });

    } else {
      label.each(function() {
          new bootstrap.Tooltip(this);
          label.addClass('btn-success');
      });
    }
  });
});

$(document).ready(function(){
          $('#addBenificiaireRowBtn').click(function(e){
            e.preventDefault();
            var rowsCount = $(".benificiaireRowInfos").length;
            rowsCount++;
            var contactRow = `

              <div class="row benificiaireRowInfos benificiaireRow`+rowsCount+`">

                <input type="text" name="ppe2_benificiaires[]" value="0" class="fichiers_caches" id="benificiaire_ppe2_id_`+rowsCount+`">
                <input type="text" name="lien2_benificiaires[]" value="0" class="fichiers_caches" id="benificiaire_lien2_id_`+rowsCount+`">

                <div class="col-md-2">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Nom / Raison sociale"
                    name="noms_rs_benificiaires[]"
                  /> 
                </div>
                <div class="col-md-2">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Prénom (p.physique)"
                    name="prenoms_benificiaires[]"
                  /> 
                </div>
                <div class="col-md-2">
                  <select class="form-control pays" name="pays_naissance_benificiaires[]">
                    <option value="10000">Pays de naissance</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                                  <input type="date" class="form-control" value="2025-05-13" name="dates_naissance_benificiaires[]">
                              </div> 
                </div>
                <div class="col-md-1">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="CIN / Passeport"
                    name="identite_benificiaires[]"
                  /> 
                </div>
                <div class="col-md-2">
                  <select class="form-control pays" name="nationalites_benificiaires[]">
                    <option value="10000">Nationalité</option>
                  </select>
                </div>

                <div class="col-md-1">
                  <a href="#" style="line-height: 3;" class="deleteBenificiaireRow deleteBenificiaireRow`+rowsCount+`" data-rowID="`+rowsCount+`"><center><i class="ti ti-trash w-100 h5"></i></center></a>
                </div>

                <div class="col-md-1">
                  <div class="mb-6">
                    <div class="mb-4 bt-switch">
                      <label>PPE </label>
                      <input
                        type="checkbox"
                        data-checked="false"
                        data-on-color="primary"
                        data-off-color="default"
                        data-off-text="Non"
                        data-on-text="Oui"
                  class="smart-switch ben_ppe"
                  id="benificiaire_ppe_id_`+rowsCount+`"
                  name="benificiaires_ppe_check[]"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="hidden-select" id="benificiaire_ppe_data_id_`+rowsCount+`" style="display:none;">

                  <label>Libelle PPE </label>
                  <select class="form-control ppes" name="benificiaires_ppe_input[]">
                    <option value="10000">PPE</option>
                  </select>

                  </div>
                </div>

                <div class="col-md-2">
                  <div class="mb-6">
                    <div class="mb-4 bt-switch">
                      <label>Lien avec une personne PPE </label>
                      <input
                        type="checkbox"
                        data-checked="false"
                        data-on-color="primary"
                        data-off-color="default"
                        data-off-text="Non"
                        data-on-text="Oui"
                  class="smart-switch ben_lien"
                  id="benificiaire_ppe_lien_id_`+rowsCount+`"
                  name="benificiaires_ppe_lien_check[]"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="hidden-select" id="benificiaire_ppe_lien_data_id_`+rowsCount+`" style="display:none;">

                  <label>Libelle PPE </label>
                  <select class="form-control ppes" name="benificiaires_ppe_lien_input[]">
                    <option value="10000">PPE</option>
                  </select>

                  </div>
                </div>
                <div class="col-md-2">
                  <label>% du capital</label>
                  <input
                    type="text"
                    class="form-control"
                    placeholder="% du capital"
                   name="benificiaires_pourcentage_capital[]"
                  /> 
                </div>
                  <div class="col-md-1">
                  <label></label>
                    <input type="file" 
                             id="cin_file_ben_` + rowsCount + `"
                             name="cin_benificiaires[]" 
                             style="display:none;" 
                             accept=".pdf,.jpg,.png" />

                      <label for="cin_file_ben_` + rowsCount + `" class="btn btn-primary w-100" data-bs-toggle="tooltip" data-bs-original-title="Joindre CIN">
                        <i class="ti ti-upload"></i>&nbsp; CIN
                      </label>
                  </div>
                  `;

                  if(rowsCount >= 1)
                  {
                    contactRow += "<hr>";
                  }

                contactRow +=  `
              </div>


            `;

            if(rowsCount <= 5)
            {

              $(".benificiairesRows").append(contactRow);
              getpays();
              getppes();

              $("#benificiaire_ppe_id_"+rowsCount).on('switchChange.bootstrapSwitch', function(event, state) {
                $("#benificiaire_ppe_data_id_"+rowsCount).show();
                  if(state) {
                      // Checkbox cochée → afficher la div
                      $("#benificiaire_ppe_data_id_"+rowsCount).show();
                  } else {
                      // Checkbox décochée → cacher la div
                      $("#benificiaire_ppe_data_id_"+rowsCount).hide();
                  }
              });

              $("#benificiaire_ppe_lien_id_"+rowsCount).on('switchChange.bootstrapSwitch', function(event, state) {
                $("#benificiaire_ppe_lien_data_id_"+rowsCount).show();
                  if(state) {
                      // Checkbox cochée → afficher la div
                      $("#benificiaire_ppe_lien_data_id_"+rowsCount).show();
                  } else {
                      // Checkbox décochée → cacher la div
                      $("#benificiaire_ppe_lien_data_id_"+rowsCount).hide();
                  }
              });

              $('[type="checkbox"].smart-switch').bootstrapSwitch();
              $(".deleteBenificiaireRow.deleteBenificiaireRow"+(rowsCount-1)).hide();
            }
            else
            {
              alert("Vous ne pouvez ajouter que 5 Bénificiaires !");
            }
          });

          $(document).on('click', '.deleteBenificiaireRow', function(x){
            x.preventDefault();

            var rowID = $(this).data('rowid');

            if (rowID !== 0 && rowID <= 5) {
                $('.benificiaireRow' + rowID).remove();
                $(".deleteBenificiaireRow.deleteBenificiaireRow"+(rowID-1)).show();
              }
          });
        });

// Administrateurs / Dirigeants

$(document).ready(function(){
          $('#addAdministrateurRowBtn').click(function(e){
            e.preventDefault();
            var rowsCount = $(".administrateurRowInfos").length;
            rowsCount++;
            var contactRow = `

              <div class="row administrateurRowInfos administrateurRow`+rowsCount+`">

              <input type="text" name="ppe2_administrateurs[]" value="0" class="fichiers_caches" id="administrateur_ppe2_id_`+rowsCount+`">
              <input type="text" name="lien2_administrateurs[]" value="0" class="fichiers_caches" id="administrateur_lien2_id_`+rowsCount+`">

                <div class="col-md-2">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Nom"
                    name="noms_administrateurs[]"
                  /> 
                </div>
                <div class="col-md-1">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Prénom"
                    name="prenoms_administrateurs[]"
                  /> 
                </div>
                <div class="col-md-2">
                  <select class="form-control pays"
                    name="pays_administrateurs[]">
                    <option value="10000">Pays de naissance</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                                  <input type="date" class="form-control" value="2025-05-13"
                    name="dates_naissance_administrateurs[]">
                              </div> 
                </div>
                <div class="col-md-2">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="CIN / Passeport"
                    name="cins_administrateurs[]"
                  /> 
                </div>
                <div class="col-md-2">
                  <select class="form-control pays"
                    name="nationalites_administrateurs[]">
                    <option value="10000">Nationalité</option>
                  </select>
                </div>

                <div class="col-md-1">
                  <a href="#" style="line-height: 3;" class="deleteAdministrateurRow deleteAdministrateurRow`+rowsCount+`" data-rowID="`+rowsCount+`"><center><i class="ti ti-trash w-100 h5"></i></center></a>
                </div>

                <div class="col-md-1">
                  <div class="mb-6">
                    <div class="mb-4 bt-switch">
                      <label>PPE </label>
                      <input
                        type="checkbox"
                        data-checked="false"
                        data-on-color="primary"
                        data-off-color="default"
                        data-off-text="Non"
                        data-on-text="Oui"
                  class="smart-switch adm_ppe"
                  id="administrateur_ppe_id_`+rowsCount+`"
                    name="ppes_administrateurs_check[]"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="hidden-select" id="administrateur_ppe_data_id_`+rowsCount+`" style="display:none;">

                  <label>Libelle PPE </label>
                  <select class="form-control ppes"
                    name="ppes_administrateurs_input[]">
                    <option value="10000">PPE</option>
                  </select>

                  </div>
                </div>

                <div class="col-md-2">
                  <div class="mb-6">
                    <div class="mb-4 bt-switch">
                      <label>Lien avec une personne PPE </label>
                      <input
                        type="checkbox"
                        data-checked="false"
                        data-on-color="primary"
                        data-off-color="default"
                        data-off-text="Non"
                        data-on-text="Oui"
                  class="smart-switch adm_lien"
                  id="administrateur_ppe_lien_id_`+rowsCount+`"
                    name="ppes_lien_administrateurs_check[]"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="hidden-select" id="administrateur_ppe_lien_data_id_`+rowsCount+`" style="display:none;">

                  <label>Libelle PPE </label>
                  <select class="form-control ppes"
                    name="ppes_lien_administrateurs_input[]">
                    <option value="10000">PPE</option>
                  </select>

                  </div>
                </div>
                <div class="col-md-2">
                  <label>Fonction </label>
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Fonction"
                    name="fonctions_administrateurs[]"
                  /> 
                </div>
                  <div class="col-md-1">
                    <div class="mb-3">
                      <label style="width:100%;"></label>
                      <input type="file" 
                               id="cin_file_adm_` + rowsCount + `"
                               name="cin_administrateurs[]" 
                               style="display:none;" 
                               accept=".pdf,.jpg,.png" />

                        <label for="cin_file_adm_` + rowsCount + `" class="btn btn-primary w-100" data-bs-toggle="tooltip" data-bs-original-title="Joindre CIN">
                          <i class="ti ti-upload"></i>&nbsp; CIN
                        </label>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="mb-3">
                      <label style="width:100%;"></label>
                      <input type="file" 
                               id="pvn_file_adm_` + rowsCount + `"
                               name="pvn_administrateurs[]" 
                               style="display:none;" 
                               accept=".pdf,.jpg,.png" />

                        <label for="pvn_file_adm_` + rowsCount + `" class="btn btn-primary w-100" data-bs-toggle="tooltip" data-bs-original-title="Joindre CIN">
                          <i class="ti ti-upload"></i>&nbsp; PV nomination
                        </label>
                    </div>
                  </div>
                  `;

                  if(rowsCount >= 1)
                  {
                    contactRow += "<hr>";
                  }

                contactRow +=  `
              </div>


            `;

            if(rowsCount <= 5)
            {

              $(".administrateursRows").append(contactRow);
              getpays();
              getppes();

              $("#administrateur_ppe_id_"+rowsCount).on('switchChange.bootstrapSwitch', function(event, state) {
                $("#administrateur_ppe_data_id_"+rowsCount).show();
                  if(state) {
                      // Checkbox cochée → afficher la div
                      $("#administrateur_ppe_data_id_"+rowsCount).show();
                  } else {
                      // Checkbox décochée → cacher la div
                      $("#administrateur_ppe_data_id_"+rowsCount).hide();
                  }
              });

              $("#administrateur_ppe_lien_id_"+rowsCount).on('switchChange.bootstrapSwitch', function(event, state) {
                $("#administrateur_ppe_lien_data_id_"+rowsCount).show();
                  if(state) {
                      // Checkbox cochée → afficher la div
                      $("#administrateur_ppe_lien_data_id_"+rowsCount).show();
                  } else {
                      // Checkbox décochée → cacher la div
                      $("#administrateur_ppe_lien_data_id_"+rowsCount).hide();
                  }
              });

              $('[type="checkbox"].smart-switch').bootstrapSwitch();
              $(".deleteAdministrateurRow.deleteAdministrateurRow"+(rowsCount-1)).hide();
            }
            else
            {
              alert("Vous ne pouvez ajouter que 5 Administrateurs ou dirigeants !");
            }
          });

          $(document).on('click', '.deleteAdministrateurRow', function(x){
            x.preventDefault();

            var rowID = $(this).data('rowid');

            if (rowID !== 0 && rowID <= 5) {
                $('.administrateurRow' + rowID).remove();
                $(".deleteAdministrateurRow.deleteAdministrateurRow"+(rowID-1)).show();
              }
          });
        });

// Personnes habilités à faire fonctionner le compte

$(document).ready(function(){
          $('#addPHabiliteRowBtn').click(function(e){
            e.preventDefault();
            var rowsCount = $(".phabiliteRowInfos").length;
            rowsCount++;
            var contactRow = `

              <div class="row phabiliteRowInfos phabiliteRow`+rowsCount+`">

              <input type="text" name="ppe2_habilites[]" value="0" class="fichiers_caches" id="habilite_ppe2_id_`+rowsCount+`">
              <input type="text" name="lien2_habilites[]" value="0" class="fichiers_caches" id="habilite_lien2_id_`+rowsCount+`">

                <div class="col-md-2">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Nom"
                    name="noms_habilites[]"
                  /> 
                </div>
                <div class="col-md-2">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Prénom"
                    name="prenoms_habilites[]"
                  /> 
                </div>
                <div class="col-md-2">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="N° CIN / Passeport"
                    name="cin_habilites[]"
                  /> 
                </div>
                <div class="col-md-1">
                  <input type="file" 
                           id="cin_file_hab_` + rowsCount + `"
                           name="cin_habilites[]" 
                           style="display:none;" 
                           accept=".pdf,.jpg,.png" />

                    <label for="cin_file_hab_` + rowsCount + `" class="btn btn-primary w-100" data-bs-toggle="tooltip" data-bs-original-title="CIN">
                      <i class="ti ti-upload"></i>&nbsp; CIN
                    </label>
                </div>
                <div class="col-md-2">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Fonction"
                    name="fonctions_habilites[]"
                  /> 
                </div>
                <div class="col-md-2">
                  <input type="file" 
                           id="hab_file_hab_` + rowsCount + `"
                           name="hab_habilites[]" 
                           style="display:none;" 
                           accept=".pdf,.jpg,.png" />

                    <label for="hab_file_hab_` + rowsCount + `" class="btn btn-primary w-100" data-bs-toggle="tooltip" data-bs-original-title="Habilitation">
                      <i class="ti ti-upload"></i>&nbsp; Habilitation
                    </label>
                </div>
                <div class="col-md-1">
                  <a href="#" style="line-height: 3;" class="deletePHabiliteRow deletePHabiliteRow`+rowsCount+`" data-rowID="`+rowsCount+`"><center><i class="ti ti-trash w-100 h5"></i></center></a>
                </div>

                <div class="row">
                  <div class="col-md-1">
                    <div class="mb-6">
                      <div class="mb-4 bt-switch">
                        <label>PPE </label>
                        <input
                          type="checkbox"
                          data-checked="false"
                          data-on-color="primary"
                          data-off-color="default"
                          data-off-text="Non"
                          data-on-text="Oui"
                    class="smart-switch hab_ppe"
                    id="habilite_ppe_id_`+rowsCount+`"
                      name="ppes_habilites_check[]"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="hidden-select" id="habilite_ppe_data_id_`+rowsCount+`" style="display:none;">

                    <label>Libelle PPE </label>
                    <select class="form-control ppes"
                      name="ppes_habilites_input[]">
                      <option value="10000">PPE</option>
                    </select>

                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="mb-6">
                      <div class="mb-4 bt-switch">
                        <label>Lien avec une personne PPE </label>
                        <input
                          type="checkbox"
                          data-checked="false"
                          data-on-color="primary"
                          data-off-color="default"
                          data-off-text="Non"
                          data-on-text="Oui"
                    class="smart-switch hab_lien"
                    id="habilite_ppe_lien_id_`+rowsCount+`"
                      name="ppes_lien_habilites_check[]"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="hidden-select" id="habilite_ppe_lien_data_id_`+rowsCount+`" style="display:none;">

                    <label>Libelle PPE </label>
                    <select class="form-control ppes"
                      name="ppes_lien_habilites_input[]">
                      <option value="10000">PPE</option>
                    </select>

                    </div>
                  </div>
                </div>


              </div>



              

            `;

            if(rowsCount <= 5)
            {
              $(".phabilitesRows").append(contactRow);
              $(".deletePHabiliteRow.deletePHabiliteRow"+(rowsCount-1)).hide();

              getpays();
              getppes();

              $("#habilite_ppe_id_"+rowsCount).on('switchChange.bootstrapSwitch', function(event, state) {
                $("#habilite_ppe_data_id_"+rowsCount).show();
                  if(state) {
                      // Checkbox cochée → afficher la div
                      $("#habilite_ppe_data_id_"+rowsCount).show();
                  } else {
                      // Checkbox décochée → cacher la div
                      $("#habilite_ppe_data_id_"+rowsCount).hide();
                  }
              });

              $("#habilite_ppe_lien_id_"+rowsCount).on('switchChange.bootstrapSwitch', function(event, state) {
                $("#habilite_ppe_lien_data_id_"+rowsCount).show();
                  if(state) {
                      // Checkbox cochée → afficher la div
                      $("#habilite_ppe_lien_data_id_"+rowsCount).show();
                  } else {
                      // Checkbox décochée → cacher la div
                      $("#habilite_ppe_lien_data_id_"+rowsCount).hide();
                  }
              });

              $('[type="checkbox"].smart-switch').bootstrapSwitch();

            }
            else
            {
              alert("Vous ne pouvez ajouter que 5 Personnes Habilités !");
            }
          });

          $(document).on('click', '.deletePHabiliteRow', function(x){
            x.preventDefault();

            var rowID = $(this).data('rowid');

            if (rowID !== 0 && rowID <= 5) {
                $('.phabiliteRow' + rowID).remove();
                $(".deletePHabiliteRow.deletePHabiliteRow"+(rowID-1)).show();
              }
          });
        });


$(document).ready(function(){
  $('#formes_juridiques_id').select2({
    ajax: {
      url: '../../../package/dist/php/get_formes_juridiques.php',
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
            if(item.id == "10000")
            {
              return;
            }
            else
            {
              return {
                id: item.id,
                text: item.libelle + ' (' + item.code + ')'
              };
            }
          })
        };
      },
      cache: true
    },
    placeholder: 'Sélectionnez un élément',
    minimumInputLength: 0
  });
});

$(document).ready(function(){
  $('#lieu_activite_id').select2({
    ajax: {
      url: '../../../package/dist/php/get_lieu_activite.php',
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
});

$(document).ready(function(){
  $('#residence_fiscale_id').select2({
    ajax: {
      url: '../../../package/dist/php/get_lieu_residence_fiscale.php',
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
});


// AJAX SECTEURS D'ACTIVITES
$(document).ready(function(){
  $('#secteur_dactivite_id').select2({
    ajax: {
      url: '../../../package/dist/php/get_secteurs.php',
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
});

// AJAX SEGMENTS
$(document).ready(function(){
  $('#segments_id').select2({
    ajax: {
      url: '../../../package/dist/php/get_segments.php',
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
});

function getpays()
{
  $('.pays').select2({
    ajax: {
      url: '../../../package/dist/php/get_pays.php',
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
}


//AJAX PPE
function getppes()
{
  $('.ppes').select2({
    ajax: {
      url: '../../../package/dist/php/get_ppes.php',
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
}

// AJAX PAYS
$(document).ready(function(){
  
  $('.pays').select2({
    ajax: {
      url: '../../../package/dist/php/get_pays.php',
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
});


$(document).on('switchChange.bootstrapSwitch', '.ben_ppe', function(event, state) {

    const $this = $(this);
    const idppe = $(this).attr('id').replace('ben_ppe2_', '');
    const index = idppe.match(/\d+$/)[0];
    const target = $("#benificiaire_ppe2_id_"+index);
    var valeur = $this.val();
    $this.val(state ? '0' : '1');

    if(valeur == 'off'){valeur = 0;}
    else if(valeur == 'on'){valeur = 1;}

    target.val(valeur);
});


$(document).on('switchChange.bootstrapSwitch', '.ben_lien', function(event, state) {

    const $this = $(this);
    const idppe = $(this).attr('id').replace('ben_lien2_', '');
    const index = idppe.match(/\d+$/)[0];
    const target = $("#benificiaire_lien2_id_"+index);
    var valeur = $this.val();
    $this.val(state ? '0' : '1');

    if(valeur == 'off'){valeur = 0;}
    else if(valeur == 'on'){valeur = 1;}

    target.val(valeur);
});


$(document).on('switchChange.bootstrapSwitch', '.adm_ppe', function(event, state) {

    const $this = $(this);
    const idppe = $(this).attr('id').replace('adm_ppe2_', '');
    const index = idppe.match(/\d+$/)[0];
    const target = $("#administrateur_ppe2_id_"+index);
    var valeur = $this.val();
    $this.val(state ? '0' : '1');

    if(valeur == 'off'){valeur = 0;}
    else if(valeur == 'on'){valeur = 1;}

    target.val(valeur);
});


$(document).on('switchChange.bootstrapSwitch', '.adm_lien', function(event, state) {

    const $this = $(this);
    const idppe = $(this).attr('id').replace('adm_lien2_', '');
    const index = idppe.match(/\d+$/)[0];
    const target = $("#administrateur_lien2_id_"+index);
    var valeur = $this.val();
    $this.val(state ? '0' : '1');

    if(valeur == 'off'){valeur = 0;}
    else if(valeur == 'on'){valeur = 1;}

    target.val(valeur);
});

$(document).on('switchChange.bootstrapSwitch', '.hab_ppe', function(event, state) {

    const $this = $(this);
    const idppe = $(this).attr('id').replace('hab_ppe2_', '');
    const index = idppe.match(/\d+$/)[0];
    const target = $("#habilite_ppe2_id_"+index);
    var valeur = $this.val();
    $this.val(state ? '0' : '1');

    if(valeur == 'off'){valeur = 0;}
    else if(valeur == 'on'){valeur = 1;}

    target.val(valeur);
});


$(document).on('switchChange.bootstrapSwitch', '.hab_lien', function(event, state) {

    const $this = $(this);
    const idppe = $(this).attr('id').replace('hab_lien2_', '');
    const index = idppe.match(/\d+$/)[0];
    const target = $("#habilite_lien2_id_"+index);
    var valeur = $this.val();
    $this.val(state ? '0' : '1');

    if(valeur == 'off'){valeur = 0;}
    else if(valeur == 'on'){valeur = 1;}

    target.val(valeur);
});



$(document).ready(function() {

    // Détecter le changement
    $('#autorite_regulation_check_id').on('switchChange.bootstrapSwitch', function(event, state) {
      $('#autorite_regulation_data_id').show();
        if(state) {
            // Checkbox cochée → afficher la div
            $('#autorite_regulation_data_id').show();
            $('input[name="autorite_regulation_check2"').val('1');
        } else {
            // Checkbox décochée → cacher la div
            $('#autorite_regulation_data_id').hide();
            $('input[name="autorite_regulation_check2"').val('0');
        }
    });


    $('#activite_etranger_id').on('switchChange.bootstrapSwitch', function(event, state) {
      $('#activite_etranger_data_id').next('.select2-container').show();
        if(state) {
            // Checkbox cochée → afficher la div
            $('#activite_etranger_data_id').next('.select2-container').show();
            $('input[name="activite_etranger_check2"').val('1');
        } else {
            // Checkbox décochée → cacher la div
            $('#activite_etranger_data_id').next('.select2-container').hide();
            $('input[name="activite_etranger_check2"').val('0');
        }
    });

    $('#sur_marche_financier_id').on('switchChange.bootstrapSwitch', function(event, state) {
      $('#sur_marche_financier_data_id').show();
        if(state) {
            // Checkbox cochée → afficher la div
            $('#sur_marche_financier_data_id').show();
            $('input[name="sur_marche_financier_check2"').val('1');
        } else {
            // Checkbox décochée → cacher la div
            $('#sur_marche_financier_data_id').hide();
            $('input[name="sur_marche_financier_check2"').val('0');
        }
    });

    $('#us_entity_id').on('switchChange.bootstrapSwitch', function(event, state) {
      $('.us_entity_data_id').show();
        if(state) {
            // Checkbox cochée → afficher la div
            $('.us_entity_data_id').show();
            $('input[name="us_entity_check2"').val('1');
        } else {
            // Checkbox décochée → cacher la div
            $('.us_entity_data_id').hide();
            $('input[name="us_entity_check2"').val('0');
        }
    });

    $('#giin_id').on('switchChange.bootstrapSwitch', function(event, state) {
      $('#giin_data_id').show();
        if(state) {
            // Checkbox cochée → afficher la div
            $('#giin_data_id').show();
            $('#giin_data_autres_id').hide();
            $('input[name="giin2"').val('1');
        } else {
            // Checkbox décochée → cacher la div
            $('#giin_data_id').hide();
            $('#giin_data_autres_id').show();
            $('input[name="giin2"').val('0');
        }
    });

    $('#mandataire_id').on('switchChange.bootstrapSwitch', function(event, state) {
      $('.mandat-hide').show();
        if(state) {
            // Checkbox cochée → afficher la div
            $('.mandat-hide').show();
            $('input[name="mandataire2"').val('1');
        } else {
            // Checkbox décochée → cacher la div
            $('.mandat-hide').hide();
            $('input[name="mandataire2"').val('0');
        }
    });

    $('#departement_gestion_id').on('switchChange.bootstrapSwitch', function(event, state) {
      $('#departement_gestion_data_id').show();
        if(state) {
            // Checkbox cochée → afficher la div
            $('#departement_gestion_data_id').show();
            $('input[name="dep_gestion2"').val('1');
        } else {
            // Checkbox décochée → cacher la div
            $('#departement_gestion_data_id').hide();
            $('input[name="dep_gestion2"').val('0');
        }
    });
});



$(document).ready(function(){
  $('.objetrelationcheck').on("change", function(){
    var valeursCochées = [];

    $('.objetrelationcheck:checked').each(function () {
      valeursCochées.push($(this).val());
    });

    console.log(valeursCochées); // Exemple : ["1", "2"]
  });
});


/*
$(document).ready(function () {
  // 1. Désactiver l'auto-initialisation
  Dropzone.autoDiscover = false;

  // 2. Supprimer toute instance déjà attachée sur ce conteneur
  if (Dropzone.instances.length > 0) {
    Dropzone.instances.forEach(dz => {
      dz.destroy(); // Supprime proprement les précédentes
    });
  }

  const fatcaDropzone = new Dropzone("#dropzone-fichierFATCA", {
    url: "../../../package/dist/php/upload_fichier_fatca.php",
    paramName: "FATCA_fichier",
    maxFiles: 1,
    autoProcessQueue: false, // empêche l’upload auto !
    acceptedFiles: ".pdf,.jpg,.jpeg,.png",
    addRemoveLinks: true,
    dictDefaultMessage: "Déposez votre fichier FATCA ici"
  });

  $('#save_form').on('click', function (e) {
    e.preventDefault(); // empêcher le submit

    // Vérifie s’il y a un fichier à uploader
    if (fatcaDropzone.getQueuedFiles().length > 0) {
      // Upload d’abord, puis on soumettra dans "success"
      fatcaDropzone.processQueue();
    } else {
      // Aucun fichier, on soumet directement
      submitFormToServer(null); // null = pas de fichier
    }
  });

  // Callback quand l’upload FATCA réussit
  fatcaDropzone.on("success", function (file, response) {
    let filePath = null;
    try {
      const data = JSON.parse(response);
      if (data.status === "success") {
        filePath = data.path;
      }
    } catch (e) {
      console.error("Erreur parsing réponse FATCA :", response);
    }

    // Ensuite on envoie le reste du formulaire avec le chemin
    submitFormToServer(filePath);
    console.log(filePath)
  });
});
*/

$(document).ready(function(){

  const fatcaInput = document.getElementById('fatca_hidden_input');
    if(!fatcaInput) {
      //console.error("Input FATCA introuvable !");
      return;
    }

  Dropzone.autoDiscover = false;
  Dropzone.instances.forEach(dz => dz.destroy());

  const fatcaDropzone = new Dropzone("#dropzone-fichierFATCA", {
    url: "#", // PAS utilisé pour l'upload, on gère après submit
    autoProcessQueue: false,
    maxFiles: 1,
    acceptedFiles: ".pdf,.jpg,.jpeg,.png",
    addRemoveLinks: true,
    dictDefaultMessage: "Déposez votre fichier FATCA ici",

    // Empêcher l'envoi auto
    accept: function(file, done) {
      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(file);
      document.getElementById('fatca_hidden_input').files = dataTransfer.files;
      done();
    },

    // Quand on clique sur "Remove file"
    init: function() {
      this.on("removedfile", function(file) {
        // Récupérer le chemin du fichier depuis l'attribut custom si stocké
        const filePath = file.uploadedPath; 
        if(filePath) {
          $.post('delete_file.php', { path: filePath }, function(response) {
            console.log("Fichier supprimé :", response);
          });
        }
        // On vide aussi le input caché
        document.getElementById('fatca_hidden_input').value = '';
      });
    }
  });

  function clearForm()
  {
    // 1️⃣ Réinitialiser le formulaire
    $('#formulaire_global')[0].reset();
    $('#formulaire_global').find('select').trigger('change');

    // 2️⃣ Réinitialiser les Dropzones
    $('#formulaire_global').find('.dropzone').each(function() {
        if (this.dropzone) this.dropzone.removeAllFiles(true);
    });

    // 3️⃣ Supprimer tous les tooltips Bootstrap du formulaire
    $('label.btn').each(function() {
        $(this).removeAttr('data-bs-original-title'); // supprime le tooltip
        $('label.btn-primary').removeClass('btn-success');
    });

    $('html, body').animate({ scrollTop: 0 }, 'slow');
  }

  $('#clear_form').on("click", function() {

      clearForm();
  });


  $('#save_form').on('click', function(e){
    e.preventDefault();

    let erreurs = [];

    // Vérifier Contacts
    $('.contactRowInfos').each(function(index) {
      let nom = $(this).find('[name="noms_contacts[]"]').val().trim();
      let fonction = $(this).find('[name="fonctions_contacts[]"]').val().trim();
      let tel = $(this).find('[name="telephones_contacts[]"]').val().trim();
      let email = $(this).find('[name="emails_contacts[]"]').val().trim();

      if (!nom && (!tel || !email)) {
        erreurs.push(`La ligne ${index + 1} des contacts est vide. Veuillez la supprimer ou la compléter.`);
      }
    });

    // Vérifier Comptes bancaires
    $('.CBancairesRowInfos').each(function(index) {
      let banque = $(this).find('[name="noms_banques[]"]').val().trim();
      let agence = $(this).find('[name="agences_banques[]"]').val().trim();
      let ville = $(this).find('[name="villes_banques[]"]').val().trim();
      let rib = $(this).find('[name="ribs_banques[]"]').val().trim();

      if (!rib) {
        erreurs.push(`La ligne ${index + 1} des comptes bancaires ne contient pas de RIB.`);
      }
    });

    // Vérifier Actionnaires
    $('.actionnaireRowInfos').each(function(index) {
      let nom = $(this).find('[name="noms_rs_actionnaires[]"]').val().trim();
      let identite = $(this).find('[name="identite_actionnaires[]"]').val().trim();
      let nbtitres = $(this).find('[name="nombre_titres_actionnaires[]"]').val().trim();
      let prccap = $(this).find('[name="pourcentage_capital_actionnaires[]"]').val().trim();

      if (!nom && (!identite || !nbtitres || !prccap)) {
        erreurs.push(`La ligne ${index + 1} des actionnaires est vide. Veuillez la supprimer ou la compléter.`);
      }
    });

    // Vérifier Benificiaires
    $('.benificiaireRowInfos').each(function(index) {

      let ppechecked = $(this).find('[name="benificiaires_ppe_check[]"]').prop('checked');
      let ppelienchecked = $(this).find('[name="benificiaires_ppe_lien_check[]"]').prop('checked');

      let nom = $(this).find('[name="noms_rs_benificiaires[]"]').val().trim();
      let pays = $(this).find('[name="pays_naissance_benificiaires[]"]').val().trim();
      let date = $(this).find('[name="dates_naissance_benificiaires[]"]').val().trim();
      let identite = $(this).find('[name="identite_benificiaires[]"]').val().trim();
      let nationalite = $(this).find('[name="nationalites_benificiaires[]"]').val().trim();
      let ppecheck = $(this).find('[name="benificiaires_ppe_check[]"]').val().trim();
      let ppein = $(this).find('[name="benificiaires_ppe_input[]"]').val().trim();
      let ppeliencheck = $(this).find('[name="benificiaires_ppe_lien_check[]"]').val().trim();
      let ppelienin = $(this).find('[name="benificiaires_ppe_lien_input[]"]').val().trim();
      let prccap = $(this).find('[name="benificiaires_pourcentage_capital[]"]').val().trim();

      if (!nom)
      {
        erreurs.push(`La ligne ${index + 1} des benificiaires est vide. Veuillez la supprimer ou la compléter.`);
      }
      else
      {
        if (ppechecked)
        {
          if(ppein == "PPE")
          {
            erreurs.push(`Merci de fournir le libellé PPE sur la ligne ${index + 1} des Bénificiaires`);
          }
        }

        if (ppelienchecked)
        {
          if(ppelienin == "PPE")
          {
            erreurs.push(`Merci de fournir le libellé du lien PPE sur la ligne ${index + 1} des Bénificiaires`);
          }
        }
      }
    });

    // Vérifier Administrateurs
    $('.administrateurRowInfos').each(function(index) {

      let ppechecked = $(this).find('[name="ppes_administrateurs_check[]"]').prop('checked');
      let ppelienchecked = $(this).find('[name="ppes_lien_administrateurs_check[]"]').prop('checked');

      let nom = $(this).find('[name="noms_administrateurs[]"]').val().trim();
      let pays = $(this).find('[name="pays_administrateurs[]"]').val().trim();
      let date = $(this).find('[name="dates_naissance_administrateurs[]"]').val().trim();
      let identite = $(this).find('[name="cins_administrateurs[]"]').val().trim();
      let nationalite = $(this).find('[name="nationalites_administrateurs[]"]').val().trim();
      let ppecheck = $(this).find('[name="ppes_administrateurs_check[]"]').val().trim();
      let ppein = $(this).find('[name="ppes_administrateurs_input[]"]').val().trim();
      let ppeliencheck = $(this).find('[name="ppes_lien_administrateurs_check[]"]').val().trim();
      let ppelienin = $(this).find('[name="ppes_lien_administrateurs_input[]"]').val().trim();
      let fonction = $(this).find('[name="fonctions_administrateurs[]"]').val().trim();

      if (!nom)
      {
        erreurs.push(`La ligne ${index + 1} des administrateurs est vide. Veuillez la supprimer ou la compléter.`);
      }
      else
      {
        if (ppechecked)
        {
          if(ppein == "PPE")
          {
            erreurs.push(`Merci de fournir le libellé PPE sur la ligne ${index + 1} des Administrateurs`);
          }
        }

        if (ppelienchecked)
        {
          if(ppelienin == "PPE")
          {
            erreurs.push(`Merci de fournir le libellé du lien PPE sur la ligne ${index + 1} des Administrateurs`);
          }
        }
      }
    });


    // Vérifier Personnes Habilités
    $('.phabiliteRowInfos').each(function(index) {
      let nom = $(this).find('[name="noms_habilites[]"]').val().trim();
      let fonction = $(this).find('[name="fonctions_habilites[]"]').val().trim();
      let cin = $(this).find('[name="cin_habilites[]"]').val().trim();

      if (!nom && (!fonction || !cin)) {
        erreurs.push(`La ligne ${index + 1} des personnes habilités est vide. Veuillez la supprimer ou la compléter.`);
      }
    });

    // Ajoute d'autres vérifications similaires pour les actionnaires, bénéficiaires, etc.

    if (erreurs.length > 0) {
      e.preventDefault(); // Stop le submit
      $('#erreurs_titre').text('Erreur');
      $('#erreurs_content').html("Erreur lors de l'enregistrement de l'établissement ! <br><br>" + erreurs.join('<br>'));
      $('#erreurs_icon').removeClass().addClass('ti ti-hexagon-x fs-10 text-danger');
      $('.modal-content').removeClass('bg-light-success').addClass('bg-light-danger');
      $('#codes_erreurs_modal').modal('show');
    }
    else
    {
      // Crée le FormData du formulaire
      var form = $('#formulaire_global')[0];
      var formData = new FormData(form);

      // Ajoute le fichier FATCA de Dropzone si présent
      if(fatcaDropzone.getAcceptedFiles().length > 0){
        formData.append('fichiers[fatca]', fatcaDropzone.getAcceptedFiles()[0]);
      }

      // Envoi AJAX
      $.ajax({
        url: '../../dist/php/save_form.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response){

          let data = JSON.parse(response);
          
          if(data.check == 'true')
          {
            $('#erreurs_titre').text(data.titre);
            $('#erreurs_content').html(data.message);
            $('#erreurs_icon').removeClass().addClass(data.icone);
            $('.modal-content').removeClass('bg-light-danger').addClass('bg-light-success');
            $('#codes_erreurs_modal').modal('show');
            setTimeout(function() {
                // Redirection vers validation_listes.php avec l'id en paramètre
                window.location.href = `validation_listes.php?idEtablissement=${data.idEtablissement}`;
            }, 1800);
          }
          else
          {
            $('#erreurs_titre').text("L'établissement n'a pas été ajouté");
            $('#erreurs_content').html("Impossible de contacter le serveur : <br> Merci de vérifier les champs et réessayer !");
            $('.modal-content').find('.btnmodal').html("Terminer");
            $('#erreurs_icon').removeClass().addClass('ti ti-server fs-10 text-danger');
            $('.modal-content').removeClass('bg-light-success').addClass('bg-light-danger');
            $('#codes_erreurs_modal').modal('show');

          }

          //clearForm();

        },
        error: function(xhr){

          let dataerr = JSON.parse(xhr);

          $('#erreurs_titre').text(dataerr.titre);
          $('#erreurs_content').html("Impossible de contacter le serveur : <br>" + dataerr.message);
          $('.modal-content').find('.btnmodal').html("Terminer");
          $('#erreurs_icon').removeClass().addClass(dataerr.icone);
          $('.modal-content').removeClass('bg-light-success').addClass('bg-light-danger');
          $('#codes_erreurs_modal').modal('show');
        }
      });


      

    }

    

  });
});



$('#us_entity_check').on("change", function(){
  const usEntityChecked = $('#us_entity_check').is(':checked');
  alert(usEntityChecked);
});


/*function submitFormToServer() {
  const form = document.getElementById("formulaire_global");
  const formData = new FormData(form); // il récupère automatiquement tous les champs + fichiers

  $.ajax({
    url: '../../../package/dist/php/save_form.php',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      console.log("Réponse serveur : ", response);
    },
    error: function (xhr) {
      console.error("Erreur AJAX : ", xhr.responseText);
    }
  });
}
*/



$(document).ready(function () {
  const container = $('#details_etablissement_page');

  // Désactiver tous les inputs classiques
  container.find('input, select, textarea').attr('disabled', true);

  // Désactiver les liens <a class="btn">
  container.find('label.btnadd').addClass('disabled').css({
    'pointer-events': 'none',
    'opacity': '0.5'
  });

  // Désactiver les checkboxes Bootstrap Switch
  container.find('input[type="checkbox"].bt-switch').each(function () {
    $(this).bootstrapSwitch('disabled', true); // désactivation logique
    $(this).bootstrapSwitch('readonly', true); // bloque le clic utilisateur
  });

  $('#edit_form').on('click', function(){
    const container = $('#details_etablissement_page');

    container.find('input, select, textarea, button').removeAttr('disabled');

    container.find('a.btn').removeClass('disabled').css({
      'pointer-events': '',
      'opacity': ''
    });

    container.find('input[type="checkbox"].bt-switch').each(function () {
      $(this).bootstrapSwitch('disabled', false);
      $(this).bootstrapSwitch('readonly', false);
    });
  });
});



$(document).ready(function()
  {
    function checks(clickchk, relChkbox) {
          var checker = $("." + clickchk);
          var multichk = $(this);
      
          checker.click(function () {
            multichk.prop("checked");
            var nbChecked = $('.contact-check-all:checked').length;
            if(nbChecked > 0)
            {
              $(".show-btn").show();
              $('#contact-check-all').prop('indeterminate', true);
            }
            else
            {
              $(".show-btn").hide();
              $('#contact-check-all').prop("checked", false);
              $('#contact-check-all').prop('indeterminate', false);
            }
          });
        }

        checks("contact-check-all", "");

  });


$(function () {
  function checkall(clickchk, relChkbox) {
    var checker = $("#" + clickchk);
    var multichk = $("." + relChkbox);

    // Clic sur la checkbox "Tout sélectionner"
    checker.on('click', function () {
      var isChecked = $(this).prop("checked");
      multichk.prop("checked", isChecked);
      toggleShowButton(); // mettre à jour la visibilité des boutons
    });

    // Clic sur une checkbox individuelle
    multichk.on('change', function () {
      toggleShowButton();

      // Mettre à jour l'état "indeterminate"
      var total = multichk.length;
      var checked = multichk.filter(':checked').length;

      if (checked === 0) {
        checker.prop('indeterminate', false).prop('checked', false);
      } else if (checked === total) {
        checker.prop('indeterminate', false).prop('checked', true);
      } else {
        checker.prop('indeterminate', true).prop('checked', false);
      }
    });

    function toggleShowButton() {
      var nbChecked = multichk.filter(":checked").length;
      if (nbChecked > 0) {
        $(".show-btn").show();
      } else {
        $(".show-btn").hide();
      }
    }
  }

  checkall("contact-check-all", "contact-chkbox");
});



$(document).ready(function() {
  var $boutons_header = $('.boutons_header');
  var $header = $('.card.bg-light-info.shadow-none.position-relative.overflow-hidden');

  // Position originale des boutons_header
  var boutons_headerOffset = $boutons_header.offset().top;
  
  $(window).scroll(function() {
    var scrollTop = $(window).scrollTop();
    var headerBottom = $header.offset().top + $header.outerHeight() +50;

    if(scrollTop > headerBottom) {
      $boutons_header.css({
        position: 'fixed',
        top: 90,           // ou 0 + padding si tu veux un petit espace
        width: $boutons_header.parent().width(), // garde la largeur du conteneur
        'z-index': 1000,
        background: 'white'
      });
    } else {
      $boutons_header.css({
        position: 'static',
        width: 'auto',
        background: 'transparent'
      });
    }
  });

  // Ajuster la largeur si la fenêtre change
  $(window).resize(function(){
    if($boutons_header.css('position') === 'fixed'){
      $boutons_header.css('width', $boutons_header.parent().width());
    }
  });
});


$(document).ready(function(){
  $("#societe_de_gestion_check_id").on('switchChange.bootstrapSwitch', function(event, state) {
    $(".societe_de_gestion_check_hide").fadeIn();
      if(state) {
          // Checkbox cochée → afficher la div
          $(".societe_de_gestion_check_hide").fadeIn();
          $('input[name="societe_de_gestion_check2"').val('1');
      } else {
          // Checkbox décochée → cacher la div
          $(".societe_de_gestion_check_hide").fadeOut();
          $('input[name="societe_de_gestion_check2"').val('0')
      }

      //alert($('input[name="societe_de_gestion_check2"').val());
  });

});


$(document).ready(function() {
  function toggleLinkedInput(checkbox) {
          let checkboxName = $(checkbox).attr('name');
          if (checkboxName && checkboxName.endsWith('_check')) {
              let baseName = checkboxName.replace('_check', '');
              let inputSelector = 'input[name="' + baseName + '_input"]';
              if ($(checkbox).is(':checked')) {
                  $(inputSelector).show(); // ou .removeClass('d-none')
              } else {
                  $(inputSelector).hide(); // ou .addClass('d-none')
              }
          }
      }

      var page_type = $("#page_type").val();

    $('input[type="checkbox"]').each(function() {


        var parent = $(this).closest('.bootstrap-switch-container');

        if(parent.prop('class') === "bootstrap-switch-container" && $(this).prop('checked'))
        {
          toggleLinkedInput(this);
          if (page_type != "insertion")
          {
            parent.addClass('div-disabled');
          }

          parent.css('margin-left','0px');
        }
        else
        {
          toggleLinkedInput(this);
          if (page_type != "insertion")
          {
            parent.addClass('div-disabled');
          }
          parent.css('margin-left','-47.7969px');
        }

        if (parent.closest('form, .some-wrapper, body').find('.mandat-hide').length > 0)
        {
          parent.closest('form, .some-wrapper, body').find('.mandat-hide').show();
        }
    });
});



$(document).ready(function() {
  $('.number-format').on('input', function() {
    let value = $(this).val();

    // Supprime tout caractère non autorisé (chiffres + virgule)
    value = value.replace(/[^0-9,]/g, '');

    // Empêche plus d'une virgule
    let parts = value.split(',');
    if (parts.length > 2) {
      parts = [parts[0], parts.slice(1).join('')]; // garde une seule virgule
    }

    // Formate la partie entière avec espaces
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ' ');

    // Recompose la valeur complète
    value = parts.join(',');

    $(this).val(value);
  });
});


$(document).ready(function() {
  // Cas 1 : Erreur
  $('#test_action').click(function() {
    const erreurs_all = "Aucune erreur détectée";

    $('#erreurs_titre').text('Enregistrement effectué');
    $('#erreurs_content').html(erreurs_all);
    $('#erreurs_icon').removeClass().addClass('ti ti-check fs-10 text-success');
    $('.modal-content').removeClass('bg-light-danger').addClass('bg-light-success');
    $('#codes_erreurs_modal').modal('show');
  });
});



function manageCheckboxes()
{
  $('input[type="checkbox"]').each(function() {

    var container =$(this).closest('.bootstrap-switch-container.div-disabled');

    if ($(this).is(':checked'))
    {
      var target = "#" + $(this).attr('data-target');
      $(target).removeClass('hidden-select');
      $(container).addClass('checkYes');
    }
    else
    {
      var target = "#" + $(this).attr('data-target');
      $(container).removeClass('checkYes');
      $(target).addClass('hidden-select');
    }
  });
}


function showPopup(titre, contenu, type, btn)
{
  $('#erreurs_titre').text(titre);
  $('#erreurs_content').html(contenu);
  $(".btnmodal").html(btn);

  if(type == 'success')
  {
    $('#erreurs_icon').removeClass().addClass('ti ti-check fs-10 text-success');
    $('.modal-content').removeClass('bg-light-danger').addClass('bg-light-success');
  }
  else if(type == 'danger')
  {
    $('#erreurs_icon').removeClass().addClass('ti ti-x fs-10 text-danger');
    $('.modal-content').removeClass('bg-light-success').addClass('bg-light-danger');
  }
  else
  {
    $('#erreurs_icon').removeClass().addClass('ti ti-alert-triangle fs-10 text-warning');
    $('.modal-content').removeClass('bg-light-success').addClass('bg-light-warning');
  }


  $('#codes_erreurs_modal').modal('show');
}


function validerEtablissement(idetablissement)
{
  //alert(idetablissement);

  $.ajax({
    url: '../../dist/php/valider_etablissement.php',
    type: 'POST',
    data: {idetablissement: idetablissement, action: 'validation' },
    success: function(response){

      let data = JSON.parse(response);
      showPopup(data.titre, data.message, 'success', 'Terminer');
    },
    error: function(xhr){

      let dataerr = JSON.parse(xhr);
      showPopup(dataerr.titre, dataerr.message, 'danger', 'Fermer');
    }
  });
}

function rejeterEtablissement(idetablissement)
{
  //alert(idetablissement);

  $.ajax({
    url: '../../dist/php/valider_etablissement.php',
    type: 'POST',
    data: {idetablissement: idetablissement, action: 'rejet' },
    success: function(response){

      let data = JSON.parse(response);
      showPopup(data.titre, data.message, 'success', 'Terminer');

    },
    error: function(xhr){

      let dataerr = JSON.parse(xhr);

      showPopup(dataerr.titre, dataerr.message, 'danger', 'Fermer');
    }
  });
}


function recevoirvalidation(idetablissement, newaction)
{
  $.ajax({
    url: '../../dist/php/recevoirvalidation.php',
    type: 'POST',
    data: {idetablissement: idetablissement, action: newaction },
    success: function(response){

      let data = JSON.parse(response);
      showPopup(data.titre, data.message, 'success', 'Terminer');

      var recevoirpar = $('#recevoirnom');
      var recevoirdate = $('#recevoirdate');

      recevoirpar.html(data.par);
      recevoirdate.html(data.date + ' à  ' + data.heure);
      $(this).css('margin-top','0');

    },
    error: function(xhr){

      let dataerr = JSON.parse(xhr);

      showPopup(dataerr.titre, dataerr.message, 'danger', 'Fermer');
    }
  });
}


$(document).ready(function(){
  manageCheckboxes();

  $('.actionetablissement').on('click', function(){

    if($(this).data('validation') == '0')
    {
      action = 'rejet';
      toupdate = $(this).data('rejet');
      contraire = $('.actionetablissement[data-validation="'+toupdate+'"]');
      text = 'Rejeté';

      $(this).removeClass('btn-danger');
      $(this).addClass('bg-light-danger');
      $(this).addClass('text-danger');
    }
    else
    {
      action = 'validation';
      toupdate = $(this).data('validation');
      contraire = $('.actionetablissement[data-rejet="'+toupdate+'"]');
      text = 'Validé';

      $(this).removeClass('btn-success');
      $(this).addClass('bg-light-danger');
      $(this).addClass('text-danger');
    }


    if(action == 'validation')
    {
      validerEtablissement(toupdate);
    }
    else
    {
      rejeterEtablissement(toupdate);
    }

    //alert(action + ' : ' + toupdate);

    $(this).removeClass('w-50');
    $(this).addClass('w-100');
    contraire.hide();
    $(this).text(text);

  });


  $('.recevoirdata').on('click', function(){

    if($(this).data('validation') == '0')
    {
      action = 'rejet';
      toupdate = $(this).data('rejet');
      contraire = $('.recevoirdata[data-validation="'+toupdate+'"]');
      text = 'Rejeté';

      $(this).removeClass('btn-danger');
      $(this).addClass('bg-light-danger');
      $(this).addClass('text-danger');
    }
    else
    {
      action = 'validation';
      toupdate = $(this).data('validation');
      contraire = $('.recevoirdata[data-rejet="'+toupdate+'"]');
      text = 'Validé';

      $(this).removeClass('btn-success');
      $(this).addClass('bg-light-danger');
      $(this).addClass('text-danger');
    }

    if(action == 'validation')
    {
      recevoirvalidation(toupdate, 'validation');
    }
    else
    {
      recevoirvalidation(toupdate, 'rejet');
    }

    //alert(action + ' : ' + toupdate);

    $(this).removeClass('w-50');
    $(this).addClass('w-100');
    contraire.hide();
    $(this).text(text);

    $("#parid").show();

  });

});


// Terminer la validation

/*



*/



$(document).ready(function(){

  $(document).on('click', '.terminer_validation', function(e)
    {
      setTimeout(() => {
          
              window.location.href = 'etablissements.php';
      }, 2000);
    });

  $(document).on('click', '.declarer_interdit', function(e){

    e.preventDefault();

    let btn = $(this);

    let row = btn.closest('table');
    let h3 = row.prev('h3');

    let data =
    {
      source: btn.data('source'),
      table: btn.data('table'),
      table_id: btn.data('table-id'),
      target_id: btn.data('target-id'),
    };

    $.ajax({
    
      url: '../../dist/js/traitement_validation.php',
      type: 'POST',
      data: data,
      dataType: 'json',
      success: function(res){
          if(res.status === 'success'){
              Swal.fire({
                  icon: 'success',
                  title: res.message,
                  timer: 4000,
                  showConfirmButton: false
              });
              row.fadeOut(1000);
              h3.fadeOut(1000);
              alert($('table').length);
          } else {
              Swal.fire('Erreur', res.message, 'error');
          }
      },
      error: function(xhr){
          Swal.fire('Erreur système', xhr.responseText, 'error');
      }

    });
    
    /*
    // Mise à jour de calcul interdit
    var target = $(this);
    var source = "";

    if(target.data('source') == 'anrf_physiques')
    {
      source = 'ANRF';
    }
    else
    {
      source = 'CNASNU'
    }

    var requeteUpdate = "UPDATE calcul_etablissement SET niveauRisque = niveauRisque + 1000 WHERE idEtablissement = (SELECT idEtablissement FROM "+target.data('table')+" WHERE id = "+target.data('table-id') + " LIMIT 1)";
    var requeteInsert = "INSERT INTO details_calcul (note, detailNote, idEtablissement) VALUES (1000, '"+source+":Présent dans la liste', (SELECT idEtablissement FROM "+target.data('table')+" WHERE id = "+target.data('table-id') + " LIMIT 1))"
    alert(requeteInsert);
    */
  });

  $(document).on('click', '.ignorer_interdit', function(e){

    e.preventDefault();

    let btn = $(this);

    let row = btn.closest('table');
    let h3 = row.prev('h3');

    row.fadeOut(1000);
    h3.fadeOut(1000);

    // Ignorer interdit
  });


});


// Affichage des champs pour l'importation
$(document).ready(function(){
  $('#import_cnasnu').hide();
  $('#import_anrf').hide();

  $(document).on('click', '.btn-import', function()
  {
    var id = 'import_' + $(this).data('id');
    $('#import_cnasnu').slideUp(500);
    $('#import_anrf').slideUp(500);
    $('#'+id).slideDown(500);
  });

  $(document).on('change', '#cnasnu_file', function() {
      // Récupère le nom du fichier (sans le chemin complet)
      let fileName = $(this).val().split('\\').pop();

      // Si aucun fichier sélectionné
      if (!fileName) fileName = 'Aucun fichier choisi';

      // Trouve le label correspondant (par exemple juste après l'input)
      $('#import_cnasnu').text(fileName);

      $('#envoyer_cnasnu').slideDown(300);
  });

  $(document).on('change', '#anrf_file', function() {
      // Récupère le nom du fichier (sans le chemin complet)
      let fileName = $(this).val().split('\\').pop();

      // Si aucun fichier sélectionné
      if (!fileName) fileName = 'Aucun fichier choisi';

      // Trouve le label correspondant (par exemple juste après l'input)
      $('#import_anrf').text(fileName);
      $('#envoyer_anrf').slideDown(300);
  });


// Envoie de fichiers xml, xlsx ou xls
  $(document).ready(function() {

      function uploadAndRedirect(fileInputId, type) {
          let fileInput = $('#' + fileInputId)[0];

          if (fileInput.files.length === 0) {
              alert("Veuillez choisir un fichier avant d'envoyer !");
              return;
          }

          let formData = new FormData();
          formData.append('file', fileInput.files[0]);
          formData.append('type', type);

          $.ajax({
              url: 'importer_liste.php',
              type: 'POST',
              data: formData,
              processData: false,
              contentType: false,
              beforeSend: function() {
                  Swal.fire({
                      title: "Importation en cours...",
                      text: "Merci de patienter quelques instants.",
                      allowOutsideClick: false,
                      didOpen: () => {
                          Swal.showLoading();
                      }
                  });
              },
              success: function(response) {
                  Swal.close();

                  // Affiche un message de succès avant redirection
                  Swal.fire({
                      icon: 'success',
                      title: 'Importation réussie !',
                      text: 'Redirection vers la page de validation...',
                      timer: 2000,
                      showConfirmButton: false
                  });

                  // Attendre un peu avant de rediriger (pour l’animation)
                  setTimeout(function() {
                      // Redirection vers validation_listes.php avec le type en paramètre
                      window.location.href = 'validation_listes.php?type=' + encodeURIComponent(type);
                  }, 1800);
              },
              error: function(xhr) {
                  Swal.fire({
                      icon: 'error',
                      title: 'Erreur lors de l’importation',
                      text: xhr.responseText || 'Une erreur est survenue. Vérifiez le fichier.'
                  });
              }
          });
      }

      // Boutons d’envoi pour chaque fichier
      $('#envoyer_cnasnu').on('click', function(e) {
          e.preventDefault();
          uploadAndRedirect('cnasnu_file', 'cnasnu');
      });

      $('#envoyer_anrf').on('click', function(e) {
          e.preventDefault();
          uploadAndRedirect('anrf_file', 'anrf');
      });
  });



});


$(document).ready(function() {

  function chargerNotifications() {
    $.ajax({
      url: '../../dist/php/get_notifications.php',
      method: 'GET',
      dataType: 'json',
      success: function(data) {
        let html = '';

        if (data.notifications.length === 0) {
          html = '<div class="text-center p-3 text-muted">Aucune notification</div>';
        } else {
          data.notifications.forEach(function(n) {
            let bg = n.statut == 0 ? 'bg-light' : '';
            let icon = n.type === 'alerte' ? '⚠️' : (n.type === 'info' ? 'ℹ️' : '🔔');

            if(n.raisonSocial == " " || n.raisonSocial == null)
            {
              n.raisonSocial = "Inconnu";
            }

            html += `
              <a href="#" data-notification="${n.id}" class="py-6 px-7 d-flex align-items-center dropdown-item notification-item ${bg}">
                <span class="me-3">${icon}</span>
                <div class="w-75 d-inline-block v-middle">
                  <h6 class="mb-1 fw-semibold">${n.titre}</h6>
                  <span class="d-block small text-muted">${n.raisonSocial} : ${n.message}</span>
                  <span class="d-block small text-secondary">${n.date_creation}</span>
                </div>
              </a>`;
          });
        }

        $('.notification-body').html(html);
        $('.badge.bg-primary').text(data.non_vues + ' nouveaux');
        $('.notification').toggle(data.non_vues > 0);
      }
    });
  }

  // Charger à l’ouverture de la page
  chargerNotifications();

  // Marquer comme vues lors du clic sur la cloche
  $('#showNotifications').on('click', function() {
    chargerNotifications();
  });

 $(document).on('click', '.notification-item', function(e) {
    e.preventDefault();
    const notifId = $(this).data('notification');

    $.ajax({
      url: '../../dist/php/marquer_vues.php',
      method: 'POST',
      data: { id: notifId },
      success: function() {
        // Optionnel : recharger les notifs ou juste griser la ligne
        $(`[data-notification="${notifId}"]`).removeClass('bg-light').css('opacity', 0.7);
        setTimeout(chargerNotifications, 1000);
      }
    });
  });

});


$(document).ready(function() {
  $.ajax({
    url: '../../dist/php/get_user_info.php',
    method: 'GET',
    dataType: 'json',
    success: function(data) {
      if (data.success) {
        let user = data.user;

        // 🔹 Nom complet
        $('.profile-dropdown h5.mb-1.fs-3').text(`${user.nom.toUpperCase()} ${user.prenom}`);

        // 🔹 Fonction
        $('.profile-dropdown span.d-block.text-dark:first').text(user.role);

        // 🔹 Email
        $('.profile-dropdown p.text-dark i.ti-mail').parent().html(`
          <i class="ti ti-mail fs-4"></i> ${user.email}
        `);

        // 🔹 Photo
        $('.profile-dropdown img.rounded-circle').attr('src', '../../dist/php/uploads/users_photos/'+user.photo);
        $('.user-profil-header').attr('src', '../../dist/php/uploads/users_photos/'+user.photo);
      } else {
        console.error('Utilisateur non connecté ou introuvable');
      }
    },
    error: function(xhr) {
      console.error('Erreur AJAX:', xhr.responseText);
    }
  });
});


// '../../dist/libs/req/roles_actions.php'

function loadUsers(roleId = null) {
  $.post('../../dist/php/get_users_by_role.php', { role_id: roleId }, function(html){
      $('#usersList').html(html);
  });
}

$(function(){

  // Ouvrir modal pour ajouter
  $('#btnAddRole').on('click', function(){
    loadUsers(null);
    $('#modalTitle').text('Ajouter un rôle');
    $('#formRole')[0].reset();
    $('#roleId').val('');
    $('input[name="actif"]').prop('checked', true);
    $('input[name="permissions[]"]').prop('checked', false);
  });

  // Ouvrir modal pour modifier (load via AJAX)
  $('.btn-edit').on('click', function(){
    const id = $(this).data('id');
    loadUsers(id);
    $('#modalTitle').text('Modifier un rôle');
    $('#formRole')[0].reset();
    $('input[name="permissions[]"]').prop('checked', false);

    $.post('../../dist/php/get_role.php', { id: id }, function(res){
      if(res && res.id){
        $('#roleId').val(res.id);
        $('#roleNom').val(res.nom);
        $('#roleDesc').val(res.description);
        $('#roleActif').prop('checked', res.actif == 1 ? true : false);

        // cocher les permissions
        if(Array.isArray(res.permissions)){
          res.permissions.forEach(function(p){
            $('input[name="permissions[]"][value="'+p+'"]').prop('checked', true);
          });
        }

        // ouvrir modal
        var myModal = new bootstrap.Modal(document.getElementById('modalRole'));
        myModal.show();
      } else {
        Swal.fire('Erreur', 'Impossible de charger le rôle', 'error');
      }
    }, 'json').fail(function(){
      Swal.fire('Erreur', 'Requête échouée', 'error');
    });
  });

  // Sauvegarde (Add/Edit)
  $('#formRole').on('submit', function(e){
    e.preventDefault();
    const formData = $(this).serialize() + '&action=save';
    $('#saveRoleBtn').prop('disabled', true).text('Enregistrement...');

    $.post('../../dist/libs/req/roles_actions.php', formData, function(res){
      $('#saveRoleBtn').prop('disabled', false).text('Enregistrer');
      if(res.success){
        Swal.fire('Succès', res.message, 'success').then(()=> location.reload());
      } else {
        Swal.fire('Erreur', res.message || 'Erreur serveur', 'error');
      }
    }, 'json').fail(function(){
      $('#saveRoleBtn').prop('disabled', false).text('Enregistrer');
      Swal.fire('Erreur', 'Requête échouée', 'error');
    });
  });

  // Supprimer
  $('.btn-delete').on('click', function(){
    const id = $(this).data('id');
    Swal.fire({
      title: 'Confirmer la suppression ?',
      text: "Cette action supprimera le rôle définitivement.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Oui, supprimer',
      cancelButtonText: 'Annuler'
    }).then((result) => {
      if(result.isConfirmed){
        $.post('../../dist/libs/req/roles_actions.php', { action: 'delete', id: id }, function(res){
          if(res.success){
            Swal.fire('Supprimé', res.message, 'success').then(()=> location.reload());
          } else {
            Swal.fire('Erreur', res.message || 'Erreur suppression', 'error');
          }
        }, 'json').fail(function(){
          Swal.fire('Erreur', 'Requête échouée', 'error');
        });
      }
    });
  });

});

// Bouton "Ajouter un rôle"
$('#btnAddRole').on('click', function () {
  $('#formRole')[0].reset(); // Réinitialise le formulaire
  $('#role_id').val(''); // Vide l'id pour indiquer un nouvel ajout
  $('#modalTitle').text('Ajouter un rôle');
  $('input[name="permissions[]"]').prop('checked', false); // Décocher toutes les permissions
  $('#modalRole').modal('show');
});


function loadUsersForRole(roleId = null) {
    $.ajax({
        url: "../../dist/libs/req/roles_actions.php",
        type: "POST",
        data: { role_id: roleId },
        success: function(response) {
            $("#usersList").html(response);
        }
    });
}


$(function () {

  // Ouvrir modal Modifier
  $('.btn-edit-user').on('click', function () {
      const id = $(this).data('id');

      $.post('../../dist/php/get_user.php', { id: id }, function (res) {

        $('#editUserId').val(res.id);
        $('#editNom').val(res.nom);
        $('#editPrenom').val(res.prenom);
        $('#editEmail').val(res.email);
        $('#editLogin').val(res.login);
        $('#editRole').val(res.role);
        if (res.photo && res.photo !== "")
        {
          $('#imgUtilisateur').attr('src', '../../dist/php/uploads/users_photos/' + res.photo);
          $('#previewPhoto').show();
        }
        else {
          $('#previewPhoto').hide();
        }

        var myModal = new bootstrap.Modal(document.getElementById('modalEditUser'));
        myModal.show();

      }, 'json');
  });


  // Sauvegarde modification utilisateur
  $('#formEditUser').on('submit', function (e) {
      e.preventDefault();

      let formData = new FormData(this);

      $.ajax({
        url: "../../dist/php/update_user.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
          Swal.fire("Succès", res.message, "success").then(() => location.reload());
        }
      });
  });


  // Ouvrir modal changer mot de passe
  $('.btn-change-pass').on('click', function () {
      $('#passUserId').val($(this).data('id'));

      new bootstrap.Modal(document.getElementById('modalChangePass')).show();
  });

  // Mise à jour mot de passe
  $('#formChangePass').on('submit', function (e) {
      e.preventDefault();

      $.post("../../dist/php/update_password.php", $(this).serialize(), function (res) {
          Swal.fire("Succès", res.message, "success");
      }, 'json');
  });

});


$(document).ready(function() {

  // Charger les rôles depuis la base
  $.get("../../dist/php/get_roles.php?action=list", function(data) {
      let roles = JSON.parse(data);
      $("#rolesList").append('<option value="">-- Sélectionner --</option>');
      roles.forEach(r => {
          $("#rolesList").append('<option value="'+r.id+'">'+r.nom+'</option>');
      });
  });

  // Aperçu de l'image
  $("#photo").change(function() {
    let file = this.files[0];
    if (file) {
      let reader = new FileReader();
      reader.onload = function(e) {
        $("#preview").attr("src", e.target.result).show();
      }
      reader.readAsDataURL(file);
    }
  });

  // Vérifier email unique
  $("#email").blur(function() {
    $.post("../../dist/php/check_email.php", { email: $(this).val() }, function(data) {
      if (data.exists) {
        $("#emailError").text("Cet email existe déjà !");
      } else {
        $("#emailError").text("");
      }
    }, "json");
  });

  // Vérifier login unique
  $("#login").blur(function() {
    $.post("../../dist/php/check_login.php", { login: $(this).val() }, function(data) {
      if (data.exists) {
        $("#loginError").text("Ce login existe déjà !");
      } else {
        $("#loginError").text("");
      }
    }, "json");
  });

  // Envoi AJAX du formulaire
  $("#formAddUser").submit(function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    $.ajax({
      url: "../../dist/php/add_user.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function(res) {

        if (res.status === "success") {
          alert("Utilisateur ajouté !");
          location.reload();
        } else {
          alert("Erreur : " + res.message);
        }

      }
    });
  });
});