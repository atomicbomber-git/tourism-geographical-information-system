@extends('shared.layout')
@section('title', 'Depan')
@section('content')

<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        @foreach ($slides as $slide)
        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
            <img class="d-block w-100" style="height: 600px; object-fit: cover; filter: brightness(70%)" src="{{ route('slide.image', $slide) }}" alt="{{ $slide->name }}">
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

<div id="app">
    <div class="my-3 container">
        <div>
            <home/>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-7 mb-5">
                <guest-map/>
            </div>
        
            <div class="col-sm-12 col-md-5">
                <h1> Situs Pariwisata Bengkayang </h1>
                <p class="lead">
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Optio blanditiis asperiores unde dicta omnis a ea tempore alias adipisci doloremque ratione fugit, iste temporibus aspernatur! Consectetur veritatis harum dicta aliquam.
                </p>
                <p>
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Tempora, ut corporis magni consequuntur ab praesentium cupiditate vero laborum. Maiores blanditiis tenetur nobis tempore laborum ipsa aut, odio ab rem numquam? Lorem ipsum dolor sit amet consectetur, adipisicing elit. Veritatis consequatur dolorem tempore, possimus animi hic deleniti neque provident, officiis ex ea id vel aut obcaecati ratione totam! Ratione, saepe vitae? Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae at quia iure tenetur earum ullam rerum itaque? Unde, assumenda officiis tempore, molestiae laudantium omnis harum, aspernatur voluptas sunt quam molestias?
                </p>
        
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatum, sunt cum doloribus eum perferendis omnis nobis voluptates cumque suscipit delectus veritatis obcaecati modi similique dolorem totam fugiat beatae sit minima.
                </p>
            </div>
        </div>
    </div>
</div>

@javascript('gmap_config', config('gmap_config'))
@javascript('points', $points)
@endsection