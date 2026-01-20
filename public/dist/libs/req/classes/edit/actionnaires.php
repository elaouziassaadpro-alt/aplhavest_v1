<?php
namespace Edit;

class actionnaires {
    public function render($actionnairesl) {
        ?>
        
        <p>
              <a
                class="btn btn-light-info w-100 font-medium text-info"
                data-bs-toggle="collapse"
                href="#actionnariat"
                role="button"
                aria-expanded="false"
                aria-controls="actionnariat"
              >
                Actionnariat
              </a>



            <div class="collapse" id="actionnariat">
              <div class="card card-body">
                <div class="actionnairesRows">
                  <h5>Actionnaires</h5>
                  <?php

                  $rowsCount = 0;

                  $nbractionnaires = count($actionnairesl);

                  foreach ($actionnairesl as $actionnaire)
                  {
                    $rowsCount++; 
                    ?>

                      <div class="row actionnaireRowInfos actionnaireRow<?php echo $rowsCount;?>">

                        <div class="col-md-2">
                          <input
                            type="text"
                            class="form-control"
                            placeholder="Nom / Raison sociale"
                            name="noms_rs_actionnaires[]"
                            value="<?php echo $actionnaire['nom'];?>"
                          /> 
                        </div>
                        <div class="col-md-2">
                          <input
                            type="text"
                            class="form-control"
                            placeholder="Prénom (p.physique)"
                            name="prenoms_actionnaires[]"
                            value="<?php echo $actionnaire['prenom'];?>"
                          /> 
                        </div>
                        <div class="col-md-2">
                          <input
                            type="text"
                            class="form-control"
                            placeholder="N° d'identité / N° du RC"
                            name="identite_actionnaires[]"
                            value="<?php echo $actionnaire['cinRC'];?>"
                          /> 
                        </div>
                        <div class="col-md-2">
                          <input
                            type="text"
                            class="form-control"
                            placeholder="Nombre de titres"
                            name="nombre_titres_actionnaires[]"
                            value="<?php echo $actionnaire['nombreTitres'];?>"
                          /> 
                        </div>
                        <div class="col-md-3">
                          <input
                            type="text"
                            class="form-control"
                            placeholder="% Capital ou droit de vote"
                            name="pourcentage_capital_actionnaires[]"
                            value="<?php echo $actionnaire['capital'];?>"
                          /> 
                        </div>


                        <div class="col-md-1">
                          <a href="#" style="line-height: 3;<?php if($rowsCount < $nbractionnaires){ echo "display:none;"; }?>" class="deleteActionnaireRow deleteActionnaireRow<?php echo $rowsCount;?>" data-rowID="<?php echo $rowsCount;?>"><center><i class="ti ti-trash w-100 h5"></i></center></a>
                        </div>
                      </div>

                    <?php
                  }
                  ?>

                </div>

                <div class="row addActionnaireRow">
                  <div class="col-md-2">
                    <a href="#" class="btn btn-light-info" style="margin-top: 10px;" id="addActionnaireRowBtn">Ajouter un actionnaire</a>
                  </div>
                </div>
              </div>
            </div>
        </p>

        <?php
    }
}