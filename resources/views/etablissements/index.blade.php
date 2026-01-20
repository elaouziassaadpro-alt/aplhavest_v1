@extends('layouts.app')
@section('content')
<div class="container-fluid mw-100">
          <div class="card bg-light-info shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
              <div class="row align-items-center">
                <div class="col-9">
                  <h4 class="fw-semibold mb-8">Ecom-Shop</h4>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="./index.html">Dashboard</a></li>
                      <li class="breadcrumb-item" aria-current="page">Shop</li>
                    </ol>
                  </nav>
                </div>
                <div class="col-3">
                  <div class="text-center mb-n5">  
                    <img src="../../dist/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="widget-content searchable-container list">
  <!-- --------------------- start Contact ---------------- -->
  <div class="card card-body boutons_header">
    <div class="row">
      <div class="col-md-4 col-xl-3">
        <form class="position-relative">
          <input type="text" class="form-control product-search ps-5" id="input-search" placeholder="Recherche rapide..." />
          <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
        </form>
      </div>
      <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
        <div class="action-btn show-btn" style="display: none">
          <a href="javascript:void(0)" class="btn-light-success btn me-2 text-success d-flex align-items-center font-medium">
            <i class="ti ti-check text-success me-1 fs-5"></i> Valider la selection 
          </a>
        </div>
        <div class="action-btn show-btn" style="display: none">
          <a href="javascript:void(0)" class="btn-light-danger btn me-2 text-danger d-flex align-items-center font-medium">
            <i class="ti ti-x text-danger me-1 fs-5"></i> Rejeter la selection
          </a>
        </div>
        <a href="nouvel_etablissement.php" class="btn btn-info d-flex align-items-center">
          <i class="ti ti-users text-white me-1 fs-5"></i> Ajouter un etablissement
        </a>
      </div>
    </div>
  </div>





  <div class="card card-body">
    <div class="table-responsive">
      <table class="table search-table align-middle text-nowrap">
        <thead class="header-item">
          <th>
            <div class="n-chk align-self-center text-center">
              <div class="form-check">
                <input type="checkbox" class="form-check-input primary" id="contact-check-all" />
                <label class="form-check-label" for="contact-check-all"></label>
                <span class="new-control-indicator">Tous</span>
              </div>
            </div>
          </th>
          <th>Dénomination</th>
          <th>Pays d'origine</th>
          <th>Secteur / Activité</th>
          <th>Risque</th>
          <th>Note</th>
          <th>Etat</th>
          <th>Détails</th>
        </thead>
        <tbody>

          

          <!-- start row -->
          <tr class="search-items">
            <td>
              <div class="n-chk align-self-center text-center">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input contact-chkbox primary contact-check-all" id="checkbox1" />
                  <label class="form-check-label" for="checkbox1"></label>
                </div>
              </div>
            </td>
            <td>
              <span class="usr-email-addr">AD CAPITAL ASSET MANAGEMENT</span>
            </td>
            <td>
             <img src="../../dist/css/icons/flag-icon-css/flags/ma.svg" class="round-20" style="height: 15px !important;margin-top: -3px;margin-right: 5px;"> <span class="usr-email-addr">Maroc</span>
            </td>
            <td>
              <span class="usr-location">Gestion de Valeurs Mobilières</span>
            </td>
            <td>
              <span class="usr-ph-no text-red">HR</span>
            </td>
            <td>
              <span class="usr-ph-no text">75</span>
            </td>
            <td>
              <span class="usr-ph-no">
                valide
              </span>
            </td>
            <td>
              <div class="action-btn">

                ->

              </div>
            </td>
          </tr>
          <!-- end row -->



        </tbody>
      </table>
    </div>
  </div>
</div>
        </div>
@endsection