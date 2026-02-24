<div>
    <x-notification />

    @if(count($comptes) > 0)
    <div class="text-end">
        <button type="button" wire:click="toggleEdit" class="btn mb-1 {{ $editing ? 'btn-secondary' : 'btn-light-info' }} btn-circle btn-sm d-inline-flex align-items-center justify-content-center" title="{{ $editing ? 'Annuler' : 'Modifier cette section' }}">
            <i class="fs-5 ti {{ $editing ? 'ti-x' : 'ti-pencil' }}"></i>
        </button>
    </div>
    @endif

    <form wire:submit.prevent="update">
        @forelse($comptes as $index => $compte)
            <div class="row mb-2 align-items-center">
                <div class="col-md-3 mb-3">
                    <label>Banque</label>
                    <x-searchable-select :options="$banques" wireModel="comptes.{{ $index }}.banque_id" :selected="$compte['banque_id'] ?? ''" :disabled="!$editing" labelField="nom" />
                </div>
                <div class="col-md-3 mb-3">
                    <label>Agence</label>
                    <input type="text" class="form-control" wire:model="comptes.{{ $index }}.agences_banque" placeholder="Agence" @disabled(!$editing)>
                </div>
                <div class="col-md-2 mb-3">
                    <label>Ville</label>
                    <x-searchable-select :options="$villes" wireModel="comptes.{{ $index }}.ville_id" :selected="$compte['ville_id'] ?? ''" :disabled="!$editing" />
                </div>
                <div class="col-md-3 mb-3">
                    <label>RIB</label>
                    <input type="text" class="form-control" wire:model="comptes.{{ $index }}.rib_banque" placeholder="RIB" @disabled(!$editing)>
                </div>
                @if($editing)
                <div class="col-md-1 text-center">
                    <button type="button" class="btn btn-danger btn-sm" wire:click="removeCompte({{ $index }})">Supprimer</button>
                </div>
                @endif
            </div>
        @empty
            <h5 class="text-center">Aucune coordonnées bancaires</h5>
        @endforelse

        <div class="text-start">
            <a href="{{ route('coordonneesbancaires.create', ['etablissement_id' => $etablissement_id]) }}" class="btn btn-light-info btn-sm mb-2">
                Ajouter un compte bancaire
            </a>
        </div>

        @if($editing && count($comptes) > 0)
        <div class="text-center">
            <button type="submit"
                    class="btn btn-success d-flex align-items-center justify-content-center mx-auto"
                    wire:loading.attr="disabled">
                <span wire:loading.remove>
                    <svg class="w-6 h-6 me-2" xmlns="http://www.w3.org/2000/svg"
                        width="20" height="20" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2"
                            d="M17.651 7.65a7.131 7.131 0 0 0-12.68 3.15M18.001 4v4h-4m-7.652 8.35a7.13 7.13 0 0 0 12.68-3.15M6 20v-4h4"/>
                    </svg>
                    Mise à jour
                </span>
                <span wire:loading>
                    Mise à jour...
                </span>
            </button>
        </div>
        @endif
    </form>
</div>
