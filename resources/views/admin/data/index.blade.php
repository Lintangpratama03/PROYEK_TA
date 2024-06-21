@extends('admin.layout.main')
@section('title', 'Import Data - Smart Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Import Data</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Data</a></li>
                        <li class="breadcrumb-item active">List</li>
                    </ol>
                </div>
                <div class="col-sm-6">
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid chart-widget">

        <div class="row">
            <div class="card o-hidden">
                <div class="card-header pb-0">
                    <h6>Daftar Data</h6>
                </div>
                <div class="card-body">
                    {{-- notifikasi form validasi --}}
                    @if ($errors->has('file'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('file') }}</strong>
                        </span>
                    @endif

                    {{-- notifikasi sukses --}}
                    @if ($sukses = Session::get('sukses'))
                        <div class="alert alert-success dark alert-dismissible fade show" role="alert"><i
                                data-feather="thumbs-up"></i>
                            <p> {{ $sukses }} </p>
                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($error = Session::get('error'))
                        <div class="alert alert-danger dark alert-dismissible fade show" role="alert"><i
                                data-feather="alert-octagon"></i>
                            <p> {{ $error }} </p>
                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-data">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul Data</th>
                                    <th>Database Table</th>
                                    <th>Menu Yang Digunakan</th>
                                    <th>Last Update</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="loading" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content py-md-5 px-md-4 p-sm-3 p-4 text-center">

                    <h3>SUKSES</h3>
                    <i class="fa fa-check"></i>
                    <p class="r3 px-md-5 px-sm-1">Data Berhasil di update</p>

                    <div class="text-center mb-3"> <button data-bs-dismiss="modal"
                            class="btn btn-primary w-50 rounded-pill b1">OK</button> </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="import_modal" aria-hidden="true" aria-labelledby="import_modal" role="dialog">
        <div class="modal-dialog modal-simple modal-top modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_title">Upload Data</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_import" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body" id="modal_body">
                        <div class="mb-3 input-group-square">
                            <label class="form-label">Import File Excel</label>
                            <div class="input-group"><span class="input-group-text"><i class="fa fa-download"></i></span>
                                <input class="form-control input-group-air" id="file" name="file" type="file"
                                    placeholder="File Excel" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <a id="file_template" class="btn btn-primary btn-square" href="" type="button"
                                onclick=""><i class="fa fa-file-excel-o"></i> Contoh Format Excel<span
                                    class="caret"></span></a>
                        </div>
                    </div>
                    <div class="modal-footer" id="modal_footer">
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="btn_simpan">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal_ambil_data" aria-hidden="true" aria-labelledby="modal_ambil_data" role="dialog">
        <div class="modal-dialog modal-simple modal-top modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_title">Filter Ambil Data</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_ambil_data" method="POST">
                    @csrf
                    <div class="modal-body" id="modal_body">
                        <div class="row">
                            <div class="mb-3 col-sm-6">
                                <select class="form-select" aria-label="Default select example" id="tahun"
                                    name="tahun">
                                    @foreach (array_combine(range(date('Y'), 2018), range(date('Y'), 2018)) as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-sm-6">
                                <select class="form-select" aria-label="Default select example" id="bulan"
                                    name="bulan">
                                    @php
                                        $bulansaatini = date('n');
                                    @endphp
                                    @foreach (getMonthList() as $index => $value)
                                        <option value="{{ $index }}"
                                            @if ($index == $bulansaatini) selected @endif>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" id="modal_footer">
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="btn_filter_ambil_data">Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@section('js')
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript">
        function formatRupiah(angka) {
            var options = {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2,
            };
            var formattedNumber = angka.toLocaleString('ID', options);
            return formattedNumber;
        }

        function openLoading() {
            Swal.fire({
                title: 'Mohon Tunggu...! ',
                html: 'Sedang Mengambil Data',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
        }

        function closeLoading() {
            Swal.close();
            // You can add additional logic here after the loading is complete
            Swal.fire({
                icon: 'success',
                title: 'Operation Complete',
                text: 'The loading is complete!',
            });
        }

        function import_excel(this_) {
            var route = $(this_).data("route");
            var route_import = "{{ url('data') }}/" + route;
            $('#form_import').attr('action', route_import);

            var file = $(this_).data("file");
            var file_import = "{{ asset('format_import') }}/" + file
            $('#file_template').attr('href', file_import);

            // console.log(route_import);
            $("#import_modal").modal("show");
        }

        function get_data(this_) {
            var route = $(this_).data("route");
            console.log(route);
            if (route === "getdata_target_realisasi" || route === "getdata_objek_pajak_wilayah" || route ===
                "getdata_penerimaan_tahun_sppt" || route === "getdata_kepatuhan_objek") {
                // Hanya tampilkan filter tahun
                $('#modal_ambil_data .modal-body').html(`
                    <div class="row">
                        <div class="mb-3 col-sm-12">
                            <select class="form-select" aria-label="Default select example" id="tahun" name="tahun">
                                @foreach (array_combine(range(date('Y'), 2018), range(date('Y'), 2018)) as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                `);
            } else if (route === "getdata_tunggakan" || route === "getdata_tunggakan_level" || route ===
                "getdata_rekap_tunggakan" || route === "getdata_detail_tunggakan" || route ===
                "getdata_detail_objek_pajak" ||
                route === "getdata_pelaporan") {
                // Hanya tampilkan filter tahun
                $('#modal_ambil_data .modal-body').html(`
                    <div class="row">
                        <div class="mb-3 col-sm-12">
                            <p class="h4">Apakah anda yakin ingin memperbarui data?</p>
                        </div>
                    </div>
                `);
            } else if (route === "getdata_penerimaan_harian") {
                // Hanya tampilkan filter tahun
                $('#modal_ambil_data .modal-body').html(`
                <div class="row">
                    <div class="mb-3 col-sm-12">
                        <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                `);
            } else {
                // Tampilkan filter tahun dan bulan
                $('#modal_ambil_data .modal-body').html(`
                    <div class="row">
                        <div class="mb-3 col-sm-6">
                            <select class="form-select" aria-label="Default select example" id="tahun" name="tahun">
                                @foreach (array_combine(range(date('Y'), 2018), range(date('Y'), 2018)) as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-sm-6">
                            <select class="form-select" aria-label="Default select example" id="bulan" name="bulan">
                                @php
                                    $bulansaatini = date('n');
                                @endphp
                                @foreach (getMonthList() as $index => $value)
                                    <option value="{{ $index }}" @if ($index == $bulansaatini) selected @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
        `);
            }
            $('#modal_ambil_data').modal("show");
            $('#btn_filter_ambil_data').click(function(e) {
                e.preventDefault();
                $('#modal_ambil_data').modal("hide");
                var route = $(this_).data("route");
                var route_getdata = "{{ url('data') }}/" + route;

                $.ajax({
                    url: route_getdata,
                    type: "POST",
                    cache: false,
                    data: {
                        "tahun": $("#tahun").val(),
                        "bulan": $("#bulan").val(),
                        "tanggal": $("#tanggal").val(),
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        openLoading()
                    },
                    success: function(respon) {
                        console.log(respon);
                        closeLoading();

                        if (respon.status == 1) {
                            //   toastr.success("Berhasil Mengambil Data");
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: 'Berhasil Mengambil Data !',
                                // text: 'Berhasil Mengambil ' + respon.total_data + ' Data !',
                            });
                            location.reload();

                        } else {
                            //   toastr.error("Gagal Mengambil Data");
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal Mengambil Data!',
                            });
                        }

                    }
                })
            });
            // console.log(route)

        }

        function table_daftar_data() {
            var table = $(".table-data").DataTable({
                "dom": 'frtip',
                paging: false,
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: '{{ route('data.datatables') }}',
                columns: [{
                        data: 'data',
                        name: 'data',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'data',
                        name: 'data'
                    },
                    {
                        data: 'table',
                        name: 'table'
                    },
                    {
                        data: 'menu',
                        name: 'menu'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi'
                    }
                ],
                // order: [[0, 'desc']],
            });
        }

        $(document).ready(function() {

            table_daftar_data();

        })
    </script>
@endsection
