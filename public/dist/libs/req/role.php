<!DOCTYPE html>
<html lang="fr">
<meta charset="UTF-8">
  <?php


  $page_title = "Mon profil";
  $page_code = "mon_profil";
  require_once '../../dist/libs/req/settings.php';
  require $req_path . 'head.php';

  ?>
  <body>
    <?php require $req_path . 'preloader.php'; ?>

    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-theme="blue_theme"  data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">

      <?php require $req_path . 'sidebar.php'; ?>
      
      <!--  Main wrapper -->
      <div class="body-wrapper">
        <?php require $req_path . 'header.php'; ?>

        <div class="container-fluid mw-100" >
          <div class="card bg-light-info shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3" style="background-image:url('../../dist/images/breadcrumb/gradient.jpeg');background-size: 100% auto;background-position: center top;">
              <div class="row align-items-center">
                <div class="col-9">
                  <h4 class="fw-semibold mb-8"><?php echo $page_title; ?></h4>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a class="text-muted " href="./">Tableau de bord</a></li>
                      <li class="breadcrumb-item" aria-current="page"><?php echo $page_title; ?></li>
                    </ol>
                  </nav>
                </div>
                <div class="col-3">
                  <div class="text-center mb-n5">  
                    <img src="../../dist/images/breadcrumb/etablissements.png" alt="" class="img-fluid mb-n4" style="display: none;">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <?php require $req_path . 'roledetails.php'; ?>

          
        </div>
      </div>
      <div class="dark-transparent sidebartoggler"></div>
    <div class="dark-transparent sidebartoggler"></div>
    </div>

    <?php // require $req_path . 'mobilenav.php'; ?>
    <?php // require $req_path . 'searchbar.php'; ?>
    <?php // require $req_path . 'customizer.php'; ?>


    <?php require $req_path . 'scripts.php'; ?>
    
    
   
    
  </body>
</html>