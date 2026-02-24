<div>
    <x-breadcrumb-header 
        :etablissementName="$etablissement->name" 
        activePage="Coordonnées bancaires" 
    />

    <div class="max-w-7xl mx-auto bg-white cardetablissement shadow-sm border border-gray-100 mt-4 ">
        <div class="card-body">

            <h2 class="text-xl font-semibold text-gray-800 mb-4">Coordonnées bancaires</h2>

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

            <!-- Dynamic rows -->
            @foreach($rows as $index => $row)
                <div class="row mb-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Banque</label>
                        <x-searchable-select :options="$banques" wireModel="rows.{{ $index }}.banque_id" :selected="$row['banque_id'] ?? ''" :disabled="false" labelField="nom" />
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Agence</label>
                        <input type="text" wire:model="rows.{{ $index }}.agences_banque" class="form-control" placeholder="Agence">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Ville</label>
                        <x-searchable-select :options="$villes" wireModel="rows.{{ $index }}.ville_id" :selected="$row['ville_id'] ?? ''" :disabled="false" />
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">RIB</label>
                        <input type="text" wire:model="rows.{{ $index }}.rib_banque" class="form-control @error('rows.'.$index.'.rib_banque') is-invalid @enderror" placeholder="RIB">
                    </div>

                    <div class="col-md-1">
                        @if(count($rows) > 1)
                            <button type="button" wire:click="removeRow({{ $index }})" class="btn btn-danger btn-sm">
                                <i class="ti ti-trash"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="row mt-3">
                <div class="col-md-3">
                    @if(count($rows) < 5)
                        <button type="button"
                                class="btn btn-light-info"
                                wire:click="addRow">
                            Ajouter un compte
                        </button>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- ===================== SAVE BUTTON ===================== -->
    <div class="row mt-4 align-items-center">

        <!-- Save (Centered) -->
        <div class="col text-center">
            <button type="button" wire:click="save('next')"
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

        <!-- Skip (Conditional) -->
        <div class="col-auto">
             <a href="{{ route('typologie.create', ['etablissement_id' => $etablissement->id]) }}"
                class="skip-link">
                Skip
            </a>
        </div>

    </div>
</div>
