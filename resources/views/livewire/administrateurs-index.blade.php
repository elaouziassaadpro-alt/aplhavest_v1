<div>
    <x-notification />

    <x-breadcrumb-header
        title="Liste des Administrateurs"
        activePage="Administrateurs"
    />

    {{-- Search & Actions --}}
    <div class="card card-body mb-3">
        <div class="row align-items-center">
            <div class="col-md-4 col-xl-3">
                <input type="text" class="form-control"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Recherche par nom ou prénom...">
            </div>

            <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0 gap-2">
                @if(count($selected) > 0)
                    <button wire:click="deleteSelection"
                        wire:confirm="Voulez-vous vraiment supprimer les administrateurs sélectionnés ?"
                        class="btn btn-light-danger text-danger d-flex align-items-center font-medium">
                        <i class="ti ti-trash me-1 fs-5"></i> Supprimer la sélection
                    </button>
                @endif

                <a href="{{ route('administrateurs.create') }}" class="btn btn-info d-flex align-items-center">
                    <i class="ti ti-user-plus text-white me-1 fs-5"></i> Ajouter un administrateur
                </a>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table align-middle text-nowrap search-table">
                <thead>
                    <tr>
                        <th class="text-center">
                            <input type="checkbox" class="form-check-input" wire:model.live="selectAll">
                        </th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Pays</th>
                        <th>Nationalité</th>
                        <th>Fonction</th>
                        <th>PPE / Lien PPE</th>
                        <th>Note</th>
                        <th>Risque</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($administrateurs as $admin)
                    @php
                            $adminNiveauRisque = $admin->note;
                            if ($adminNiveauRisque >= 300 ) {
                                $color = 'text-danger';
                                $risk = 'Interdit';
                            } elseif ($adminNiveauRisque >= 30) {
                                $color = 'text-warning';
                                $risk = 'High Risk';
                            } elseif ($adminNiveauRisque >=7)     {
                                $color = 'text-primary';
                                $risk = 'Medium Risk';
                            } else {
                                $color = 'text-success';
                                $risk = 'Low Risk';
                            }
                        @endphp
                        <tr wire:key="admin-{{ $admin->id }}">
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input"
                                       wire:model.live="selected" value="{{ $admin->id }}">
                            </td>
                            <td>{{ $admin->nom }}</td>
                            <td>{{ $admin->prenom ?? '—' }}</td>
                            <td>
                                @if($admin->pays?->iso)
                                    <img src="{{ asset('dist/css/icons/flag-icon-css/flags/' . strtolower($admin->pays->iso) . '.svg') }}" alt="{{ $admin->pays->libelle ?? '' }}" width="20">
                                @endif
                                {{ $admin->pays?->libelle ?? '—' }}
                            </td>
                            <td>
                                @if($admin->nationalite?->iso)
                                    <img src="{{ asset('dist/css/icons/flag-icon-css/flags/' . strtolower($admin->nationalite->iso) . '.svg') }}" alt="{{ $admin->nationalite->libelle ?? '' }}" width="20">
                                @endif
                                {{ $admin->nationalite?->libelle ?? '—' }}
                            </td>
                            <td>{{ $admin->fonction ?? '—' }}</td>
                            <td>{{ $admin->getPpeLibelle() ?? '—' }}</td>
                            <td class="{{ $color }}">{{ $adminNiveauRisque ?? '—' }}</td>
                            <td class="{{ $color }}">{{ $risk }}</td>
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
