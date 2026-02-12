@extends('layouts.app')

@section('content')
<script src="{{ asset('dist/js/pages/situationfinanciere.js') }}"></script>

<div class="container-fluid mw-100">

    <!-- ===================== Breadcrumb ===================== -->
    <div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-2">Establishment : {{ $etablissement->name }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" >Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Situation Financière Patrimoniale</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3 text-center">
                    <img src="{{ asset('dist/images/breadcrumb/ChatBc.png') }}" class="img-fluid" alt="Breadcrumb image">
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== FORM ===================== -->
    <form action="{{ route('situationfinanciere.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="etablissement_id" value="{{ $etablissement->id }}">

        <div class="max-w-7xl mx-auto bg-white cardetablissement shadow-sm border border-gray-100 mt-4 ">

            <!-- Card header -->
            <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl">
                <h2 class="text-xl font-semibold text-gray-800">Situation Financière Patrimoniale </h2>
            </div>

            <!-- Card body -->
            <div class="mt-4">

                <!-- Capital social, origine des fonds, pays -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label>Capital social</label>
                        <input type="text" class="form-control number-format" name="capital_social" value="0">
                    </div>
                    <div class="col-md-4">
                        <label>Origine des fonds</label>
                        <input type="text" class="form-control" name="origine_fonds" value="">
                    </div>
                    <div class="col-md-4 mb-3">
                      <label>Pays de résidence fiscale</label>
                      <div class="custom-select-wrapper relative">
                          <input type="text" id="formeInputpayresidence" class="form-control" placeholder="Tapez pour rechercher...">
                          <ul id="formeDropdownpayresidence" class="custom-dropdown absolute bg-white border border-gray-300 w-full max-h-40 overflow-y-auto hidden z-50">
                              @foreach($pays as $pay)
                                  <li class="px-2 py-1 hover:bg-gray-200 cursor-pointer" data-value="{{ $pay->id }}">{{ $pay->libelle }}</li>
                              @endforeach
                          </ul>
                          <input type="hidden" id="formeSelectpayresidence" name="paysResidence" required>
                        </div>
                      </div>
                </div>

                <!-- Chiffre d'affaires -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label>Chiffre d'affaires (exercice écoulé)</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="chiffre_affaires" value="<5M">
                            <label class="form-check-label">< 5 M.DH</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="chiffre_affaires" value="5-10M">
                            <label class="form-check-label">5 M.DH < CA < 10 M.DH</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="chiffre_affaires" value=">10M">
                            <label class="form-check-label">> 10 M.DH</label>
                        </div>
                    </div>

                    <!-- Résultat net -->
                    <div class="col-md-4">
                        <label>Résultat net (exercice écoulé)</label>
                        <input type="text" class="form-control number-format" name="resultat_net" value="0">
                    </div>

                    <!-- Groupe holding -->
                    <div class="col-md-4">
                        <label>Appartient à un groupe <b>"holding"</b></label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="groupe_holding" value="1">
                            <label class="form-check-label">Oui</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="groupe_holding" value="0">
                            <label class="form-check-label">Non</label>
                        </div>
                    </div>
                </div>

                <!-- Upload états de synthèse -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="etat_synthese" class="btn btn-primary w-100">
                            <i class="ti ti-upload"></i> Etats de synthèse
                        </label>
                        <input type="file" name="etat_synthese" id="etat_synthese" class="d-none">
                    </div>
                </div>

                

            </div>
        </div>
    <!-- ===================== SAVE BUTTON ===================== -->
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-save d-flex align-items-center justify-content-center mx-auto">
                <svg class="w-6 h-6 me-2" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 7 2 2 4-4m-5-9v4h4V3h-4Z"/>
                </svg>
                Save
            </button>
        </div>
</form>
</div>


@endsection
