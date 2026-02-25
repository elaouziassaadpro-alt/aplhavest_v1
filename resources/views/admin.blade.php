@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
                <div class="card-body px-4 py-3">
                    <div class="row align-items-center">
                        <div class="col-9">
                            <h4 class="fw-semibold mb-8">Tableau de bord Admin</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Accueil</a></li>
                                    <li class="breadcrumb-item" aria-current="page">Administration</li>
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
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="row">
        <!-- User Stats -->
        <div class="col-lg-8">
            <div class="row">
                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 zoom-in bg-light-primary shadow-none">
                        <div class="card-body text-center">
                            <i class="ti ti-users fs-5 text-primary mb-2"></i>
                            <p class="fw-semibold fs-3 text-primary mb-1">Utilisateurs</p>
                            <h5 class="fw-semibold text-primary mb-0">{{ $userStats['total'] }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 zoom-in bg-light-warning shadow-none">
                        <div class="card-body text-center">
                            <i class="ti ti-user-check fs-5 text-warning mb-2"></i>
                            <p class="fw-semibold fs-3 text-warning mb-1">Admins</p>
                            <h5 class="fw-semibold text-warning mb-0">{{ $userStats['admins'] }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 zoom-in bg-light-info shadow-none">
                        <div class="card-body text-center">
                            <i class="ti ti-briefcase fs-5 text-info mb-2"></i>
                            <p class="fw-semibold fs-3 text-info mb-1">AK / BAK</p>
                            <h5 class="fw-semibold text-info mb-0">{{ $userStats['ak'] + $userStats['bak'] }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 zoom-in bg-light-danger shadow-none">
                        <div class="card-body text-center">
                            <i class="ti ti-eye fs-5 text-danger mb-2"></i>
                            <p class="fw-semibold fs-3 text-danger mb-1">Consultants</p>
                            <h5 class="fw-semibold text-danger mb-0">{{ $userStats['ci'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Establishment Status -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Statut des Établissements</h5>
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <span class="round-12 bg-success rounded-circle me-3"></span>
                                <span class="fs-4">Validés</span>
                                <span class="ms-auto fw-semibold fs-4">{{ $establishmentStats['valide'] }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <span class="round-12 bg-warning rounded-circle me-3"></span>
                                <span class="fs-4">En attente</span>
                                <span class="ms-auto fw-semibold fs-4">{{ $establishmentStats['en_attente'] }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <span class="round-12 bg-danger rounded-circle me-3"></span>
                                <span class="fs-4">Rejetés</span>
                                <span class="ms-auto fw-semibold fs-4">{{ $establishmentStats['rejete'] }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="display-4 fw-bold text-dark">{{ $establishmentStats['total'] }}</div>
                            <p class="text-muted">Total Établissements</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Actions Rapides</h5>
                    <div class="d-grid gap-3">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-primary d-flex align-items-center justify-content-between">
                            <span><i class="ti ti-users me-2"></i> Gérer les Utilisateurs</span>
                            <i class="ti ti-chevron-right"></i>
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-between">
                            <span><i class="ti ti-user-plus me-2"></i> Nouvel Utilisateur</span>
                            <i class="ti ti-chevron-right"></i>
                        </a>
                        <a href="{{ route('import_files.index') }}" class="btn btn-outline-info d-flex align-items-center justify-content-between">
                            <span><i class="ti ti-upload me-2"></i> Importer des listes</span>
                            <i class="ti ti-chevron-right"></i>
                        </a>
                        <a href="{{ route('files_list.index') }}" class="btn btn-outline-danger d-flex align-items-center justify-content-between">
                            <span><i class="ti ti-ban me-2"></i> Liste noire</span>
                            <i class="ti ti-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Row -->
    <div class="row">
        <!-- Recent Establishments -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Derniers Établissements</h5>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="text-muted">
                                <tr>
                                    <th>Nom</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentEtablissements as $etab)
                                <tr>
                                    <td>
                                        <a href="{{ route('etablissements.show', $etab->id) }}" class="fw-semibold mb-0 text-dark">{{ $etab->nom_etablissement }}</a>
                                    </td>
                                    <td>{{ $etab->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($etab->validation == 'valide')
                                            <span class="badge bg-light-success text-success fw-semibold">Validé</span>
                                        @elseif($etab->validation == 'rejete')
                                            <span class="badge bg-light-danger text-danger fw-semibold">Rejeté</span>
                                        @else
                                            <span class="badge bg-light-warning text-warning fw-semibold">En attente</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Derniers Utilisateurs</h5>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="text-muted">
                                <tr>
                                    <th>Utilisateur</th>
                                    <th>Rôle</th>
                                    <th>Email</th>
                                    <th>Statut</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentUsers as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($user->avatar)
                                                <img src="{{ asset($user->avatar) }}" class="rounded-circle" width="35" height="35" alt="" />
                                            @else
                                                <div class="bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div class="ms-3">
                                                <h6 class="fw-semibold mb-0">{{ $user->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light-secondary text-secondary fw-semibold text-uppercase">{{ $user->role }}</span>
                                    </td>
                                    <td><span class="fs-3">{{ $user->email }}</span></td>
                                    <td>
                                        @if($user->status == 1)
                                            <span class="badge bg-light-success text-success fw-semibold">Actif</span>
                                        @else
                                            <span class="badge bg-light-warning text-warning fw-semibold">En attente</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <form action="{{ route('admin.users.update-status', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="role" value="{{ $user->role }}">
                                                @if($user->status == 1)
                                                    <input type="hidden" name="status" value="0">
                                                    <button type="submit" class="btn btn-sm btn-light-warning text-warning" title="Désactiver">
                                                        <i class="ti ti-player-pause"></i>
                                                    </button>
                                                @else
                                                    <input type="hidden" name="status" value="1">
                                                    <button type="submit" class="btn btn-sm btn-light-success text-success" title="Activer">
                                                        <i class="ti ti-player-play"></i>
                                                    </button>
                                                @endif
                                            </form>

                                            @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light-danger text-danger" title="Supprimer">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection