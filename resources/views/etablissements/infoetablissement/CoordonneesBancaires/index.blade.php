@extends('layouts.app')

@section('content')
<div class="container-fluid mw-100">

    <!-- Header -->
    <div class="card bg-light-info shadow-none position-relative overflow-hidden mb-3">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-0">Liste des Coordonnées Bancaires</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('dashboard') }}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Coordonnées Bancaires
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Bulk Actions -->
    <div class="card card-body mb-3">
        <div class="row align-items-center">
            
            <!-- Search -->
            <div class="col-md-4">
                <input
                    type="text"
                    class="form-control"
                    id="input-search"
                    placeholder="Recherche par banque, ville ou RIB"
                >
            </div>

            <!-- Actions -->
            <div class="col-md-8 d-flex justify-content-end align-items-center gap-2">

                <!-- Bulk delete -->
                <div id="bulk-action" style="display: none;">
                    <a href="javascript:void(0)"
                    id="delete-selection"
                    class="btn btn-light-danger text-danger d-flex align-items-center font-medium">
                        <i class="ti ti-trash me-1 fs-5"></i>
                        Supprimer la sélection
                    </a>
                </div>

                <!-- Add button -->
                <a href="{{ route('coordonneesbancaires.create') }}"
                class="btn btn-info d-flex align-items-center">
                    <i class="ti ti-credit-card text-white me-1 fs-5"></i>
                    Ajouter
                </a>

            </div>
        </div>
    </div>


    <!-- Table -->
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table align-middle text-nowrap">
                <thead>
                    <tr>
                        <th class="text-center">
                            <input type="checkbox" id="coordonnees-check-all" class="form-check-input">
                        </th>
                        <th>Banque</th>
                        <th>Agence</th>
                        <th>Ville</th>
                        <th>RIB</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coordonneesbancaires as $coord)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input coordonnees-chkbox" value="{{ $coord->id }}">
                            </td>
                            <td>{{ $coord->banque?->nom ?? '—' }}</td>
                            <td>{{ $coord->agences_banque ?? '—' }}</td>
                            <td>{{ $coord->ville?->libelle ?? '—' }}</td>
                            <td>{{ $coord->rib_banque ?? '—' }}</td>
                            <td>
                                <a href="" class="btn btn-sm btn-secondary">
                                    <i class="ti ti-pencil"></i> Modifier
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Aucune coordonnée bancaire enregistrée</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.coordonneesRoutes = {
        bulkDelete: "{{ route('coordonneesbancaires.bulkDelete') }}",
        csrfToken: "{{ csrf_token() }}"
    };
</script>
<script src="{{ asset('dist/js/pages/coordonneesBoncaire.js') }}?v={{ time() }}"></script>
@endpush

@endsection
