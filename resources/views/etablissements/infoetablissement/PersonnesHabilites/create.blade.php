@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('dist/js/pages/personnehabilite.js') }}"></script>

<script>
    window.APP_DATA = {
        ppes: @json($ppes)
    };
</script>

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
                            <li class="breadcrumb-item active">Personne habilité</li>
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
    <form action="{{ route('personneshabilites.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
        <input type="hidden" name="etablissement_id" value="{{ $etablissement->id }}">

        <div class="max-w-7xl mx-auto bg-white cardetablissement shadow-sm border border-gray-100 mt-4 ">
            <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl">
                <h2 class="text-xl font-semibold text-gray-800">Personne Habilité </h2>
            </div>

            <div class="mt-4 px-6">
                @if($etablissement->PersonnesHabilites && $etablissement->PersonnesHabilites->count())
                    <h5 class="fw-semibold mb-3">Personnes habilitées existantes :</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Identité / Passport</th>
                                    <th>Qualité</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($etablissement->PersonnesHabilites as $exist)
                                    <tr>
                                        <td>{{ $exist->nom }}</td>
                                        <td>{{ $exist->prenom ?? '—' }}</td>
                                        <td>{{ $exist->identite ?? '—' }}</td>
                                        <td>{{ $exist->qualite ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <hr class="mb-4">
                @endif
            </div>
            <!-- Conteneur pour les lignes dynamiques -->
            <div class="personneHabiliteRows"></div>
            
            <!-- Bouton Ajouter -->
            <div class="row addPHabiliteRow mb-3">
                <div class="col-md-3">
                    <a href="#" class="btn btn-light-info" id="addPHabiliteRowBtn" style="margin-top: 10px;">
                        Ajouter une personne habilité
                    </a>
                </div>
            </div>

            

            <!-- Bouton de sauvegarde -->
            

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
