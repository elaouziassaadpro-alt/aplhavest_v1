@extends('layouts.app')

@section('content')

<script src="{{ asset('dist/js/pages/ProfilRisque.js') }}"></script>
<div class="container-fluid mw-100">

    <!-- Breadcrumb -->
    <div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-2">Establishment : {{ $etablissement->name }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ url('/') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Profil Risque</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3 text-center">
                    <img src="{{ asset('dist/images/breadcrumb/ChatBc.png') }}" class="img-fluid" alt="Breadcrumb image">
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('profilrisque.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="etablissement_id" value="{{ $etablissement->id }}">

        <div class="max-w-7xl mx-auto bg-white cardetablissement shadow-sm border border-gray-100 mt-4">

            <!-- First Row -->
            <div class="row">

                <!-- Département en charge -->
                <div class="col-md-6">
                    <h5>Département en charge de la gestion des placements / investissements</h5>
                    <div class="mb-3 row">
                        <div class="mb-4 row">
                          <!-- Switch -->
                          <div class="mb-4 bt-switch col-1 form-check form-switch flex">
                              
                              <input
                                  type="checkbox"
                                  id="departement_gestion_id"
                                  name="departement_en_charge_check"
                                  class="form-check-input"
                              />
                              <label id="labelcheckbox">No</label>
                          </div>
                            
                          <!-- Select: hidden by default -->
                          <div class="mb-3 col-8 hidden" id="departement_gestion_data_container">
                              <select class="form-control" id="departement_gestion_data_id" name="departement_gestion_input">
                                  <option>La part du portfeuille de valeurs mobilières</option>
                                  <option>Inférieur à 5%</option>
                                  <option>Entre 5% et 10%</option>
                                  <option>Entre 10% et 25%</option>
                                  <option>Entre 25% et 50%</option>
                                  <option>Supérieure à 50%</option>
                              </select>
                          </div>
                      </div>

                  </div>
                </div>
                <!-- Instruments financiers -->
                <div class="col-md-6">
                    <h5>Les instruments financiers souhaités</h5>
                    <div class="mb-3 row">
                        <div class="mb-4 col-12">
                            <div class="mb-6">
                                @php
                                    $instruments = [
                                        'OPCVM Monétaires', 'OPCVM Obligataires', 'OPCVM Actions',
                                        'OPCVM Diversifiés', 'Bons de Trésor', 'Titres de dette privé', 'Actions'
                                    ];
                                @endphp
                                @foreach($instruments as $index => $instrument)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="instruments_souhaites_input[]" id="instrumentsSouhaites{{ $index+1 }}" value="{{ $instrument }}">
                                        <label class="form-check-label" for="instrumentsSouhaites{{ $index+1 }}">{{ $instrument }}</label>
                                    </div>
                                @endforeach
                                <div class="form-check form-check-inline">
                                    <input type="text" class="form-control inline-block" placeholder="Autres" name="instruments_souhaites_input[]"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Second Row: Niveau de risque et années d'investissement -->
            <div class="row">

                <!-- Niveau de risque toléré -->
                <div class="col-md-6">
                    <h5>Le niveau de risque toléré</h5>
                    <div class="mb-3 row">
                        <div class="mb-4 col-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="nrTolere1" value="Faible" name="niveau_risque_tolere_radio">
                                <label class="form-check-label" for="nrTolere1">Faible (Stratégie défensive)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="nrTolere2" value="Moyen" name="niveau_risque_tolere_radio">
                                <label class="form-check-label" for="nrTolere2">Moyen (Stratégie équilibrée)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="nrTolere3" value="Elevé" name="niveau_risque_tolere_radio">
                                <label class="form-check-label" for="nrTolere3">Elevé (Stratégie agressive)</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Années d'investissement -->
                <div class="col-md-6">
                    <h5>Années d'investissement dans les produits financiers</h5>
                    <div class="mb-3 row">
                        <div class="mb-4 col-12">
                            @php
                                $anneesOptions = ['Jamais', 'Jusqu’à 1 an', 'Entre 1 et 5 ans', 'Plus que 5 ans'];
                            @endphp
                            @foreach($anneesOptions as $index => $value)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="anneesProduitsFinanciers{{ $index+1 }}" value="{{ $value }}" name="annees_investissement_produits_finaniers">
                                    <label class="form-check-label" for="anneesProduitsFinanciers{{ $index+1 }}">{{ $value }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <!-- Third Row: Transactions -->
            <div class="row">
                <div class="col-md-6">
                    <h5>Transactions en moyenne réalisés sur le marché courant 2 dernières années</h5>
                    <div class="mb-3 row">
                        <div class="mb-4 col-12">
                            @php
                                $transactions = ['Aucune', 'Moins de 30', 'Plus de 30'];
                            @endphp
                            @foreach($transactions as $index => $value)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="transactions2annees{{ $index+1 }}" value="{{ $value }}" name="transactions_courant_2_annees">
                                    <label class="form-check-label" for="transactions2annees{{ $index+1 }}">{{ $value }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Submit button -->
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
