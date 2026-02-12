@extends('layouts.app')

@section('content')
<script src="{{ asset('dist/js/pages/etablissement.js') }}"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    window.routes = {
        updateValidation: "{{ route('etablissement.update.validation') }}",
        deleteMultiple: "{{ route('etablissements.destroy-multiple') }}"
    };
</script>




<script src="{{ asset('js/etablissement.js') }}"></script>




<div class="container-fluid mw-100">

    {{-- Header --}}
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-2">Ecom-Shop</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="#">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Shop</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Buttons --}}
    <div class="card card-body">
        <div class="row align-items-center">
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Recherche rapide...">
            </div>

            <div class="col-md-8 text-end d-flex justify-content-end gap-2">

                <div class="action-btn show-btn" style="display:none">
                    <button id="validate-selection"
                        class="btn btn-light-success text-success d-flex align-items-center">
                        <i class="ti ti-check me-1"></i> Valider la sélection
                    </button>
                </div>

                <div class="action-btn show-btn" style="display:none">
                    <button id="reject-selection"
                        class="btn btn-light-danger text-danger d-flex align-items-center">
                        <i class="ti ti-x me-1"></i> Rejeter la sélection
                    </button>
                </div>

                <div class="action-btn show-btn" style="display:none">
                    <button id="delete-selection"
                        class="btn btn-light-danger text-danger d-flex align-items-center">
                        <i class="ti ti-trash me-1"></i> Supprimer la sélection
                    </button>
                </div>


                <a href="{{ route('infogeneral.create') }}" class="btn btn-info">
                    <i class="ti ti-users me-1"></i> Ajouter un établissement
                </a>

            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table align-middle text-nowrap">
                <thead>
                    <tr>
                        <th class="text-center">
                            <input type="checkbox" id="contact-check-all" class="form-check-input">
                        </th>
                        <th>Dénomination</th>
                        <th>Pays</th>
                        <th>Secteur</th>
                        <th>Risque</th>
                        <th>Note</th>
                        <th>État</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>

                @foreach($etablissements as $etablissement)
                    <tr>
                        <td class="text-center">
                            <input type="checkbox"  
                                class="form-check-input contact-chkbox"
                                data-etablissement-id="{{ $etablissement->id }}">
                        </td>

                        <td>{{ $etablissement->name }}</td>

                        <td>
                            {{ $etablissement->infoGenerales->paysresidence->libelle ?? '—' }}
                        </td>

                        <td>
                            {{ $etablissement->typologieClient?->secteur?->libelle ?? '—' }}
                        </td>

                        <td>
                            @php
                                $riskClass = match($etablissement->risque) {
                                    'LR' => 'text-success',
                                    'MR' => 'text-warning',
                                    'HR' => 'text-danger',
                                    default => 'text-secondary'
                                };
                            @endphp
                            <span class="{{ $riskClass }}">{{ $etablissement->risque }}</span>
                        </td>
                            
                        <td >
                            
                        <span class="{{$riskClass}}">{{ $etablissement->note }}</span>
                    </td>

                        <td>
                        @php
                            if (is_null($etablissement->validation)) {
                                $validationLabel = 'en attente';
                                $validationClass = 'text-warning';
                            } elseif ($etablissement->validation === 'valide') {
                                $validationLabel = 'valide';
                                $validationClass = 'text-success';
                            } elseif ($etablissement->validation === 'rejete') {
                                $validationLabel = 'rejeté';
                                $validationClass = 'text-danger';
                            } else {
                                $validationLabel = 'en attente';
                                $validationClass = 'text-warning';
                            }
                        @endphp

                        <span class="{{ $validationClass }}">
                            {{ $validationLabel }}
                        </span>
                    </td>



                        <td>
                            <a href="{{ route('etablissements.show', $etablissement) }}">Voir/</a>
                            <a href="{{ route('Rating', ['etablissement_id' => $etablissement->id]) }}">Rating</a>

                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
