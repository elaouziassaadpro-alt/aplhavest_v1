<!DOCTYPE html>
<html lang="en">
  <head>
    <title>AlphaVest</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/themify-icons@0.1.2/css/themify-icons.css">

    
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('dist/images/logos/favicon.ico') }}" />

    <!-- Owl Carousel -->
    <link rel="stylesheet" href="{{ asset('dist/libs/owl.carousel/dist/assets/owl.carousel.min.css') }}">

    <!-- Core CSS -->
    <link id="themeColors" rel="stylesheet" href="{{ asset('dist/css/style.min.css') }}" />
    <link id="themeColors" rel="stylesheet" href="{{ asset('dist/css/css_file_custom.css') }}" />
  </head>

  <body>
    <!-- Preloader -->
    <div class="preloader">
      <img src="{{ asset('dist/images/logos/favicon.ico') }}" alt="loader" class="lds-ripple img-fluid" />
    </div>

    <!-- Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-theme="blue_theme" data-layout="vertical"
         data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">

      <!-- Sidebar Start -->
      @include('layouts.sidebar')
      <!-- Sidebar End -->

      <!-- Main wrapper -->
      <div class="body-wrapper">
        <!-- Header Start -->
        @include('layouts.navbar')
        <!-- Header End -->

        <!-- Page Content -->
        
          @yield('content')
        
      </div>
    </div>
    <!-- Alert Messages -->
    
  
  @if(session('success') || session('error'))
    @php
        $type = session('success') ? 'success' : 'danger';
        $message = session('success') ?? session('error');
    @endphp

    <div 
        id="flashAlert"
        class="alert alert-{{ $type }} alert-dismissible fade show text-white shadow-lg"
        role="alert"
        style="
            position: fixed;
            top: 20px;
            right: 20px;
            width: 350px;      /* Custom width */
            z-index: 1055;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-weight: 500;
        "
    >
        <button 
            type="button" 
            class="btn-close btn-close-white" 
            data-bs-dismiss="alert" 
            aria-label="Close">
        </button>

        <strong>{{ ucfirst($type) }}!</strong> {{ $message }}
    </div>

    
@endif


    <!-- Import Js Files -->
     <script src="{{ asset('dist/libs/jquery/dist/jquery.min.js') }}"></script>
@stack('scripts')
    <script src="{{ asset('dist/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('dist/libs/simplebar/dist/simplebar.min.js') }}"></script>
    <script src="{{ asset('dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/app.min.js') }}"></script>
    <script src="{{ asset('dist/js/app.init.js') }}"></script>
    <script src="{{ asset('dist/js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('dist/js/custom.js') }}"></script>
    <script src="{{ asset('dist/libs/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('dist/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ asset('dist/js/dashboard.js') }}"></script>
    <script src="{{ asset('dist/js/script_file_custom.js') }}"></script>


  </body>
</html>