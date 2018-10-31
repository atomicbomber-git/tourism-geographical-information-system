@extends('shared.layout')
@section('title', 'Tambahkan Situs Wisata Baru')
@section('content')
<div class="container my-5">
    <h1 class='mb-5'>
        <i class='fa fa-plus'></i>
        Tambahkan Situs Wisata Baru
    </h1>

    <div id="app">
        <site-create/>
    </div>
</div>

@javascript('gmap_config', config('gmap_config'))
@javascript('categories', $categories)
@javascript('init_points', $points)

@endsection