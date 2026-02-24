<div>
    <x-notification />

    <x-breadcrumb-header
        title="Liste des Coordonnées Bancaires"
        activePage="Coordonnées Bancaires"
    />

    {{-- Search & Actions --}}
    <div class="card card-body mb-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <input type="text" class="form-control"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Recherche par banque, ville ou RIB">
            </div>

            <div class="col-md-8 d-flex justify-content-end align-items-center gap-2">
                @if(count($selected) > 0)
                    <button wire:click="deleteSelection"
                        wire:confirm="Voulez-vous vraiment supprimer les coordonnées sélectionnées ?"
                        class="btn btn-light-danger text-danger d-flex align-items-center font-medium">
                        <i class="ti ti-trash me-1 fs-5"></i> Supprimer la sélection
                    </button>
                @endif

                <a href="{{ route('coordonneesbancaires.create') }}" class="btn btn-info d-flex align-items-center">
                    <i class="ti ti-credit-card text-white me-1 fs-5"></i> Ajouter
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
                            <input type="checkbox" class="form-check-input" wire:model.live="selectAll">
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
                        <tr wire:key="coord-{{ $coord->id }}">
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input"
                                       wire:model.live="selected" value="{{ $coord->id }}">
                            </td>
                            <td>{{ $coord->banque?->nom ?? '—' }}</td>
                            <td>{{ $coord->agences_banque ?? '—' }}</td>
                            <td>{{ $coord->ville?->libelle ?? '—' }}</td>
                            <td>{{ $coord->rib_banque ?? '—' }}</td>
                            <td></td>
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
