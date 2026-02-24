<form id="riskFormBE" action="{{ route('benificiaireeffectif.store') }}" method="POST">
    @csrf
    <input type="hidden" name="etablissement_id" value="{{ $etablissement->id }}">
    <input type="hidden" name="redirect_to" value="{{ $redirect_to }}">

    <div class="card shadow-none border">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nom / Raison Sociale</th>
                            <th>CIN / Identité</th>
                            <th>Note PPE</th>
                            <th>Note Nat.</th>
                            <th>Note Totale</th>
                            <th>Match %</th>
                            <th>Source</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($analyses as $index => $item)
                            @php
                                $risk = $item['risk'];
                                $note = $risk['note'] ?? 0;
                                $isInterdit = ($note >= 300);
                                $isHighRisk = !$isInterdit && ($note >= 30);
                            @endphp

                            <tr class="risk-row {{ $isInterdit ? 'table-danger' : ($isHighRisk ? 'table-warning' : '') }}"
                                data-status="{{ $isInterdit ? 'interdit' : ($isHighRisk ? 'high-risk' : 'ok') }}"
                                data-table="{{ $risk['table'] ?? '-' }}">

                                <td>{{ $item['data']->nom_rs }} {{ $item['data']->prenom }}</td>

                                <td>{{ $item['data']->identite ?? '-' }}</td>

                                <td>{{ $risk['ppe_note'] ?? '-' }}</td>

                                <td>{{ $risk['nationalite_note'] ?? '-' }}</td>

                                <td>
                                    <span class="risk-note-display">{{ $note }}</span>

                                    <input type="hidden" name="noms_rs_benificiaires[]" value="{{ $item['data']->nom_rs }}">
                                    <input type="hidden" name="prenoms_benificiaires[]" value="{{ $item['data']->prenom }}">
                                    <input type="hidden" name="identite_benificiaires[]" value="{{ $item['data']->identite }}">
                                    <input type="hidden" name="notes_benificiaires[]" id="note-{{ $index }}" value="{{ $note }}">
                                    <input type="hidden" name="percentages_benificiaires[]" id="percentage-input-{{ $index }}" value="{{ $risk['percentage'] ?? 0 }}">
                                    <input type="hidden" name="tables_benificiaires[]" id="table-input-{{ $index }}" value="{{ $risk['table'] ?? '' }}">
                                    <input type="hidden" name="match_ids_benificiaires[]" id="match-name-input-{{ $index }}" value="{{ $risk['match_id'] ?? '' }}">

                                    <input type="hidden" name="pays_naissance_benificiaires[]" value="{{ $item['data']->pays_naissance_id }}">
                                    <input type="hidden" name="dates_naissance_benificiaires[]" value="{{ $item['data']->date_naissance }}">
                                    <input type="hidden" name="nationalites_benificiaires[]" value="{{ $item['data']->nationalite_id }}">
                                    <input type="hidden" name="benificiaires_pourcentage_capital[]" value="{{ $item['data']->pourcentage_capital }}">
                                    
                                    <input type="hidden" name="benificiaires_ppe_check[]" value="{{ $item['data']->ppe }}">
                                    <input type="hidden" name="benificiaires_ppe_input[]" value="{{ $item['data']->ppe_id }}">
                                    <input type="hidden" name="benificiaires_ppe_lien_check[]" value="{{ $item['data']->ppe_lien }}">
                                    <input type="hidden" name="benificiaires_ppe_lien_input[]" value="{{ $item['data']->ppe_lien_id }}">
                                </td>

                                <td>
                                    <span id="percentage-{{ $index }}"
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
                                        <button type="button" class="btn btn-danger btn-sm btn-non-be" data-index="{{ $index }}">
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
        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <button type="button" class="btn btn-primary btn-sm" id="validerBE">
            <i class="ti ti-check"></i> Valider
        </button>

        <button type="button" class="btn btn-danger btn-sm" id="interditBE">
            <i class="ti ti-ban"></i> Interdit
        </button>
    </div>

</form>

<script>
$(document).ready(function() {
    const modalContainer = $('#riskModalBodyBE');

    /* ✅ Faux positif */
    modalContainer.off('click', '.btn-non-be').on('click', '.btn-non-be', function() {
        if (!confirm('Voulez-vous marquer ce résultat comme faux positif ?')) return;

        let row             = $(this).closest('.risk-row');
        let index           = $(this).data('index');
        let tableSource     = row.data('table');
        
        let noteInput       = $('#note-' + index);
        let percentageInput = $('#percentage-input-' + index);
        let tableInput      = $('#table-input-' + index);
        let nameMatchInput  = $('#match-name-input-' + index);
        let percentageBadge = $('#percentage-' + index);

        let currentNote = parseInt(noteInput.val()) || 0;
        let newNote = currentNote;

        if (tableSource === 'Cnasnu') {
            newNote -= 299;
        } else if (tableSource === 'Anrf') {
            newNote -= 2;
        }

        if (newNote < 1) newNote = 1;

        noteInput.val(newNote);
        percentageInput.val(0);
        tableInput.val('');
        nameMatchInput.val('');

        row.find('.risk-note-display').text(newNote);

        percentageBadge.removeClass('bg-danger')
                      .addClass('bg-success')
                      .text('0%');

        row.find('.risk-status').html(
            '<span class="text-success fw-bold">' +
            '<i class="ti ti-check"></i> OK (Faux positif)' +
            '</span>'
        );

        row.find('.risk-actions').html('<span class="text-muted">Ignoré</span>');
        row.removeClass('table-danger table-warning').attr('data-status', 'ok');
    });

    /* ✅ Validation */
    modalContainer.off('click', '#validerBE').on('click', '#validerBE', function() {

        let forbiddenFound = $('.risk-row[data-status="interdit"]').length;

        if (forbiddenFound > 0) {
            alert('Impossible de valider : Bénéficiaire INTERDIT détecté');
            return;
        }

        if (confirm('Voulez-vous valider ces bénéficiaires ?')) {
            $('#riskFormBE').submit();
        }
    });

    /* ✅ Rejet établissement */
    modalContainer.off('click', '#interditBE').on('click', '#interditBE', function() {

        if (!confirm('Confirmer le rejet de cet établissement ?')) return;

        $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Traitement...');

        $.post("{{ route('etablissement.update.validation') }}", {
            _token: "{{ csrf_token() }}",
            ids: ["{{ $etablissement->id }}"],
            validation: "rejete"
        })
        .done(function() {
            window.location.href = "{{ route('dashboard') }}";
        })
        .fail(function() {
            alert('Une erreur est survenue lors du rejet.');
            $('#interditBE').prop('disabled', false).html('<i class="ti ti-ban"></i> Interdit');
        });

    });

});
</script>