<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light">

        {{-- ── LEFT: Sidebar toggle + search ── --}}
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link sidebartoggler nav-icon-hover ms-n3" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2"></i>
                </a>
            </li>
            <li class="nav-item d-none d-lg-block">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="ti ti-search"></i>
                </a>
            </li>
        </ul>

        {{-- ── CENTER: Quick nav links (desktop) ── --}}
        <ul class="navbar-nav quick-links d-none d-lg-flex">
            <li class="nav-item dropdown-hover d-none d-lg-block">
                <a class="nav-link {{ request()->routeIs('etablissements.*') ? 'active fw-bold' : '' }}"
                   href="{{ route('etablissements.index') }}">
                    <i class="ti ti-building me-1"></i> Établissements
                </a>
            </li>
            <li class="nav-item dropdown-hover d-none d-lg-block">
                <a class="nav-link {{ request()->routeIs('Rating') ? 'active fw-bold' : '' }}"
                   href="{{ route('Rating') }}">
                    <i class="ti ti-star me-1"></i> Rating
                </a>
            </li>
            <li class="nav-item dropdown-hover d-none d-lg-block">
                <a class="nav-link {{ request()->routeIs('files_list.*') ? 'active fw-bold' : '' }}"
                   href="{{ route('files_list.index') }}">
                    <i class="ti ti-files me-1"></i> Fichiers OPC
                </a>
            </li>
        </ul>

        {{-- ── Mobile: Logo ── --}}
        <div class="d-block d-lg-none">
            <img src="{{ asset('dist/images/logos/alphavest-logo.png') }}" class="dark-logo"  width="180" alt="" />
            <img src="{{ asset('dist/images/logos/alphavest-logo.png') }}" class="light-logo" width="180" alt="" />
        </div>

        {{-- ── Mobile toggler ── --}}
        <button class="navbar-toggler p-0 border-0" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="p-2"><i class="ti ti-dots fs-7"></i></span>
        </button>

        {{-- ── RIGHT: Action icons + profile ── --}}
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <div class="d-flex align-items-center justify-content-between">
                <a href="javascript:void(0)" class="nav-link d-flex d-lg-none align-items-center justify-content-center"
                   type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar" aria-controls="offcanvasWithBothOptions">
                    <i class="ti ti-align-justified fs-7"></i>
                </a>

                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">

                    {{-- ── Pending dossiers badge (replaces basket) ── --}}
                    @php
                        $pendingCount = \App\Models\Etablissement::where('risque', '-')->count();
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link notify-badge nav-icon-hover position-relative"
                           href="{{ route('etablissements.index') }}"
                           title="{{ $pendingCount }} dossier(s) incomplet(s)">
                            <i class="ti ti-file-alert"></i>
                            @if($pendingCount > 0)
                                <span class="badge rounded-pill bg-warning text-dark fs-2">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </li>

                    {{-- ── Admin settings (admin only) ── --}}
                    @auth
                        @if(Auth::user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link nav-icon-hover" href="{{ route('admin.index') }}" title="Administration">
                                <i class="ti ti-settings"></i>
                            </a>
                        </li>
                        @endif
                    @endauth

                    {{-- ── User profile dropdown ── --}}
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link pe-0" href="javascript:void(0)"
                           id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <div class="user-profile-img">
                                    @if(Auth::user()->avatar && file_exists(public_path(Auth::user()->avatar)))
                                        <img src="{{ asset(Auth::user()->avatar) }}"
                                             class="rounded-circle" width="35" height="35" alt="" />
                                    @else
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                                             style="width:35px;height:35px;font-size:0.9rem;">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>

                        <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                             aria-labelledby="drop1">
                            <div class="profile-dropdown position-relative" data-simplebar>

                                {{-- Profile header --}}
                                <div class="py-3 px-7 pb-0">
                                    <h5 class="mb-0 fs-5 fw-semibold">Profil Utilisateur</h5>
                                </div>
                                <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                                    @if(Auth::user()->avatar && file_exists(public_path(Auth::user()->avatar)))
                                        <img src="{{ asset(Auth::user()->avatar) }}"
                                             class="rounded-circle" width="80" height="80" alt="" />
                                    @else
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                                             style="width:80px;height:80px;font-size:1.8rem;">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="ms-3">
                                        <h5 class="mb-1 fs-3">{{ Auth::user()->name }}</h5>
                                        <span class="mb-1 d-block text-dark">{{ Auth::user()->role }}</span>
                                        <p class="mb-0 d-flex text-dark align-items-center gap-2">
                                            <i class="ti ti-mail fs-4"></i> {{ Auth::user()->email }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Menu links --}}
                                <div class="message-body">
                                    <a href="{{ route('profile.edit') }}"
                                       class="py-8 px-7 mt-8 d-flex align-items-center">
                                        <span class="d-flex align-items-center justify-content-center bg-light rounded-1 p-6">
                                            <img src="{{ asset('dist/images/svgs/icon-account.svg') }}" alt="" width="24" height="24">
                                        </span>
                                        <div class="w-75 d-inline-block v-middle ps-3">
                                            <h6 class="mb-1 bg-hover-primary fw-semibold">Mon Profil</h6>
                                            <span class="d-block text-dark">Paramètres du compte</span>
                                        </div>
                                    </a>

                                    @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.index') }}"
                                       class="py-8 px-7 d-flex align-items-center">
                                        <span class="d-flex align-items-center justify-content-center bg-light rounded-1 p-6">
                                            <img src="{{ asset('dist/images/svgs/icon-dd-application.svg') }}" alt="" width="24" height="24">
                                        </span>
                                        <div class="w-75 d-inline-block v-middle ps-3">
                                            <h6 class="mb-1 bg-hover-primary fw-semibold">Administration</h6>
                                            <span class="d-block text-dark">Gestion des utilisateurs</span>
                                        </div>
                                    </a>
                                    @endif
                                </div>

                                {{-- Logout --}}
                                <div class="d-grid py-4 px-7 pt-8">
                                    <a href="{{ route('logout') }}" class="btn btn-outline-primary">
                                        <i class="ti ti-logout me-1"></i> Se déconnecter
                                    </a>
                                </div>

                            </div>
                        </div>
                    </li>
                    @endauth

                </ul>
            </div>
        </div>

    </nav>
</header>