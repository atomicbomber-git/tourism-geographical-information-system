@extends('shared.layout')
@section('title', 'Sunting Waypoint ' . $waypoint->name)
@section('content')
<div class="container my-5">
    <h1 class='mb-5'>
        <i class='fa fa-plus'></i>
        Sunting Waypoint {{ $waypoint->name }}
    </h1>

    <div id="app">
        <waypoint-edit/>
    </div>
</div>

@javascript('init_points', $points)
@javascript('gmap_style', config('gmap_style'))
@javascript('init_waypoint', $waypoint)

@endsection