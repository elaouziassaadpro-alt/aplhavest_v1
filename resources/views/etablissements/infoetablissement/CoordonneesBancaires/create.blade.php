@extends('layouts.app')

@section('content')

{{-- ✅ Pass PHP data to JS --}}
<script>
    window.banquesData = @json($banques);
    window.villesData  = @json($villes);
</script>

<div class="container-fluid mw-100">

    <!-- ===================== Breadcrumb ===================== -->
    <div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-2">
                        Establishment {{ $info_generales_id }}
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ url('/') }}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                Nouvel Établissement
                            </li>
                        </ol>
                    </nav>
                </div>

                <div class="col-3 text-center">
                    <img src="{{ asset('dist/images/breadcrumb/ChatBc.png') }}"
                         class="img-fluid"
                         alt="Breadcrumb image">
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== FORM ===================== -->
    <form action="{{ route('coordonneesbancaires.store') }}" method="POST">
        @csrf

        <input type="hidden" name="info_generales_id" value="{{ $info_generales_id }}">

        <div class="card">
            <div class="card-body">

                <h5 class="mb-4">Coordonnées bancaires</h5>

                <!-- Dynamic rows -->
                <div class="CBancairesRows"></div>

                <div class="row mt-3">
                    <div class="col-md-3">
                        <button type="button"
                                class="btn btn-light-info"
                                id="addCBancairesRowBtn">
                            Ajouter un compte
                        </button>
                    </div>
                </div>

                

            </div>
        </div>
        <!-- Submit -->
                <div class="text-center mt-4">
                    <button type="submit"
                            class="btn btn-save d-flex align-items-center justify-content-center mx-auto">
                        Save
                    </button>
                </div>
    </form>

</div>

@endsection

@push('scripts')
<script src="{{ asset('dist/js/pages/coordonneesBoncaire.js') }}"></script>
@endpush
