@extends('shared.layout')
@section('title', 'Perhitungan Dijkstra')
@section('content')
<div class="container my-5">
    <h1 class='mb-5'>
        <i class='fa fa-calculator'></i>
        Perhitungan Dijkstra
    </h1>

    <div class="card">
        <div class="card-header">
            <i class="fa fa-calculator"></i>
            Perhitungan Dijkstra
        </div>
        <div class="card-body" id="app">
            <dijkstra-route
                :points='{{ json_encode($points) }}'
                >
            </dijkstra-route>
        </div>
    </div>
</div>
@endsection
