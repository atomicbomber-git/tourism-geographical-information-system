@extends('shared.layout')
@section('title', 'Masuk')

@section("extra-head-content")
<style>
    body {
        background: white;
    }
</style>
@endsection

@section('content')
<div class="container my-5">

    <div class="text-center">
        <img
            style="margin:auto"
            height="300px"
            src="{{ asset("login.jpeg") }}"
        >
    </div>
    

    <div class="card mx-auto" style="width: 30rem">
        <div class="card-body">

            

            <div class="text-center">
                <img
                    style="margin: auto"
                    class="mx-5 my-3"
                    height="auto" width="140"
                    src="{{ asset("bengkayang.png") }}">
            </div>

            <h1 class="h4 text-center text-uppercase mb-5">
                Sistem Informasi Geografis Objek Wisata dengan
                Algoritma Dijkstra untuk Rute Terdekat dan Metode Analytical Hierarchy Process
                (AHP) untuk Rekomendasi <br/>
                (Studi Kasus Kabupaten Bengkayang)
            </h1>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class='form-group'>
                    <label for='username'> Nama Pengguna: </label>
                
                    <input
                        id='username' name='username' type='text'
                        placeholder='Nama Pengguna'
                        value='{{ old('username') }}'
                        class='form-control {{ !$errors->has('username') ?: 'is-invalid' }}'>
                
                    <div class='invalid-feedback'>
                        {{ $errors->first('username') }}
                    </div>
                </div>

                <div class='form-group'>
                    <label for='password'> Kata Sandi: </label>
                
                    <input
                        id='password' name='password' type='password'
                        placeholder='Kata Sandi'
                        value='{{ old('password') }}'
                        class='form-control {{ !$errors->has('password') ?: 'is-invalid' }}'>
                
                    <div class='invalid-feedback'>
                        {{ $errors->first('password') }}
                    </div>
                </div>

                <div class="form-group text-right">
                    <button class="btn btn-primary">
                        Masuk
                        <i class="fa fa-sign-in"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection