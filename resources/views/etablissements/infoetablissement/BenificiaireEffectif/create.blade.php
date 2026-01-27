@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    window.dropdownData = {
        ppesArray: [] // will be filled by AJAX
    };
</script>

<script src="{{ asset('dist/js/pages/benificiaireeffectif.js') }}"></script>
<style>
  .custom-select-wrapper {
    position: relative;
}

.custom-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    z-index: 1000;
    display: none; /* Hidden by default */
    background: white;
    border: 1px solid #ddd;
    max-height: 200px;
    overflow-y: auto;
    list-style: none;
    padding: 0;
    margin: 0;
}

.custom-dropdown.show {
    display: block; /* Shown when class is added via JS */
}

.custom-dropdown li {
    padding: 8px 12px;
    cursor: pointer;
}

.custom-dropdown li:hover {
    background-color: #f8f9fa;
}
</style>

<div class="container-fluid mw-100">

    <!-- ===================== Breadcrumb ===================== -->
    <div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-2">Establishment</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ url('/') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">
                                Bénéficiaire effectif #{{ $info_generales_id }}{{ $ppes[0]->libelle }}
                            </li>
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
    <form action="{{ route('benificiaireeffectif.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="info_generales_id" value="{{ $info_generales_id }}">

        <div class="max-w-7xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 mt-4 p-6">

            <!-- Card header -->
            <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl">
                <h2 class="text-xl font-semibold text-gray-800">Bénéficiaires Effectif</h2>
            </div>

            <!-- Card body -->
            <div class="mt-4">

                <!-- Bénéficiaires -->
                <div class="benificiairesRows mb-4">
                </div>        

                <div class="row addBenificiaireRow">
                    <div class="col-md-2">
                        <a href="#" class="btn btn-light-info mt-3" id="addBenificiaireRowBtn">Ajouter un bénéficiaire</a>
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
