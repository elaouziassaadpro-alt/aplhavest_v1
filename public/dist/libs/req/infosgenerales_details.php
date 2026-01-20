<div class="card w-100">
  <div class="card-body">
    <form id="formulaire_global" enctype="multipart/form-data">
      <div class="form-body">
        <?php

          use Edit\titreinsertion;
          use Edit\infosgenerales;
          use Edit\coordonneesbancaires;
          use Edit\typologieclient;
          use Edit\statutfatca;
          use Edit\situationfinanciere;
          use Edit\actionnaires;
          use Edit\benificiaireseffectifs;
          use Edit\administrateurs;
          use Edit\personneshabilites;
          use Edit\objetrelation;
          use Edit\profilrisque;
          use Edit\boutonsinserer;
          use Edit\popupinsertion;


          use Edit\detailscalcul;

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
          $detailscalcul = new detailscalcul();

          
          $titrePage->render("Etablissement : " . $page_title);

          $infosgenerales->render(
            $nom_rs,
            $capitalSocialPrimaire,
            $forme_juridique_id,
            $forme_juridique,
            $dateImmatriculation,
            $ice,
            $rc,
            $ifiscal,
            $siegeSocial,
            $lieuActivite,
            $lieuActiviteid,
            $paysResidence,
            $paysResidenceid,
            $nomRegulateur,
            $regule,
            $telephone,
            $email,
            $siteweb,
            $societeGestion,
            $contacts);

          $coordonneesbancaires->render($cBancaires);

          $typologieclient->render(
            $scID,
            $scLibelle,
            $smID,
            $smLibelle,
            $activiteEtranger,
            $paysEtranger,
            $paysEtrangerid,
            $publicEpargne2,
            $publicEpargne);

          $statutfatca->render(
          $usEntity,
          $usEntity2,
          $giin,
          $giin2,
          $giinAutres);

          $situationfinanciere->render(
            $capitalSocial,
            $origineFonds,
            $chiffreAffaires,
            $resultatsNet,
            $holding,
            $paysOrigineFonds,
            $paysOrigineFondsLibelle);

          $actionnaires->render($actionnairesl);
          $benificiaireseffectifs->render($benificiairesl);
          $administrateurs->render($administrateursl);
          $personneshabilites->render($habilitesl);

          $objetrelation->render(
            $frequenceOperations,
            $horizonPlacement,
            $objetRelationAffaires,
            $nomMandataire,
            $dateFinMandat);

          $profilrisque->render();

          $detailscalcul->render($detailscalculetablissement);


          use Edit\fichierscaches;
          $fichierscaches = new fichierscaches();
          $fichierscaches->render();

        ?>
    </div>
  </form>
</div>
</div>

<?php

$boutonsinserer->render();
$popupinsertion->render();

?>