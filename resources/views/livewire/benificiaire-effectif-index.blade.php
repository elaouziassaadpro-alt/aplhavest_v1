<div>
    <x-notification />

    <x-breadcrumb-header
        title="Liste des Bénéficiaires Effectifs"
        activePage="Bénéficiaires Effectifs"
    />

    {{-- Search & Actions --}}
    <div class="card card-body mb-3">
        <div class="row align-items-center">
            <div class="col-md-4 col-xl-3">
                <form class="position-relative">
                    <input type="text" class="form-control ps-5"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Recherche par nom, prénom...">
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                </form>
            </div>

            <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0 gap-2">
                @if(count($selected) > 0)
                    <button wire:click="deleteSelection"
                        wire:confirm="Voulez-vous vraiment supprimer les bénéficiaires sélectionnés ?"
                        class="btn btn-light-danger text-danger d-flex align-items-center font-medium">
                        <i class="ti ti-trash me-1 fs-5"></i> Supprimer la sélection
                    </button>
                @endif

                <a href="{{ route('benificiaireeffectif.create') }}" class="btn btn-info d-flex align-items-center">
                    <i class="ti ti-users text-white me-1 fs-5"></i> Ajouter un bénéficiaire
                </a>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table search-table-be align-middle text-nowrap">
                <thead>
                    <tr>
                        <th class="text-center">
                            <input type="checkbox" class="form-check-input" wire:model.live="selectAll">
                        </th>
                        <th>Nom / Raison sociale</th>
                        <th>Prénom</th>
                        <th>Pays de naissance</th>
                        <th>Date de naissance</th>
                        <th>Identité</th>
                        <th>% Capital</th>
                        <th>Nationalité</th>
                        <th>Risque</th>
                        <th>Risque</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beneficiaires as $ben)
                        @php
                            $benNiveauRisque = $ben->note;
                            if ($benNiveauRisque >= 300 ) {
                                $color = 'text-danger';
                                $risk = 'Interdit';
                            } elseif ($benNiveauRisque >= 30) {
                                $color = 'text-warning';
                                $risk = 'High Risk';
                            } elseif ($benNiveauRisque >=7)     {
                                $color = 'text-primary';
                                $risk = 'Medium Risk';
                            } else {
                                $color = 'text-success';
                                $risk = 'Low Risk';
                            }
                        @endphp
                        <tr wire:key="ben-{{ $ben->id }}">
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input"
                                       wire:model.live="selected" value="{{ $ben->id }}">
                            </td>
                            <td>{{ $ben->nom_rs }}</td>
                            <td>{{ $ben->prenom ?? '—' }}</td>
                            <td>
                                @if($ben->paysNaissance?->iso)
                                    <img src="{{ asset('dist/css/icons/flag-icon-css/flags/' . strtolower($ben->paysNaissance->iso) . '.svg') }}" alt="{{ $ben->paysNaissance->libelle ?? '' }}" width="20">
                                @endif
                                {{ $ben->paysNaissance?->libelle ?? '—' }}
                            </td>
                            <td>{{ $ben->date_naissance ?? '—' }}</td>
                            <td>{{ (!$ben->identite || $ben->identite == '' )? '—' : $ben->identite }}</td>
                            <td>{{ $ben->pourcentage_capital ?? '—' }}%</td>
                            <td>
                                @if($ben->nationalite?->iso)
                                    <img src="{{ asset('dist/css/icons/flag-icon-css/flags/' . strtolower($ben->nationalite->iso) . '.svg') }}" alt="{{ $ben->nationalite->libelle ?? '' }}" width="20">
                                @endif
                                {{ $ben->nationalite?->libelle ?? '—' }}
                            </td>
                            <td class="{{ $color }}">{{ $benNiveauRisque }}</td>
                            <td class="{{ $color }}">{{ $risk }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">Aucun bénéficiaire enregistré</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
