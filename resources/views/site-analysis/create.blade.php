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

                <div class="alert alert-info">
                    <i class="fa fa-info"></i>
                    Mohon masukkan nilai dengan rentang <strong>
                            1-10 </strong> untuk kolom-kolom dibawah ini.
                </div>

                @inject('siteAnalysisPriorityOptions', 'App\Interfaces\SiteAnalysisPriorityOptions')

                <div class='form-group'>
                    <label for='visitor_count'> Prioritas Aspek Jumlah Pengunjung: </label>
                    <select name='visitor_count' id='visitor_count' class='form-control'>
                        @foreach($siteAnalysisPriorityOptions->visitor_count() as $name => $priority)
                        <option {{ old('visitor_count') !== $priority ?: 'selected' }} value='{{ $priority }}'> {{ $name }} </option>
                        @endforeach
                    </select>
                    <div class='invalid-feedback'>
                        {{ $errors->first('visitor_count') }}
                    </div>
                </div>

                <div class='form-group'>
                    <label for='fee'> Prioritas Aspek Harga Tiket Masuk: </label>
                    <select name='fee' id='fee' class='form-control'>
                        @foreach($siteAnalysisPriorityOptions->fee() as $name => $priority)
                        <option {{ old('fee') !== $priority ?: 'selected' }} value='{{ $priority }}'> {{ $name }} </option>
                        @endforeach
                    </select>
                    <div class='invalid-feedback'>
                        {{ $errors->first('fee') }}
                    </div>
                </div>

                <div class='form-group'>
                    <label for='facility_count'> Prioritas Aspek Jumlah Fasilitas: </label>
                    <select name='facility_count' id='facility_count' class='form-control'>
                        @foreach($siteAnalysisPriorityOptions->facility_count() as $name => $priority)
                        <option {{ old('facility_count') !== $priority ?: 'selected' }} value='{{ $priority }}'> {{ $name }} </option>
                        @endforeach
                    </select>
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
