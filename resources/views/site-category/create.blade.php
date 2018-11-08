@extends('shared.layout')
@section('title', 'Tambahkan Kategori Situs Baru')
@section('content')
<div class="container my-5">
    <h1 class='mb-5'>
        <i class='fa fa-plus'></i>
        Tambahkan Kategori Situs Baru
    </h1>

    <div class="card" style="max-width: 30rem">
        <div class="card-header">
            <i class="fa fa-icon"></i>
            Tambahkan Kategori Situs Baru
        </div>
        <div class="card-body">
            <form action="{{ route('site-category.store') }}" method="POST">
                @csrf
                <div class='form-group'>
                    <label for='name'> Nama: </label>
                
                    <input
                        id='name' name='name' type='text'
                        placeholder='Nama'
                        value='{{ old('name') }}'
                        class='form-control {{ !$errors->has('name') ?: 'is-invalid' }}'>
                
                    <div class='invalid-feedback'>
                        {{ $errors->first('name') }}
                    </div>
                </div>

                <div class="form-group text-right">
                    <button class="btn btn-primary">
                        Tambahkan
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection