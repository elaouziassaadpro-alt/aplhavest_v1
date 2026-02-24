<div>
    <x-breadcrumb-header 
        :etablissementName="$etablissement->name" 
        activePage="Statut FATCA" 
    />

    <div class="max-w-7xl mx-auto bg-white cardetablissement shadow-sm border border-gray-100 mt-4">
        <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl">
            <h2 class="text-xl font-semibold text-gray-800">Statut FATCA</h2>
        </div>

        <div class="card-body px-6 py-4">

            <!-- Errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row mt-4">

                <!-- US Entity -->
                <div class="col-md-6 mb-4">
                    <h5 class="font-medium text-gray-700 mb-3">
                        Votre établissement est-il considéré comme <b>"US Entity"</b> ?
                    </h5>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" wire:model.live="usEntity" id="us_entity_check">
                        <label class="form-check-label" for="us_entity_check">
                            {{ $usEntity ? 'Oui' : 'Non' }}
                        </label>
                    </div>

                    @if($usEntity)
                        <div class="mt-3">
                            <label class="btn btn-primary mb-0">
                                <i class="ti ti-upload me-1"></i> Fichier FATCA
                                <input type="file" wire:model="fichier_usEntity" hidden accept=".pdf,.jpg,.jpeg,.png">
                            </label>
                            @if($fichier_usEntity)
                                <span class="text-success ms-2"><i class="ti ti-check"></i> Fichier sélectionné</span>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Participating Financial Institution -->
                <div class="col-md-6 mb-4">
                    <h5 class="font-medium text-gray-700 mb-3">
                        Votre établissement est une <b>"Participating Financial Institution"</b> ?
                    </h5>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" wire:model.live="giin" id="giin_check">
                        <label class="form-check-label" for="giin_check">
                            {{ $giin ? 'Oui' : 'Non' }}
                        </label>
                    </div>

                    @if($giin)
                        <div class="mt-3">
                            <label class="form-label">GIIN:</label>
                            <input type="text" class="form-control" wire:model="giin_label"
                                   placeholder="Global Intermediary Identification Number">
                        </div>
                    @else
                        <div class="mt-3">
                            <label class="form-label">Autres:</label>
                            <input type="text" class="form-control" wire:model="giin_label_Autres"
                                   placeholder="Précisions">
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>

    <!-- Buttons -->
    <div class="row mt-4 align-items-center">
        <div class="col text-center">
            <button type="button" wire:click="save"
                    class="btn btn-save d-flex align-items-center justify-content-center mx-auto"
                    wire:loading.attr="disabled">
                <span wire:loading.remove>
                    <svg class="w-6 h-6 me-2" xmlns="http://www.w3.org/2000/svg"
                        width="20" height="20" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2"
                            d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 7 2 2 4-4m-5-9v4h4V3h-4Z"/>
                    </svg>
                    Save
                </span>
                <span wire:loading>
                    Saving...
                </span>
            </button>
        </div>

        @if($redirect_to !== 'dashboard')
            <div class="col-auto">
                <a href="{{ route('situationfinanciere.create', ['etablissement_id' => $etablissement->id]) }}"
                class="skip-link">
                    Skip
                </a>
            </div>
        @endif
    </div>

</div>
