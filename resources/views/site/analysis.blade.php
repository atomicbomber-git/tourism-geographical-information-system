@extends('shared.layout')
@section('title', 'Hasil Proses AHP terhadap Data Situs Wisata')
@section('content')
<div class="container mt-5">
    <h1 class="mb-5">
        <i class="fa fa-list"></i>
        Hasil Proses AHP terhadap Data Situs Wisata
    </h1>


    @foreach ($aspects as $aspect_key => $aspect_name)
    <div class="card mb-5">
        <div class="card-header">
            <i class="fa fa-list"></i>
            Hasil Kalkulasi Prioritas Lokal <strong> {{ $aspect_name  }} </strong>
        </div>
        <div class="card-body" style="overflow-y: auto">

            <h3> 1. Tabel Perbandingan Lokal </h3>

            <table class="table table-sm table-bordered">
                <tbody>
                    {{-- HEADER ROW --}}
                    <tr class="table-info">
                        <th> {{ $aspect_name }} </th>

                        @foreach($local_comparisons[$aspect_key] as $point_id => $comparisons)
                        <th> {{ $points[$point_id]->name }} </th>
                        @endforeach

                    </tr>

                    @foreach ($local_comparisons[$aspect_key] as $point_id => $comparisons)
                    <tr>
                        <th class="table-info"> {{ $points[$point_id]->name }} </th>
                        
                        @foreach ($comparisons as $comparison_point_id => $comparison)
                        <th> @number_format($comparison) </th>
                        @endforeach

                    </tr>
                    @endforeach

                    <tr class="table-danger">
                        <th> Total </th>
                        @foreach ($local_comparison_sums[$aspect_key] as $point_id => $sum)
                        <th> @number_format($sum) </th>
                        @endforeach
                    </tr>

                </tbody>
            </table>

            <h3 class="mt-5"> 2. Tabel Prioritas Lokal </h3>

            <table class="table table-sm table-bordered">
                <tbody>
                    {{-- HEADER ROW --}}
                    <tr>
                        <th class="table-success"> {{ $aspect_name }} </th>

                        @foreach($local_priorities[$aspect_key] as $point_id => $comparisons)
                        <th class="table-success"> {{ $points[$point_id]->name }} </th>
                        @endforeach
                        
                        <th class="table-success"> Prioritas </th>
                    </tr>

                    @foreach ($local_priorities[$aspect_key] as $point_id => $comparisons)
                    <tr>
                        <th class="table-info"> {{ $points[$point_id]->name }} </th>
                        
                        @foreach ($comparisons as $comparison_point_id => $comparison)
                        <th> @number_format($comparison) </th>
                        @endforeach
                        
                        <th class="table-warning"> @number_format($comparisons->average()) </th>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach

    <div class="card">
        <div class="card-header">
            <i class="fa fa-list"></i>
            Daftar Prioritas Lokal Per-Aspek
        </div>
        <div class="card-body">
            <table class="table table-sm table-bordered">
                <thead class="thead thead-dark">
                    <tr>
                        <th> Alternatif S. Wisata </th>
                        @foreach ($aspects as $aspect_name)
                        <th> {{ $aspect_name }} </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @foreach ($points as $point)
                    <tr>
                        <td> {{ $point->name }} </td>
                        @foreach ($aspects as $aspect_key => $aspect_name)
                        <td> @number_format($local_priorities[$aspect_key][$point->id]->average()) </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    
</div>
@endsection