@extends('layouts.app')

@section('content')
<div class="container-fluid mw-100">
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Liste des Administrateurs</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Administrateurs</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Actions -->
    <div class="card card-body mb-3">
        <div class="row align-items-center">
            <div class="col-md-4 col-xl-3">
                <form class="position-relative">
                    <input type="text" class="form-control" id="input-search" placeholder="Recherche par nom ou prénom...">
                    
                </form>
            </div>

            <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0 gap-2">
                <div class="action-btn show-btn" id="bulk-action" style="display: none">
                    <a href="javascript:void(0)" id="delete-selection" class="btn btn-light-danger text-danger d-flex align-items-center font-medium">
                        <i class="ti ti-trash me-1 fs-5"></i> Supprimer la sélection
                    </a>
                </div>

                <a href="{{ route('administrateurs.create') }}" class="btn btn-info d-flex align-items-center">
                    <i class="ti ti-user-plus text-white me-1 fs-5"></i> Ajouter un administrateur
                </a>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table align-middle text-nowrap search-table">
                <thead>
                    <tr>
                        <th class="text-center">
                            <input type="checkbox" id="admin-check-all" class="form-check-input">
                        </th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Pays</th>
                        <th>Nationalité</th>
                        <th>Fonction</th>
                        <th>PPE / Lien PPE</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($administrateurs as $admin)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input admin-chkbox" value="{{ $admin->id }}">
                            </td>
                            <td>{{ $admin->nom }}</td>
                            <td>{{ $admin->prenom ?? '—' }}</td>
                            <td>{{ $admin->pays?->libelle ?? '—' }}</td>
                            <td>{{ $admin->nationalite?->libelle ?? '—' }}</td>
                            <td>{{ $admin->fonction ?? '—' }}</td>
                            <td>{{ $admin->getPpeLibelle() ?? '—' }}</td>
                            <td>
                                <a href="/" class="btn btn-sm btn-primary">
                                    <i class="ti ti-pencil me-1"></i> Modifier
                                </a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-danger delete-single" data-id="{{ $admin->id }}">
                                    <i class="ti ti-trash me-1"></i> Supprimer
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Aucun administrateur enregistré</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JS: Search, check all, bulk delete -->


@push('scripts')
<script>
    window.APP_DATA = {
        pays: @json($pays ?? []), // Ensure these variables are passed from controller if needed, or handle empty
        ppes: @json($ppes ?? [])
    };
    window.administrateurRoutes = {
        bulkDelete: "{{ route('administrateurs.bulkDelete') }}",
        csrfToken: "{{ csrf_token() }}"
    };
</script>
<script src="{{ asset('dist/js/pages/Administrateur.js') }}?v={{ time() }}"></script>
@endpush
@endsection
