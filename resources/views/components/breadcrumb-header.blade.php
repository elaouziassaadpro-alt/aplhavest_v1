@props(['etablissementName' => null, 'activePage', 'title' => null])

<div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-2">
                    @if($etablissementName)
                        Establishment : {{ $etablissementName }}
                    @else
                        {{ $title ?? 'Establishment' }}
                    @endif
                </h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a class="text-muted text-decoration-none" href="{{ url('/') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $activePage }}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-3 text-center">
                <img src="{{ asset('dist/images/breadcrumb/ChatBc.png') }}" class="img-fluid" alt="Breadcrumb image">
            </div>
        </div>
    </div>
</div>
