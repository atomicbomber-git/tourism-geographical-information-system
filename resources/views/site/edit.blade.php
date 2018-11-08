@extends('shared.layout')
@section('title', 'Sunting Situs Wisata')
@section('content')
<div class="container my-5">
    <h1 class='mb-5'>
        <i class='fa fa-plus'></i>
        Sunting Situs Wisata Baru
    </h1>

    <div id="app">
        <site-edit/>
    </div>
</div>

@javascript('gmap_config', config('gmap_config'))
@javascript('site', $site)
@javascript('categories', $categories)
@javascript('init_points', $points)

@endsection