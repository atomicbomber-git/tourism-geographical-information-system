@extends('shared.layout')
@section('title', 'Tambah Jalur')
@section('content')
<div class="container mt-5">
    <h1 class="mb-5">
        <i class="fa fa-plus"></i>
        Tambah Jalur
    </h1>

    <div id="app">
        <path-create/>
    </div>
</div>

@javascript('gmap_styles', config('gmap_style'))
@javascript('gmap_config', config('gmap_config'))
@javascript('init_points', $points)
@javascript('gmap_zoom', session('gmap_zoom') ?? 11)

@endsection
