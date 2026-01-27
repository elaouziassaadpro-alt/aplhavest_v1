@extends('layouts.app')

@section('content')
<script>
    window.dropdownData = {
        secteurs: @json($secteurs),
        segments: @json($segments),
        pays: @json($pays)
    };
</script>
<script src="{{ asset('dist/js/pages/typologie.js') }}"></script>

<div class="container-fluid mw-100">

    <!-- ===================== Breadcrumb ===================== -->
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-2">Establishment</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ url('/') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Nouvel Établissement {{ $secteurs[0]->libelle }} </li>
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
    <form action="{{ route('typologie.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="info_generales_id" value="{{ $info_generales_id }}">

        <!-- ===================== MAIN CARD ===================== -->
        <div class="max-w-7xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 mt-4 p-4">

            <!-- Card header -->
            <div class="px-6 py-4 border-b bg-gray-50 rounded-top">
                <h2 class="text-xl font-semibold text-gray-800">Typologie Client</h2>
            </div>

            <!-- Card body -->
            <div class="row mt-3">
                <div class="col-md-2 mb-3">
                  <label>Secteur d'activité</label>
                  <div class="custom-select-wrapper">
                      <input type="text" id="formeInputsecteur" class="form-control" placeholder="Tapez pour rechercher...">
                      <ul id="formeDropdownsecteur" class="custom-dropdown">
                          @foreach($secteurs as $secteur)
                              <li data-value="{{ $secteur->id }}">{{ $secteur->libelle }}</li>
                          @endforeach
                      </ul>
                      <input type="hidden" id="formeSelectsecteur" name="secteurActivite" required>
                  </div>
              </div>

                <!-- Segment -->
                <div class="col-md-2 mb-3">
                    <label>Segment</label>
                    <div class="custom-select-wrapper">
                        <input type="text" id="formeInputsegment" class="form-control" placeholder="Tapez pour rechercher...">
                        <ul id="formeDropdownsegment" class="custom-dropdown">
                            @foreach($segments as $segment)
                                <li data-value="{{ $segment->id }}">{{ $segment->libelle }}</li>
                            @endforeach
                        </ul>
                        <input type="hidden" id="formeSelectsegment" name="segment" required>
                    </div>
                </div>

                <!-- Activité à l'étranger -->
                <div class="col-md-1 mb-3">
                <label>Activité à l'étranger</label>

                <div class="form-check form-switch">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="activite_etranger_check"
                        name="activite_etranger"
                        value="1"
                    >
                    <label class="form-check-label">
                        <span id="activiteEtrangerLabel">Non</span>
                    </label>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                
                <div class="mt-2 d-none" id="paysInputWrapper">
                    <label>Pays</label>
                    <div class="custom-select-wrapper">
                        <input
                            type="text"
                            id="formeInputpays"
                            class="form-control"
                            placeholder="Tapez pour rechercher..."
                        >
                        <ul id="formeDropdownpays" class="custom-dropdown">
                            @foreach($pays as $pay)
                                <li data-value="{{ $pay->id }}">{{ $pay->libelle }}</li>
                            @endforeach
                        </ul>
                        <input type="hidden" id="formeSelectpays" name="pays">
                    </div>
                </div>
            </div>


                <!-- Sur un marché financier / appel public à l'épargne -->
            
                
            <div class="col-md-2 mb-3">
                <label >
                    Sur un marché financier ou fait-il appel public à l'épargne ?
                </label>
                <div class="d-flex align-items-center gap-3">
                    <div class="form-check form-switch m-0">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="sur_marche_financier_check"
                            name="sur_marche_financier"
                            value="1"
                        >
                        <label class="form-check-label">
                            <span id="marcheFinancierLabel">Non</span>
                        </label>
                    </div>
                </div>
            </div>
                <div class="col-md-2 mb-3">
                    <div class="mt-2 d-none" id="marcheFinancierInputWrapper">
                        <label >
                    Précisez
                </label>
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Indiquez le marché financier"
                            name="sur_marche_financier_input"
                        />
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
