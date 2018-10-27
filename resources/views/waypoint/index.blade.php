@extends('shared.layout')
@section('title', 'Kelola Waypoint')
@section('content')
<div class="container my-5">
    <h1 class="mb-5">
        <i class="fa fa-road"></i>
        Kelola Waypoint
    </h1>

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