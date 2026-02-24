<div>
    <x-notification />

    <div class="text-end">
        <button type="button" wire:click="toggleEdit" class="btn mb-1 {{ $editing ? 'btn-secondary' : 'btn-light-info' }} btn-circle btn-sm d-inline-flex align-items-center justify-content-center" title="{{ $editing ? 'Annuler' : 'Modifier cette section' }}">
            <i class="fs-5 ti {{ $editing ? 'ti-x' : 'ti-pencil' }}"></i>
        </button>
    </div>

    <form wire:submit.prevent="update">
        <div class="row">
            <!-- Département en charge -->
            <div class="col-md-6">
                <h5>Département en charge de la gestion des placements / investissements</h5>
                <div class="mb-3 row align-items-center">
                    <div class="col-1 form-check form-switch">
                        <input type="checkbox" class="form-check-input" wire:model.live="departement_en_charge_check" @disabled(!$editing)>
                        <label>{{ $departement_en_charge_check ? 'Oui' : 'Non' }}</label>
                    </div>

                    @if($departement_en_charge_check)
                    <div class="col-8">
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
                         class="position-relative">
                        @if(!$editing)
                            <input type="text" class="form-control" wire:model="departement_gestion_input" disabled readonly>
                        @else
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
                        @endif
                    </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Instruments financiers -->
            <div class="col-md-6">
                <h5>Les instruments financiers souhaités</h5>
                <div class="mb-3 row">
                    <div class="col-12">
                        @foreach(['OPCVM Monétaires', 'OPCVM Obligataires', 'OPCVM Actions', 'OPCVM Diversifiés', 'Bons de Trésor', 'Titres de dette privé', 'Actions'] as $instrument)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" wire:model="instruments_souhaites" value="{{ $instrument }}" @disabled(!$editing)>
                                <label class="form-check-label">{{ $instrument }}</label>
                            </div>
                        @endforeach
                        <input type="text" class="form-control mt-2" placeholder="Autres instruments" wire:model="instruments_souhaites_autres" @disabled(!$editing)>
                    </div>
                </div>
            </div>
        </div>

        <!-- Niveau de risque & Années d'investissement -->
        <div class="row">
            <div class="col-md-6">
                <h5>Le niveau de risque toléré</h5>
                <div class="mb-3 row">
                    <div class="col-12">
                        @foreach(['Faible' => 'Stratégie défensive', 'Moyen' => 'Stratégie équilibrée', 'Elevé' => 'Stratégie agressive'] as $val => $desc)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" wire:model="niveau_risque_tolere_radio" value="{{ $val }}" @disabled(!$editing)>
                                <label class="form-check-label">{{ $val }} ({{ $desc }})</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <h5>Années d'investissement dans les produits financiers</h5>
                <div class="mb-3 row">
                    <div class="col-12">
                        @foreach(['Jamais', "Jusqu'à 1 an", 'Entre 1 et 5 ans', 'Plus que 5 ans'] as $val)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" wire:model="annees_investissement_produits_finaniers" value="{{ $val }}" @disabled(!$editing)>
                                <label class="form-check-label">{{ $val }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions -->
        <div class="row">
            <div class="col-md-6">
                <h5>Transactions en moyenne réalisés sur le marché courant 2 dernières années</h5>
                <div class="mb-3 row">
                    <div class="col-12">
                        @foreach(['Aucune', 'Moins de 30', 'Plus de 30'] as $val)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" wire:model="transactions_courant_2_annees" value="{{ $val }}" @disabled(!$editing)>
                                <label class="form-check-label">{{ $val }}</label>
                            </div>
                        @endforeach
                    </div>
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
    </form>
</div>
