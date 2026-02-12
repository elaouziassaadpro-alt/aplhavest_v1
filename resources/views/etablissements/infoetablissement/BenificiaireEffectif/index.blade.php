@extends('layouts.app')

@section('content')
<div class="container-fluid mw-100">

    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <h4 class="fw-semibold mb-8">Liste des Bénéficiaires Effectifs</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Bénéficiaires Effectifs</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Search & Actions -->
    <div class="card card-body mb-3">
        <div class="row align-items-center">
            <div class="col-md-4 col-xl-3">
                <form class="position-relative">
                    <input type="text" id="input-search-be" class="form-control ps-5" placeholder="Recherche par nom, prénom...">
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                </form>
            </div>

            <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0 gap-2">
                <div class="action-btn show-btn" id="bulk-action-be" style="display: none">
                    <a href="javascript:void(0)" id="delete-selection-be" class="btn btn-light-danger text-danger d-flex align-items-center font-medium">
                        <i class="ti ti-trash me-1 fs-5"></i> Supprimer la sélection
                    </a>
                </div>

                <a href="{{ route('benificiaireeffectif.create') }}" class="btn btn-info d-flex align-items-center">
                    <i class="ti ti-users text-white me-1 fs-5"></i> Ajouter un bénéficiaire
                </a>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table search-table-be align-middle text-nowrap">
                <thead>
                    <tr>
                        <th class="text-center">
                            <input type="checkbox" id="be-check-all" class="form-check-input">
                        </th>
                        <th>Nom / Raison sociale</th>
                        <th>Prénom</th>
                        <th>Pays de naissance</th>
                        <th>Date de naissance</th>
                        <th>Identité</th>
                        <th>% Capital</th>
                        <th>Nationalité</th>
                        <th>Risque</th>
                        <th>Détails</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($beneficiaires as $ben)
                        @php
                            $benNiveauRisque = $ben->checkRisk();

                            if ($benNiveauRisque['note'] < 33) {
                                $color = 'text-danger';       // rouge
                            } elseif ($benNiveauRisque['note'] < 50) {
                                $color = 'text-warning';      // jaune
                            } else {
                                $color = 'text-success';      // vert
                            }
                        @endphp
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input be-chkbox" value="{{ $ben->id }}">
                            </td>

                            <td>{{ $ben->nom_rs }}</td>
                            <td>{{ $ben->prenom ?? '—' }}</td>
                            <td>{{ $ben->paysNaissance?->libelle ?? '—' }}</td>
                            <td>{{ $ben->date_naissance ?? '—' }}</td>
                            <td>{{ $ben->identite ?? '—' }}</td>
                            <td>{{ $ben->pourcentage_capital ?? '—' }}%</td>
                            <td>{{ $ben->nationalite?->libelle ?? '—' }}</td>
                            <td class="{{ $color }}">
                                {{ $benNiveauRisque['note'] }}
                            </td>
                            <td>
                                <a href="/" class="btn btn-sm btn-primary d-flex align-items-center">
                                    <i class="ti ti-pencil me-1"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">
                                Aucun bénéficiaire enregistré
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>


<script>
    window.benificiaireRoutes = {
        bulkDelete: "{{ route('beneficiaire.bulkDelete') }}",
        csrfToken: "{{ csrf_token() }}"
    };
</script>
<script src="{{ asset('dist/js/pages/benificiaireeffectif.js') }}?v={{ time() }}"></script>
@endsection
