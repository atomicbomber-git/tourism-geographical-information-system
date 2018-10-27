@extends('shared.layout')
@section('title', 'Kelola Situs Pariwisata')
@section('content')
<div class="container my-5">
    <h1 class="mb-5">
        <i class="fa fa-map"></i>
        Peta Situs Pariwisata
    </h1>

    <div id="app">
        <site-map/>
    </div>
</div>

@javascript('init_points', $points)

@endsection