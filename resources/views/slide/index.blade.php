@extends('shared.layout')
@section('title', 'Kelola Gambar Slide')
@section('content')
<div class="container my-5">
    <h1 class='mb-5'>
        <i class='fa fa-picture-o'></i>
        Kelola Gambar Slide
    </h1>

    <div class="my-4">
        <a href="{{ route('slide.create') }}" class="btn btn-dark">
            Tambah Slide
            <i class="fa fa-plus"></i>
        </a>
    </div>

    @include('shared.message', ['session_key' => 'message.success', 'state' => 'success'])

    <div class="card">
        <div class="card-header">
            <i class="fa fa-picture-o"></i>
            Kelola Gambar Slide
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($slides as $slide)
                    <div class="col-md-4">
                        <div class='card mr-2 mb-4'>
                            <img style="height: 240px; object-fit: cover" class='card-img-top' src='{{ route('slide.image', $slide) . '?' . time() }}' alt='{{ $slide->name  }}'>
                            <div class='card-body' >
                                <h5 class='card-title font-weight-bold'> {{ $slide->name }} </h5>
                                <p class='card-text'>
                                    {{ $slide->description  }}
                                </p>
                                <div class="text-right" style="bottom: 0px">
                                    <form action='{{ route('slide.delete', $slide) }}' method='POST' class='d-inline-block'>
                                        @csrf
                                        <button type='submit' class='btn btn-danger btn-sm'>
                                            <i class='fa fa-trash'></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('slide.edit', $slide) }}" class="btn btn-dark btn-sm">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection