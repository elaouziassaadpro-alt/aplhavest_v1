<div>
    <x-notification />

    <div class="text-end">
        <button type="button" wire:click="toggleEdit" class="btn mb-1 {{ $editing ? 'btn-secondary' : 'btn-light-info' }} btn-circle btn-sm d-inline-flex align-items-center justify-content-center" title="{{ $editing ? 'Annuler' : 'Modifier cette section' }}">
            <i class="fs-5 ti {{ $editing ? 'ti-x' : 'ti-pencil' }}"></i>
        </button>
    </div>

    <form wire:submit.prevent="update">
        <div class="row mb-4 justify-content-center">
            <div class="col-md-4 mb-3">
                <label>Capital social</label>
                <input type="number" class="form-control" wire:model="capitalSocial" @disabled(!$editing)>
            </div>
            <div class="col-md-4 mb-3">
                <label>Origine des fonds</label>
                <input type="text" class="form-control" wire:model="origineFonds" @disabled(!$editing)>
            </div>
            <div class="col-md-4 mb-3">
                <label>Pays de résidence fiscale</label>
                <x-searchable-select :options="$pays" wireModel="paysOrigineFonds" :selected="$paysOrigineFonds" :disabled="!$editing" />
            </div>
        </div>

        <div class="row mb-4 justify-content-center">
            <div class="col-md-4 mb-3">
                <label>Chiffre d'affaires (exercice écoulé)</label><br>
                @foreach(['<5M' => '< 5 M.DH', '5-10M' => '5 M.DH < CA < 10 M.DH', '>10M' => '> 10 M.DH'] as $val => $label)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" wire:model="chiffreAffaires" value="{{ $val }}" @disabled(!$editing)>
                        <label class="form-check-label">{{ $label }}</label>
                    </div>
                @endforeach
            </div>

            <div class="col-md-4 mb-3">
                <label>Résultat net (exercice écoulé)</label>
                <input type="text" class="form-control" wire:model="resultatsNET" @disabled(!$editing)>
            </div>

            <div class="col-md-4 mb-3">
                <label>Appartient à un groupe <b>"holding"</b></label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" wire:model="holding" value="1" @disabled(!$editing)>
                    <label class="form-check-label">Oui</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" wire:model="holding" value="0" @disabled(!$editing)>
                    <label class="form-check-label">Non</label>
                </div>
            </div>
        </div>

        <div class="row mb-4 justify-content-center">
            <div class="col-md-4 mb-3">
                @if($editing)
                    <label for="etat_synthese_edit" class="btn btn-primary w-100">
                        <i class="ti ti-upload"></i> Etats de synthèse
                    </label>
                    <input type="file" wire:model="etat_synthese" id="etat_synthese_edit" class="d-none">
                @endif
                @if(!empty($existing_etat_synthese))
                    <a href="{{ asset('storage/' . $existing_etat_synthese) }}" class="btn btn-sm btn-outline-primary mt-2 w-100" download>
                        <i class="ti ti-download"></i> Télécharger
                    </a>
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
