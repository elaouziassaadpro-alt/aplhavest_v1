@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-semibold mb-1">
                <i class="ti ti-file-import text-primary me-2"></i>Importation de Fichiers
            </h4>
            <p class="mb-0 text-muted fs-3">Importer les fichiers CNASNUS et ANRF (XML, HTML, PDF)</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="text-muted text-decoration-none">Accueil</a>
                </li>
                <li class="breadcrumb-item active fw-semibold">Importation</li>
            </ol>
        </nav>
    </div>

    {{-- Livewire component handles everything below --}}
    @livewire('import-files')

</div>{{-- end container-fluid --}}

@endsection