<div>
    <x-notification />

    <div class="text-end">
        <button type="button" wire:click="toggleEdit" class="btn mb-1 {{ $editing ? 'btn-secondary' : 'btn-light-info' }} btn-circle btn-sm d-inline-flex align-items-center justify-content-center" title="{{ $editing ? 'Annuler' : 'Modifier cette section' }}">
            <i class="fs-5 ti {{ $editing ? 'ti-x' : 'ti-pencil' }}"></i>
        </button>
    </div>

    @if($etablissement->typologieClient || $editing)
        <form wire:submit.prevent="update">
            <div class="row">
                <!-- Secteur -->
                <div class="col-md-2 mb-3">
                    <label>Secteur d'activité</label>
                    <x-searchable-select :options="$secteurs" wireModel="secteurActivite" :selected="$secteurActivite" :disabled="!$editing" />
                </div>

                <!-- Segment -->
                <div class="col-md-2 mb-3">
                    <label>Segment</label>
                    <x-searchable-select :options="$segments_list" wireModel="segment" :selected="$segment" :disabled="!$editing" />
                </div>

                <!-- Activité à l'étranger -->
                <div class="col-md-2 mb-3">
                    <label>Activité à l'étranger</label>
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" wire:model.live="activiteEtranger" @disabled(!$editing)>
                        <label class="form-check-label">{{ $activiteEtranger ? 'Oui' : 'Non' }}</label>
                    </div>
                </div>

                <!-- Pays étranger -->
                @if($activiteEtranger)
                <div class="col-md-2 mb-3">
                    <label>Pays</label>
                    <x-searchable-select :options="$pays" wireModel="paysEtranger" :selected="$paysEtranger" :disabled="!$editing" />
                </div>
                @endif

                <!-- Marché financier -->
                <div class="col-md-2 mb-3">
                    <label>Marché financier</label>
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" wire:model.live="publicEpargne" @disabled(!$editing)>
                        <label class="form-check-label">{{ $publicEpargne ? 'Oui' : 'Non' }}</label>
                    </div>
                </div>

                <!-- Précisez -->
                @if($publicEpargne)
                <div class="col-md-2 mb-3">
                    <label>Précisez</label>
                    <input type="text" wire:model="publicEpargne_label" class="form-control" @disabled(!$editing)>
                </div>
                @endif
            </div>

            @if($editing)
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
    @else
        <h5 class="text-center mt-4">Aucune typologie client</h5>
    @endif
</div>
