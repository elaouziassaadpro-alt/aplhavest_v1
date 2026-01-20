<div class="card w-100">
  <div class="card-body">
    <form id="formulaire_global" enctype="multipart/form-data">
      <div class="form-body">
        <?php

          use Layout\titreinsertion;
          use Layout\infosgenerales;
          use Layout\coordonneesbancaires;
          use Layout\typologieclient;
          use Layout\statutfatca;
          use Layout\situationfinanciere;
          use Layout\actionnaires;
          use Layout\benificiaireseffectifs;
          use Layout\administrateurs;
          use Layout\personneshabilites;
          use Layout\objetrelation;
          use Layout\profilrisque;
          use Layout\boutonsinserer;
          use Layout\popupinsertion;

          $titrePage = new titreInsertion();
          $infosgenerales = new infosGenerales();
          $coordonneesbancaires = new coordonneesbancaires();
          $typologieclient = new typologieclient();
          $statutfatca = new statutfatca();
          $situationfinanciere = new situationfinanciere();
          $actionnaires = new actionnaires();
          $benificiaireseffectifs = new benificiaireseffectifs();
          $administrateurs = new administrateurs();
          $personneshabilites = new personneshabilites();
          $objetrelation = new objetrelation();
          $profilrisque = new profilrisque();
          $boutonsinserer = new boutonsinserer();
          $popupinsertion = new popupinsertion();

          
          $titrePage->render();
          $infosgenerales->render();
          $coordonneesbancaires->render();
          $typologieclient->render();
          $statutfatca->render();
          $situationfinanciere->render();
          $actionnaires->render();
          $benificiaireseffectifs->render();
          $administrateurs->render();
          $personneshabilites->render();
          $objetrelation->render();
          $profilrisque->render();


          use Layout\fichierscaches;
          $fichierscaches = new fichierscaches();
          $fichierscaches->render();

        ?>
    </div>
  </form>
  <input type="hidden" class="hidden" name="" id="page_type" value="insertion">
</div>
</div>

<?php

$boutonsinserer->render();
$popupinsertion->render();

?>