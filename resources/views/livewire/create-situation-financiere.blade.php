<div>
    <x-breadcrumb-header 
        :etablissementName="$etablissement->name" 
        activePage="Situation Financière Patrimoniale" 
    />

    <div class="max-w-7xl mx-auto bg-white cardetablissement shadow-sm border border-gray-100 mt-4">
        <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl">
            <h2 class="text-xl font-semibold text-gray-800">Situation Financière Patrimoniale</h2>
        </div>

        <div class="card-body px-6 py-4 mt-4">

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

            <!-- Capital social, origine des fonds, pays -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label">Capital social</label>
                    <input type="number" class="form-control" wire:model="capitalSocial">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Origine des fonds</label>
                    <input type="text" class="form-control" wire:model="origineFonds">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pays de résidence fiscale</label>
                    <x-searchable-select :options="$pays" wireModel="paysOrigineFonds" :selected="$paysOrigineFonds" :disabled="false" />
                </div>
            </div>

            <!-- Chiffre d'affaires, Résultat net, Holding -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label">Chiffre d'affaires (exercice écoulé)</label><br>
                    @foreach(['<5M' => '< 5 M.DH', '5-10M' => '5 M.DH < CA < 10 M.DH', '>10M' => '> 10 M.DH'] as $value => $label)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" wire:model="chiffreAffaires" id="ca_{{ $loop->index }}" value="{{ $value }}">
                            <label class="form-check-label" for="ca_{{ $loop->index }}">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>

                <div class="col-md-4">
                    <label class="form-label">Résultat net (exercice écoulé)</label>
                    <input type="text" class="form-control" wire:model="resultatsNET">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Appartient à un groupe <b>"holding"</b></label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" wire:model="holding" id="holding_oui" value="1">
                        <label class="form-check-label" for="holding_oui">Oui</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" wire:model="holding" id="holding_non" value="0">
                        <label class="form-check-label" for="holding_non">Non</label>
                    </div>
                </div>
            </div>

            <!-- Upload états de synthèse -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="btn btn-primary mb-0">
                        <i class="ti ti-upload me-1"></i> Etats de synthèse
                        <input type="file" wire:model="etat_synthese" hidden accept=".pdf,.png,.jpg,.jpeg">
                    </label>
                    @if($etat_synthese)
                        <span class="text-success ms-2"><i class="ti ti-check"></i> Fichier sélectionné</span>
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
                <a href="{{ route('actionnariat.create', ['etablissement_id' => $etablissement->id]) }}"
                class="skip-link">
                    Skip
                </a>
            </div>
        @endif
    </div>

</div>
