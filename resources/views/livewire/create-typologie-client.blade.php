<div>
    <x-breadcrumb-header 
        :etablissementName="$etablissement->name" 
        activePage="Typologie Client" 
    />

    <div class="max-w-7xl mx-auto bg-white cardetablissement shadow-sm border border-gray-100 mt-4">
        <div class="px-6 py-4 border-b bg-gray-50 rounded-top">
            <h2 class="text-xl font-semibold text-gray-800">Typologie Client</h2>
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

            <div class="row mt-3">

                <!-- Secteur d'activité -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">Secteur d'activité <span class="text-danger">*</span></label>
                    <x-searchable-select :options="$secteurs" wireModel="secteurActivite" :selected="$secteurActivite" :disabled="false" />
                </div>

                <!-- Segment -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">Segment <span class="text-danger">*</span></label>
                    <x-searchable-select :options="$segments" wireModel="segment" :selected="$segment" :disabled="false" />
                </div>

                <!-- Activité à l'étranger -->
                <div class="col-md-1 mb-3">
                    <label class="form-label">Activité à l'étranger</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" wire:model.live="activiteEtranger" id="activite_check">
                        <label class="form-check-label" for="activite_check">
                            {{ $activiteEtranger ? 'Oui' : 'Non' }}
                        </label>
                    </div>
                </div>

                @if($activiteEtranger)
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Pays</label>
                        <x-searchable-select :options="$pays" wireModel="paysEtranger" :selected="$paysEtranger" :disabled="false" />
                    </div>
                @endif

                <!-- Sur un marché financier -->
                <div class="col-md-1 mb-3">
                    <label class="form-label">Sur un marché financier ?</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" wire:model.live="publicEpargne" id="marche_check">
                        <label class="form-check-label" for="marche_check">
                            {{ $publicEpargne ? 'Oui' : 'Non' }}
                        </label>
                    </div>
                </div>

                @if($publicEpargne)
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Précisez</label>
                        <input type="text" class="form-control" wire:model="publicEpargne_label" placeholder="Indiquez le marché financier">
                    </div>
                @endif

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
                <a href="{{ route('statutfatca.create', ['etablissement_id' => $etablissement->id]) }}"
                class="skip-link">
                    Skip
                </a>
            </div>
        @endif
    </div>

</div>
