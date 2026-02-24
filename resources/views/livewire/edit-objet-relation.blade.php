<div>
    <x-notification />

    <div class="text-end">
        <button type="button" wire:click="toggleEdit" class="btn mb-1 {{ $editing ? 'btn-secondary' : 'btn-light-info' }} btn-circle btn-sm d-inline-flex align-items-center justify-content-center" title="{{ $editing ? 'Annuler' : 'Modifier cette section' }}">
            <i class="fs-5 ti {{ $editing ? 'ti-x' : 'ti-pencil' }}"></i>
        </button>
    </div>

    <form wire:submit.prevent="update">
        <div class="row mb-4">
            <!-- Fréquence des opérations -->
            <div class="col-md-7">
                <h5>Fréquence des opérations :</h5>
                @foreach (['Quotidienne','Hebdomadaire','Mensuelle','Trimestrielle','Annuelle','Ponctuelle'] as $freq)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" wire:model="relation_affaire" value="{{ $freq }}" @disabled(!$editing)>
                        <label class="form-check-label">{{ $freq }}</label>
                    </div>
                @endforeach
            </div>

            <!-- Horizon de placement -->
            <div class="col-md-5">
                <h5>Horizon de placement :</h5>
                @foreach (['< 1 an','Entre 1 et 3 ans','Entre 3 et 5 ans','< 5 ans'] as $horizon)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" wire:model="horizon_placement" value="{{ $horizon }}" @disabled(!$editing)>
                        <label class="form-check-label">{{ $horizon }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="row mb-4">
            <!-- Objet de la relation -->
            <div class="col-md-7">
                <h5>Objet de la relation d'affaire :</h5>
                @foreach (['Assurer des revenus réccurents','Profits à moyen et court terme','Gestion de la trésorerie'] as $objet)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" wire:model="objet_relation" value="{{ $objet }}" @disabled(!$editing)>
                        <label class="form-check-label">{{ $objet }}</label>
                    </div>
                @endforeach
            </div>

            <!-- Mandataire -->
            <div class="col-md-5">
                <h5>Compte géré par mandataire :</h5>
                <div class="row mb-3 align-items-center">
                    <div class="col-2 form-check form-switch">
                        <input class="form-check-input" type="checkbox" wire:model.live="mandataire_check" @disabled(!$editing)>
                        <label class="form-check-label">{{ $mandataire_check ? 'Oui' : 'Non' }}</label>
                    </div>

                    @if($mandataire_check)
                    <div class="col-4">
                        <input type="text" class="form-control" wire:model="mandataire_input" placeholder="Description" @disabled(!$editing)>
                    </div>
                    <div class="col-2">
                        <label>Date fin de mandat</label>
                    </div>
                    <div class="col-4">
                        <input type="date" class="form-control" wire:model="mandataire_fin_mandat_date" @disabled(!$editing)>
                    </div>
                    @endif
                </div>

                @if($mandataire_check)
                <div class="row" style="margin-top:-10px;">
                    <div class="col-4">
                        @if($editing)
                            <label for="mandat_file_edit" class="btn btn-primary w-100">
                                <i class="ti ti-upload"></i> Mandat pouvoir
                            </label>
                            <input type="file" id="mandat_file_edit" wire:model="mandat_file" hidden>
                        @endif
                        @if(!empty($existing_mandat_file))
                            <a href="{{ asset('storage/' . $existing_mandat_file) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2" download>
                                <i class="ti ti-download"></i> Télécharger
                            </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
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
</div>
