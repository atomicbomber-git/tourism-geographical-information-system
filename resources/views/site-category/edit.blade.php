@extends('shared.layout')
@section('title', 'Sunting Kategori Situs')
@section('content')
<div class="container my-5">
    <h1 class='mb-5'>
        <i class='fa fa-pencil'></i>
        Sunting Kategori Situs
    </h1>

    @include('shared.message', ['session_key' => 'message.success', 'state' => 'success'])

    <div class="card" style="max-width: 30rem">
        <div class="card-header">
            <i class="fa fa-pencil"></i>
            Sunting Kategori Situs
        </div>
        <div class="card-body">
            <form action='{{ route('site-category.update', $site_category) }}' method='POST'>
                @csrf
                <div class='form-group'>
                    <label for='name'> Nama: </label>
                
                    <input
                        id='name' name='name' type='text'
                        placeholder='Nama'
                        value='{{ old('name', $site_category->name) }}'
                        class='form-control {{ !$errors->has('name') ?: 'is-invalid' }}'>
                
                    <div class='invalid-feedback'>
                        {{ $errors->first('name') }}
                    </div>
                </div>

                <div class="form-group text-right">
                    <button type='submit' class='btn btn-primary'>
                        Ubah Data
                        <i class='fa fa-check'></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection