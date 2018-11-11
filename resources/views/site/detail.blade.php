@extends('shared.layout')
@section('title', $site->name)
@section('content')
<div class="container my-5">
    <h1 class='mb-5'>
        {{ $site->name }}
    </h1>
    
    <div class="card">
        <div class="card-body">
            <div>
                <h1> {{ $site->point->name }} </h1>
                <hr>
            </div>
            <div class="row">
                <div class="col">
                    <p>
                        {{ $site->description }}
                    </p>
                </div>

                <div class="col-4">
                    <div class='card' style="width: 100%; height: auto">
                        <img 
                            class='card-img-top'
                            src="{{ route('site.image', $site) }}"
                            alt="{{ $site->point->name }}">

                        <div class='card-body'>
                            <h5 class='card-title h2 mb-4'> {{ $site->point->name }} </h5>
                            <div>
                                <dl>
                                    <dt> Jumlah Pengunjung Tahunan </dt>
                                    <dd> {{ number_format($site->visitor_count) }} </dd>

                                    <dt> Harga Tiket Masuk </dt>
                                    <dd> {{ number_format($site->fee) }} </dd>

                                    <dt> Jumlah Fasilitas </dt>
                                    <dd> {{ number_format($site->facility_count) }} </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection