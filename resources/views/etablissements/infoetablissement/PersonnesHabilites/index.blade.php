@extends('layouts.app')

@section('content')
<div class="container-fluid mw-100">

    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Liste des Personnes Habilitées</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Personnes Habilitées</li>
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
                <input type="text" class="form-control product-search" id="input-search" placeholder="Recherche par nom, prénom ou fonction...">
            </div>

            <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0 gap-2">
                <div class="action-btn" id="bulk-action" style="display:none">
                    <a href="javascript:void(0)" id="delete-selection" class="btn btn-light-danger text-danger d-flex align-items-center font-medium">
                        <i class="ti ti-trash me-1 fs-5"></i> Supprimer la sélection
                    </a>
                </div>

                <a href="{{ route('personneshabilites.create') }}" class="btn btn-info d-flex align-items-center">
                    <i class="ti ti-user-plus text-white me-1 fs-5"></i> Ajouter une personne
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
                            <input type="checkbox" id="check-all" class="form-check-input">
                        </th>
                        <th>Nom / Raison sociale</th>
                        <th>Prénom</th>
                        <th>Identité</th>
                        <th>Fonction</th>
                        <th>PPE</th>
                        <th>Lien PPE</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($personnesHabilites as $personne)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input personne-chkbox" value="{{ $personne->id }}">
                            </td>
                            <td>{{ $personne->nom_rs }}</td>
                            <td>{{ $personne->prenom ?? '—' }}</td>
                            <td>{{ $personne->identite ?? '—' }}</td>
                            <td>{{ $personne->fonction ?? '—' }}</td>
                            <td>
                                @php
                                    $ppe = $personne->ppeRelation?->libelle ?? '—';
                                    $words = explode(' ', $ppe);
                                    $ppeDisplay = implode(' ', array_slice($words, 0, 3)) . (count($words) > 3 ? '...' : '');
                                @endphp
                                <span title="{{ $ppe }}">{{ $ppeDisplay }}</span>
                            </td>

                            <td>
                                @php
                                    $lienPpe = $personne->lienPpeRelation?->libelle ?? '—';
                                    $wordsLien = explode(' ', $lienPpe);
                                    $lienDisplay = implode(' ', array_slice($wordsLien, 0, 3)) . (count($wordsLien) > 3 ? '...' : '');
                                @endphp
                                <span title="{{ $lienPpe }}">{{ $lienDisplay }}</span>
                            </td>


                            <td>
                                <a href="/" class="btn btn-sm btn-primary">
                                    Modifier
                                </a>
                                <a href="javascript:void(0)" data-id="{{ $personne->id }}" class="btn btn-sm btn-danger delete-single">
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Aucune personne habilitée enregistrée</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>



@push('scripts')
<script>
    window.personnesRoutes = {
        bulkDelete: "{{ route('personneshabilites.bulkDelete') }}",
        destroy: "{{ url('personnes-habilites') }}", // used as base for /personnes-habilites/{id}
        csrfToken: "{{ csrf_token() }}"
    };
</script>
<script src="{{ asset('dist/js/pages/personnehabilite.js') }}?v={{ time() }}"></script>
@endpush
@endsection
