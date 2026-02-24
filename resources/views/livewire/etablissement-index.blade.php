<div>
    {{-- Flash Messages --}}
    <x-notification />


    {{-- Header --}}
    <x-breadcrumb-header
        title="Etablissements"
        activePage="Etablissements"
    />

    {{-- Search & Actions --}}
    <div class="card card-body">
        <div class="row align-items-center">
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Recherche rapide..."
                       wire:model.live.debounce.300ms="search">
            </div>

            <div class="col-md-8 text-end d-flex justify-content-end gap-2">

                @if(count($selected) > 0)
                    <button wire:click="validateSelection"
                        class="btn btn-light-success text-success d-flex align-items-center">
                        <i class="ti ti-check me-1"></i> Valider la sélection
                    </button>

                    <button wire:click="rejectSelection"
                        class="btn btn-light-danger text-danger d-flex align-items-center">
                        <i class="ti ti-x me-1"></i> Rejeter la sélection
                    </button>

                    <button wire:click="deleteSelection"
                        wire:confirm="Voulez-vous vraiment supprimer les établissements sélectionnés ?"
                        class="btn btn-light-danger text-danger d-flex align-items-center">
                        <i class="ti ti-trash me-1"></i> Supprimer la sélection
                    </button>
                @endif

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
                            <input type="checkbox" class="form-check-input"
                                   wire:model.live="selectAll">
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
                @forelse ($etablissements as $etablissement)
                    <tr wire:key="etab-{{ $etablissement->id }}">
                        <td class="text-center">
                            <input type="checkbox" class="form-check-input"
                                   wire:model.live="selected"
                                   value="{{ $etablissement->id }}">
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

                        <td>
                            <span class="{{ $riskClass }}">{{ $etablissement->note }}</span>
                        </td>

                        <td>
                            @php
                                if (is_null($etablissement->validation)) {
                                    $validationLabel = 'en attente';
                                    $validationClass = 'text-warning';
                                } elseif ($etablissement->validation === 1) {
                                    $validationLabel = 'valide';
                                    $validationClass = 'text-success';
                                } elseif ($etablissement->validation === 0) {
                                    $validationLabel = 'rejeté';
                                    $validationClass = 'text-danger';
                                } else {
                                    $validationLabel = 'en attente';
                                    $validationClass = 'text-warning';
                                }
                            @endphp
                            <span class="{{ $validationClass }}">{{ $validationLabel }}</span>
                        </td>

                        <td>
                            <a href="{{ route('etablissements.show', $etablissement) }}">Voir/</a>
                            <a href="{{ route('Rating', ['etablissement_id' => $etablissement->id]) }}">Rating</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Aucun établissement trouvé.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
