@extends('layouts.app')

@section('content')

<div class="container-fluid mw-100">
    <livewire:create-personne-habilite :etablissement_id="$etablissement->id" />
</div>

@endsection
