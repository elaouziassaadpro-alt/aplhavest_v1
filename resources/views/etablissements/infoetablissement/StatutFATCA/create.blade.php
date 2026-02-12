@extends('layouts.app')

@section('content')
<script src="{{ asset('dist/js/pages/StatutFATCA.js') }}"></script>

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
                                <a class="text-muted text-decoration-none" href="{{ url('/') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Statut FATCA</li>
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
    <form action="{{ route('statutfatca.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="etablissement_id" value="{{ $etablissement->id }}">

        <div class="max-w-7xl mx-auto bg-white cardetablissement shadow-sm border border-gray-100 mt-4 ">

            <!-- Card header -->
            <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl">
                <h2 class="text-xl font-semibold text-gray-800">Statut FATCA</h2>
            </div>

            <!-- Card body -->
            <div class="row mt-4">

                <!-- US Entity -->
                <div class="col-md-6 mb-4">
                    <h5 class="font-medium text-gray-700 mb-3">Votre établissement est-il considéré comme <b>"US Entity"</b> ?</h5>
                    <div class="flex items-center gap-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="us_entity_id" name="us_entity_check" value="1">
                            <label class="form-check-label" for="us_entity_id">
                                <span id="usEntityLabel">Non</span>
                            </label>
                        </div>

                        <div class="flex-1">
                            <input type="file" name="fichiers[fatca]" id="fatca_hidden_input" class="d-none" />

                        <div id="dropzone-fichierFATCA" class="dropzone mt-2"
                            style="display:none; min-height: 80px; border: 2px dashed #ccc; border-radius: 8px; padding: 10px; cursor:pointer;">
                            <p class="text-muted text-center my-2">
                                Glissez-déposez votre fichier FATCA ici ou cliquez pour sélectionner
                            </p>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Participating Financial Institution -->
                <div class="col-md-6 mb-4">
                    <h5 class="font-medium text-gray-700 mb-3">
                        Votre établissement est une <b>"Participating Financial Institution"</b> ?
                    </h5>

                    <div class="flex items-center gap-4 mt-2">

                        <!-- Checkbox -->
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="giin_id" name="giin_check" value="1">
                            <label class="form-check-label" for="giin_id">
                                <span id="giinLabel">Non</span>
                            </label>
                        </div>

                        <!-- GIIN Input -->
                        <div class="flex-1 " id="giin_data_id" style="display:none;">
                            <label>GIIN:</label>
                            <input
                                type="text"
                                class="form-control giin-inputmask"
                                placeholder="Global Intermediary Identification Number"
                                name="giin_inputs"
                            >
                        </div>

                        <!-- Autres Input -->
                        <div class="flex-1" id="giin_data_autres_id">
                            <label>Autres:</label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Précisions"
                                name="giin_autres_input"
                            >
                        </div>

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

<!-- ===================== Styles ===================== -->
<style>
#dropzone-fichierFATCA {
    min-height: 90px;
    border: 2px dashed rgba(0,0,0,0.1);
    background: #fff;
    border-radius: 10px;
    padding: 10px;
    cursor: pointer;
}
#dropzone-fichierFATCA:hover {
    background: #f9f9f9;
}
</style>


@endsection
