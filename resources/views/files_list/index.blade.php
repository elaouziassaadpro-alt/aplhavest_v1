@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-semibold mb-1">
                <i class="ti ti-database text-primary me-2"></i>Listes de Surveillance
            </h4>
            <p class="mb-0 text-muted fs-3">Données importées — CNASNUS &amp; ANRF</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-muted text-decoration-none">Accueil</a>
                    </li>
                    <li class="breadcrumb-item active fw-semibold">Listes</li>
                </ol>
            </nav>
            <a href="{{ route('import_files.index') }}" class="btn btn-primary btn-sm">
                <i class="ti ti-file-import me-1"></i>Importer
            </a>
        </div>
    </div>

    {{-- ════════════ TAB SWITCHER ════════════ --}}
    <ul class="nav nav-pills mb-4 gap-2" id="listeTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active d-flex align-items-center gap-2 px-4 py-2 fw-semibold"
                    id="cnasnus-tab"
                    data-bs-toggle="pill"
                    data-bs-target="#cnasnus-pane"
                    type="button" role="tab"
                    aria-controls="cnasnus-pane"
                    aria-selected="true"
                    style="border:1px solid #dee2e6;">
                <i class="ti ti-file-text"></i> CNASNUS
                <span class="badge bg-danger text-white ms-1 fs-2">
                    @if(request('cnasnus_search'))
                        {{ $cnasnusFilteredCount }} / {{ $cnasnusCount }}
                    @else
                        {{ $cnasnusCount }}
                    @endif
                </span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center gap-2 px-4 py-2 fw-semibold"
                    id="anrf-tab"
                    data-bs-toggle="pill"
                    data-bs-target="#anrf-pane"
                    type="button" role="tab"
                    aria-controls="anrf-pane"
                    aria-selected="false"
                    style="border:1px solid #dee2e6;">
                <i class="ti ti-file-analytics"></i> ANRF
                <span class="badge bg-warning text-white ms-1 fs-2">
                    @if(request('anrf_search'))
                        {{ $anrfFilteredCount }} / {{ $anrfCount }}
                    @else
                        {{ $anrfCount }}
                    @endif
                </span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="listeTabsContent">

    {{-- ════════════════════ CNASNUS TABLE ════════════════════ --}}
    <div class="tab-pane fade show active" id="cnasnus-pane" role="tabpanel" aria-labelledby="cnasnus-tab">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">

            {{-- Card header --}}
            <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2 bg-light-danger rounded-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                        <i class="ti ti-file-text text-danger fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-0">CNASNUS</h6>
                        <p class="text-muted mb-0 fs-2">Caisse Nationale de Sécurité Sociale</p>
                    </div>
                    <span class="badge bg-light-danger text-danger fs-2 fw-semibold">
                        @if(request('cnasnus_search'))
                            {{ $cnasnusFilteredCount }} trouvé(s) sur {{ $cnasnusCount }}
                        @else
                            {{ $cnasnusCount }} entrée(s)
                        @endif
                    </span>
                </div>

                {{-- Search CNASNUS --}}
                <form method="GET" action="{{ route('files_list.index') }}" class="d-flex gap-2">
                    <input type="hidden" name="anrf_search" value="{{ request('anrf_search') }}">
                    <input type="hidden" name="active_tab" value="cnasnus">
                    <div class="input-group input-group-sm" style="width:240px;">
                        <span class="input-group-text bg-white"><i class="ti ti-search text-muted"></i></span>
                        <input type="text"
                               name="cnasnus_search"
                               value="{{ request('cnasnus_search') }}"
                               class="form-control border-start-0"
                               placeholder="Rechercher CNASNUS…">
                    </div>
                    <button type="submit" class="btn btn-danger btn-sm">Filtrer</button>
                    @if(request('cnasnus_search'))
                        <a href="{{ route('files_list.index', ['anrf_search' => request('anrf_search')]) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="ti ti-x"></i>
                        </a>
                    @endif
                </form>
            </div>

            {{-- CNASNUS Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3 text-muted fw-semibold fs-2" style="width:48px;">#</th>
                            <th class="py-3 text-muted fw-semibold fs-2">DataID</th>
                            <th class="py-3 text-muted fw-semibold fs-2">Nom complet</th>
                            <th class="py-3 text-muted fw-semibold fs-2">Nationalité</th>
                            <th class="py-3 text-muted fw-semibold fs-2">Date de naissance</th>
                            <th class="py-3 text-muted fw-semibold fs-2">Pays</th>
                            <th class="py-3 text-muted fw-semibold fs-2">Document</th>
                            <th class="py-3 text-muted fw-semibold fs-2">Date d'ajout</th>
                            <th class="pe-4 py-3 text-muted fw-semibold fs-2 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cnasnusRecords as $record)
                            <tr>
                                <td class="ps-4 text-muted fs-2">{{ $loop->iteration + ($cnasnusRecords->currentPage() - 1) * $cnasnusRecords->perPage() }}</td>
                                <td>
                                    <span class="badge bg-light-danger text-danger fw-semibold fs-2">
                                        {{ $record->dataID ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold fs-3">
                                        {{ collect([$record->firstName, $record->secondName, $record->thirdName, $record->fourthName])->filter()->join(' ') ?: ($record->originalName ?? '—') }}
                                    </div>
                                    @if($record->aliasName)
                                        <small class="text-muted fs-2">Alias: {{ $record->aliasName }}</small>
                                    @endif
                                </td>
                                <td class="fs-3">{{ $record->nationality ?? '—' }}</td>
                                <td class="fs-3">{{ $record->dateOfBirth ?? '—' }}</td>
                                <td class="fs-3">{{ $record->country ?? '—' }}</td>
                                <td>
                                    @if($record->typeOfDocument)
                                        <div class="fs-3">{{ $record->typeOfDocument }}</div>
                                        <small class="text-muted fs-2">{{ $record->documentNumber }}</small>
                                    @else
                                        <span class="text-muted fs-2">—</span>
                                    @endif
                                </td>
                                <td class="fs-2 text-muted">
                                    @php
                                        $formattedDate = '—';
                                        if ($record->dateAjout) {
                                            try {
                                                $formattedDate = \Carbon\Carbon::parse($record->dateAjout)->format('d/m/Y');
                                            } catch (\Exception $e) {
                                                $formattedDate = $record->dateAjout;
                                            }
                                        }
                                    @endphp
                                    {{ $formattedDate }}
                                </td>
                                <td class="pe-4 text-end">
                                    <button type="button"
                                            class="btn btn-white border btn-sm px-3 fw-semibold shadow-sm"
                                            style="background:#fff; color:#495057;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#cnasnusModal{{ $record->id }}">
                                        <i class="ti ti-eye me-1 text-danger"></i>Vérifier
                                    </button>
                                </td>
                            </tr>

                            {{-- Modal detail --}}
                            <div class="modal fade" id="cnasnusModal{{ $record->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="p-2 bg-light-danger rounded-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                    <i class="ti ti-file-text text-danger fs-5"></i>
                                                </div>
                                                <div>
                                                    <h5 class="modal-title fw-semibold mb-0">Détail CNASNUS</h5>
                                                    <p class="text-muted mb-0 fs-2">{{ $record->dataID ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body pt-3">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="fs-2 text-muted fw-semibold text-uppercase">Prénom(s)</label>
                                                    <p class="fs-3 fw-semibold mb-0">
                                                        {{ collect([$record->firstName, $record->secondName, $record->thirdName, $record->fourthName])->filter()->join(' ') ?: '—' }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fs-2 text-muted fw-semibold text-uppercase">Nom (alphabet d'origine)</label>
                                                    <p class="fs-3 fw-semibold mb-0">{{ $record->originalName ?? '—' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fs-2 text-muted fw-semibold text-uppercase">Alias</label>
                                                    <p class="fs-3 mb-0">{{ $record->aliasName ?? '—' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fs-2 text-muted fw-semibold text-uppercase">Nationalité</label>
                                                    <p class="fs-3 mb-0">{{ $record->nationality ?? '—' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fs-2 text-muted fw-semibold text-uppercase">Date de naissance</label>
                                                    <p class="fs-3 mb-0">{{ $record->dateOfBirth ?? '—' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fs-2 text-muted fw-semibold text-uppercase">Lieu de naissance</label>
                                                    <p class="fs-3 mb-0">{{ $record->city ?? '—' }}, {{ $record->country ?? '—' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fs-2 text-muted fw-semibold text-uppercase">Type de document</label>
                                                    <p class="fs-3 mb-0">{{ $record->typeOfDocument ?? '—' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fs-2 text-muted fw-semibold text-uppercase">Numéro de document</label>
                                                    <p class="fs-3 mb-0">{{ $record->documentNumber ?? '—' }}</p>
                                                </div>
                                                <div class="col-12">
                                                    <label class="fs-2 text-muted fw-semibold text-uppercase">Adresse</label>
                                                    <p class="fs-3 mb-0">{{ $record->adresse ?? '—' }}</p>
                                                </div>
                                                @if($record->comment1)
                                                <div class="col-12">
                                                    <label class="fs-2 text-muted fw-semibold text-uppercase">Remarques</label>
                                                    <p class="fs-3 mb-0 text-muted">{{ $record->comment1 }}</p>
                                                </div>
                                                @endif
                                                <div class="col-md-6">
                                                    <label class="fs-2 text-muted fw-semibold text-uppercase">Date d'inscription</label>
                                                    @php
                                                        $modalDateAjout = '—';
                                                        if ($record->dateAjout) {
                                                            try {
                                                                $modalDateAjout = \Carbon\Carbon::parse($record->dateAjout)->format('d/m/Y');
                                                            } catch (\Exception $e) {
                                                                $modalDateAjout = $record->dateAjout;
                                                            }
                                                        }
                                                    @endphp
                                                    <p class="fs-3 mb-0">{{ $modalDateAjout }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="ti ti-database-off fs-7 d-block mb-2"></i>
                                    <span class="fs-3">Aucune donnée CNASNUS importée</span><br>
                                    <a href="{{ route('import_files.index') }}" class="btn btn-sm btn-danger mt-3">
                                        <i class="ti ti-file-import me-1"></i>Importer maintenant
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- CNASNUS Pagination --}}
            @if($cnasnusRecords->hasPages())
                <div class="px-4 py-3 border-top d-flex justify-content-end">
                    <div class="d-flex justify-content-center mt-4">
                        {{ $cnasnusRecords->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>  
                </div>
            @endif

        </div>
    </div>

    </div>{{-- end cnasnus-pane --}}

    {{-- ════════════════════ ANRF TABLE ════════════════════ --}}
    <div class="tab-pane fade" id="anrf-pane" role="tabpanel" aria-labelledby="anrf-tab">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">

            {{-- Card header --}}
            <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2 bg-light-warning rounded-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                        <i class="ti ti-file-analytics text-warning fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-0">ANRF</h6>
                        <p class="text-muted mb-0 fs-2">Autorité Nationale de Réglementation Financière</p>
                    </div>
                    <span class="badge bg-light-warning text-warning fs-2 fw-semibold">
                        @if(request('anrf_search'))
                            {{ $anrfFilteredCount }} trouvé(s) sur {{ $anrfCount }}
                        @else
                            {{ $anrfCount }} entrée(s)
                        @endif
                    </span>
                </div>

                {{-- Search ANRF --}}
                <form method="GET" action="{{ route('files_list.index') }}" class="d-flex gap-2">
                    <input type="hidden" name="cnasnus_search" value="{{ request('cnasnus_search') }}">
                    <input type="hidden" name="active_tab" value="anrf">
                    <div class="input-group input-group-sm" style="width:240px;">
                        <span class="input-group-text bg-white"><i class="ti ti-search text-muted"></i></span>
                        <input type="text"
                               name="anrf_search"
                               value="{{ request('anrf_search') }}"
                               class="form-control border-start-0"
                               placeholder="Rechercher ANRF…">
                    </div>
                    <button type="submit" class="btn btn-warning btn-sm text-white">Filtrer</button>
                    @if(request('anrf_search'))
                        <a href="{{ route('files_list.index', ['cnasnus_search' => request('cnasnus_search')]) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="ti ti-x"></i>
                        </a>
                    @endif
                </form>
            </div>

            {{-- ANRF Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3 text-muted fw-semibold fs-2" style="width:48px;">#</th>
                            <th class="py-3 text-muted fw-semibold fs-2">Identifiant</th>
                            <th class="py-3 text-muted fw-semibold fs-2">Nom</th>
                            <th class="py-3 text-muted fw-semibold fs-2">Pays</th>
                            <th class="py-3 text-muted fw-semibold fs-2">Activité</th>
                            <th class="py-3 text-muted fw-semibold fs-2">Date d'ajout</th>
                            <th class="pe-4 py-3 text-muted fw-semibold fs-2 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anrfRecords as $record)
                            <tr>
                                <td class="ps-4 text-muted fs-2">{{ $loop->iteration + ($anrfRecords->currentPage() - 1) * $anrfRecords->perPage() }}</td>
                                <td>
                                    <span class="badge bg-light-warning text-warning fw-semibold fs-2">
                                        {{ $record->identifiant ?? '—' }}
                                    </span>
                                </td>
                                <td class="fw-semibold fs-3">{{ $record->nom ?? '—' }}</td>
                                <td class="fs-3">{{ $record->pays ?? '—' }}</td>
                                <td class="fs-3">{{ $record->activite ?? '—' }}</td>
                                <td class="fs-2 text-muted">
                                    @php
                                        $anrfDate = '—';
                                        if ($record->dateAjout) {
                                            try {
                                                $anrfDate = \Carbon\Carbon::parse($record->dateAjout)->format('d/m/Y');
                                            } catch (\Exception $e) {
                                                $anrfDate = $record->dateAjout;
                                            }
                                        }
                                    @endphp
                                    {{ $anrfDate }}
                                </td>
                                <td class="pe-4 text-end">
                                    <button type="button"
                                            class="btn btn-white border btn-sm px-3 fw-semibold shadow-sm"
                                            style="background:#fff; color:#495057;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#anrfModal{{ $record->id }}">
                                        <i class="ti ti-eye me-1 text-warning"></i>Vérifier
                                    </button>
                                </td>
                            </tr>

                            {{-- Modal detail ANRF --}}
                            <div class="modal fade" id="anrfModal{{ $record->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="p-2 bg-light-warning rounded-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                    <i class="ti ti-file-analytics text-warning fs-5"></i>
                                                </div>
                                                <div>
                                                    <h5 class="modal-title fw-semibold mb-0">Détail ANRF</h5>
                                                    <p class="text-muted mb-0 fs-2">{{ $record->identifiant ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body pt-3">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="fs-2 text-muted fw-semibold text-uppercase">Nom</label>
                                                    <p class="fs-3 fw-semibold mb-0">{{ $record->nom ?? '—' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fs-2 text-muted fw-semibold text-uppercase">Identifiant</label>
                                                    <p class="fs-3 mb-0">{{ $record->identifiant ?? '—' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fs-2 text-muted fw-semibold text-uppercase">Pays</label>
                                                    <p class="fs-3 mb-0">{{ $record->pays ?? '—' }}</p>
                                                </div>
                                                <div class="col-12">
                                                    <label class="fs-2 text-muted fw-semibold text-uppercase">Activité</label>
                                                    <p class="fs-3 mb-0">{{ $record->activite ?? '—' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fs-2 text-muted fw-semibold text-uppercase">Date d'ajout</label>
                                                    <p class="fs-3 mb-0">
                                                        @php
                                                            $anrfModalDate = '—';
                                                            if ($record->dateAjout) {
                                                                try {
                                                                    $anrfModalDate = \Carbon\Carbon::parse($record->dateAjout)->format('d/m/Y');
                                                                } catch (\Exception $e) {
                                                                    $anrfModalDate = $record->dateAjout;
                                                                }
                                                            }
                                                        @endphp
                                                        {{ $anrfModalDate }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="ti ti-database-off fs-7 d-block mb-2"></i>
                                    <span class="fs-3">Aucune donnée ANRF importée</span><br>
                                    <a href="{{ route('import_files.index') }}" class="btn btn-sm btn-warning text-white mt-3">
                                        <i class="ti ti-file-import me-1"></i>Importer maintenant
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ANRF Pagination --}}
            @if($anrfRecords->hasPages())
                <div class="px-4 py-3 border-top d-flex justify-content-end">
                    <div class="d-flex justify-content-center mt-4">
                        {{ $anrfRecords->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif

        </div>
    </div>

    </div>{{-- end anrf-pane --}}

    </div>{{-- end tab-content --}}

</div>{{-- end container-fluid --}}

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const params   = new URLSearchParams(window.location.search);
        const activeTab = params.get('active_tab');

        if (activeTab === 'anrf') {
            const trigger = document.getElementById('anrf-tab');
            if (trigger) {
                trigger.click();
            }
        }
    });
</script>
@endpush

@endsection