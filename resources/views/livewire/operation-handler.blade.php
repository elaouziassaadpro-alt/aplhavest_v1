<div>
    <div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Opérations</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="/">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Opérations</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{ asset('dist/images/breadcrumb/ChatBc.png') }}" alt="" class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Importer des Opérations</h5>
                    
                    @if($successMessage)
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ $successMessage }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errorMessage)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ $errorMessage }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form wire:submit.prevent="importExcel" class="row g-3 align-items-center">
                        <div class="col-auto">
                            <input type="file" wire:model="excelFile" class="form-control" id="excelFile">
                            @error('excelFile') <span class="text-danger fs-3">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-auto">
                            @if(empty($operation))
                                <button type="submit" class="btn btn-primary d-flex align-items-center" wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        <i class="ti ti-upload me-1"></i> Importer
                                    </span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                        En cours...
                                    </span>
                                </button>
                            @else
                                <div class="d-flex gap-2">
                                    <button type="button" wire:click="save" class="btn btn-success d-flex align-items-center" wire:loading.attr="disabled">
                                        <i class="ti ti-check me-1"></i> Envoyer
                                    </button>
                                    <button type="button" wire:click="cancel" class="btn btn-outline-danger d-flex align-items-center" wire:loading.attr="disabled">
                                        <i class="ti ti-x me-1"></i> Annuler
                                    </button>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="card-title fw-semibold mb-0">Liste des Opérations</h5>
                            @if(!empty($operation))
                                <span class="badge bg-light-warning text-warning fw-semibold">Prévisualisation (Non enregistré)</span>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Rechercher...">
                            <button wire:click="$refresh" class="btn btn-sm btn-outline-secondary">
                                <i class="ti ti-refresh"></i>
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive" style="max-height: 600px;">
                        <table class="table table-hover align-middle text-nowrap mb-0 border">
                            <thead class="table-dark sticky-top">
                                <tr>
                                    <th class="border-bottom-0">N° Ope</th>
                                    <th class="border-bottom-0">N° Event</th>
                                    <th class="border-bottom-0">Titre</th>
                                    <th class="border-bottom-0">Desc Titre</th>
                                    <th class="border-bottom-0">Poste</th>
                                    <th class="border-bottom-0">Entité</th>
                                    <th class="border-bottom-0">Portefeuille</th>
                                    <th class="border-bottom-0">Desc Portefeuille</th>
                                    <th class="border-bottom-0">Statut</th>
                                    <th class="border-bottom-0">Date Saisi</th>
                                    <th class="border-bottom-0">Date Opé</th>
                                    <th class="border-bottom-0">Date Valeur</th>
                                    <th class="border-bottom-0">Date Livaison</th>
                                    <th class="border-bottom-0">Date Validation</th>
                                    <th class="border-bottom-0">Date Annulation</th>
                                    <th class="border-bottom-0">Intermédiaire</th>
                                    <th class="border-bottom-0">Dépositaire</th>
                                    <th class="border-bottom-0">Compte Titre</th>
                                    <th class="border-bottom-0">Compte Espèce</th>
                                    <th class="border-bottom-0">Contrepartie</th>
                                    <th class="border-bottom-0">Desc Contrepartie</th>
                                    <th class="border-bottom-0">Dépositaire CtrP</th>
                                    <th class="border-bottom-0">Compte Titres CtrP</th>
                                    <th class="border-bottom-0 text-end">Quantité</th>
                                    <th class="border-bottom-0 text-end">Cours</th>
                                    <th class="border-bottom-0 text-end">Montant Devise</th>
                                    <th class="border-bottom-0">Devise Ref</th>
                                    <th class="border-bottom-0 text-end">Taux Ref</th>
                                    <th class="border-bottom-0">Devise Reg</th>
                                    <th class="border-bottom-0 text-end">Frais Total</th>
                                    <th class="border-bottom-0 text-end">Montant Brut</th>
                                    <th class="border-bottom-0 text-end">Montant Net</th>
                                    <th class="border-bottom-0 text-end">Intérêt Couru</th>
                                    <th class="border-bottom-0 text-end">PMV Back</th>
                                    <th class="border-bottom-0">Contrat</th>
                                    <th class="border-bottom-0">Titre Jouissance</th>
                                    <th class="border-bottom-0">Titre Échéance</th>
                                    <th class="border-bottom-0 text-end">Prix Négo</th>
                                    <th class="border-bottom-0 text-end">Prix PPC</th>
                                    <th class="border-bottom-0 text-end">Négo Spread</th>
                                    <th class="border-bottom-0 text-end">Négo Taux</th>
                                    <th class="border-bottom-0 text-end">Taux Placement</th>
                                    <th class="border-bottom-0 text-end">Jours Place.</th>
                                    <th class="border-bottom-0 text-end">Intérêts</th>
                                    <th class="border-bottom-0 text-end">Décalage Val</th>
                                    <th class="border-bottom-0 text-center">Front</th>
                                    <th class="border-bottom-0 text-center">Back</th>
                                    <th class="border-bottom-0 text-center">Annul</th>
                                    <th class="border-bottom-0">Échéance</th>
                                    <th class="border-bottom-0">ISIN</th>
                                    <th class="border-bottom-0">Émetteur</th>
                                    <th class="border-bottom-0">Classe</th>
                                    <th class="border-bottom-0">Catégorie</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pagedOperations as $op)
                                    <tr>
                                        <td>{{ $op->operation_number }}</td>
                                        <td>{{ $op->event_number }}</td>
                                        <td><strong>{{ $op->titre }}</strong></td>
                                        <td class="text-muted small">{{ Str::limit($op->titre_description, 20) }}</td>
                                        <td>{{ $op->poste }}</td>
                                        <td>{{ $op->entite }}</td>
                                        <td>{{ $op->portefeuille }}</td>
                                        <td>{{ $op->portefeuille_description }}</td>
                                        <td>
                                            <span class="badge {{ str_contains(strtolower($op->statut), 'val') ? 'bg-light-success text-success' : (str_contains(strtolower($op->statut), 'ann') ? 'bg-light-danger text-danger' : 'bg-light-warning text-warning') }} rounded-3 fw-semibold">
                                                {{ $op->statut }}
                                            </span>
                                        </td>
                                        <td>{{ $op->date_saisi?->format('d/m/Y') }}</td>
                                        <td>{{ $op->date_operation?->format('d/m/Y') }}</td>
                                        <td>{{ $op->date_valeur?->format('d/m/Y') }}</td>
                                        <td>{{ $op->date_livraison?->format('d/m/Y') }}</td>
                                        <td>{{ $op->date_validation?->format('d/m/Y') }}</td>
                                        <td>{{ $op->date_annulation?->format('d/m/Y') }}</td>
                                        <td>{{ $op->intermediaire }}</td>
                                        <td>{{ $op->depositaire }}</td>
                                        <td>{{ $op->compte_titre }}</td>
                                        <td>{{ $op->compte_espece }}</td>
                                        <td>{{ $op->contrepartie }}</td>
                                        <td>{{ $op->contrepartie_description }}</td>
                                        <td>{{ $op->depositaire_contrepartie }}</td>
                                        <td>{{ $op->compte_titres_contrepartie }}</td>
                                        <td class="text-end fw-bold">{{ number_format($op->quantite, 0, '.', ' ') }}</td>
                                        <td class="text-end">{{ number_format($op->cours, 4, '.', ' ') }}</td>
                                        <td class="text-end">{{ number_format($op->montant_devise, 2, '.', ' ') }}</td>
                                        <td>{{ $op->devise_ref }}</td>
                                        <td class="text-end">{{ number_format($op->taux_ref, 6) }}</td>
                                        <td>{{ $op->devise_reg }}</td>
                                        <td class="text-end">{{ number_format($op->frais_total, 2, '.', ' ') }}</td>
                                        <td class="text-end">{{ number_format($op->montant_brut, 2, '.', ' ') }}</td>
                                        <td class="text-end fw-bold text-dark">{{ number_format($op->montant_net, 2, '.', ' ') }}</td>
                                        <td class="text-end">{{ number_format($op->interet_couru, 2, '.', ' ') }}</td>
                                        <td class="text-end">{{ number_format($op->pmv_back, 2, '.', ' ') }}</td>
                                        <td>{{ $op->contrat }}</td>
                                        <td>{{ $op->titre_jouissance?->format('d/m/Y') }}</td>
                                        <td>{{ $op->titre_echeance?->format('d/m/Y') }}</td>
                                        <td class="text-end">{{ number_format($op->prix_nego, 6) }}</td>
                                        <td class="text-end">{{ number_format($op->prix_ppc, 6) }}</td>
                                        <td class="text-end">{{ number_format($op->nego_spread, 6) }}</td>
                                        <td class="text-end">{{ number_format($op->nego_taux, 6) }}</td>
                                        <td class="text-end">{{ number_format($op->taux_placement, 6) }}</td>
                                        <td class="text-end">{{ $op->nbre_jours_placement }}</td>
                                        <td class="text-end">{{ number_format($op->interets, 2, '.', ' ') }}</td>
                                        <td class="text-end">{{ $op->decalage_valeur }}</td>
                                        <td class="text-center">
                                            {{ $op->ope_front ?? '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $op->ope_back ?? '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $op->ope_annul ?? '-' }}
                                        </td>
                                        <td>{{ $op->date_echeance?->format('d/m/Y') }}</td>
                                        <td><span class="badge bg-light-secondary text-secondary">{{ $op->code_isin }}</span></td>
                                        <td>{{ $op->emetteur }}</td>
                                        <td>{{ $op->classe }}</td>
                                        <td>{{ $op->categorie }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="53" class="text-center py-4 text-muted">
                                            Aucune opération trouvée.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $pagedOperations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
