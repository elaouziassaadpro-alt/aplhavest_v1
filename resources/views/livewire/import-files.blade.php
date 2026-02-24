<div>
    {{-- ── Success / Error flash messages ────────────────────────── --}}
    @if($successMessage)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-circle-check me-2"></i>{{ $successMessage }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errorMessage)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-circle me-2"></i>{{ $errorMessage }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-triangle me-2"></i>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- ============================= CNASNUS ============================= --}}
        <div class="col-xl-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">

                    {{-- Card header --}}
                    <div class="d-flex align-items-center mb-4">
                        <div class="p-10 bg-light-primary rounded-2 me-3 d-flex align-items-center justify-content-center" style="width:52px;height:52px;">
                            <i class="ti ti-file-text text-primary fs-6"></i>
                        </div>
                        <div>
                            <h5 class="fw-semibold mb-0">Fichier CNASNUS</h5>
                            <p class="text-muted mb-0 fs-3">Caisse Nationale de Sécurité Sociale</p>
                        </div>
                        <span class="badge bg-light-primary text-primary ms-auto fs-2 fw-semibold">CNASNUS</span>
                    </div>

                    {{-- Format badges --}}
                    <div class="d-flex gap-2 mb-4">
                        <span class="badge bg-light-success text-success fw-semibold px-3 py-2"><i class="ti ti-file-code me-1"></i>XML</span>
                        <span class="badge bg-light-warning text-warning fw-semibold px-3 py-2"><i class="ti ti-world me-1"></i>HTML</span>
                        <span class="badge bg-light-danger text-danger fw-semibold px-3 py-2"><i class="ti ti-file-type-pdf me-1"></i>PDF</span>
                    </div>

                    {{-- Drop zone --}}
                    <div class="upload-zone border border-2 border-dashed rounded-2 text-center p-5 mb-3"
                         id="dropzone-cnasnus"
                         style="border-color:#1e88e5!important; background:#f0f7ff; cursor:pointer; transition:background .2s;">
                        <i class="ti ti-cloud-upload fs-8 text-primary mb-2 d-block"></i>
                        <p class="fw-semibold text-primary mb-1">Glisser-déposer le fichier ici</p>
                        <p class="text-muted fs-3 mb-3">ou cliquez pour sélectionner</p>

                        {{-- Livewire file input --}}
                        <input type="file"
                               wire:model="cnasnusFile"
                               id="cnasnus_file"
                               accept=".xml,.html,.htm,.pdf"
                               class="d-none">

                        <button type="button"
                                class="btn btn-outline-primary btn-sm px-4"
                                onclick="document.getElementById('cnasnus_file').click()">
                            <i class="ti ti-folder-open me-1"></i>Parcourir
                        </button>

                        {{-- Live file name preview --}}
                        @if($cnasnusFile)
                            <div class="mt-3 fs-3">
                                <i class="ti ti-file-check text-success me-1"></i>
                                <span class="text-success fw-semibold">{{ $cnasnusFile->getClientOriginalName() }}</span>
                                <small class="text-muted ms-2">({{ round($cnasnusFile->getSize() / 1024, 1) }} Ko)</small>
                            </div>
                        @endif

                        {{-- Upload progress --}}
                        <div wire:loading wire:target="cnasnusFile" class="mt-2">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            <small class="text-muted ms-1">Chargement…</small>
                        </div>
                    </div>

                    {{-- Description (optional) --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold fs-3">
                            Description <span class="text-muted fw-normal">(optionnel)</span>
                        </label>
                        <input type="text"
                               wire:model="cnasnusDescription"
                               class="form-control form-control-sm"
                               placeholder="Ex: Rapport mensuel CNASNUS - Janvier 2026">
                    </div>

                    {{-- Submit --}}
                    <button type="button"
                            wire:click="importCnasnus"
                            wire:loading.attr="disabled"
                            class="btn btn-primary w-100">
                        <span wire:loading.remove wire:target="importCnasnus">
                            <i class="ti ti-upload me-2"></i>Importer le fichier CNASNUS
                        </span>
                        <span wire:loading wire:target="importCnasnus">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Importation en cours…
                        </span>
                    </button>

                </div>
            </div>
        </div>

        {{-- ============================= ANRF ============================= --}}
        <div class="col-xl-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">

                    {{-- Card header --}}
                    <div class="d-flex align-items-center mb-4">
                        <div class="p-10 bg-light-warning rounded-2 me-3 d-flex align-items-center justify-content-center" style="width:52px;height:52px;">
                            <i class="ti ti-file-analytics text-warning fs-6"></i>
                        </div>
                        <div>
                            <h5 class="fw-semibold mb-0">Fichier ANRF</h5>
                            <p class="text-muted mb-0 fs-3">Autorité Nationale de Réglementation</p>
                        </div>
                        <span class="badge bg-light-warning text-warning ms-auto fs-2 fw-semibold">ANRF</span>
                    </div>

                    {{-- Format badges --}}
                    <div class="d-flex gap-2 mb-4">
                        <span class="badge bg-light-success text-success fw-semibold px-3 py-2"><i class="ti ti-file-code me-1"></i>XML</span>
                        <span class="badge bg-light-warning text-warning fw-semibold px-3 py-2"><i class="ti ti-world me-1"></i>HTML</span>
                        <span class="badge bg-light-danger text-danger fw-semibold px-3 py-2"><i class="ti ti-file-type-pdf me-1"></i>PDF</span>
                        <span class="badge bg-light-info text-info fw-semibold px-3 py-2"><i class="ti ti-file-spreadsheet me-1"></i>Excel</span>
                    </div>

                    {{-- Drop zone --}}
                    <div class="upload-zone border border-2 border-dashed rounded-2 text-center p-5 mb-3"
                         id="dropzone-anrf"
                         style="border-color:#ffb22b!important; background:#fffbf0; cursor:pointer; transition:background .2s;">
                        <i class="ti ti-cloud-upload fs-8 text-warning mb-2 d-block"></i>
                        <p class="fw-semibold text-warning mb-1">Glisser-déposer le fichier ici</p>
                        <p class="text-muted fs-3 mb-3">ou cliquez pour sélectionner</p>

                        {{-- Livewire file input --}}
                        <input type="file"
                               wire:model="anrfFile"
                               id="anrf_file"
                               accept=".xml,.html,.htm,.pdf,.xlsx,.xls"
                               class="d-none">

                        <button type="button"
                                class="btn btn-outline-warning btn-sm px-4"
                                onclick="document.getElementById('anrf_file').click()">
                            <i class="ti ti-folder-open me-1"></i>Parcourir
                        </button>

                        {{-- Live file name preview --}}
                        @if($anrfFile)
                            <div class="mt-3 fs-3">
                                <i class="ti ti-file-check text-success me-1"></i>
                                <span class="text-success fw-semibold">{{ $anrfFile->getClientOriginalName() }}</span>
                                <small class="text-muted ms-2">({{ round($anrfFile->getSize() / 1024, 1) }} Ko)</small>
                            </div>
                        @endif

                        {{-- Upload progress --}}
                        <div wire:loading wire:target="anrfFile" class="mt-2">
                            <div class="spinner-border spinner-border-sm text-warning" role="status"></div>
                            <small class="text-muted ms-1">Chargement…</small>
                        </div>
                    </div>

                    {{-- Description (optional) --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold fs-3">
                            Description <span class="text-muted fw-normal">(optionnel)</span>
                        </label>
                        <input type="text"
                               wire:model="anrfDescription"
                               class="form-control form-control-sm"
                               placeholder="Ex: Rapport ANRF trimestriel - T1 2026">
                    </div>

                    {{-- Submit --}}
                    <button type="button"
                            wire:click="importAnrf"
                            wire:loading.attr="disabled"
                            class="btn btn-warning text-white w-100">
                        <span wire:loading.remove wire:target="importAnrf">
                            <i class="ti ti-upload me-2"></i>Importer le fichier ANRF
                        </span>
                        <span wire:loading wire:target="importAnrf">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Importation en cours…
                        </span>
                    </button>

                </div>
            </div>
        </div>

    </div>{{-- end row --}}

    {{-- ============================= Info / Help Section ============================= --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3"><i class="ti ti-info-circle text-info me-2"></i>Informations sur les formats acceptés</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="d-flex align-items-start p-3 bg-light-success rounded-2">
                                <div class="me-3 mt-1"><i class="ti ti-file-code text-success fs-6"></i></div>
                                <div>
                                    <h6 class="fw-semibold mb-1 text-success">XML</h6>
                                    <p class="fs-2 text-muted mb-0">Fichiers structurés. <code>.xml</code>. Taille max : <strong>100 Mo</strong>.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-start p-3 bg-light-warning rounded-2">
                                <div class="me-3 mt-1"><i class="ti ti-world text-warning fs-6"></i></div>
                                <div>
                                    <h6 class="fw-semibold mb-1 text-warning">HTML</h6>
                                    <p class="fs-2 text-muted mb-0">Format web. <code>.html</code> / <code>.htm</code>. Taille max : <strong>100 Mo</strong>.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-start p-3 bg-light-danger rounded-2">
                                <div class="me-3 mt-1"><i class="ti ti-file-type-pdf text-danger fs-6"></i></div>
                                <div>
                                    <h6 class="fw-semibold mb-1 text-danger">PDF</h6>
                                    <p class="fs-2 text-muted mb-0">Documents officiels. Extension <code>.pdf</code>. Taille max : <strong>100 Mo</strong>.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-start p-3 bg-light-info rounded-2">
                                <div class="me-3 mt-1"><i class="ti ti-file-spreadsheet text-info fs-6"></i></div>
                                <div>
                                    <h6 class="fw-semibold mb-1 text-info">EXCEL</h6>
                                    <p class="fs-2 text-muted mb-0">Listes Excel. Extension <code>.xlsx</code> ou <code>.xls</code>. Taille max : <strong>100 Mo</strong>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Drag-and-drop JS (enhances the dropzone click area) --}}
    @push('scripts')
    <script>
        function setupDropzone(dropzoneId, inputId, hoverBg, defaultBg) {
            const dropzone = document.getElementById(dropzoneId);
            if (!dropzone) return;
            const input = document.getElementById(inputId);

            dropzone.addEventListener('click', function (e) {
                if (e.target.tagName !== 'BUTTON' && e.target.tagName !== 'INPUT') {
                    input.click();
                }
            });
            dropzone.addEventListener('dragover', function (e) {
                e.preventDefault();
                dropzone.style.background = hoverBg;
            });
            dropzone.addEventListener('dragleave', function () {
                dropzone.style.background = defaultBg;
            });
            dropzone.addEventListener('drop', function (e) {
                e.preventDefault();
                dropzone.style.background = defaultBg;
                const files = e.dataTransfer.files;
                if (files && files[0]) {
                    const dt = new DataTransfer();
                    dt.items.add(files[0]);
                    input.files = dt.files;
                    // Trigger Livewire's file detection
                    input.dispatchEvent(new Event('change'));
                }
            });
        }

        document.addEventListener('livewire:navigated', () => {
            setupDropzone('dropzone-cnasnus', 'cnasnus_file', '#d0e8ff', '#f0f7ff');
            setupDropzone('dropzone-anrf',    'anrf_file',    '#fff3d0', '#fffbf0');
        });
        setupDropzone('dropzone-cnasnus', 'cnasnus_file', '#d0e8ff', '#f0f7ff');
        setupDropzone('dropzone-anrf',    'anrf_file',    '#fff3d0', '#fffbf0');
    </script>
    @endpush

</div>
