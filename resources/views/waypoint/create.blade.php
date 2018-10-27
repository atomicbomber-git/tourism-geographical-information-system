@extends('shared.layout')
@section('title', 'Tambahkan Waypoint Baru')
@section('content')
<div class="container my-5">
    <h1 class='mb-5'>
        <i class='fa fa-plus'></i>
        Tambahkan Waypoint Baru
    </h1>

    <div id="app">
        <waypoint-create/>
    </div>
</div>

@javascript('init_points', $points)
@javascript('gmap_style', config('gmap_style'))

@endsection