@extends('shared.layout')
@section('title', 'Sunting Gambar Slide')
@section('content')
<div class="container my-5">
    <h1 class='mb-5'>
        <i class='fa fa-picture-o'></i>
        Sunting Gambar Slide
    </h1>

    @include('shared.message', ['session_key' => 'message.success', 'state' => 'success'])

    <div class="card" style="max-width: 30rem">
        <div class="card-header">
            <i class="fa fa-picture-o"></i>
            Sunting Gambar Slide
        </div>
        <div class="card-body">
            <form action="{{ route('slide.update', $slide) }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class='form-group'>
                    <label for='name'> Nama: </label>
                
                    <input
                        id='name' name='name' type='text'
                        placeholder='Nama'
                        value='{{ old('name', $slide->name) }}'
                        class='form-control {{ !$errors->has('name') ?: 'is-invalid' }}'>
                
                    <div class='invalid-feedback'>
                        {{ $errors->first('name') }}
                    </div>
                </div>

                <div class='form-group'>
                    <label for='description'> Deskripsi: </label>
                
                    <textarea
                        id='description' name='description'
                        class='form-control {{ !$errors->has('description') ?: 'is-invalid' }}'
                        col='30' row='6'
                        >{{ old('description', $slide->description) }}</textarea>
                
                    <div class='invalid-feedback'>
                        {{ $errors->first('description') }}
                    </div>
                </div>

                <div class="form-group">
                    <label> Gambar Lama: </label>
                    <img style="object-fit: cover; width: 100%" src="{{ route('slide.image', $slide) . "?" . time() }}" alt="{{ $slide->name }}">
                </div>

                <div class="alert alert-info">
                    Kosongkan kolom dibawah jika Anda 
                    tidak ingin mengubah gambar.
                </div>

                <div class="form-group">
                    <label for="image"> Gambar Baru: </label>
                    <input
                        class='form-control {{ !$errors->has('image') ?: 'is-invalid' }}'
                        name="image" type="file" class="form-control">
                    <div class='invalid-feedback'>
                        {{ $errors->first('image') }}
                    </div>
                </div>

                <div class="form-group text-right">
                    <button class="btn btn-primary">
                        Ubah Data
                        <i class="fa fa-check"></i>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection