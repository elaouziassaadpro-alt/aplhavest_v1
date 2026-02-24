<div>
    
  <x-notification />

    <div class="text-end">
        <button type="button" wire:click="toggleEdit" class="btn mb-1 {{ $editing ? 'btn-secondary' : 'btn-light-info' }} btn-circle btn-sm d-inline-flex align-items-center justify-content-center" title="{{ $editing ? 'Annuler' : 'Modifier cette section' }}">
            <i class="fs-5 ti {{ $editing ? 'ti-x' : 'ti-pencil' }}"></i>
        </button>
    </div>

    <form wire:submit.prevent="update">
        <!-- ROW 1 -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Nom / Raison sociale</label>
                <input type="text" class="form-control" wire:model="raisonSocial" @disabled(!$editing)>
            </div>
            <div class="col-md-3 mb-3">
                <label>Forme juridique</label>
                <x-searchable-select :options="$formejuridiques" wireModel="FormeJuridique" :selected="$FormeJuridique" :disabled="!$editing" />
            </div>
            <div class="col-md-3 mb-3">
                <label>Date d'immatriculation</label>
                <input type="date" class="form-control" wire:model="dateImmatriculation" @disabled(!$editing)>
            </div>
        </div>

        <!-- ROW 2: ICE, Statut, RC, IF -->
        <div class="row">
            <!-- ICE -->
            <div class="col-md-2 mb-3">
                <label>ICE</label>
                <input type="text" class="form-control" wire:model="ice" @disabled(!$editing)>
            </div>
            <div class="col-md-1 mb-3 d-flex align-items-end">
                @if($editing)
                <label class="btn btn-primary w-100 mb-0">
                    <i class="ti ti-upload"></i> ICE
                    <input type="file" wire:model="ice_file" hidden accept=".pdf,.jpg,.png">
                </label>
                @endif
                @if($existing_ice_file)
                    <a href="{{ asset('storage/'.$existing_ice_file) }}" class="btn btn-sm btn-outline-primary" download>
                        <i class="ti ti-download"></i> Télécharger
                    </a>
                @endif
            </div>

            <!-- Statut -->
            <div class="col-md-1 mb-3 d-flex align-items-end">
                @if($editing)
                <label class="btn btn-primary w-100 mb-0">
                    <i class="ti ti-upload"></i> Statut
                    <input type="file" wire:model="status_file" hidden accept=".pdf,.jpg,.png">
                </label>
                @endif
                @if($existing_status_file)
                    <a href="{{ asset('storage/'.$existing_status_file) }}" class="btn btn-sm btn-outline-primary" download>
                        <i class="ti ti-download"></i> Télécharger
                    </a>
                @endif
            </div>

            <!-- RC -->
            <div class="col-md-3 mb-3">
                <label>RC</label>
                <input type="text" class="form-control" wire:model="rc_input" @disabled(!$editing)>
            </div>
            <div class="col-md-1 mb-3 d-flex align-items-end">
                @if($editing)
                <label class="btn btn-primary w-100 mb-0">
                    <i class="ti ti-upload"></i> RC
                    <input type="file" wire:model="rc_file" hidden accept=".pdf,.jpg,.png">
                </label>
                @endif
                @if($existing_rc_file)
                    <a href="{{ asset('storage/'.$existing_rc_file) }}" class="btn btn-sm btn-outline-primary" download>
                        <i class="ti ti-download"></i> Télécharger
                    </a>
                @endif
            </div>

            <!-- IF -->
            <div class="col-md-4 mb-3">
                <label>IF</label>
                <input type="text" class="form-control" wire:model="ifiscal" @disabled(!$editing)>
            </div>
        </div>

        <!-- ROW 3: Siege, Pays Activite, Pays Residence -->
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Siège social</label>
                <input type="text" class="form-control" wire:model="siegeSocial" @disabled(!$editing)>
            </div>
            <div class="col-md-4 mb-3">
                <label>Lieu d'activité</label>
                <x-searchable-select :options="$pays" wireModel="paysActivite" :selected="$paysActivite" :disabled="!$editing" />
            </div>
            <div class="col-md-4 mb-3">
                <label>Pays de résidence fiscale</label>
                <x-searchable-select :options="$pays" wireModel="paysResidence" :selected="$paysResidence" :disabled="!$editing" />
            </div>
        </div>

        <!-- ROW 4: Regulation, Tel, Email, Web -->
        <div class="row align-items-end">
            <div class="col-md-4 mb-3">
                <label>Autorité de régulation</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" wire:model.live="regule" @disabled(!$editing)>
                    <label class="form-check-label">{{ $regule ? 'Oui' : 'Non' }}</label>
                </div>
                @if($regule)
                <div class="mt-2">
                    <input type="text" class="form-control" wire:model="nomRegulateur" placeholder="Nom de l’autorité" @disabled(!$editing)>
                </div>
                @endif
            </div>
            <div class="col-md-3 mb-3">
                <label>Téléphone</label>
                <input type="tel" class="form-control" wire:model="telephone" @disabled(!$editing)>
            </div>
            <div class="col-md-3 mb-3">
                <label>E-mail</label>
                <input type="email" class="form-control" wire:model="email" @disabled(!$editing)>
            </div>
            <div class="col-md-2 mb-3">
                <label>Site web</label>
                <input type="text" class="form-control" wire:model="siteweb" @disabled(!$editing)>
            </div>
        </div>

        <!-- ROW 5: Societe Gestion Files -->
            <div class="col-md-2 mb-3">
                <label>Société gestion</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" wire:model.live="societe_gestion" @disabled(!$editing)>
                    <label class="form-check-label">{{ $societe_gestion ? 'Oui' : 'Non' }}</label>
                </div>
            </div>
            
            @if($societe_gestion)
                <div class="col-12 mt-3">
                     <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Liste des OPC</h5>
                        @if($editing)
                        <button type="button" class="btn btn-light-info btn-sm" wire:click="addOpcFile">
                            <i class="ti ti-plus"></i> Ajouter un OPC
                        </button>
                        @endif
                    </div>

                    @foreach($opcFiles as $index => $opcFile)
                        <div class="card mb-3 border">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                <span class="fw-bold">OPC #{{ $index + 1 }}</span>
                                @if($editing)
                                <button type="button" wire:click="removeOpcFile({{ $index }})" class="btn btn-danger btn-sm">
                                    <i class="ti ti-trash"></i>
                                </button>
                                @endif
                            </div>
                            <div class="card-body py-3">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Nom OPC</label>
                                        <input type="text" class="form-control" wire:model="opcFiles.{{ $index }}.opc" placeholder="Nom OPC" @disabled(!$editing)>
                                    </div>
                                    
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Agrément</label>
                                        @if($editing)
                                        <input type="file" class="form-control mb-2" wire:model="opcFiles.{{ $index }}.incrument" accept=".pdf,.jpg,.png">
                                        @endif
                                        @if(!empty($opcFiles[$index]['existing_incrument']))
                                            <a href="{{ asset('storage/'.$opcFiles[$index]['existing_incrument']) }}" class="btn btn-sm btn-outline-primary" download>
                                                <i class="ti ti-download"></i> Télécharger
                                            </a>
                                        @endif
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">NI</label>
                                        @if($editing)
                                        <input type="file" class="form-control mb-2" wire:model="opcFiles.{{ $index }}.ni" accept=".pdf,.jpg,.png">
                                        @endif
                                        @if(!empty($opcFiles[$index]['existing_ni']))
                                            <a href="{{ asset('storage/'.$opcFiles[$index]['existing_ni']) }}" class="btn btn-sm btn-outline-primary" download>
                                                <i class="ti ti-download"></i> Télécharger
                                            </a>
                                        @endif
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">FS</label>
                                        @if($editing)
                                        <input type="file" class="form-control mb-2" wire:model="opcFiles.{{ $index }}.fs" accept=".pdf,.jpg,.png">
                                        @endif
                                         @if(!empty($opcFiles[$index]['existing_fs']))
                                            <a href="{{ asset('storage/'.$opcFiles[$index]['existing_fs']) }}" class="btn btn-sm btn-outline-primary" download>
                                                <i class="ti ti-download"></i> Télécharger
                                            </a>
                                        @endif
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">RG</label>
                                        @if($editing)
                                        <input type="file" class="form-control mb-2" wire:model="opcFiles.{{ $index }}.rg" accept=".pdf,.jpg,.png">
                                        @endif
                                         @if(!empty($opcFiles[$index]['existing_rg']))
                                            <a href="{{ asset('storage/'.$opcFiles[$index]['existing_rg']) }}" class="btn btn-sm btn-outline-primary" download>
                                                <i class="ti ti-download"></i> Télécharger
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        <!-- CONTACTS -->
        <div class="mt-4">
            <h5>Contacts</h5>
            @foreach($contacts as $index => $contact)
                <div class="row align-items-end mb-2">
                    <div class="col-md-2 mb-3">
                        <label>Nom</label>
                        <input type="text" class="form-control" wire:model="contacts.{{ $index }}.nom" @disabled(!$editing)>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Prénom</label>
                        <input type="text" class="form-control" wire:model="contacts.{{ $index }}.prenom" @disabled(!$editing)>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" wire:model="contacts.{{ $index }}.email" @disabled(!$editing)>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Fonction</label>
                        <input type="text" class="form-control" wire:model="contacts.{{ $index }}.fonction" @disabled(!$editing)>
                    </div>
                     <div class="col-md-2 mb-3">
                        <label>Téléphone</label>
                        <input type="text" class="form-control" wire:model="contacts.{{ $index }}.telephone" @disabled(!$editing)>
                    </div>
                    @if($editing)
                    <div class="col-md-1 mb-3">
                        <button type="button" class="btn btn-danger btn-sm" wire:click="removeContact({{ $index }})"><i class="ti ti-trash"></i></button>
                    </div>
                    @endif
                </div>
            @endforeach
            @if($editing)
                <button type="button" class="btn btn-light-info btn-sm mb-2" wire:click="addContact">Ajouter un contact</button>
            @endif
        </div>

        @if($editing)
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
                        <h5 class="modal-title">Analyse du risque - Raison Sociale</h5>
                        <button type="button" class="btn-close" wire:click="closeRiskModal"></button>
                    </div>
                    <div class="modal-body">
                        @php
                            $risk = $analysisResult['risk'] ?? [];
                            $isInterdit = $analysisResult['is_interdit'] ?? false;
                            $isHighRisk = $analysisResult['is_high_risk'] ?? false;
                            $isMediumRisk = $analysisResult['is_medium_risk'] ?? false;
                            $isLowRisk = $analysisResult['is_low_risk'] ?? false;
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
                                                ($isLowRisk ? 'table-success' : ''))) }}">
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
                                            <div>

                                            </div>
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
                        <h5 class="modal-title">Détails de la correspondance ({{ $table_match }})</h5>
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
