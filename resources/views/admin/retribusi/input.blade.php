@extends('admin.layout.main')
@section('title', 'Retribusi - Smart Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Retribusi</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Retribusi</a></li>
                        <li class="breadcrumb-item active">Data</li>
                    </ol>
                </div>
                <div class="col-sm-6">
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label for="jenisRetribusi">Jenis Retribusi</label>
                                <select class="form-control" id="jenisRetribusi">
                                    <option value="00">-- Pilih Jenis Retribusi --</option>
                                    <option value="AL">Retribus Jasa Umum</option>
                                    <option value="WY">Retribusi Jasa Usaha</option>
                                    <option value="WY">Retribusi Perizinan Tertentu</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="bulan">Bulan</label>
                                <select class="form-control" id="bulan">
                                    <option value="00">-- Pilih Bulan --</option>
                                    <option value="01">Januari</option>
                                    <option value="02">Februari</option>
                                    <option value="03">Maret</option>
                                    <option value="04">April</option>
                                    <option value="05">Mei</option>
                                    <option value="06">Juni</option>
                                    <option value="07">Juli</option>
                                    <option value="08">Agustus</option>
                                    <option value="09">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="target">Target</label>
                                <input type="text" class="form-control" id="target" placeholder="Input Target">
                            </div>
                            <div class="form-group">
                                <label for="realisasi">Realisasi</label>
                                <input type="text" class="form-control" id="realisasi" placeholder="Input Realisasi">
                            </div>
                            <button type="submit" class="btn btn-primary"><i class='fa fa-plus'></i> Tambah
                                Retribusi</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <label for="">Tabel Data Retribusi</label>
                        <div class="table-responsive">
                            <table class="table dtTable">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Bulan</th>
                                        <th>Target</th>
                                        <th>Realisasi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Container-fluid Ends-->
@endsection

@section('js')
    <script></script>
@endsection
