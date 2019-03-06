@extends('shared.layout')
@section('title', 'Situs Wisata Terbaik')

@section('extra-head-content')
<style>
    * {
        font-size: 0.85rem;
    }
    
    .card {
        margin-bottom: 2rem;
    }

    .container {
        max-width: 90%;
    }

    .table-sm td, .table-sm th {
        padding: 1px;
    }
</style>
@endsection

@section('content')
<div class="container mt-5">
    <h1 class="mb-5">
        <i class="fa fa-list"></i>
        Situs Wisata Terbaik
    </h1>

    @auth

    <div class="card">
        <div class="card-header">
            <i class="fa fa-icon"></i>
            Hasil Kalkulasi Prioritas Aspek
        </div>
        <div class="card-body">
           <table class="tabel table-sm table-bordered">
               <tbody>
                    <tr class="table-info">
                        <th> ASPEK </th>
                        @foreach ($aspect_comparisons as $aspect_key => $comparisons)
                        <th> {{ $aspects[$aspect_key] }} </th>
                        @endforeach
                    </tr>
                    @foreach ($aspect_comparisons as $aspect_key_r => $comparisons)
                    <tr>
                        <th class="table-info"> {{ $aspects[$aspect_key_r] }} </th>
                        @foreach ($comparisons as $comparison)
                        <td> @number_format($comparison) </td>
                        @endforeach
                    </tr>
                    @endforeach
                    <tr class="table-danger">
                        <th> Total: </th>
                        @foreach ($aspect_comparisons as $aspect_key => $comparisons)
                        <td> @number_format($aspect_comparison_sums[$aspect_key]) </td>
                        @endforeach
                    </tr>
               </tbody>
           </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fa fa-icon"></i>
            Hasil Kalkulasi Prioritas Aspek
        </div>
        <div class="card-body">
           <table class="tabel table-sm table-bordered">
               <tbody>
                    <tr class="table-info">
                        <th> ASPEK </th>
                        @foreach ($normalized_aspect_comparisons as $aspect_key => $comparisons)
                        <th> {{ $aspects[$aspect_key] }} </th>
                        @endforeach
                        <th> Rata-Rata Prioritas </th>
                    </tr>
                    @foreach ($normalized_aspect_comparisons as $aspect_key_r => $priorities)
                    <tr>
                        <th class="table-info"> {{ $aspects[$aspect_key_r] }} </th>
                        @foreach ($priorities as $priority)
                        <td> @number_format($priority) </td>
                        @endforeach

                        <td> @number_format($aspect_priorities[$aspect_key_r]) </td>

                    </tr>
                    @endforeach

               </tbody>
           </table>
        </div>
    </div>

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
                    <tr class="table-info">
                        <td> --- </td>
                        @foreach ($aspects as $aspect_key => $aspect_name)
                        <td> @number_format($aspect_priorities[$aspect_key]) </td>
                        @endforeach
                    </tr>

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

    @endauth

    <div class="card mt-3">
        <div class="card-header">
            Situs Terbaik
        </div>
        <div class="card-body">
            <div class='table-responsive'>
                <table class='table table-sm table-bordered'>
                   <thead class="thead thead-dark">
                        <tr>
                            <th> # </th>
                            <th> Nama </th>
                            <th> Nilai </th>
                        </tr>
                   </thead>
                   <tbody>
                       @foreach ($overall_priorities as $point_id => $priority)
                        <tr>
                            <td> {{ $loop->iteration }}. </td>
                            <td>
                                <a href="{{ route("site.detail", $points[$point_id]->site->id) }}">
                                    {{ $points[$point_id]->name }} </td>
                                </a>
                            <td> @number_format($priority["sum"]) </td>
                        </tr>
                       @endforeach
                   </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection