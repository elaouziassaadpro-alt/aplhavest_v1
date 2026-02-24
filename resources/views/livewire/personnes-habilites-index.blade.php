<div>
    <x-notification />

    <x-breadcrumb-header
        title="Liste des Personnes Habilitées"
        activePage="Personnes Habilitées"
    />

    {{-- Search & Actions --}}
    <div class="card card-body mb-3">
        <div class="row align-items-center">
            <div class="col-md-4 col-xl-3">
                <input type="text" class="form-control product-search"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Recherche par nom, prénom ou fonction...">
            </div>

            <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0 gap-2">
                @if(count($selected) > 0)
                    <button wire:click="deleteSelection"
                        wire:confirm="Voulez-vous vraiment supprimer les personnes sélectionnées ?"
                        class="btn btn-light-danger text-danger d-flex align-items-center font-medium">
                        <i class="ti ti-trash me-1 fs-5"></i> Supprimer la sélection
                    </button>
                @endif

                <a href="{{ route('personneshabilites.create') }}" class="btn btn-info d-flex align-items-center">
                    <i class="ti ti-user-plus text-white me-1 fs-5"></i> Ajouter une personne
                </a>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table search-table align-middle text-nowrap">
                <thead>
                    <tr>
                        <th class="text-center">
                            <input type="checkbox" class="form-check-input" wire:model.live="selectAll">
                        </th>
                        <th>Nom / Raison sociale</th>
                        <th>Prénom</th>
                        <th>Identité</th>
                        <th>Nationalité</th>
                        <th>Fonction</th>
                        <th>PPE</th>
                        <th>Lien PPE</th>   
                        <th>Note</th>
                        <th>Risque</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($personnesHabilites as $personne)
                    @php
                            $personneNiveauRisque = $personne->note;
                            if ($personneNiveauRisque >= 30) {
                                $color = 'text-warning';
                                $risk = 'High Risk';
                            } elseif ($personneNiveauRisque >=7)     {
                                $color = 'text-primary';
                                $risk = 'Medium Risk';
                            } else {
                                $color = 'text-success';
                                $risk = 'Low Risk';
                            }
                        @endphp
                        <tr wire:key="ph-{{ $personne->id }}">
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input"
                                       wire:model.live="selected" value="{{ $personne->id }}">
                            </td>
                            <td>{{ $personne->nom_rs }}</td>
                            <td>{{ $personne->prenom ?? '—' }}</td>
                            <td>{{ $personne->identite ?? '—' }}</td>
                            
                            <td>
                                @if($personne->nationalite?->iso)
                                    <img src="{{ asset('dist/css/icons/flag-icon-css/flags/' . strtolower($personne->nationalite->iso) . '.svg') }}" alt="{{ $personne->nationalite->libelle ?? '' }}" width="20">
                                @endif
                                {{ $personne->nationalite?->libelle ?? '—' }}
                            </td>
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
                            <td class="{{ $color }}">{{ $personneNiveauRisque }}</td>
                            <td class="{{ $color }}">{{ $risk }}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">Aucune personne habilitée enregistrée</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
