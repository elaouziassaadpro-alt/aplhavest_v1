@extends('layouts.app')

@section('content')
<script>
    window.actionnariatRoutes = {
        bulkDelete: "{{ route('actionnariat.bulkDelete') }}",
        csrfToken: "{{ csrf_token() }}"
    };
</script>
<script src="{{ asset('dist/js/pages/Actionnariat.js') }}?v={{ time() }}"></script>

<div class="container-fluid mw-100">
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Liste des Actionnaires</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Actionnaires</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Search & Actions -->
    <div class="card card-body boutons_header">
        <div class="row align-items-center">
            <div class="col-md-4 col-xl-3">
                <form class="position-relative">
                    <input type="text" class="form-control product-search ps-5" id="input-search" placeholder="Recherche par nom ou prénom...">
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                </form>
            </div>

            <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0 gap-2">
                <div class="action-btn show-btn" id="bulk-action" style="display: none">
                    <a href="javascript:void(0)"
                    id="delete-selection"
                    class="btn btn-light-danger text-danger d-flex align-items-center font-medium">
                        <i class="ti ti-trash me-1 fs-5"></i> Supprimer la sélection
                    </a>
                </div>


                <a href="{{ route('actionnariat.create') }}" class="btn btn-info d-flex align-items-center">
                    <i class="ti ti-users text-white me-1 fs-5"></i> Ajouter un actionnaire
                </a>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table search-table align-middle text-nowrap">
                <thead>
                    <tr>
                        <th class="text-center">
                            <input type="checkbox" id="actionnaire-check-all" class="form-check-input">
                        </th>
                        <th>Etablissement</th>
                        <th>Nom / Raison sociale</th>
                        <th>Prénom</th>
                        <th>CIN / Passport</th>
                        <th>% Capital</th>
                        <th>N* de titres</th>
                        <th>Détails</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($actionnariats as $actionnaire)
                        <tr>
                            <td class="text-center">
                                <input
                                    type="checkbox"
                                    class="form-check-input actionnaire-chkbox"
                                    value="{{ $actionnaire->id }}"
                                >

                            </td>
                            <td>{{ $actionnaire->etablissement->name }}</td>

                            <td>{{ $actionnaire->nom_rs }}</td>

                            <td>{{ $actionnaire->prenom ?? '—' }}</td>

                            <td>{{ $actionnaire->identite ?? '—' }}</td>

                            <td>
                                {{ $actionnaire->pourcentage_capital !== null
                                    ? $actionnaire->pourcentage_capital.' %'
                                    : '—' }}
                            </td>

                            <td>{{ $actionnaire->nombre_titres ?? '—' }}</td>

                            <td>
                               
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Aucun actionnaire enregistré
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>


@endsection
