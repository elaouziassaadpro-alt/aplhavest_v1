<div>
    <x-notification />

    @if(count($actionnaires) > 0)
    <div class="text-end">
        <button type="button" wire:click="toggleEdit" class="btn mb-1 {{ $editing ? 'btn-secondary' : 'btn-light-info' }} btn-circle btn-sm d-inline-flex align-items-center justify-content-center" title="{{ $editing ? 'Annuler' : 'Modifier cette section' }}">
            <i class="fs-5 ti {{ $editing ? 'ti-x' : 'ti-pencil' }}"></i>
        </button>
    </div>
    @endif

    <form wire:submit.prevent="update">
        @forelse($actionnaires as $index => $actionnaire)
            <div class="row border rounded p-3 mb-4 justify-content-center align-items-center">
                <div class="col-md-2 mb-2">
                    <label>Nom / Raison sociale</label>
                    <input type="text" class="form-control" wire:model="actionnaires.{{ $index }}.nom_rs" @disabled(!$editing)>
                </div>
                <div class="col-md-2 mb-2">
                    <label>Prénom</label>
                    <input type="text" class="form-control" wire:model="actionnaires.{{ $index }}.prenom" @disabled(!$editing)>
                </div>
                <div class="col-md-2 mb-2">
                    <label>N° d'identité / RC</label>
                    <input type="text" class="form-control" wire:model="actionnaires.{{ $index }}.identite" @disabled(!$editing)>
                </div>
                <div class="col-md-2 mb-2">
                    <label>Nombre de titres</label>
                    <input type="number" class="form-control" wire:model="actionnaires.{{ $index }}.nombre_titres" @disabled(!$editing)>
                </div>
                <div class="col-md-2 mb-2">
                    <label>Pourcentage capital</label>
                    <input type="number" step="0.01" class="form-control" wire:model="actionnaires.{{ $index }}.pourcentage_capital" @disabled(!$editing)>
                </div>
                @if($editing)
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm" wire:click="removeActionnaire({{ $index }})">Supprimer</button>
                </div>
                @endif
            </div>
        @empty
            <h5 class="text-center">Aucun actionnaire enregistré</h5>
        @endforelse

        <div class="text-start">
            <a href="{{ route('actionnariat.create', ['etablissement_id' => $etablissement_id, 'redirect_to' => 'dashboard']) }}" class="btn btn-light-info btn-sm mb-2">
                Ajouter un actionnaire
            </a>
        </div>

        @if($editing && count($actionnaires) > 0)
        <div class="text-center d-flex justify-content-center gap-2">
            <button type="button"
                    class="btn btn-light-warning"
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

            <button type="submit"
                    class="btn btn-success d-flex align-items-center justify-content-center"
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

    {{-- Risk Modal --}}
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
                                            <td>{{ $result['data']['nom_rs'] }} {{ $result['data']['prenom'] ?? '' }}</td>
                                            <td>{{ $result['data']['identite'] ?? '—' }}</td>
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
                            <button type="button" class="btn btn-primary btn-sm" wire:click="update" wire:loading.attr="disabled">
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
