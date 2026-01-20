<?php
namespace Edit;

class detailscalcul {
    public function render($detailscalculetablissement) {
        ?>
        
          <p>
            <a
              class="btn btn-light-info w-100 font-medium text-info"
              data-bs-toggle="collapse"
              href="#detailscalcul"
              role="button"
              aria-expanded="false"
              aria-controls="detailscalcul"
            >
              Détails calcul
            </a>
      

          <div class="collapse" id="detailscalcul">
            <div class="card card-body">

              <table class="table mb-0" style="width:100%;max-width: 100%;">
                  <thead>
                      <tr>
                          <th scope="col">#</th>
                          <th scope="col">Note</th>
                          <th scope="col">Détails</th>
                          <th scope="col">Valeur</th>
                          <th scope="col">Risque</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php


                        $numero = 0;

                        foreach ($detailscalculetablissement as $detail)
                        {

                          $numero++;
                          
                      ?>

                      <tr class="text-danger">
                          <th scope="row"  <?php if($detail['note'] < 3){echo "class='text-success'";}else if($detail['note']<10){echo "class='text-warning'";}else if($detail['note']>10){echo "class='text-danger'";}  ?>><?php echo $numero; ?></th>
                          <td <?php if($detail['note'] < 3){echo "class='text-success'";}else if($detail['note']<10){echo "class='text-warning'";}else if($detail['note']>10){echo "class='text-danger'";}  ?>><?php echo $detail['note']; ?></td>
                          <?php

                            $parts = explode(':', $detail['detailNote'], 2); // 2 = maximum 2 éléments

                            $chaine = trim($parts[0] ?? "");
                            $valeur = trim($parts[1] ?? "");

                            if($valeur == '')
                            {
                              $valeur = '-';
                            }

                          ?>
                          <td <?php if($detail['note'] < 3){echo "class='text-success'";}else if($detail['note']<10){echo "class='text-warning'";}else if($detail['note']>10){echo "class='text-danger'";}  ?>><?php echo $chaine; ?></td>
                          <td <?php if($detail['note'] < 3){echo "class='text-success'";}else if($detail['note']<10){echo "class='text-warning'";}else if($detail['note']>10){echo "class='text-danger'";}  ?>><?php echo $valeur; ?></td>
                          <td <?php if($detail['note'] < 3){echo "class='text-success'";}else if($detail['note']<10){echo "class='text-warning'";}else if($detail['note']>10){echo "class='text-danger'";}  ?>>
                            
                            <?php 

                              if($detail['note'] < 3)
                                {
                                  echo "LR";
                                }
                              else if($detail['note']<10)
                                {
                                  echo "MR";
                                }
                              else if($detail['note']>10 && $detail['note'] <500)
                                {
                                  echo "HR";
                                }
                              else if($detail['note']>499 && $detail['note'] <1000)
                                {
                                  echo "A SURVEILLER";
                                }
                              else if($detail['note']>999)
                                {
                                  echo "INTERDIT";
                                }
                              

                            ?>

                          </td>
                      </tr>

                      <?php

                        } 

                      ?>
                  </tbody>
              </table>

            </div>
          </div>
        </p>

        <?php
    }
}