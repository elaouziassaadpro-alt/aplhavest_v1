@extends('layouts.app')

@section('content')
<script src="{{ asset('dist/js/pages/Actionnariat.js') }}"></script>

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
                            <li class="breadcrumb-item active">Actionnaires</li>
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
    <form action="{{ route('actionnariat.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
        <input type="hidden" name="etablissement_id" value="{{ $etablissement->id }}">

        <div class="max-w-7xl mx-auto bg-white cardetablissement shadow-sm border border-gray-100 mt-4 ">

            <!-- Card header -->
            <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl">
                <h2 class="text-xl font-semibold text-gray-800">Actionnaires</h2>
            </div>

            <!-- Card body -->
            <div class="mt-4 px-6">
                @if($etablissement->Actionnaire && $etablissement->Actionnaire->count())
                    <h5 class="fw-semibold mb-3">Actionnaires existants :</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Nom / Raison sociale</th>
                                    <th>Prénom</th>
                                    <th>Identité / RC</th>
                                    <th>Nombre de titres</th>
                                    <th>% Capital</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($etablissement->Actionnaire as $exist)
                                    <tr>
                                        <td>{{ $exist->nom_rs }}</td>
                                        <td>{{ $exist->prenom ?? '—' }}</td>
                                        <td>{{ $exist->identite ?? '—' }}</td>
                                        <td>{{ $exist->nombre_titres ?? '—' }}</td>
                                        <td>{{ $exist->pourcentage_capital ? $exist->pourcentage_capital . '%' : '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <hr class="mb-4">
                @endif
            </div>

            <div class="mt-4"></div>
                <div class="actionnairesRows">

                </div>

                <div class="row addActionnaireRow mt-3">
                  <div class="col-md-2">
                    <a href="#" id="addActionnaireRowBtn" class="btn btn-light-info">
                        Ajouter un actionnaire
                    </a>                  
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
    

<!-- ===================== SAVE BUTTON ===================== -->
        

@endsection