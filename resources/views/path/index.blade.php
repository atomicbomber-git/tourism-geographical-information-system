@extends('shared.layout')
@section('title', 'Kelola Jalur')
@section('content')
<div class="container mt-5">
    <h1 class="mb-5">
        <i class="fa fa-arrow-circle-o-right"></i>
        Kelola Jalur
    </h1>

    <div class="row my-4">
        <div class="col-2">
            <a href="{{ route('path.create') }}" class="btn btn-dark">
                Tambahkan Jalur
                <i class="fa fa-plus"></i>
            </a>
        </div>
        <div class="col"></div>
        <div class="col-2"></div>
    </div>

    @include('shared.message', ['session_key' => 'message.success', 'state' => 'success'])

    <div class="card">
        <div class="card-header">
            <i class="fa fa-arrow-circle-o-right"></i>
            Kelola Jalur
        </div>
        <div class="card-body">
            <table class="table table-sm table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th> No. </th>
                        <th> Asal </th>
                        <th> Tujuan </th>
                        <th> Kendali </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paths as $path)
                    <tr>
                        <td> {{ $loop->iteration }}. </td>
                        <td> {{ $path->point_from->name }} </td>
                        <td> {{ $path->point_to->name }} </td>
                        <td>
                            <form method="POST" action="{{ route('path.delete', $path) }}" class="d-inline-block">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i>
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