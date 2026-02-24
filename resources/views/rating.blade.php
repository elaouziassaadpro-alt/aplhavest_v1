@extends('layouts.app')

@section('content')
    <livewire:rating-etablissement :etablissement_id="$etablissement->id" />
@endsection
