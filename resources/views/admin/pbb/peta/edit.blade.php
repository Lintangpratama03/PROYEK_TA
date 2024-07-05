@extends('admin.layout.main')
@section('title', 'Edit Peta - Smart Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Edit Peta</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Edit</a></li>
                        <li class="breadcrumb-item active">Peta</li>
                    </ol>
                </div>
                <div class="col-sm-6">
                </div>
            </div>
        </div>

        <div class="container-fluid chart-widget">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h6>Form Edit Peta</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('kelola_peta.update', $wilayah->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="kecamatan">Kecamatan</label>
                                    <input type="text" name="kecamatan" id="kecamatan" class="form-control"
                                        value="{{ $wilayah->kecamatan }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="kelurahan">Kelurahan</label>
                                    <input type="text" name="kelurahan" id="kelurahan" class="form-control"
                                        value="{{ $wilayah->kelurahan }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="geometry">Geometry</label>
                                    <textarea name="geometry" id="geometry" class="form-control" required>{{ json_encode($wilayah->geometry) }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
