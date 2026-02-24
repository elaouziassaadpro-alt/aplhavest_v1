<div>
    <x-notification />

    <x-breadcrumb-header
        title="Contacts"
        activePage="Contacts"
    />

    {{-- Search & Actions --}}
    <div class="card card-body boutons_header">
        <div class="row align-items-center">
            <div class="col-md-4 col-xl-3">
                <form class="position-relative">
                    <input type="text" class="form-control product-search ps-5"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Recherche rapide...">
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                </form>
            </div>

            <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0 gap-2">
                @if(count($selected) > 0)
                    <button wire:click="deleteSelection"
                        wire:confirm="Voulez-vous vraiment supprimer les contacts sélectionnés ?"
                        class="btn btn-light-danger text-danger d-flex align-items-center font-medium">
                        <i class="ti ti-trash me-1 fs-5"></i> Supprimer la sélection
                    </button>
                @endif

                <a href="/" class="btn btn-info d-flex align-items-center">
                    <i class="ti ti-users text-white me-1 fs-5"></i> Ajouter un contact
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
                        <th>Raison sociale</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Fonction</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                        <tr wire:key="contact-{{ $contact->id }}">
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input"
                                       wire:model.live="selected" value="{{ $contact->id }}">
                            </td>
                            <td>{{ $contact->infoGeneral->raisonSocial }}</td>
                            <td>{{ $contact->nom }}</td>
                            <td>{{ $contact->prenom }}</td>
                            <td>{{ $contact->fonction ?? '—' }}</td>
                            <td>{{ $contact->telephone ?? '—' }}</td>
                            <td>{{ $contact->email ?? '—' }}</td>
                            <td></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Aucun contact enregistré</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
