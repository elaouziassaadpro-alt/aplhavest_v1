@extends('layouts.app')

@section('content')

<div class="container-fluid mw-100">
    <livewire:create-objet-relation :etablissement_id="$etablissement->id" />
</div>

@endsection