<div>
    <x-breadcrumb-header 
        :etablissementName="$etablissement->name" 
        activePage="Actionnaires" 
    />
    <x-notification />

    <div class="max-w-7xl mx-auto bg-white cardetablissement shadow-sm border border-gray-100 mt-4 ">
        <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl">
            <h2 class="text-xl font-semibold text-gray-800">Actionnaires</h2>
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

            <!-- Existing Actionnaires (Read-only view) -->
            @if($etablissement->Actionnaire && $etablissement->Actionnaire->count())
                <h5 class="fw-semibold mb-3">Actionnaires existants :</h5>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Nom / Raison sociale</th>
                                <th>Prénom</th>
                                <th>Identité / RC</th>
                                <th>Nombre de titres</th>
                                <th>% Capital</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($etablissement->Actionnaire as $exist)
                                <tr>
                                    <td>{{ $exist->nom_rs }}</td>
                                    <td>{{ $exist->prenom ?? '—' }}</td>
                                    <td>{{ $exist->identite ?? '—' }}</td>
                                    <td>{{ $exist->nombre_titres ?? '—' }}</td>
                                    <td>{{ $exist->pourcentage_capital ? $exist->pourcentage_capital . '%' : '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr class="mb-4">
            @endif

            <!-- Dynamic Rows -->
             <div class="actionnairesRows">
                @foreach($rows as $index => $row)
                    <div class="row mb-3 align-items-end border-bottom pb-3">
                        <div class="col-md-3">
                            <label class="form-label">Nom / Raison Sociale</label>
                            <input type="text" wire:model="rows.{{ $index }}.nom_rs" class="form-control" placeholder="Nom ou Raison Sociale">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Prénom</label>
                            <input type="text" wire:model="rows.{{ $index }}.prenom" class="form-control" placeholder="Prénom (Optionnel)">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Identité / RC</label>
                            <input type="text" wire:model="rows.{{ $index }}.identite" class="form-control" placeholder="CIN, Passeport, RC...">
                        </div>
                        <div class="col-md-2">
                             <label class="form-label">Nbr Titres</label>
                             <input type="number" wire:model="rows.{{ $index }}.nombre_titres" class="form-control" placeholder="0">
                        </div>
                        <div class="col-md-2">
                             <label class="form-label">% Capital</label>
                             <input type="number" step="0.01" wire:model="rows.{{ $index }}.pourcentage_capital" class="form-control" placeholder="0.00">
                        </div>

                        <div class="col-md-1">
                             <button type="button" wire:click="removeRow({{ $index }})" class="btn btn-danger btn-sm">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    <button type="button" class="btn btn-light-info" wire:click="addRow">
                        Ajouter un actionnaire
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Buttons -->
    <div class="row mt-4 align-items-center">
        <div class="col text-center">
            <button type="button"
                    class="btn btn-light-warning mt-2"
                    wire:click="checkRisque"
                    wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="checkRisque">
                    Vérifier le risque
                </span>
                <span wire:loading wire:target="checkRisque">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Analyse...
                </span>
            </button>
        </div>

        @if($redirect_to !== 'dashboard')
            <div class="col-auto">
                 <a href="{{ route('benificiaireeffectif.create', ['etablissement_id' => $etablissement->id]) }}"
                class="skip-link">
                    Skip
                </a>
            </div>
        @endif
    </div>

    <!-- Risk Modal (Custom Implementation) -->
    @if($showRiskModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Analyse du risque - Actionnaires</h5>
                        <button type="button" class="btn-close" wire:click="closeRiskModal"></button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nom / Raison Sociale</th>
                                        <th>CIN / Identité</th>
                                        <th>Note Totale</th>
                                        <th>Match %</th>
                                        <th>Source</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analysisResults as $index => $result)
                                        @php
                                            $risk = $result['risk'];
                                            $isInterdit = $result['is_interdit'] ?? false;
                                            $isHighRisk = $result['is_high_risk'] ?? false;
                                            $isMediumRisk = $result['is_medium_risk'] ?? false;
                                            $isLowRisk = $result['is_low_risk'] ?? false;
                                        @endphp
                                         <tr class="{{ $isInterdit ? 'table-danger' : 
                                                    ($isHighRisk ? 'table-warning' : 
                                                    ($isMediumRisk ? 'table-info' : 
                                                    ($isLowRisk ? 'table-success' : ''))) }}">
                                            <td>{{ $result['data']['nom_rs'] }} {{ $result['data']['prenom'] }}</td>
                                            <td>{{ $result['data']['identite'] }}</td>
                                            <td>{{ $risk['note'] ?? 1 }}</td>
                                            <td>
                                                <span class="badge {{ ($risk['percentage'] ?? 0) >= 70 ? 'bg-danger' : 'bg-success' }}">
                                                    {{ $risk['percentage'] ?? 0 }}%
                                                </span>
                                            </td>
                                            <td>
                                                {{ !empty($risk['table']) ? $risk['table'] : '-' }}
                                                @if(!empty($risk['match_id']))
                                                    <br><small class="text-muted" style="cursor: pointer; text-decoration: underline;" wire:click="showDetail({{ $risk['match_id'] }}, '{{ $risk['table'] }}')">Détail</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($isInterdit)
                                                    <span class="text-danger fw-bold"><i class="ti ti-ban"></i> Interdit</span>
                                                @elseif($isHighRisk)
                                                    <span class="text-warning fw-bold"><i class="ti ti-alert-triangle"></i> High Risk</span>
                                                @elseif($isMediumRisk)
                                                    <span class="text-info fw-bold"><i class="ti ti-info-circle"></i> Medium Risk</span>
                                                @elseif($isLowRisk)
                                                    <span class="text-success fw-bold"><i class="ti ti-check"></i> Low Risk</span>
                                                @else
                                                    <span class="text-success fw-bold"><i class="ti ti-check"></i> OK</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($risk['percentage'] != 0)
                                                    <button type="button" class="btn btn-danger btn-sm" wire:click="markAsFalsePositive({{ $index }})" 
                                                        wire:confirm="Voulez-vous marquer ce résultat comme faux positif ?">
                                                        <i class="ti ti-x"></i> Faux Positif
                                                    </button>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                         </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 d-flex gap-2">
                            <button type="button" class="btn btn-primary btn-sm" wire:click="save" wire:loading.attr="disabled">
                                <i class="ti ti-check"></i> Valider et Enregistrer
                            </button>

                            <button type="button" class="btn btn-danger btn-sm" wire:click="rejectEtablissement" wire:confirm="Confirmer le rejet de cet établissement ?">
                                <i class="ti ti-ban"></i> Interdjit / Rejeter
                            </button>
                             <button type="button" class="btn btn-secondary btn-sm" wire:click="closeRiskModal">
                                Fermer
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Detail Modal --}}
    @if($showDetailModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5); z-index: 1060;" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Détails de la correspondance ({{ $detail_table_match }})</h5>
                        <button type="button" class="btn-close" wire:click="closeDetailModal"></button>
                    </div>
                    <div class="modal-body">
                         <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th class="bg-light w-25">Identifiant</th>
                                        <td>{{ $match_detail_identifiant }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Nom complet</th>
                                        <td>{{ $match_detail_full_name }}</td>
                                    </tr>
                                </tbody>
                            </table>
                         </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeDetailModal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
