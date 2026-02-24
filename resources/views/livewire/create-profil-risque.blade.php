<div>
    <x-breadcrumb-header 
        :etablissementName="$etablissement->name" 
        activePage="Profil Risque" 
    />

    <div class="max-w-7xl mx-auto bg-white cardetablissement shadow-sm border border-gray-100 mt-4">
        <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl">
            <h2 class="text-xl font-semibold text-gray-800">Profil Risque</h2>
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

            <!-- First Row -->
            <div class="row">

                <!-- Département en charge -->
                <div class="col-md-6">
                    <h5 class="mb-3">Département en charge de la gestion des placements / investissements</h5>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" wire:model.live="departement_en_charge_check" id="departement_check">
                            <label class="form-check-label" for="departement_check">
                                {{ $departement_en_charge_check ? 'Oui' : 'Non' }}
                            </label>
                        </div>

                        @if($departement_en_charge_check)
                            <div x-data="{
                                    open: false,
                                    search: '',
                                    options: ['La part du portfeuille de valeurs mobilières','Inférieur à 5%','Entre 5% et 10%','Entre 10% et 25%','Entre 25% et 50%','Supérieure à 50%'],
                                    get filtered() {
                                        if (this.search === '') return this.options;
                                        let s = this.search.toLowerCase();
                                        return this.options.filter(o => o.toLowerCase().startsWith(s));
                                    }
                                 }"
                                 @click.outside="open = false"
                                 class="position-relative mt-2">
                                <div class="input-group" @click="open = !open" style="cursor: pointer;">
                                    <input type="text" class="form-control"
                                           :value="open ? search : $wire.departement_gestion_input"
                                           @input="search = $event.target.value; open = true"
                                           @focus="open = true; search = ''"
                                           @keydown.escape="open = false"
                                           placeholder="-- Choisir --" autocomplete="off">
                                    <span class="input-group-text" style="cursor: pointer;">
                                        <i class="ti" :class="open ? 'ti-chevron-up' : 'ti-chevron-down'"></i>
                                    </span>
                                </div>
                                <ul x-show="open" x-transition
                                    class="list-group position-absolute w-100 shadow-sm border"
                                    style="z-index: 1050; max-height: 200px; overflow-y: auto; margin-top: 2px;">
                                    <template x-for="option in filtered" :key="option">
                                        <li class="list-group-item list-group-item-action py-1 px-2"
                                            :class="{ 'active': option === $wire.departement_gestion_input }"
                                            @click="$wire.set('departement_gestion_input', option); search = ''; open = false;"
                                            x-text="option"
                                            style="cursor: pointer; font-size: 0.9rem;">
                                        </li>
                                    </template>
                                    <li x-show="filtered.length === 0" class="list-group-item text-muted py-1 px-2" style="font-size: 0.9rem;">Aucun résultat</li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Instruments financiers souhaités -->
                <div class="col-md-6">
                    <h5 class="mb-3">Les instruments financiers souhaités</h5>
                    <div class="mb-3">
                        @php
                            $instrumentsList = [
                                'OPCVM Monétaires', 'OPCVM Obligataires', 'OPCVM Actions',
                                'OPCVM Diversifiés', 'Bons de Trésor', 'Titres de dette privé', 'Actions'
                            ];
                        @endphp
                        @foreach($instrumentsList as $index => $instrument)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" wire:model="instruments_souhaites" id="instrument_{{ $index }}" value="{{ $instrument }}">
                                <label class="form-check-label" for="instrument_{{ $index }}">{{ $instrument }}</label>
                            </div>
                        @endforeach
                        <div class="form-check form-check-inline mt-2">
                            <input type="text" class="form-control" placeholder="Autres" wire:model="instruments_souhaites_autres">
                        </div>
                    </div>
                </div>

            </div>

            <hr class="my-4">

            <!-- Second Row -->
            <div class="row">

                <!-- Niveau de risque toléré -->
                <div class="col-md-6">
                    <h5 class="mb-3">Le niveau de risque toléré</h5>
                    <div class="mb-3">
                        @foreach([
                            'Faible' => 'Faible (Stratégie défensive)',
                            'Moyen' => 'Moyen (Stratégie équilibrée)',
                            'Elevé' => 'Elevé (Stratégie agressive)',
                        ] as $value => $label)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" wire:model="niveau_risque_tolere_radio" id="nrTolere_{{ $value }}" value="{{ $value }}">
                                <label class="form-check-label" for="nrTolere_{{ $value }}">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Années d'investissement -->
                <div class="col-md-6">
                    <h5 class="mb-3">Années d'investissement dans les produits financiers</h5>
                    <div class="mb-3">
                        @foreach(['Jamais', 'Jusqu\'à 1 an', 'Entre 1 et 5 ans', 'Plus que 5 ans'] as $index => $value)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" wire:model="annees_investissement_produits_finaniers" id="annees_{{ $index }}" value="{{ $value }}">
                                <label class="form-check-label" for="annees_{{ $index }}">{{ $value }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <hr class="my-4">

            <!-- Third Row -->
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">Transactions en moyenne réalisés sur le marché courant 2 dernières années</h5>
                    <div class="mb-3">
                        @foreach(['Aucune', 'Moins de 30', 'Plus de 30'] as $index => $value)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" wire:model="transactions_courant_2_annees" id="transactions_{{ $index }}" value="{{ $value }}">
                                <label class="form-check-label" for="transactions_{{ $index }}">{{ $value }}</label>
                            </div>
                        @endforeach
                    </div>
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
                <a href="{{ route('etablissements.index') }}"
                class="skip-link">
                    Skip
                </a>
            </div>
        @endif
    </div>

</div>
