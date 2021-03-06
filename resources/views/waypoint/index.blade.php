@extends('shared.layout')
@section('title', 'Kelola Waypoint')
@section('content')
<div class="container my-5">
    <h1 class="mb-5">
        <i class="fa fa-road"></i>
        Kelola Waypoint
    </h1>

    <div class="row my-4">
        <div class="col">
            <a href="{{ route('waypoint.create') }}" class="btn btn-dark">
                Tambahkan Waypoint Baru
                <i class="fa fa-plus"></i>
            </a>
        </div>
        <div class="col-2"></div>
        <div class="col"></div>
    </div>

    @include('shared.message', ['session_key' => 'message.success', 'state' => 'success'])

    <div class="card">
        <div class="card-header">
            <i class="fa fa-road"></i>
            Kelola Waypoint
        </div>
        <div class="card-body">
           
            <table class="table table-sm table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th> No. </th>
                        <th> Nama </th>
                        <th> Latitude </th>
                        <th> Longitude </th>
                        <th> Kendali </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($points as $point)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $point->name }} </td>
                        <td> {{ $point->latitude }} </td>
                        <td> {{ $point->longitude }} </td>
                        <td>
                            <a href="{{ route('waypoint.edit', $point) }}" class="btn btn-dark btn-sm">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <form action="{{ route('waypoint.delete', $point) }}" method="POST" class="d-inline-block">
                                @method('DELETE')
                                @csrf
                                <button class="btn btn-danger btn-sm" type="submit">
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