<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="Mordenize" />
    <meta name="author" content="" />
    <meta name="keywords" content="Mordenize" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- --------------------------------------------------- -->
    <!-- Favicon -->
    <!-- --------------------------------------------------- -->
    <link rel="shortcut icon" type="image/png" href="../../dist/images/logos/favicon.ico" />

    <!-- --------------------------------------------------- -->
    <!-- Prism Js -->
    <!-- --------------------------------------------------- -->
    <link rel="stylesheet" href="../../dist/libs/prismjs/themes/prism-okaidia.min.css">

    <!-- --------------------------------------------------- -->
    <!-- Core Css -->
    <!-- --------------------------------------------------- -->
    
    <link  id="themeColors"  rel="stylesheet" href="../../dist/css/style.min.css" />
    <title>login</title>
</head>
<body>
    <x-notification />

    <!-- Preloader -->
<div class="preloader">
  <img src="{{ asset('dist/images/logos/favicon.ico') }}" alt="loader" class="lds-ripple img-fluid" />
  {{-- Load Tailwind + JS via Vite --}}
    

</div>

<!-- Body Wrapper -->
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
  <div class="position-relative overflow-hidden radial-gradient min-vh-100">
    <div class="position-relative z-index-5">
      <div class="row">
        <!-- Left side -->
        <div class="col-xl-7 col-xxl-8">
          <a href="{{ url('/') }}" class="text-nowrap logo-img d-block px-4 py-9 w-100">
            <img src="{{ asset('dist/images/logos/alphavest-logo.png') }}" width="180" alt="Logo">
          </a>
          <div class="d-none d-xl-flex align-items-center justify-content-center" style="height: calc(100vh - 80px);">
            <img src="{{ asset('dist/images/backgrounds/login-security.svg') }}" alt="Security" class="img-fluid" width="500">
          </div>
        </div>

        <!-- Right side -->
        <div class="col-xl-5 col-xxl-4">
          <div class="authentication-login min-vh-100 bg-body row justify-content-center align-items-center p-4">
            <div class="col-sm-8 col-md-6 col-xl-9">
              <h2 class="mb-3 fs-7 fw-bolder">Bienvenue dans l'application KYC</h2>
              <p class="mb-9">Par <b>AlphaVest Asset Management</b></p>

              <!-- Error messages -->
              <div class="errors">
                @if ($errors->any())
                  <div class="p text-danger">
                    <i class="ti ti-info-triangle-filled"></i>
                    @foreach ($errors->all() as $error)
                      {{ $error }}<br>
                    @endforeach
                  </div>
                @endif

                @if (session('status'))
                  <div class="p text-danger">
                    <i class="ti ti-info-triangle-filled"></i> {{ session('status') }}
                  </div>
                @endif
              </div>

              <!-- Login form -->
              <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                  <label for="email" class="form-label">Nom d'utilisateur</label>
                  <input type="text" class="form-control" id="email" name="email" required placeholder="Email ou login" value="{{ old('email') }}">
                </div>
                <div class="mb-4">
                  <label for="password" class="form-label">Mot de passe</label>
                  <input type="password" class="form-control" id="password" name="password" required placeholder="Mot de passe">
                </div>
                <div class="d-flex align-items-center justify-content-between mb-4">
                  <div class="form-check">
                    <input class="form-check-input primary" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label text-dark" for="remember">
                      Se souvenir de moi
                    </label>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2">Connexion</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Import Js Files -->
<script src="{{ asset('dist/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('dist/libs/simplebar/dist/simplebar.min.js') }}"></script>
<script src="{{ asset('dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/js/app.min.js') }}"></script>
<script src="{{ asset('dist/js/app.init.js') }}"></script>
<script src="{{ asset('dist/js/app-style-switcher.js') }}"></script>
<script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
<script src="{{ asset('dist/js/custom.js') }}"></script>
</body>
</html>