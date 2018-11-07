@extends('shared.layout')
@section('title', 'Depan')
@section('content')

<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        @foreach ($slides as $slide)
        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
            <img class="d-block w-100" style="height: 600px; object-fit: cover; filter: brightness(90%)" src="{{ route('slide.image', $slide) }}" alt="{{ $slide->name }}">
            <div class="carousel-caption d-none d-md-block">
                <h5> {{ $slide->name }} </h5>
                <p> {{ $slide->description }} </p>
            </div>
        </div>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<div class="container my-5">
    <p>
        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Corporis, adipisci sed dicta quos est temporibus corrupti in rerum explicabo sit voluptatem dolorem vel natus architecto illum suscipit unde neque omnis!
    </p>
    <p>
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam sint, deserunt maxime facere nulla voluptas vero deleniti officiis sapiente magni enim repellendus consectetur optio dignissimos vel numquam? Veniam, distinctio aliquid?
    </p>
</div>
@endsection