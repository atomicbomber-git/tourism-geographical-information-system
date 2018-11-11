@extends('shared.layout')
@section('title', "Situs Wisata Kategori $site_category->name")
@section('content')
<div class="container my-5">
    <h1 class='mb-5'>
        <i class='fa fa-tree'></i>
        Situs Wisata Kategori {{ $site_category->name }} 
    </h1>

    <div class="d-flex flex-wrap">
        @foreach ($site_category->sites as $site)
        <div class='card d-inline-block mr-3 mb-3' style='width: 20rem; height: 34rem'>
            <img
                style="object-fit: cover; width: 20rem; height: 16rem"
                class='card-img-top' src='{{ route('site.image', $site) }}' alt='{{ $site->point->name }}'/>
            <div class='card-body'>
                <h5 class='card-title font-weight-bold'>
                    {{ $site->point->name }}
                </h5>
                <p>
                    <span class="badge badge-info"> H. Masuk: Rp. {{ number_format($site->fee) }} </span>
                    <span class="badge badge-info"> J. Pengunjung Tahunan: {{ number_format($site->visitor_count) }} </span>
                    <span class="badge badge-info"> J. Fasilitas: {{ $site->facility_count }} </span>
                </p>
                <p class='card-text'>
                    {{ str_limit($site->description, 140) }}
                </p>
                <div class="text-right" style="height: 100%">
                    <a href="{{ route('site.detail', $site) }}" class="btn btn-primary">
                        Detail
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection