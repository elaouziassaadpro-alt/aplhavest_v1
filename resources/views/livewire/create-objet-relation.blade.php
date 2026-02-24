<div>
    <x-breadcrumb-header 
        :etablissementName="$etablissement->name" 
        activePage="Obejet relation" 
    />

    <div class="max-w-7xl mx-auto bg-white cardetablissement shadow-sm border border-gray-100 mt-4 ">
        <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl">
            <h2 class="text-xl font-semibold text-gray-800">Obejet relation</h2>
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

            <form wire:submit.prevent="save">
            
            <div class="row">
                <div class="col-md-7">
                    <h5>Fréquence des opérations :</h5>
                    <div class="row">
                        <div class="mb-6">
                            <div style="margin-top:10px;"></div>
                            @php
                                $frequencies = ['Quotidienne', 'Hebdomadaire', 'Mensuelle', 'Trimestrielle', 'Annuelle', 'Ponctuelle'];
                            @endphp
                            @foreach($frequencies as $freq)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" wire:model="relation_affaire" value="{{ $freq }}" id="natureRelation{{ $loop->index }}">
                                    <label class="form-check-label" for="natureRelation{{ $loop->index }}">{{ $freq }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <h5>Horizon de placement :</h5>
                    <div class="row">
                        <div class="mb-6">
                            <div style="margin-top:10px;"></div>
                            @php
                                $horizons = ['< 1 an', 'Entre 1 et 3 ans', 'Entre 3 et 5 ans', '< 5 ans'];
                            @endphp
                            @foreach($horizons as $horz)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" wire:model="horizon_placement" value="{{ $horz }}" id="horizonPlacement{{ $loop->index }}">
                                    <label class="form-check-label" for="horizonPlacement{{ $loop->index }}">{{ $horz }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <br>
            
            <div class="row">
                <div class="col-md-7">
                    <h5>Objet de la relation d'affaire :</h5>
                    <div class="row">
                        <div class="mb-6">
                            <div style="margin-top:10px;"></div>
                            @php
                                $objets = ['Assurer des revenus réccurents', 'Profits à moyen et court terme', 'Gestion de la trésorerie'];
                            @endphp
                            @foreach($objets as $obj)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" wire:model="objet_relation_checked" value="{{ $obj }}" id="objetRelation{{ $loop->index }}">
                                    <label class="form-check-label" for="objetRelation{{ $loop->index }}">{{ $obj }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <h5>Compte géré par mandataire :</h5>

                    <div class="mb-3 row">
                        <div class="mb-4 bt-switch col-2 form-check form-switch">
                            <input class="form-check-input" type="checkbox" wire:model.live="mandataire_check" id="mandataire_id">
                            <label class="form-check-label" for="mandataire_id">
                                @if($mandataire_check)
                                    Oui
                                @else
                                    Non
                                @endif
                            </label>
                        </div>
                        
                        @if($mandataire_check)
                            <div class="mb-3 col-4">
                                <input type="text" class="form-control" placeholder="Description" wire:model="mandataire_label">
                            </div>

                            <div class="mb-3 col-2">
                                <label>Date fin de mandat</label>
                            </div>

                            <div class="mb-3 col-4">
                                <input type="date" class="form-control" wire:model="mandataire_fin_mandat_date">
                            </div>
                        @endif
                    </div>

                    @if($mandataire_check)
                        <div class="mb-3 row" style="margin-top:-30px">
                            <div class="mb-4 col-4">
                                <label for="mandat_file" class="btn btn-primary w-100">
                                    <i class="ti ti-upload"></i>&nbsp; Mandat pouvoir
                                </label>
                                <input type="file" id="mandat_file" wire:model="mandat_file" hidden>
                                @if($mandat_file)
                                    <small class="text-success d-block text-center mt-1">Fichier sélectionné: {{ $mandat_file->getClientOriginalName() }}</small>
                                @elseif($objetRelation && $objetRelation->mandat_file)
                                    <small class="text-muted d-block text-center mt-1">Fichier actuel: <a href="{{ Storage::url($objetRelation->mandat_file) }}" target="_blank">Voir</a></small>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Buttons -->
            <div class="row mt-4 align-items-center">
                <div class="col text-center">
                    <button type="submit" class="btn btn-save d-flex align-items-center justify-content-center mx-auto" wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            <svg class="w-6 h-6 me-2" xmlns="http://www.w3.org/2000/svg"
                            width="20" height="20" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2"
                                d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 7 2 2 4-4m-5-9v4h4V3h-4Z"/>
                        </svg>
                            Enregistrer
                        </span>
                        <span wire:loading>
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            Enregistrement...
                        </span>
                    </button>
                </div>

                @if($redirect_to !== 'dashboard')
                    <div class="col-auto">
                         <a href="{{ route('profilrisque.create', ['etablissement_id' => $etablissement->id]) }}" class="skip-link">
                            Skip
                        </a>
                    </div>
                @endif
            </div>

            </form>

        </div>
    </div>
</div>
