@extends('layouts.app')

@section('content')

<div class="container-fluid mw-100">
    <livewire:create-benificiaire-effectif :etablissement_id="$etablissement->id" />
</div>

@endsection
