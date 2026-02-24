<div class="container-fluid">
    <!-- Header Section with Establishment Info -->
    <div class="card text-white shadow-lg mb-4" style="background: linear-gradient(135deg, #1A3A6C 0%, #01A9E4 100%);">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2 text-white"><i class="ti ti-building me-2"></i>{{ $etablissement->name }}</h2>
                    <p class="mb-1 text-white"><strong>ICE:</strong> {{ $etablissement->infoGeneral->ice ?? 'N/A' }}</p>
                    <p class="mb-1 text-white"><strong>Secteur:</strong> {{ $etablissement->secteur->libelle ?? 'N/A' }}</p>
                    <p class="mb-0 text-white"><strong>Pays:</strong> {{ $etablissement->infoGeneral->paysActiviteInfo->libelle ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 text-end">
                    @if($isCompleted && isset($facteursCalcul['global']))
                        @php
                        $color = $facteursCalcul['global']['scoring'] === 'HR'
                            ? 'text-danger'
                            : ($facteursCalcul['global']['scoring'] === 'MR'
                                ? 'text-warning'
                                : 'text-success');
                        $bg = $facteursCalcul['global']['scoring'] === 'HR'
                            ? 'bg-danger'
                            : ($facteursCalcul['global']['scoring'] === 'MR'
                                ? 'bg-warning'
                                : 'bg-success');
                        @endphp
                        <div class="rounded p-4 shadow {{ $bg }}">
                            <h3 class="mb-1 text-white">Score Final</h3>
                            <h1 class="display-3 fw-bold mb-2 text-white">
                                {{ $facteursCalcul['global']['scoring'] }}
                            </h1>
                            <p class="mb-0">
                                <strong class="text-white">Note :</strong>
                                <span class="text-white">{{ $facteursCalcul['global']['note_totale'] }}</span>
                            </p>
                        </div>
                    @else
                        <div class="bg-light-warning p-4 rounded text-center">
                            <i class="ti ti-alert-triangle fs-7 text-warning mb-2"></i>
                            <h5 class="text-dark">Évaluation Incomplète</h5>
                            <p class="text-muted small mb-0">Veuillez compléter toutes les étapes pour générer le score.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($isCompleted && isset($facteursCalcul['global']))
    <!-- Risk Factors Overview Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm h-100" style="border-left: 4px solid #1A3A6C;">
                <div class="card-body text-center">
                    <i class="ti ti-user-circle fs-1 mb-2" style="color: #1A3A6C;"></i>
                    <h6 class="text-muted">Risque Client</h6>
                    <h3 class="fw-bold">{{ $facteursCalcul['client']['total_client'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100" style="border-left: 4px solid #01A9E4;">
                <div class="card-body text-center">
                    <i class="ti ti-users fs-1 mb-2" style="color: #01A9E4;"></i>
                    <h6 class="text-muted">Bénéficiaires</h6>
                    <h3 class="fw-bold">{{ $facteursCalcul['beneficiaire_effectif']['note'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100" style="border-left: 4px solid #1A3A6C;">
                <div class="card-body text-center">
                    <i class="ti ti-briefcase fs-1 mb-2" style="color: #1A3A6C;"></i>
                    <h6 class="text-muted">Administrateurs</h6>
                    <h3 class="fw-bold">{{ $facteursCalcul['administrateurs']['note'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100" style="border-left: 4px solid #01A9E4;">
                <div class="card-body text-center">
                    <i class="ti ti-chart-pie fs-1 mb-2" style="color: #01A9E4;"></i>
                    <h6 class="text-muted">Actionnaires</h6>
                    <h3 class="fw-bold">{{ $facteursCalcul['actionnaires']['note'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Calculation Sections -->
    <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
            <!-- Client Risk Details -->
            <div class="card shadow-sm mb-4">
                <div class="card-header text-white" style="background-color: #1A3A6C;">
                    <h5 class="mb-0 text-white"><i class="ti ti-shield-check me-2"></i>Détails du Risque Client</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td class="fw-semibold"><i class="ti ti-tag me-2 text-primary"></i>Segment</td>
                                <td>{{ $etablissement->typologieClient?->secteur?->libelle ?? 'N/A' }}</td>
                                <td>{{ $facteursCalcul['client']['segment'] }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold"><i class="ti ti-map-pin me-2 text-primary"></i>Pays de résidence</td>
                                <td>{{ $etablissement->infoGenerales?->paysresidence?->libelle ?? 'N/A' }}</td>
                                <td>{{ $facteursCalcul['client']['pays_residence'] }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold"><i class="ti ti-building-factory me-2 text-primary"></i>Secteur</td>
                                <td>{{ $etablissement->typologieClient?->segment_get?->libelle ?? 'N/A' }}</td>
                                <td>{{ $facteursCalcul['client']['secteur'] }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold"><i class="ti ti-license me-2 text-primary"></i>Activité régulée</td>
                                <td>{{ $etablissement->typologieClient?->nomRegulateur ?? 'N/A' }}</td>
                                <td>{{ $facteursCalcul['client']['activite_regulee'] }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold"><i class="ti ti-world me-2 text-primary"></i>Pays activité étranger</td>
                                <td>{{ $etablissement->typologieClient?->paysEtrangerInfo?->libelle ?? 'N/A' }}</td>
                                <td>{{ $facteursCalcul['client']['pays_activite_etranger'] }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold"><i class="ti ti-cash me-2 text-primary"></i>Origine des fonds</td>
                                <td>{{ $etablissement->SituationFinanciere?->origineFonds ??  'N/A' }}</td>
                                <td>{{ $facteursCalcul['client']['origine_fonds'] }}</td>
                            </tr>
                            <tr class="table-primary">
                                <td class="fw-bold">TOTAL</td>
                                <td class="fw-bold"></td>
                                <td class="fw-bold">{{ $facteursCalcul['client']['total_client'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Actionnaires -->
            <div class="card shadow-sm mb-4">
                <div class="card-header text-white" style="background-color: #01A9E4;">
                    <h5 class="mb-0 text-white"><i class="ti ti-chart-pie me-2"></i>Actionnaires</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <p class="text-muted mb-1">Note</p>
                            <h4 class="fw-bold">{{ $facteursCalcul['actionnaires']['note'] }}</h4>
                        </div>
                        <div class="col-6">
                            <p class="text-muted mb-1">Nombre d'actionnaires</p>
                            <h4 class="fw-bold">{{ $etablissement->Actionnaire->count() }}</h4>
                        </div>
                    </div>
                    @if($etablissement->Actionnaire && $etablissement->Actionnaire->count())
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Nom/RS</th>
                                        <th>Capital %</th>
                                        <th>Titres</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($etablissement->Actionnaire as $act)
                                    <tr>
                                        <td>{{ $act->nom_rs }}</td>
                                        <td>{{ $act->pourcentage_capital }}%</td>
                                        <td>{{ $act->nombre_titres }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">Aucun actionnaire enregistré</p>
                    @endif
                </div>
            </div>

            <!-- Personnes Habilitées -->
            <div class="card shadow-sm mb-4">
                <div class="card-header text-white" style="background-color: #1A3A6C;">
                    <h5 class="mb-0 text-white"><i class="ti ti-key me-2"></i>Personnes Habilitées</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <p class="text-muted mb-1">Note</p>
                            <h4 class="fw-bold">{{ $facteursCalcul['personnes_habilitees']['note'] }}</h4>
                        </div>
                        <div class="col-6">
                            <p class="text-muted mb-1">Nombre de personnes habilitées</p>
                            <h4 class="fw-bold">{{ $etablissement->PersonnesHabilites->count() }}</h4>
                        </div>
                    </div>
                    @if($etablissement->PersonnesHabilites && $etablissement->PersonnesHabilites->count())
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Fonction</th>
                                        <th>PPE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($etablissement->PersonnesHabilites as $ph)
                                    <tr>
                                        <td>{{ $ph->nom_rs }}</td>
                                        <td><small>{{ $ph->fonction ?? 'N/A' }}</small></td>
                                        <td>
                                            @if($ph->ppe)
                                                <span class="badge bg-danger">Oui</span>
                                            @else
                                                <span class="badge bg-secondary">Non</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">Aucune personne habilitée</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
            <!-- Bénéficiaires Effectifs -->
            <div class="card shadow-sm mb-4">
                <div class="card-header text-white" style="background-color: #01A9E4;">
                    <h5 class="mb-0 text-white"><i class="ti ti-crown me-2"></i>Bénéficiaires Effectifs</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <p class="text-muted mb-1">Note</p>
                            <h4 class="fw-bold">{{ $facteursCalcul['beneficiaire_effectif']['note'] }}</h4>
                        </div>
                        <div class="col-6">
                            <p class="text-muted mb-1">Nombre de bénéficiaires effectifs</p>
                            <h4 class="fw-bold">{{ $etablissement->BeneficiaireEffectif->count() }}</h4>
                        </div>
                    </div>
                    @if($etablissement->BeneficiaireEffectif && $etablissement->BeneficiaireEffectif->count())
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Nationalité</th>
                                        <th>PPE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($etablissement->BeneficiaireEffectif as $be)
                                    <tr>
                                        <td>{{ $be->nom_rs }} {{ $be->prenom }}</td>
                                        <td><small>{{ $be->nationalite->libelle ?? 'N/A' }}</small></td>
                                        <td>
                                            @if($be->ppe_id)
                                                <span class="badge bg-danger">Oui</span>
                                            @else
                                                <span class="badge bg-secondary">Non</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">Aucun bénéficiaire effectif</p>
                    @endif
                </div>
            </div>

            <!-- Administrateurs -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white"><i class="ti ti-users-group me-2"></i>Administrateurs / Dirigeants</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <p class="text-muted mb-1">Note</p>
                            <h4 class="fw-bold">{{ $facteursCalcul['administrateurs']['note'] }}</h4>
                        </div>
                        <div class="col-6">
                            <p class="text-muted mb-1">Nombre d'administrateurs</p>
                            <h4 class="fw-bold">{{ $etablissement->Administrateur->count() }}</h4>
                        </div>
                    </div>
                    @if($etablissement->Administrateur && $etablissement->Administrateur->count())
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Function</th>
                                        <th>PPE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($etablissement->Administrateur as $adm)
                                    <tr>
                                        <td>{{ $adm->nom }} {{ $adm->prenom }}</td>
                                        <td><small>{{ $adm->fonction ?? 'N/A' }}</small></td>
                                        <td>
                                            @if($adm->ppe_id)
                                                <span class="badge bg-danger">Oui</span>
                                            @else
                                                <span class="badge bg-secondary">Non</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">Aucun administrateur enregistré</p>
                    @endif
                </div>
            </div>

            <!-- Objet de la Relation -->
            <div class="card shadow-sm mb-4">
                <div class="card-header text-white" style="background-color: #01A9E4;">
                    <h5 class="mb-0 text-white"><i class="ti ti-link me-2"></i>Objet de la Relation</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <p class="text-muted mb-1">Note</p>
                        <h4 class="fw-bold">{{ optional($etablissement->objetRelation)->mandataire_check ? 30 : 1 }}</h4>
                    </div>
                    @if($etablissement->objetRelation)
                        @php $objRel = $etablissement->objetRelation; @endphp
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Objet:</dt>
                            <dd class="col-sm-7">
                                @if(is_array($objRel->objet_relation))
                                    {{ implode(', ', $objRel->objet_relation) }}
                                @else
                                    {{ $objRel->objet_relation ?? 'N/A' }}
                                @endif
                            </dd>

                            <dt class="col-sm-5">Relation d'affaire:</dt>
                            <dd class="col-sm-7">{{ $objRel->relation_affaire ?? 'N/A' }}</dd>

                            <dt class="col-sm-5">Horizon de placement:</dt>
                            <dd class="col-sm-7">{{ $objRel->horizon_placement ?? 'N/A' }}</dd>

                            <dt class="col-sm-5 mt-2">Mandataire:</dt>
                            <dd class="col-sm-7 mt-2">
                                @if($objRel->mandataire_check == 1)
                                    <span class="badge bg-success">Oui</span>
                                @else
                                    <span class="badge bg-secondary">Non</span>
                                @endif
                            </dd>
                            @if($objRel->mandataire_check)
                                <dt class="col-sm-5">Nom du mandataire:</dt>
                                <dd class="col-sm-7">{{ $objRel->mandataire_input ?? 'N/A' }}</dd>
                                <dt class="col-sm-5">Fin du mandat:</dt>
                                <dd class="col-sm-7">{{ $objRel->mandataire_fin_mandat_date ? \Carbon\Carbon::parse($objRel->mandataire_fin_mandat_date)->format('d/m/Y') : 'N/A' }}</dd>
                                @if($objRel->mandat_file)
                                    <dt class="col-sm-5">Fichier mandat:</dt>
                                    <dd class="col-sm-7"><a href="{{ Storage::url($objRel->mandat_file) }}" target="_blank">Voir le fichier</a></dd>
                                @endif
                            @endif
                        </dl>
                    @else
                        <p class="text-center text-muted">Aucune information sur l'objet de la relation.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Final Summary Card -->
    <div class="card shadow-lg border-0">
        <div class="card-header text-white text-center py-3
            @if($facteursCalcul['global']['scoring'] === 'Elevé') bg-danger
            @elseif($facteursCalcul['global']['scoring'] === 'Moyen') bg-warning
            @else bg-success
            @endif">
            <h4 class="mb-0"><i class="ti ti-graph me-2"></i>Résultat de l'Évaluation des Risques</h4>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-6">
                    <div class="p-4 border-end">
                        <p class="text-muted mb-2">Note Totale Calculée</p>
                        <h1 class="display-4 fw-bold text-primary">{{ $facteursCalcul['global']['note_totale'] }}</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-4">
                        <p class="text-muted mb-2">Classification du Risque</p>
                        <h1 class="display-4 fw-bold
                            @if($facteursCalcul['global']['scoring'] === 'Elevé') text-danger
                            @elseif($facteursCalcul['global']['scoring'] === 'Moyen') text-warning
                            @else text-success
                            @endif">
                            {{ $facteursCalcul['global']['scoring'] }}
                        </h1>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center no-print">
                <a href="{{ route('etablissements.show', $etablissement->id) }}" class="btn btn-lg me-2" style="background-color: #1A3A6C; color: white;">
                    <i class="ti ti-arrow-left me-2"></i>Retour au Dashboard
                </a>
                <button onclick="window.print()" class="btn btn-outline-secondary btn-lg me-2">
                    <i class="ti ti-printer me-2"></i>Imprimer le Rapport
                </button>

                @if($etablissement->validation === '1')
                    <span class="badge bg-success fs-5 p-3 ms-2">
                        <i class="ti ti-check me-2"></i>Validé
                    </span>
                @elseif($etablissement->validation === '0')
                    <span class="badge bg-danger fs-5 p-3 ms-2">
                        <i class="ti ti-x me-2"></i>Rejeté
                    </span>
                @else
                    <button type="button" class="btn btn-success btn-lg me-2" wire:click="validateEtablissement" wire:confirm="Confirmer la validation ?">
                        <i class="ti ti-check me-2"></i>Valider
                    </button>
                
                    <button type="button" class="btn btn-danger btn-lg" wire:click="rejectEtablissement" wire:confirm="Confirmer le rejet ?">
                        <i class="ti ti-x me-2"></i>Rejeter
                    </button>
                @endif
            </div>
        </div>
    </div>
    @endif
    
    @if(!$isCompleted)
    <div class="text-center py-4 no-print">
        <a href="{{ route('etablissements.show', $etablissement->id) }}" class="btn btn-primary btn-lg">
            <i class="ti ti-arrow-left me-2"></i>Retour au Dashboard pour compléter
        </a>
    </div>
    @endif
    
    <style>
        @media print {
            .btn, .card-header { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
        /* Brand colors from AlphaVest logo */
        .brand-navy { color: #1A3A6C; }
        .brand-cyan { color: #01A9E4; }
    </style>
</div>
