<div>
    <x-notification />

    <div class="text-end">
        <button type="button" wire:click="toggleEdit" class="btn mb-1 {{ $editing ? 'btn-secondary' : 'btn-light-info' }} btn-circle btn-sm d-inline-flex align-items-center justify-content-center" title="{{ $editing ? 'Annuler' : 'Modifier cette section' }}">
            <i class="fs-5 ti {{ $editing ? 'ti-x' : 'ti-pencil' }}"></i>
        </button>
    </div>

    <form wire:submit.prevent="update">
        <div class="row mt-4 justify-content-center">
            <!-- US Entity -->
            <div class="col-md-6 mb-4">
                <h5 class="mb-3 text-center">Votre établissement est-il considéré comme <b>"US Entity"</b> ?</h5>
                <div class="d-flex align-items-center gap-3 justify-content-center">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" wire:model.live="usEntity" @disabled(!$editing)>
                        <label class="form-check-label">{{ $usEntity ? 'Oui' : 'Non' }}</label>
                    </div>
                    <div class="flex-grow-1">
                        @if($usEntity && $editing)
                            <input type="file" wire:model="fichier_usEntity" class="form-control">
                        @endif
                        @if(!empty($existing_fichier_usEntity))
                            <a href="{{ asset('storage/' . $existing_fichier_usEntity) }}" class="btn btn-sm btn-outline-primary mt-2 w-100" download>
                                <i class="ti ti-download"></i> Télécharger
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- GIIN -->
            <div class="col-md-6 mb-4">
                <h5 class="mb-3 text-center">Votre établissement est une <b>"Participating Financial Institution"</b> ?</h5>
                <div class="d-flex align-items-center gap-3 justify-content-center">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" wire:model.live="giin" @disabled(!$editing)>
                        <label class="form-check-label">{{ $giin ? 'Oui' : 'Non' }}</label>
                    </div>
                    @if($giin)
                    <div class="flex-grow-1">
                        <label>GIIN :</label>
                        <input type="text" class="form-control" wire:model="giin_label" @disabled(!$editing)>
                    </div>
                    @endif
                    <div class="flex-grow-1">
                        <label>Autres :</label>
                        <input type="text" class="form-control" wire:model="giin_label_Autres" @disabled(!$editing)>
                    </div>
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
        </div>
    </form>
</div>
