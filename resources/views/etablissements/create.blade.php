@extends('layouts.app')
@section('content')
<div class="container-fluid mw-100">
          <div class="card bg-light-info shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
              <div class="row align-items-center">
                <div class="col-9">
                  <h4 class="fw-semibold mb-8">Establissment</h4>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="./index.html">Dashboard</a></li>
                      <li class="breadcrumb-item" aria-current="page">Nouvel Etablissement</li>
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
          <div class="max-w-sm mx-auto bg-white rounded-xl shadow-md overflow-hidden">
<div class="max-w-7xl mx-auto bg-white rounded-xl shadow-sm border border-gray-50">
    
    <!-- Card Header -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50  rounded-t-xl">
        <h2 class="text-xl font-semibold text-gray-800">
            Establissment 
        </h2>
    </div>

    <!-- Card Body -->
    <div class="p-6">
        <div class="widget-content searchable-container list space-y-6">

            @include('etablissements.infoetablissement.InfoGenerals.create')

            @include('etablissements.infoetablissement.CoordonneesBancaires.create')

            @include('etablissements.infoetablissement.TypologieClient.create')

            @include('etablissements.infoetablissement.StatutFATCA.create')

            @include('etablissements.infoetablissement.SituationFinanciere.create')

            @include('etablissements.infoetablissement.Actionnariat.create')

            @include('etablissements.infoetablissement.BenificiaireEffectif.create')

            @include('etablissements.infoetablissement.Administrateurs.create')

            @include('etablissements.infoetablissement.PersonnesHabilites.create')

            @include('etablissements.infoetablissement.ObjetRelation.create')

            @include('etablissements.infoetablissement.ProfilRisque.create')

        </div>
    </div>

</div>

</div>
@endsection
