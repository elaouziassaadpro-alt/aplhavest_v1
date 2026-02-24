@php
    $note = $risk['note'] ?? 1;
    $isInterdit = ($note >= 300);
    $isHighRisk = ($note >= 50 && $note < 300);
@endphp

<div id="riskResultsWrapper">
    <form id="riskFormInfoGeneral" method="POST">
        @csrf
        {{-- Hidden inputs to store results that JS will copy back to the main form --}}
        <input type="hidden" id="note-infogeneral" value="{{ $note }}">
        <input type="hidden" id="percentage-input-infogeneral" value="{{ $risk['percentage'] ?? 0 }}">
        <input type="hidden" id="table-input-infogeneral" value="{{ $risk['table'] ?? '' }}">
        <input type="hidden" name="match_id" id="match-name-input-infogeneral" value="{{ $risk['match_id'] ?? '' }}">

        <div class="card shadow-none border">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Raison Sociale</th>
                                <th>Note Totale</th>
                                <th>Match %</th>
                                <th>Source</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="risk-row {{ $isInterdit ? 'table-danger' : ($isHighRisk ? 'table-warning' : '') }}"
                                data-status="{{ $isInterdit ? 'interdit' : ($isHighRisk ? 'high-risk' : 'ok') }}"
                                data-table="{{ $risk['table'] ?? '-' }}">

                                <td>
                                    {{ $info->nom_rs }}
                                </td>

                                <td>
                                    <span class="risk-note-display">{{ $note }}</span>
                                </td>

                                <td>
                                    <span id="percentage-infogeneral-badge"
                                          class="badge {{ ($risk['percentage'] ?? 0) >= 70 ? 'bg-danger' : 'bg-success' }}">
                                        {{ $risk['percentage'] ?? 0 }}%
                                    </span>
                                </td>

                                <td>
                                    {{ $risk['table'] ?? '-' }}
                                    @if(!empty($risk['match_id']))
                                        <br><small class="text-muted">Détail : {{ $risk['match_id'] }}</small>
                                    @endif
                                </td>

                                <td class="risk-status">
                                    @if($isInterdit)
                                        <span class="text-danger fw-bold"><i class="ti ti-ban"></i> Interdit</span>
                                    @elseif($isHighRisk)
                                        <span class="text-warning fw-bold"><i class="ti ti-alert-triangle"></i> High Risk</span>
                                    @else
                                        <span class="text-success fw-bold"><i class="ti ti-check"></i> OK</span>
                                    @endif
                                </td>

                                <td class="risk-actions">
                                    @if (in_array($risk['table'] ?? '', ['Cnasnu', 'Anrf']))
                                        <button type="button" class="btn btn-danger btn-sm btn-non-infogeneral">
                                            <i class="ti ti-x"></i> Faux Positif
                                        </button>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3 d-flex gap-2">
            <button type="button" class="btn btn-primary btn-sm" id="validerInfoGeneral">
                <i class="ti ti-check"></i> Valider & Enregistrer
            </button>

            @if($isInterdit || $isHighRisk)
                <button type="button" class="btn btn-danger btn-sm" id="annulerInfoGeneral" data-bs-dismiss="modal">
                    <i class="ti ti-x"></i> Annuler
                </button>
            @endif
        </div>
    </form>
</div>
