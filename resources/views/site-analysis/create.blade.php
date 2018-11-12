@extends('shared.layout')
@section('title', 'Penentuan Situs Wisata Terbaik dengan Metode AHP')
@section('content')
<div class="container my-5">
    <h1 class='mb-5'>
        <i class='fa fa-cog'></i>
        Penentuan Situs Wisata Terbaik dengan Metode AHP
    </h1>

    <div class="card">
        <div class="card-header">
            <i class="fa fa-cog"></i>
            Penentuan Situs Wisata Terbaik dengan Metode AHP
        </div>
        <div class="card-body">
           <form action="{{ route('site-analysis.result') }}" method="POST">
               @csrf

                <div class='form-group'>
                    <label for='visitor_count'> Prioritas Aspek J. Pengunjung: </label>
                
                    <input
                        id='visitor_count' name='visitor_count' type='number'
                        min="1" max="10"
                        value='{{ old('visitor_count') }}'
                        placeholder='Prioritas Aspek J. Pengunjung:'
                        class='form-control {{ !$errors->has('visitor_count') ?: 'is-invalid' }}'>
                
                    <div class='invalid-feedback'>
                        {{ $errors->first('visitor_count') }}
                    </div>
                </div>

                <div class='form-group'>
                    <label for='fee'> Prioritas Aspek Harga Tiket Masuk: </label>
                
                    <input
                        id='fee' name='fee' type='number'
                        min="1" max="10"
                        placeholder="Prioritas Aspek Harga Tiket Masuk"
                        value='{{ old('fee') }}'
                        class='form-control {{ !$errors->has('fee') ?: 'is-invalid' }}'>
                
                    <div class='invalid-feedback'>
                        {{ $errors->first('fee') }}
                    </div>
                </div>

                <div class='form-group'>
                    <label for='facility_count'> Prioritas Aspek Jumlah Fasilitas: </label>
                
                    <input
                        id='facility_count' name='facility_count' type='number'
                        min="1" max="10"
                        placeholder='Prioritas Aspek Jumlah Fasilitas'
                        value='{{ old('facility_count') }}'
                        class='form-control {{ !$errors->has('facility_count') ?: 'is-invalid' }}'>
                
                    <div class='invalid-feedback'>
                        {{ $errors->first('facility_count') }}
                    </div>
                </div>

                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary">
                        Lakukan
                        <i class="fa fa-check"></i>
                    </button>
                </div>
           </form>
        </div>
    </div>
</div>
@endsection