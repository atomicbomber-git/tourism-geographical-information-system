@extends('shared.layout')
@section('title', 'Kelola Kategori Situs')
@section('content')
<div class="container my-5">
    <h1 class='mb-5'>
        <i class='fa fa-list'></i>
        Kelola Kategori Situs
    </h1>

    @include('shared.message', ['session_key' => 'message.success', 'state' => 'success'])

    <div class="row my-4">
        <div class="col">
            <a href="{{ route('site-category.create') }}" class="btn btn-dark">
                Tambahkan Kategori Situs
                <i class="fa fa-plus"></i>
            </a>
        </div>
        <div class="col-2"></div>
        <div class="col"></div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fa fa-list"></i>
            Kelola Kategori Situs
        </div>
        <div class="card-body">
            <div class='table-responsive'>
                <table class='table table-sm table-striped'>
                   <thead class="thead-dark">
                        <tr>
                            <th> No. </th>
                            <th> Nama </th>
                            <th> Kendali </th>
                        </tr>
                   </thead>
                   <tbody>
                       @foreach ($site_categories as $category)
                        <tr>
                            <td> {{ $loop->iteration }}. </td>
                            <td> {{ $category->name }} </td>
                            <td>

                                <a href="{{ route('site-category.edit', $category) }}" class="btn btn-dark btn-sm">
                                    <i class="fa fa-pencil"></i>
                                </a>

                                @if($category->sites_count == 0)
                                <form action='{{ route('site-category.delete', $category) }}' method='POST' class='d-inline-block'>
                                    @csrf
                                    <button type='submit' class='btn btn-danger btn-sm'>
                                        <i class='fa fa-trash'></i>
                                    </button>
                                </form>
                                @else
                                <button disabled type='submit' class='btn btn-danger btn-sm'>
                                    <i class='fa fa-trash'></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                       @endforeach
                   </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection