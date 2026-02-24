@extends('layouts.app')
@section('content')

<script src="{{ asset('dist/js/pages/user.js') }}"></script>
<!-- Preloader -->
<div class="preloader">
  <img src="{{ asset('dist/images/logos/favicon.ico') }}" alt="loader" class="lds-ripple img-fluid" />
</div>

<!-- Body Wrapper -->
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
  <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
    <div class="d-flex align-items-center justify-content-center w-100">
      <div class="row justify-content-center w-100">
        <div class="col-md-10 col-lg-8 col-xxl-6 mt-5">
          <div class="card mb-0">
            <div class="card-body">
              <a href="{{ route('dashboard') }}" class="text-nowrap logo-img text-center d-block py-3 w-100">
                <img src="{{ asset('dist/images/logos/alphavest-logo.png') }}" width="180" alt="">
              </a>
              <h2 class="mb-3 fs-7 fw-bolder text-center">Créer un compte</h2>
              <p class="mb-9 text-center">Par <b>AlphaVest Asset Management</b></p>

              <!-- Error messages -->
              <div class="errors">
                @if ($errors->any())
                  <div class="alert alert-danger">
                    <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                  </div>
                @endif

                @if (session('status'))
                  <div class="alert alert-success">
                    {{ session('status') }}
                  </div>
                @endif
              </div>

              <!-- Registration form -->
              <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nom complet</label>
                    <input type="text" class="form-control" id="name" name="name" required placeholder="Votre nom complet" value="{{ old('name') }}">
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="Votre email" value="{{ old('email') }}">
                  </div>
                </div>
                
                <div class="mb-3">
                  <label for="role" class="form-label">Profil</label>
                  <select name="role" class="form-select" required>
                    <option value="AK">AKYC</option>
                    <option value="CI">CI</option>
                    <option value="BAK">Backup AKYC</option>
                  </select>
                </div>

                <div class="mb-3">
                <label class="form-label">Avatar</label>
                <div class="d-flex gap-2 flex-wrap">
                    @foreach ($avatars as $avatar)
                        <label class="border p-1 rounded-circle avatar-label" style="cursor:pointer; transition: all 0.2s;" onclick="selectAvatar(this)">
                            <input type="radio" name="avatar" value="{{ $avatar }}" class="d-none" required>
                            <img src="{{ asset($avatar) }}" alt="Avatar" width="60" class="rounded-circle">
                        </label>
                    @endforeach
                </div>

            </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="Mot de passe">
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Confirmez le mot de passe">
                  </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                  <div class="form-check">
                    <input class="form-check-input primary" type="checkbox" id="terms" name="terms" required>
                    <label class="form-check-label text-dark" for="terms">
                      J'accepte les <a href="#" class="text-primary">conditions d'utilisation</a>
                    </label>
                  </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">S'inscrire</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection