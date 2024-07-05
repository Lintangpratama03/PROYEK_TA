@extends('admin.layout.main')
@section('title', 'Kelola Peta - Smart Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Kelola Peta</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Kelola</a></li>
                        <li class="breadcrumb-item active">Peta</li>
                    </ol>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('kelola_peta.create') }}" class="btn btn-primary float-right">Tambah Peta</a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="container-fluid chart-widget">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h6>Data Peta</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Kecamatan</th>
                                        <th>Kelurahan</th>
                                        <th>Geometry</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($geojson['features'] as $feature)
                                        <tr>
                                            <td>{{ $feature['properties']['id'] }}</td>
                                            <td>{{ $feature['properties']['kecamatan'] }}</td>
                                            <td>{{ $feature['properties']['kelurahan'] }}</td>
                                            <td>{{ json_encode($feature['geometry']) }}</td>
                                            <td>
                                                <a href="{{ route('kelola_peta.show', $feature['properties']['id']) }}"
                                                    class="btn btn-info">Show</a>
                                                <a href="{{ route('kelola_peta.edit', $feature['properties']['id']) }}"
                                                    class="btn btn-warning">Edit</a>
                                                <form
                                                    action="{{ route('kelola_peta.destroy', $feature['properties']['id']) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
