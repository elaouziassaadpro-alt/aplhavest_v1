  @extends('layouts.app')

  @section('content')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="{{ asset('dist/js/pages/ObjetRelation.js') }}"></script>
  <style>.mandat-hide {
      display: none;
  }
  </style>

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
                              <li class="breadcrumb-item active">Obejet relation</li>
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
      <form action="{{ route('objetrelation.store') }}" method="post" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="etablissement_id" value="{{ $etablissement->id }}">

          <div class="max-w-7xl mx-auto bg-white cardetablissement shadow-sm border border-gray-100 mt-4 ">
            <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl">
                <h2 class="text-xl font-semibold text-gray-800">Obejet relation</h2>
            </div>
                  <div class="row">
                    <div class="col-md-7">
                      <h5>Fréquence des opérations :</h5>
                      <div class="row">
                        <div class="mb-6">
                          <div style="margin-top:10px;"></div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="relation_affaire_radio" id="natureRelation1" value="Quotidienne">
                            <label class="form-check-label" for="natureRelation1">Quotidienne</label>
                          </div>

                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="relation_affaire_radio" id="natureRelation2" value="Hebdomadaire">
                            <label class="form-check-label" for="natureRelation2">Hebdomadaire</label>
                          </div>

                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="relation_affaire_radio" id="natureRelation3" value="Mensuelle">
                            <label class="form-check-label" for="natureRelation3">Mensuelle</label>
                          </div>

                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="relation_affaire_radio" id="natureRelation4" value="Trimestrielle">
                            <label class="form-check-label" for="natureRelation4">Trimestrielle</label>
                          </div>

                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="relation_affaire_radio" id="natureRelation5" value="Annuelle">
                            <label class="form-check-label" for="natureRelation5">Annuelle</label>
                          </div>

                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="relation_affaire_radio" id="natureRelation6" value="Ponctuelle">
                            <label class="form-check-label" for="natureRelation6">Ponctuelle</label>
                          </div>

                        </div>
                      </div>
                    </div>

                    <div class="col-md-5">
                      <h5>Horizon de placement :</h5>
                      <div class="row">
                        <div class="mb-6">
                          <div style="margin-top:10px;"></div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="horizon_placement_radio" id="horizonPlacement1" value="< 1 an">
                            <label class="form-check-label" for="horizonPlacement1">< 1 an</label>
                          </div>

                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="horizon_placement_radio" id="horizonPlacement2" value="Entre 1 et 3 ans">
                            <label class="form-check-label" for="horizonPlacement2">Entre 1 et 3 ans</label>
                          </div>

                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="horizon_placement_radio" id="horizonPlacement3" value="Entre 3 et 5 ans">
                            <label class="form-check-label" for="horizonPlacement3">Entre 3 et 5 ans</label>
                          </div>

                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="horizon_placement_radio" id="horizonPlacement4" value="< 5 ans">
                            <label class="form-check-label" for="horizonPlacement4">< 5 ans</label>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-7">
                      <h5>Objet de la relation d'affaire :</h5>
                      <div class="row">
                        <div class="mb-6">

                          <div style="margin-top:10px;"></div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input objetrelationcheck" type="checkbox" id="objetRelation1" name="objet_relation[]" value="Assurer des revenus réccurents">
                            <label class="form-check-label" for="objetRelation1">Assurer des revenus réccurents</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input objetrelationcheck" type="checkbox" id="objetRelation2" name="objet_relation[]" value="Profits à moyen et court terme">
                            <label class="form-check-label" for="objetRelation2">Profits à moyen et court terme</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input objetrelationcheck" type="checkbox" id="objetRelation3" name="objet_relation[]" value="Gestion de la trésorerie">
                            <label class="form-check-label" for="objetRelation3">Gestion de la trésorerie</label>
                          </div>

                        </div>
                      </div>
                    </div>

                    <div class="col-md-5">
                      <h5>Compte géré par mandataire :</h5>

                      <div class="mb-3 row">
                        <div class="mb-4 bt-switch col-2 form-check form-switch">
                          <input type="hidden" name="mandataire_check" value="0">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="mandataire_id"
                                name="mandataire_check"
                                value="1"
                            />
                          <label class="form-check-label">
                            <span id="mandataireLabel">Non</span>
                          </label>
                        </div>
                        
                        <div class="mb-3 col-4 mandat-hide">
                          <input
                            type="text"
                            class="form-control"
                            placeholder="Description"
                            name="mandataire_label"
                            style="display: block !important;"
                          />
                        </div>

                        <div class="mb-3 col-2 mandat-hide">
                          <label>Date fin de mandat</label>
                        </div>

                        <div class="mb-3 col-4 mandat-hide">
                          <input
                            type="date"
                            class="form-control"
                            name="mandataire_fin_mandat_date"
                          />
                        </div>
                      </div>

                      <div class="mb-3 row" style="margin-top:-30px">
                        <div class="mb-4 col-4 mandat-hide">
                          <label for="mandat_file" class="btn btn-primary" style="width:100%;">
                            <i class="ti ti-upload"></i>&nbsp; Mandat pouvoir
                          </label>
                          <input type="file" id="mandat_file" name="mandat_file" hidden>
                        </div>
                      </div>
                    </div>

                  </div>

                </div>
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