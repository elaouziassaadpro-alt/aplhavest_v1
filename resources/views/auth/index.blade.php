@extends('layouts.app')

@section('content')
<script>
    window.userRoutes = {
        bulkDelete: "",
        csrfToken: "{{ csrf_token() }}"
    };
</script>
    <script src="{{ asset('dist/js/pages/user.js') }}"></script>

<div class="container-fluid mw-100">
    <!-- Breadcrumb -->
    <div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Liste des utilisateurs</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Utilisateurs</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Actions -->
    <div class="card card-body boutons_header mb-3">
        <div class="row align-items-center">
            <div class="col-md-4 col-xl-3">
                <form class="position-relative">
                    <input type="text" class="form-control product-search ps-5" id="input-search" placeholder="Recherche par nom ou email...">
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                </form>
            </div>

            <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0 gap-2">
                <div class="action-btn show-btn" id="bulk-action" style="display: none">
                    <a href="javascript:void(0)"
                       id="delete-selection"
                       class="btn btn-light-danger text-danger d-flex align-items-center font-medium">
                        <i class="ti ti-trash me-1 fs-5"></i> Supprimer la sélection
                    </a>
                </div>

                <a href="{{ route('register') }}" class="btn btn-info d-flex align-items-center">
                    <i class="ti ti-user-plus text-white me-1 fs-5"></i> Ajouter un utilisateur
                </a>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table search-table align-middle text-nowrap">
                <thead>
                    <tr>
                        <th class="text-center">
                            <input type="checkbox" id="users-check-all" class="form-check-input">
                        </th>
                        
                        <th><h6 class="fs-4 fw-semibold mb-0">User</h6></th>
                        <th class="fs-4 fw-semibold mb-0">Email</th>
                        <th class="fs-4 fw-semibold mb-0">Status</th>
                        <th class="fs-4 fw-semibold mb-0">Date de création</th>
                        <th class="fs-4 fw-semibold mb-0 text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input user-chkbox" value="{{ $user->id }}">
                            </td>
                            
                            <td>
                                <div class="d-flex align-items-center">
                                <img src="{{ asset($user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle" width="40" height="40" />
                                <div class="ms-3">
                                    <h6 class="fs-4 fw-semibold mb-0">{{ $user->name }}</h6>
                                    <span class="fw-normal">{{ $user->role }}</span>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->status === 1)
                                    <span class="badge bg-light-success rounded-3 py-8 text-success fw-semibold fs-2">active</span>
                                @else
                                    <span class="badge bg-light-warning rounded-3 py-8 text-warning fw-semibold fs-2">pending</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            <!-- <td>
                                <div class="d-flex gap-2">
                                    {{-- Edit/Delete buttons --}}
                                    <a href="#" class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                </div>
                            </td> -->
                            <td>
                        <div class="dropdown dropstart text-center">
                          <a href="#" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-dots-vertical fs-6"></i>
                          </a>
                          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li>
                              <a class="dropdown-item d-flex align-items-center gap-3" href="{{ route('profile.edit', ['user_id' => $user->id]) }}"><i class="fs-4 ti ti-edit"></i>Modifier Profil</a>
                            </li>
                            <li>
                              <form action="{{ route('admin.users.update-status', $user->id) }}" method="POST" id="status-form-{{ $user->id }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="role" value="{{ $user->role }}">
                                @if($user->status == 1)
                                    <input type="hidden" name="status" value="0">
                                    <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)" onclick="document.getElementById('status-form-{{ $user->id }}').submit(); text-warning">
                                        <i class="fs-4 ti ti-player-pause"></i>Désactiver
                                    </a>
                                @else
                                    <input type="hidden" name="status" value="1">
                                    <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)" onclick="document.getElementById('status-form-{{ $user->id }}').submit(); text-success">
                                        <i class="fs-4 ti ti-player-play"></i>Activer
                                    </a>
                                @endif
                              </form>
                            </li>
                            @if($user->id !== auth()->id())
                            <li>
                              <hr class="dropdown-divider">
                            </li>
                            <li>
                              <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" id="delete-form-{{ $user->id }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                @csrf
                                @method('DELETE')
                                <a class="dropdown-item d-flex align-items-center gap-3 text-danger" href="javascript:void(0)" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) document.getElementById('delete-form-{{ $user->id }}').submit();">
                                    <i class="fs-4 ti ti-trash"></i>Supprimer
                                </a>
                              </form>
                            </li>
                            @endif
                          </ul>
                        </div>
                      </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Aucun utilisateur trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
