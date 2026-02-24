<div>
    <x-breadcrumb-header 
        activePage="Nouvel Établissement" 
    />
    <x-notification />

    <div class="max-w-7xl mx-auto bg-white cardetablissement shadow-sm border border-gray-100 mt-4">
        <div class="px-6 py-4 border-b bg-gray-50 rounded-top">
            <h2 class="text-xl font-semibold text-gray-800">Informations Générales</h2>
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

            <!-- ===================== ROW 1 ===================== -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Raison sociale <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" wire:model="raisonSocial" placeholder="Ex: Société ABC SARL">
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Capital social <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" wire:model="capitalSocialPrimaire" placeholder="Ex: 100000">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Forme juridique</label>
                    <x-searchable-select :options="$formejuridiques" wireModel="FormeJuridique" :selected="$FormeJuridique" :disabled="false" />
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Date d'immatriculation</label>
                    <input type="date" class="form-control" wire:model="dateImmatriculation">
                </div>
            </div>

            <!-- ===================== ROW 2 ===================== -->
            <div class="row">
                <div class="col-md-2 mb-3">
                    <label class="form-label">ICE</label>
                    <input type="text" class="form-control" wire:model="ice" placeholder="Identifiant Commun">
                </div>
                <div class="col-md-1 mb-3 d-flex align-items-end">
                    <label class="btn btn-primary w-100 mb-0">
                        <i class="ti ti-upload"></i> ICE
                        <input type="file" wire:model="ice_file" hidden accept=".pdf,.jpg,.png">
                    </label>
                </div>

                <div class="col-md-1 mb-3 d-flex align-items-end">
                    <label class="btn btn-primary w-100 mb-0">
                        <i class="ti ti-upload"></i> Statut
                        <input type="file" wire:model="status_file" hidden accept=".pdf,.jpg,.png">
                    </label>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">RC<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" wire:model="rc_input" placeholder="Registre de commerce">
                </div>
                <div class="col-md-1 mb-3 d-flex align-items-end">
                    <label class="btn btn-primary w-100 mb-0">
                        <i class="ti ti-upload"></i> RC
                        <input type="file" wire:model="rc_file" hidden accept=".pdf,.jpg,.png">
                    </label>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">IF</label>
                    <input type="text" class="form-control" wire:model="ifiscal" placeholder="Identifiant fiscal">
                </div>
            </div>

            <!-- ===================== ROW 3 ===================== -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Siège social</label>
                    <input type="text" class="form-control" wire:model="siegeSocial" placeholder="Adresse complète">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Lieu d'activité</label>
                    <x-searchable-select :options="$pays" wireModel="paysActivite" :selected="$paysActivite" :disabled="false" />
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Pays de résidence fiscale <span class="text-danger">*</span></label>
                    <x-searchable-select :options="$pays" wireModel="paysResidence" :selected="$paysResidence" :disabled="false" />
                </div>
            </div>

            <!-- ===================== ROW 4 ===================== -->
            <div class="row align-items-end">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Autorité de régulation</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" wire:model.live="regule" id="regule_check">
                        <label class="form-check-label" for="regule_check">
                            {{ $regule ? 'Oui' : 'Non' }}
                        </label>
                    </div>
                    @if($regule)
                        <div class="mt-2">
                            <input type="text" class="form-control" wire:model="nomRegulateur" placeholder="Nom de l'autorité">
                        </div>
                    @endif
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="tel" class="form-control" wire:model="telephone" placeholder="+212 6XX XXX XXX">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="email" class="form-control" wire:model="email" placeholder="contact@entreprise.com">
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Site web</label>
                    <input type="text" class="form-control" wire:model="siteweb" placeholder="https://www.site.com">
                </div>
            </div>

            <!-- ===================== ROW 5: Société de gestion ===================== -->
            <div class="row align-items-end">
                <div class="col-md-2 mb-3">
                    <label class="form-label">Société gestion</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" wire:model.live="societe_gestion" id="societe_gestion_check">
                        <label class="form-check-label" for="societe_gestion_check">
                            {{ $societe_gestion ? 'Oui' : 'Non' }}
                        </label>
                    </div>
                </div>
            </div> <!-- End of ROW 5 -->

            <!-- OPC Files Section -->
            @if($societe_gestion)
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Liste des OPC</h5>
                        <button type="button" class="btn btn-light-info btn-sm" wire:click="addOpcFile">
                            <i class="ti ti-plus"></i> Ajouter un OPC
                        </button>
                    </div>

                    @foreach($opcFiles as $index => $opcFile)
                        <div class="card mb-3 border">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                <span class="fw-bold">OPC #{{ $index + 1 }}</span>
                                <button type="button" wire:click="removeOpcFile({{ $index }})" class="btn btn-danger btn-sm">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                            <div class="card-body py-3">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Nom OPC</label>
                                        <input type="text" class="form-control" wire:model="opcFiles.{{ $index }}.opc" placeholder="Nom OPC">
                                    </div>
                                    
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Agrément</label>
                                        <input type="file" class="form-control" wire:model="opcFiles.{{ $index }}.incrument" accept=".pdf,.jpg,.png">
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">NI</label>
                                        <input type="file" class="form-control" wire:model="opcFiles.{{ $index }}.ni" accept=".pdf,.jpg,.png">
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">FS</label>
                                        <input type="file" class="form-control" wire:model="opcFiles.{{ $index }}.fs" accept=".pdf,.jpg,.png">
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">RG</label>
                                        <input type="file" class="form-control" wire:model="opcFiles.{{ $index }}.rg" accept=".pdf,.jpg,.png">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- ===================== CONTACTS ===================== -->
            <div class="mt-4">
                <h5>Contacts</h5>
                <button type="button" class="btn btn-light-info btn-sm mb-2" wire:click="addContact">
                    <i class="ti ti-plus me-1"></i> Ajouter un contact
                </button>

                @foreach($contacts as $index => $contact)
                    <div class="card mb-2 border">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                            <span class="fw-bold">Contact #{{ $index + 1 }}</span>
                            @if(count($contacts) >= 1)
                                <button type="button" wire:click="removeContact({{ $index }})" class="btn btn-danger btn-sm">
                                    <i class="ti ti-trash"></i>
                                </button>
                            @endif
                        </div>
                        <div class="card-body py-2">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <label class="form-label">Nom</label>
                                    <input type="text" class="form-control" wire:model="contacts.{{ $index }}.nom" placeholder="Nom">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Prénom</label>
                                    <input type="text" class="form-control" wire:model="contacts.{{ $index }}.prenom" placeholder="Prénom">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Fonction</label>
                                    <input type="text" class="form-control" wire:model="contacts.{{ $index }}.fonction" placeholder="Fonction">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" class="form-control" wire:model="contacts.{{ $index }}.telephone" placeholder="Téléphone">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" wire:model="contacts.{{ $index }}.email" placeholder="Email">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    <!-- ===================== BUTTONS ===================== -->
    <div class="text-center mt-4 d-flex flex-column align-items-center gap-2">
        <button type="button"
                class="btn btn-light-warning"
                wire:click="checkRisque"
                wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="checkRisque">
                <i class="ti ti-alert-triangle"></i> Vérifier le risque (Raison Sociale)
            </span>
            <span wire:loading wire:target="checkRisque">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Vérification...
            </span>
        </button>
    </div>

    <!-- ===================== RISK MODAL ===================== -->
    @if($showRiskModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Analyse du risque - Raison Sociale</h5>
                        @if(session()->has('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        <button type="button" class="btn-close" wire:click="closeRiskModal"></button>
                    </div>
                    <div class="modal-body">
                        @php
                            $risk = $analysisResult['risk'];
                            $isInterdit = $analysisResult['is_interdit'];
                            $isHighRisk = $analysisResult['is_high_risk'];
                            $isMediumRisk = $analysisResult['is_medium_risk'] ?? false;
                            $isLowRisk = $analysisResult['is_low_risk'] ?? false;
                            $isFalsePositive = $analysisResult['is_false_positive'] ?? false;
                        @endphp

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
                                    <tr class="{{ $isInterdit ? 'table-danger' : 
                                                ($isHighRisk ? 'table-warning' : 
                                                ($isMediumRisk ? 'table-info' : 
                                                ($isLowRisk ? 'table-success' :
                                                ($isFalsePositive ? 'table-success' : '')))) }}">
                                        <td>{{ $raisonSocial }}</td>
                                        <td>{{ $note }}</td>
                                        <td>
                                            <span class="badge {{ $percentage >= 70 ? 'bg-danger' : 'bg-success' }}">
                                                {{ $percentage }}%
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
                                                <button type="button" class="btn btn-danger btn-sm" wire:click="markAsFalsePositive"
                                                    wire:confirm="Voulez-vous marquer ce résultat comme faux positif ?">
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

                        <div class="mt-3 d-flex gap-2">
                            <button type="button" class="btn btn-primary btn-sm" wire:click="save" wire:loading.attr="disabled">
                                <i class="ti ti-check"></i> Valider & Enregistrer
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

