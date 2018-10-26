@extends('shared.layout')
@section('title', 'Kelola Situs Pariwisata')
@section('content')
<div class="container my-5">
    <h1 class="mb-5">
        <i class="fa fa-tree"></i>
        Kelola Situs Wisata
    </h1>

    <div id="app">
        <site-index/>
    </div>
</div>

@javascript('init_points', $points)

@endsection