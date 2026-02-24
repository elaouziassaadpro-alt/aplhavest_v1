<div>
    <x-breadcrumb-header 
        :etablissementName="$etablissement->name" 
        activePage="Bénéficiaire effectif" 
    />
    <x-notification />

    <div class="max-w-7xl mx-auto bg-white cardetablissement shadow-sm border border-gray-100 mt-4 ">
        <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl">
            <h2 class="text-xl font-semibold text-gray-800">Bénéficiaires Effectif</h2>
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

            <!-- Existing (Optional: if we want to show existing in read-only or edit mode, currently just recreating form) -->
            <!-- If we want to mirror the original, we might want to list existing. But original design was add-only in some contexts or edit. Let's stick to adding new rows for now as per controller logic which seemed to be 'store' new ones or 'update' all. -->
            
            <div class="benificiairesRows">
                @foreach($rows as $index => $row)
                    <div class="card mb-3 border">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Bénéficiaire #{{ $index + 1 }}</span>
                            @if(count($rows) > 1)
                                <button type="button" wire:click="removeRow({{ $index }})" class="btn btn-danger btn-sm">
                                    <i class="ti ti-trash"></i>
                                </button>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Nom / RS -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Nom / Raison Sociale <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="rows.{{ $index }}.nom_rs" class="form-control" placeholder="Nom">
                                </div>
                                
                                <!-- Prénom -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Prénom</label>
                                    <input type="text" wire:model="rows.{{ $index }}.prenom" class="form-control" placeholder="Prénom">
                                </div>

                                <!-- Pays Naissance -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Pays de naissance</label>
                                    <x-searchable-select :options="$pays" wireModel="rows.{{ $index }}.pays_naissance_id" :selected="$row['pays_naissance_id'] ?? ''" :disabled="false" />
                                </div>

                                <!-- Date Naissance -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Date de naissance</label>
                                    <input type="date" wire:model="rows.{{ $index }}.date_naissance" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <!-- Identité -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Identité (CIN/Passeport)</label>
                                    <input type="text" wire:model="rows.{{ $index }}.identite" class="form-control">
                                </div>

                                <!-- Nationalité -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Nationalité <span class="text-danger">*</span></label>
                                    <x-searchable-select :options="$pays" wireModel="rows.{{ $index }}.nationalite_id" :selected="$row['nationalite_id'] ?? ''" :disabled="false" />
                                </div>

                                <!-- Pourcentage -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">% Capital</label>
                                    <input type="number" step="0.01" wire:model="rows.{{ $index }}.pourcentage_capital" class="form-control" placeholder="0.00">
                                </div>

                                <!-- File Upload -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">CIN (Fichier)</label>
                                    <input type="file" wire:model="rows.{{ $index }}.cin_file" class="form-control">
                                </div>
                            </div>

                            <!-- PPE Checkboxes -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" wire:model.live="rows.{{ $index }}.is_ppe" id="ppe_{{ $index }}">
                                        <label class="form-check-label" for="ppe_{{ $index }}">Est PPE ?</label>
                                    </div>
                                    @if($row['is_ppe'])
                                        <x-searchable-select :options="$ppes" wireModel="rows.{{ $index }}.ppe_id" :selected="$row['ppe_id'] ?? ''" :disabled="false" />
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" wire:model.live="rows.{{ $index }}.is_ppe_lien" id="ppe_lien_{{ $index }}">
                                        <label class="form-check-label" for="ppe_lien_{{ $index }}">A un lien avec PPE ?</label>
                                    </div>
                                    @if($row['is_ppe_lien'])
                                        <x-searchable-select :options="$ppes" wireModel="rows.{{ $index }}.ppe_lien_id" :selected="$row['ppe_lien_id'] ?? ''" :disabled="false" />
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    <button type="button" class="btn btn-light-info" wire:click="addRow">
                        <i class="ti ti-plus me-1"></i> Ajouter un bénéficiaire
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
                 <a href="{{ route('administrateurs.create', ['etablissement_id' => $etablissement->id]) }}"
                class="skip-link">
                    Skip
                </a>
            </div>
        @endif
    </div>

    <!-- Risk Modal -->
    @if($showRiskModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Analyse du risque - Bénéficiaires Effectifs</h5>
                        <button type="button" class="btn-close" wire:click="closeRiskModal"></button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nom / Raison Sociale</th>
                                        <th>Identité</th>
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
                                            $isInterdit = $result['is_interdit'];
                                            $isHighRisk = $result['is_high_risk'];
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
                                <i class="ti ti-ban"></i> Interdit / Rejeter
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
