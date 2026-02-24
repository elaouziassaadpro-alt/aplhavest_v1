@extends('layouts.app')

@section('content')
<script src="{{ asset('dist/js/pages/user.js') }}"></script>

<div class="container-fluid">
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Mon Profil</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Profil</li>
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
        <div class="col-lg-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset($user->avatar) }}" alt="" class="rounded-circle mb-3" width="120" height="120">
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="mb-0">{{ $user->role }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h5 class="mb-4">Informations Personnelles</h5>
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom complet</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Avatar</label>
                            <div class="d-flex gap-2 flex-wrap">
                                @foreach ($avatars as $avatar)
                                    <label class="border p-1 rounded-circle avatar-label {{ $user->avatar == $avatar ? 'border-primary border-2' : '' }}" style="cursor:pointer; transition: all 0.2s;" onclick="selectAvatar(this)">
                                        <input type="radio" name="avatar" value="{{ $avatar }}" class="d-none" {{ $user->avatar == $avatar ? 'checked' : '' }}>
                                        <img src="{{ asset($avatar) }}" alt="Avatar" width="60" class="rounded-circle">
                                    </label>
                                @endforeach
                            </div>
                            @error('avatar')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Changer le mot de passe (Optionnel)</h5>

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                            <input type="password" class="form-control" id="current_password" name="current_password">
                            @error('current_password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password">
                                @error('password')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Mettre à jour le profil</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection