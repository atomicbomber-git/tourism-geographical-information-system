@extends('shared.layout')
@section('title', 'Kelola Situs Wisata')
@section('content')
<div class="container my-5">
    <h1 class="mb-5">
        <i class="fa fa-tree"></i>
        Kelola Situs Wisata
    </h1>

    <div class="row my-4">
        <div class="col">
            <a href="{{ route('site.create') }}" class="btn btn-dark">
                Tambahkan Situs Wisata Baru
                <i class="fa fa-plus"></i>
            </a>
        </div>
        <div class="col-2"></div>
        <div class="col"></div>
    </div>

    @include('shared.message', ['session_key' => 'message.success', 'state' => 'success'])

    <div class="card">
        <div class="card-header">
            <i class="fa fa-tree"></i>
            Kelola Situs Wisata
        </div>
        <div class="card-body">
           <table class="table table-sm table-striped">
               <thead class="thead thead-dark">
                   <tr>
                       <th> No. </th>
                       <th> Nama </th>
                       <th> J. Pengunjung Tahunan </th>
                       <th> J. Fasilitas </th>
                       <th> H. Tiket Masuk </th>
                       <th> Kategori </th>
                       <th> Aksi </th>
                   </tr>
               </thead>

               <tbody>
                   @foreach ($points as $point)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $point->name }} </td>
                        <td> {{ $point->site->visitor_count }} </td>
                        <td> {{ $point->site->facility_count }} </td>
                        <td> {{ number_format($point->site->fee, 0, ',', '.') }} </td>
                        <td> {{ $point->site->category->name }} </td>
                        <td>
                            <a href="{{ route('site.edit', $point->site) }}" class="btn btn-dark btn-sm">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <form action='{{ route('site.delete', $point->site) }}' method='POST' class='d-inline-block'>
                                @method('DELETE')
                                @csrf
                                <button type='submit' class='btn btn-danger btn-sm'>
                                    <i class='fa fa-trash'></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                   @endforeach
               </tbody>
           </table>
        </div>
    </div>
</div>
@endsection