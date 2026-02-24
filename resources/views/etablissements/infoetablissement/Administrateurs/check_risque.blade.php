@if(count($analyses) > 0)

<form action="{{ route('administrateurs.store') }}" method="POST" enctype="multipart/form-data" id="riskForm">
    @csrf

    <input type="hidden" name="redirect_to" value="{{ $redirect_to }}">
    <input type="hidden" name="etablissement_id" value="{{ $etablissement->id }}">

    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 text-white">Analyse des Risques (Pré-validation)</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Nom & Prénom</th>
                            <th>Identité</th>
                            <th>PPE</th>
                            <th>Nationalité</th>
                            <th>Note</th>
                            <th>Correspondance (%)</th>
                            <th>Source</th>
                            <th>Statut</th>
                            <th>Validation</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($analyses as $index => $item)

                            @php
                                $risk = $item['risk'];
                                $note = $risk['note'] ?? 0;

                                $isInterdit = $note >= 300;

                                $isHighRisk = !$isInterdit &&
                                    (
                                        ($risk['percentage'] ?? 0) >= 70 ||
                                        $note >= 30
                                    );
                            @endphp

                            <tr class="risk-row {{ $isInterdit ? 'table-danger' : ($isHighRisk ? 'table-warning' : '') }}"
                                data-status="{{ $isInterdit ? 'interdit' : ($isHighRisk ? 'high-risk' : 'ok') }}"
                                data-table="{{ $risk['table'] ?? '-' }}">

                                <td>{{ $item['data']->nom }} {{ $item['data']->prenom }}</td>

                                <td>{{ $item['data']->identite ?? '-' }}</td>

                                <td>{{ $risk['ppe_note'] ?? '-' }}</td>

                                <td>{{ $risk['nationalite_note'] ?? '-' }}</td>

                                <td>
                                    <span class="risk-note-display">{{ $note }}</span>
                                    

                                    <input type="hidden" name="noms_administrateurs[]" value="{{ $item['data']->nom }}">
                                    <input type="hidden" name="prenoms_administrateurs[]" value="{{ $item['data']->prenom }}">
                                    <input type="hidden" name="cins_administrateurs[]" value="{{ $item['data']->identite }}">
                                    <input type="hidden" name="notes_administrateurs[]" id="note-{{ $index }}" value="{{ $note }}">
                                    <input type="hidden" name="percentages_administrateurs[]" id="percentage-input-{{ $index }}" value="{{ $risk['percentage'] ?? 0 }}">
                                    <input type="hidden" name="tables_administrateurs[]" id="table-input-{{ $index }}" value="{{ $risk['table'] ?? '' }}">
                                    <input type="hidden" name="match_ids_administrateurs[]" id="match-name-input-{{ $index }}" value="{{ $risk['match_id'] ?? '' }}">

                                    <input type="hidden" name="pays_administrateurs[]" value="{{ $item['data']->pays_id }}">
                                    <input type="hidden" name="dates_naissance_administrateurs[]" value="{{ $item['data']->date_naissance }}">
                                    <input type="hidden" name="nationalites_administrateurs[]" value="{{ $item['data']->nationalite_id }}">
                                    <input type="hidden" name="fonctions_administrateurs[]" value="{{ $item['data']->fonction }}">
                                    <input type="hidden" name="existing_cin_administrateurs[{{ $index }}]" value="{{ $item['data']->cin_file }}">
                                    <input type="hidden" name="existing_pvn_administrateurs[{{ $index }}]" value="{{ $item['data']->pvn_file }}">

                                    @if($item['data']->ppe)
                                        <input type="hidden" name="ppes_administrateurs_check[{{ $index }}]" value="1">
                                        <input type="hidden" name="ppes_administrateurs_input[{{ $index }}]" value="{{ $item['data']->ppe_id }}">
                                    @endif

                                    @if($item['data']->lien_ppe)
                                        <input type="hidden" name="ppes_lien_administrateurs_check[{{ $index }}]" value="1">
                                        <input type="hidden" name="ppes_lien_administrateurs_input[{{ $index }}]" value="{{ $item['data']->lien_ppe_id }}">
                                    @endif
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
                                        <button type="button" class="btn btn-danger btn-sm btn-non" data-index="{{ $index }}">
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
        <button type="button" class="btn btn-primary btn-sm" id="valider">
            <i class="ti ti-check"></i> Valider
        </button>

        <button type="button" class="btn btn-danger btn-sm" id="interdit">
            <i class="ti ti-ban"></i> Interdit
        </button>
    </div>

</form>

<script>
$(document).ready(function() {
    // We keep the wrap just in case, but ensure we use delegated events or standard ones
    // Since this HTML is injected into #riskModalBody, we can use delegated events from #riskModalBody
    const modalContainer = $('#riskModalBody');

    /* ✅ Faux positif */
    modalContainer.off('click', '.btn-non').on('click', '.btn-non', function() {
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

        // Ensure note doesn't go below minimum reasonable value
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

    /* ✅ Validation avec sécurité renforcée */
    modalContainer.off('click', '#valider').on('click', '#valider', function() {

        let forbiddenFound = $('.risk-row[data-status="interdit"]').length;

        if (forbiddenFound > 0) {
            alert('Impossible de valider : Administrateur INTERDIT détecté');
            return;
        }

        if (confirm('Voulez-vous valider ces administrateurs ?')) {
            $('#riskForm').submit();
        }
    });

    /* ✅ Rejet établissement */
    modalContainer.off('click', '#interdit').on('click', '#interdit', function() {

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
            $('#interdit').prop('disabled', false).html('<i class="ti ti-ban"></i> Interdit');
        });

    });

});
</script>

@endif
