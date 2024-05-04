@extends('admin.layout.main')
@section('title', 'Target Pajak - Smart Dashboard')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Target Pajak</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Import Target Pajak</a></li>
                        <li class="breadcrumb-item active">index</li>
                    </ol>
                </div>
                <div class="col-sm-6">
                </div>

            </div>
        </div>
    </div>

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Import File Target Pajak</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('target_pajak.import_target_pajak') }}" method="POST" enctype="multipart/form-data"
                    class="dropzone" id="dropzone">
                    @csrf
                    <div class="fallback">
                        <div class="mb-3">
                            <a class="btn btn-primary btn-square" href="{{ asset('format_import/target-pajak.xlsx') }}"
                                type="button">
                                <i class="fa fa-file-excel-o"></i> Contoh Format Excel
                            </a>
                        </div>
                        <div class="form-group">
                            <label for="file" class="form-label">Choose Excel File</label>
                            <div class="custom-file">
                                <input type="file" name="file" id="file" class="custom-file-input">
                                <label class="custom-file-label" for="file">Pilih File...</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" id="btn-import" disabled>
                            <i class="fa fa-upload"></i> Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <style>
            .dropzone {
                border: 2px dashed #007bff;
                border-radius: 5px;
                background: white;
                min-height: 150px;
                padding: 20px 20px;
            }

            .dropzone .fallback {
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: column;
            }

            .custom-file-input {
                cursor: pointer;
            }

            .custom-file-label {
                background-color: #f8f9fa;
                border: 1px solid #ced4da;
                border-radius: 0.25rem;
                cursor: pointer;
                padding: 0.375rem 0.75rem;
            }

            .custom-file-label::after {
                content: "Telusuri";
            }
        </style>
        <div class="col-xl-12">
            <div class="card o-hidden">
                <div class="card-header pb-0">
                    <h6>Target Pajak</h6>
                    <div class="mb-3 draggable">
                        <div class="row">
                            <div class="col-xl-6">
                                <select id="filter-tahun-target" class="form-control btn-square">
                                    <option value="">-- Filter Tahun --</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm" id="datatable-target-pajak-tw">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Jenis Pajak</th>
                                        <th>Target</th>
                                        <th>Target TW 1</th>
                                        <th>Target TW 2</th>
                                        <th>Target TW 3</th>
                                        <th>Target TW 4</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection


    @section('js')
        <script>
            function datatable_target_pajak() {
                let table = $("#datatable-target-pajak-tw").DataTable({
                    "dom": 'rtip',
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    searchDelay: 2000,
                    ajax: {
                        url: "{{ route('target_pajak.datatable_target_pajak') }}",
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'tahun',
                            name: 'tahun',
                        },
                        {
                            data: 'jenis_pajak',
                            name: 'jenis_pajak'
                        },
                        {
                            data: 'target_tahun',
                            name: 'target_tahun'
                        },
                        {
                            data: 'target_tw1',
                            name: 'target_tw1'
                        },
                        {
                            data: 'target_tw2',
                            name: 'target_tw2'
                        },
                        {
                            data: 'target_tw3',
                            name: 'target_tw3'
                        },
                        {
                            data: 'target_tw4',
                            name: 'target_tw4'
                        },
                    ],
                    initComplete: function() {
                        $('#filter-tahun-target').on('change', function() {
                            var tahun = $(this).val();
                            $('#datatable-target-pajak-tw').DataTable().columns(1).search(tahun == '' ? '' :
                                '^' + $.fn.dataTable.util.escapeRegex(tahun) + '$', true, false).draw();
                        });

                    },
                    order: [
                        [0, 'desc']
                    ],
                });
            }
            $(document).ready(function() {
                datatable_target_pajak();
                $('#file').on('change', function() {
                    if ($(this).val() != '') {
                        $('#btn-import').prop('disabled', false);
                    } else {
                        $('#btn-import').prop('disabled', true);
                    }
                });
            })
        </script>
    @endsection
