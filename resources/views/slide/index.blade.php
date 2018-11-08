@extends('shared.layout')
@section('title', 'Kelola Gambar Slide')
@section('content')
<div class="container my-5">
    <h1 class='mb-5'>
        <i class='fa fa-picture-o'></i>
        Kelola Gambar Slide
    </h1>

    <div class="card">
        <div class="card-header">
            <i class="fa fa-picture-o"></i>
            Kelola Gambar Slide
        </div>
        <div class="card-body" style="display:flex">
            @foreach ($slides as $slide)
            <div class='card d-inline-block mr-2' style="max-width: 18rem; height: 34; align-self:flex-start">
                <img style="width: 18rem; height: 240px; object-fit: cover" class='card-img-top' src='{{ route('slide.image', $slide) . '?' . time() }}' alt='{{ $slide->name  }}'>
                <div class='card-body' >
                    <h5 class='card-title font-weight-bold'> {{ $slide->name }} </h5>
                    <p class='card-text'>
                        {{ $slide->description  }}
                    </p>
                    <div class="text-right" style="bottom: 0px">
                        <a href="{{ route('slide.edit', $slide) }}" class="btn btn-dark btn-sm">
                            <i class="fa fa-pencil"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection