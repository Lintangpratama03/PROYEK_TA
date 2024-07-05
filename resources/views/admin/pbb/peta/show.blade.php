@extends('admin.layout.main')
@section('title', 'Detail Peta - Smart Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Detail Peta</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Detail</a></li>
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
                            <h6>Data Peta</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="kecamatan">Kecamatan</label>
                                <input type="text" name="kecamatan" id="kecamatan" class="form-control"
                                    value="{{ $wilayah->kecamatan }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="kelurahan">Kelurahan</label>
                                <input type="text" name="kelurahan" id="kelurahan" class="form-control"
                                    value="{{ $wilayah->kelurahan }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="geometry">Geometry</label>
                                <textarea name="geometry" id="geometry" class="form-control" readonly>{{ json_encode($wilayah->geometry) }}</textarea>
                            </div>
                            <a href="{{ route('kelola_peta.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
