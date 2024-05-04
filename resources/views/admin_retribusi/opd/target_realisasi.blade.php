@extends('admin.layout.main')
@section('title', 'Retribusi - Smart Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 style="font-size: 30px;font-weight: bold;">Retribusi</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" style="font-size: 18px;font-weight: 600;"><a href="#">Retribusi</a></li>
                        <li class="breadcrumb-item active" style="font-size: 18px;font-weight: 500;">Target Realisasi</li>
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
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <label for="" style="font-size: 20px;font-weight: bold;">Tabel Target Realisasi</label>
                        <div class="table-responsive">
                            <table class="table tabel_detail_realisasi" style="font-size: 16px;font-weight: 500;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Retribusi</th>
                                        <th>Sudah Diisi</th>
                                        <th>Total Realisasi</th>
                                        <th>Target</th>
                                        <th>Persen</th>
                                        <th>Aksi</th>
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
    <script>

        function datatable_detail_realisasi() {
            var table = $(".tabel_detail_realisasi").DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: '{{ route('retribusi.realisasi.datatable_target_realisasi_opd') }}',
                    type: 'GET',
                },
                columns: [
                    {
                        data: 'no',
                        name: 'no'
                    },
                    {
                        data: 'tahun',
                        name: 'tahun'
                    },
                    {
                        data: 'id_retribusi_opd',
                        name: 'id_retribusi_opd'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah',
                        render: function (data, type, full, meta) {
                            return "<span class='text-success fw-bold' >"+data+"</span>" + " /12";
                        }
                    },
                    {
                        data: 'total_realisasi',
                        name: 'total_realisasi'
                    },
                    {
                        data: 'target',
                        name: 'target'
                    },
                    {
                        data: 'persen',
                        name: 'persen',
                        render: function (data, type, full, meta) {
                            return data + " %";
                        }
                    },
                    { data: 'action', name: 'action' },
                ]
            });
        }

        $(document).ready(function() {
            datatable_detail_realisasi();
        });

    </script>
@endsection
