@extends('shared.layout')
@section('title', 'Kelola Situs Wisata')
@section('content')
<div class="container my-5">
    <h1 class="mb-5">
        <i class="fa fa-map"></i>
        Peta Situs Wisata
    </h1>

    <div id="app">
        <site-map/>
    </div>
</div>

@javascript('gmap_config', config('gmap_config'))
@javascript('gmap_styles', config('gmap_style'))
@javascript('init_points', $points)

@endsection