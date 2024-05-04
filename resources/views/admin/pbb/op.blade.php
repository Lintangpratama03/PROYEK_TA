@extends('admin.layout.main')
@section('title', 'Objek Pajak PBB - Smart Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12 col-xl-6 col-lg-6">
                    <h3>Objek Pajak PBB</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">PBB</a></li>
                        <li class="breadcrumb-item active">Objek Pajak</li>
                    </ol>
                </div>
                <div class="col-sm-12 col-xl-6 col-lg-6 d-flex justify-content-end text-center">
                    <a class="btn btn-primary btn-sm" href="{{ route('pbb.op.search') }}"><i class="fa fa-search"></i> Cari
                        Objek Pajak</a>
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid chart-widget">
        <div class="row">
            <div class="col-xl-12">
                <div class="row mb-4">
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 mb-2">
                        <select name="role_code" id="tahun_filter" class="form-control btn-square">
                            <option value="">Pilih Tahun SPPT</option>
                            @foreach (array_combine(range(date('Y'), 1900), range(date('Y'), 1900)) as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 mb-2">
                        <select name="kecamatan"
                            id="kecamatan"class="form-control btn-square js-example-basic-single col-sm-12"
                            style="border: 1px solid #808080;">
                            <option value="" class = "d-flex align-items-center">Pilih Kecamatan</option>
                        </select>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 mb-2">
                        <select name="kelurahan"
                            id="kelurahan"class="form-control btn-square js-example-basic-single col-sm-12"
                            style="border: 1px solid #808080;">
                            <option value="" class = "d-flex align-items-center">Pilih Kelurahan</option>
                        </select>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 mb-2">
                        <div class="input-group-btn btn btn-square p-0">
                            <a class="btn btn-primary btn-square" type="button"
                                onclick="filterTahunPembayaran()">Terapkan<span class="caret"></span></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card o-hidden">
                    <div class="card-header pb-0">
                        <h6>Kepatuhan Wajib Pajak</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-kepatuhan-wp">
                                <thead>
                                    <tr>
                                        <th>Tahun SPPT</th>
                                        <th>NOP Terbit</th>
                                        <th>NOP Tepat Waktu</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="col-xl-12">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6>Pembayaran Paling Awal</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-pembayaran-awal">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal Pembayaran</th>
                                            <th>NOP</th>
                                            <th>Wajib Pajak</th>
                                            <th>Alamat OP</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6>Pembayaran Paling Tinggi</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-pembayaran-tinggi">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NOP</th>
                                            <th>Wajib Pajak</th>
                                            <th>Alamat OP</th>
                                            <th>Nominal</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-xl-10 col-lg-10 col-md-4 col-sm-12 mb-2">
                <div class="draggable">
                    <div class="input-group">
                        <input type="hidden" id="tunggakan" value="tunggakan">
                        <select name="role_code" id="tahun" class="form-control btn-square">
                            <option value="">Pilih Tahun SPPT</option>
                            @foreach (array_combine(range(date('Y'), 1900), range(date('Y'), 1900)) as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 mb-2">
                <div class="input-group-btn btn btn-square p-0">
                    <a class="btn btn-primary btn-square" type="button" onclick="filterTahun()">Terapkan<span
                            class="caret"></span></a>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card o-hidden">
                    <div class="card-header pb-0">
                        <h6>Objek Pajak per Wilayah</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-op-wilayah">
                                <thead>
                                    <tr>
                                        <th>Kecamatan</th>
                                        <th>Kelurahan</th>
                                        <th>Jumlah OP </th>
                                        <th>Nominal</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card o-hidden">
                    <div class="card-header pb-0">
                        <h6>Objek Pajak Per Wilayah</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="row">
                                    <div class="col-xl-10 col-sm-12 col-md-12">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <label for="kategori">Pilih Kategori</label><br>
                                            </div>
                                            <div class="col-xl-12">
                                                <select name="kategori" id="kategori"
                                                    class="form-control btn-square"
                                                    style="border: 1px solid #808080;">
                                                    <option value="nop" class="d-flex align-items-center">NOP
                                                    </option>
                                                    <option value="nominal" class="d-flex align-items-center">NOMINAL
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-sm-12 col-md-12">
                                        <div class="input-group-btn btn btn-square p-0 mt-1">
                                            <a class="btn btn-primary btn-square" type="button"
                                                onclick="filterOP()">Terapkan<span class="caret"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bar-chart-widget">
                        <div class="bottom-content card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div id="chart-line"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="col-xl-12">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6 class="mb-4">Jumlah Transaksi PBB</h6>
                            <div class="row mb-4">
                                <div class="col-xl-5 col-md-6 col-sm-12 mb-2">
                                    <select name="role_code" id="filter_tahun_jumlah_transaksi_pbb" class="form-control btn-square">
                                        @foreach (array_combine(range(date('Y'), 1900), range(date('Y'), 1900)) as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-5 col-md-6 col-sm-12 mb-2">
                                    <select name="role_code" id="filter_metode_pembayaran_jumlah_transaksi_pbb" class="form-control btn-square">
                                        <option value="BANK JATIM">BANK JATIM</option>
                                    </select>
                                </div>
                                <div class="col-xl-2 col-md-6 col-sm-12 mb-2">
                                    <div class="input-group-btn btn btn-square p-0">
                                        <a class="btn btn-primary btn-square" type="button"
                                            onclick="filterJTB()">Terapkan<span class="caret"></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                @php
                                    $hari = array(                                                    
                                                "Senin",
                                                "Selasa",
                                                "Rabu",
                                                "Kamis",
                                                "Jumat",
                                                "Sabtu",
                                                "Minggu"
                                            );
                                    // $jam = array_map(function($i) {
                                    //     return sprintf("%02d", $i) . ":00";
                                    // }, range(0, 23));
                                    
                                @endphp
                                <table class="table table-jumlah-transaksi-pbb">
                                    <thead>                                      
                                        <tr>
                                            <th>Jam</th>
                                            @foreach ($hari as $item)
                                                <th>{{ $item }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Container-fluid Ends-->
@endsection

@section('js')
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
        <!-- Plugins JS Ends-->
    <script>
        let v_tahun = $('#tahun').val();
        var curKelurahan = $('#kelurahan').val();
        var curKecamatan = $('#kecamatan').val();
        let v_kategori = $('#kategori').val();
        const day = new Date();
        var currentYear = day.getFullYear();

        function newexportaction(e, dt, button, config) {
            var self = this;
            var oldStart = dt.settings()[0]._iDisplayStart;
            dt.one('preXhr', function(e, s, data) {
                // Just this once, load all data from the server...
                data.start = 0;
                data.length = 2147483647;
                dt.one('preDraw', function(e, settings) {
                    // Call the original action function
                    if (button[0].className.indexOf('buttons-copy') >= 0) {
                        $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                        $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                        $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                        $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-print') >= 0) {
                        $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                    }
                    dt.one('preXhr', function(e, s, data) {
                        // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                        // Set the property to what it was before exporting.
                        settings._iDisplayStart = oldStart;
                        data.start = oldStart;
                    });
                    // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                    setTimeout(dt.ajax.reload, 0);
                    // Prevent rendering of the full data to the DOM
                    return false;
                });
            });
            // Requery the server with the new one-time export settings
            dt.ajax.reload();
        };

        function formatRupiah(angka) {
            var options = {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2,
            };
            var formattedNumber = angka.toLocaleString('ID', options);
            return formattedNumber;
        }

        function bulatkanAngka(angka) {
            var isMin = angka < 0 ? '-' : '';
            angka = Math.abs(angka);

            if (angka >= 1000000000000) {
                return isMin + 'Rp. ' + (angka / 1000000000000).toFixed(2) + ' Triliun';
            } else if (angka >= 1000000000) {
                return isMin + 'Rp. ' + (angka / 1000000000).toFixed(2) + ' Miliar';

            } else if (angka >= 1000000) {
                return isMin + 'Rp. ' + (angka / 1000000).toFixed(2) + ' Juta';
            } else if (angka >= 100000 || angka >= 10000 || angka >= 1000) {
                return isMin + 'Rp. ' + angka
            } else {
                return 'Rp. 0'
            }
        }

        function filterTahun() {
            var tahun = $('#tahun').val();
            var kategori = $('#kategori').val();
            $(".table-op-wilayah").DataTable().destroy();
            if (tahun !== null) {
                table_op_wilayah(tahun);
                get_chart_op_wilayah(kategori, tahun);
            }
        }

        function filterTahunPembayaran() {
            var tahunPem = $('#tahun_filter').val();
            var tahun = (tahunPem === "") ? null : tahunPem;
            var kecamatan = $('#kecamatan').val();
            var kelurahan = $('#kelurahan').val();
            var kategori = $('#kategori').val();
            var currentYear = day.getFullYear();

            $(".table-pembayaran-awal").DataTable().destroy();
            $(".table-pembayaran-tinggi").DataTable().destroy();
            $(".table-op-wilayah").DataTable().destroy();
            $(".table-kepatuhan-wp").DataTable().destroy();
            if (tahun !== null) {
                table_kepatuhan_wp(tahun, kecamatan, kelurahan);
                table_pembayaran_awal(tahun, kecamatan, kelurahan);
                table_pembayaran_tinggi(tahun, kecamatan, kelurahan);
                table_op_wilayah(tahun);
            }
            if (tahun == null) {
                table_kepatuhan_wp(null, kecamatan, kelurahan);
                table_pembayaran_awal(currentYear, kecamatan, kelurahan);
                table_pembayaran_tinggi(currentYear, kecamatan, kelurahan);
                table_op_wilayah(currentYear);
            }
            //table_op_wilayah(tahunPem, uptd, kecamatan, kelurahan);
            //get_chart_op_wilayah(kategori, tahun, uptd, kecamatan);
        }

        function filterOP() {
            var tahun = $('#tahun').val();
            var kecamatan = $('#kecamatan').val();
            var kelurahan = $('#kelurahan').val();
            var kategori = $('#kategori').val();
            if (tahun !== null || kategori !== '') {
                get_chart_op_wilayah(kategori, tahun, kecamatan);
            }
        }

        function filterWilayah() {
            $("#kecamatan").html('');
            $.ajax({
                url: '{{ route('pbb.op.get_wilayah') }}',
                type: "GET",
                data: {
                    "wilayah": 'uptd'
                },
                dataType: 'json',
                success: function(result) {
                    console.log(result);
                    $('#kecamatan').append(
                        '<option value="" "class = "d-flex align-items-center">Pilih Kecamatan</option>'
                    );
                    $.each(result, function(key, value) {
                        $("#kecamatan").append('<option value="' + value
                            .nama_kecamatan + '"class = "d-flex align-items-center">' + value
                            .nama_kecamatan + '</option>');
                    });
                }
            });
            $('#kecamatan').on('change', function() {
                var kecamatan = this.value;
                $("#kelurahan").html('');
                $.ajax({
                    url: '{{ route('pbb.penerimaan.get_wilayah') }}',
                    type: "GET",
                    data: {
                        "wilayah": 'kecamatan',
                        "data": kecamatan
                    },
                    dataType: 'json',
                    success: function(result) {
                        console.log(result);
                        $('#kelurahan').append(
                            '<option value="" "class = "d-flex align-items-center">Pilih Kelurahan</option>'
                        );
                        $.each(result, function(key, value) {
                            $("#kelurahan").append('<option value="' + value
                                .kelurahan + '"class = "d-flex align-items-center">' + value
                                .kelurahan + '</option>');
                        });
                    }
                });
            });
        }

        function table_kepatuhan_wp(tahun = null ,kecamatan = curKecamatan, kelurahan =curKelurahan) {
            var table = $(".table-kepatuhan-wp").DataTable({
                dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'>>" +
                    // dengan Button
                    "<'row'<'col-sm-12'tr>>" + // Add table
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Export to Excel',
                    "filename": 'Kepatuhan Wajib Pajak PBB ',
                    "action": newexportaction
                }, ],
                paging: true,
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: '{{ route('pbb.op.datatable_kepatuhan_wp') }}',
                    type: 'GET',
                    data: {
                        "tahun": tahun,
                        "kecamatan": kecamatan,
                        "kelurahan": kelurahan
                    }
                },
                columns: [{
                        data: 'tahun',
                        name: 'tahun'
                    },
                    {
                        data: 'nop_baku',
                        name: 'nop_baku'
                    },
                    {
                        data: 'nop_bayar',
                        name: 'nop_bayar'
                    },
                    {
                        data: 'persen',
                        name: 'persen'
                    }
                ],
                order: [
                    [0, 'desc']
                ],
            });
        }

        function table_pembayaran_awal(tahun = currentYear, kecamatan = curKecamatan, kelurahan =
            curKelurahan) {
            var table = $(".table-pembayaran-awal").DataTable({
                dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'>>" +
                    // dengan Button
                    "<'row'<'col-sm-12'tr>>" + // Add table
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Export to Excel',
                    "filename": 'Pembayaran Paling Awal Wajib Pajak PBB ',
                    "action": newexportaction
                }, ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: '{{ route('pbb.op.datatable_pembayaran_awal') }}',
                    type: 'GET',
                    data: {
                        "tahun": tahun,
                        "kecamatan": kecamatan,
                        "kelurahan": kelurahan,
                    }
                },
                columns: [{
                        data: 'tgl_bayar',
                        name: 'tgl_bayar',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'tgl_bayar',
                        name: 'tgl_bayar'
                    },
                    {
                        data: 'nop',
                        name: 'nop'
                    },
                    {
                        data: 'wp',
                        name: 'wp'
                    },
                    {
                        data: 'alamatop',
                        name: 'alamatop'
                    }
                ],
                order: [
                    [1, 'asc']
                ],
            });
        }

        function table_pembayaran_tinggi(tahun = currentYear, kecamatan = curKecamatan, kelurahan =
            curKelurahan) {
            var table = $(".table-pembayaran-tinggi").DataTable({
                dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'>>" +
                    // dengan Button
                    "<'row'<'col-sm-12'tr>>" + // Add table
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Export to Excel',
                    "filename": 'Pembayaran Paling Tinggi Wajib Pajak PBB ',
                    "action": newexportaction
                }, ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: '{{ route('pbb.op.datatable_pembayaran_tinggi') }}',
                    type: 'GET',
                    data: {
                        "tahun": tahun,
                        "kecamatan": kecamatan,
                        "kelurahan": kelurahan,
                    }
                },
                columns: [{
                        data: 'nop',
                        name: 'nop',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'nop',
                        name: 'nop'
                    },
                    {
                        data: 'wp',
                        name: 'wp'
                    },
                    {
                        data: 'alamatop',
                        name: 'alamatop'
                    },
                    {
                        data: 'nominal',
                        name: 'nominal'
                    }
                ],
                order: [
                    [4, 'desc']
                ],
            });
        }

        function table_op_wilayah(tahun = currentYear) {
            var table = $(".table-op-wilayah").DataTable({
                dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'>>" +
                    // dengan Button
                    "<'row'<'col-sm-12'tr>>" + // Add table
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Export to Excel',
                    "filename": 'Objek Pajak PBB per Wilayah  ',
                    "action": newexportaction
                }, ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: '{{ route('pbb.op.datatable_op_wilayah') }}',
                    type: 'GET',
                    data: {
                        "tahun": tahun
                    }
                },
                columns: [{
                        data: 'kecamatan',
                        name: 'kecamatan'
                    },
                    {
                        data: 'kelurahan',
                        name: 'kelurahan'
                    },
                    {
                        data: 'nop',
                        name: 'nop'
                    },
                    {
                        data: 'nominal',
                        name: 'nominal'
                    }
                ],
                order: [
                    [0, 'asc'],
                    [1, 'asc']
                ],
            });
        }

        function get_chart_op_wilayah(kategori = v_kategori, tahun = currentYear) {
            if (!tahun) {
                tahun = currentYear;
            }
            let url_submit = "{{ route('pbb.op.get_chart_op_wilayah') }}";
            $.ajax({
                type: 'GET',
                url: url_submit,
                data: {
                    "tahun": tahun,
                    "kategori": kategori
                },
                cache: false,
                contentType: false,
                processData: true,
                success: function(data) {
                    console.log("data", data);
                    series = data.series;
                    value = data.nilai;
                    chart_op_wilayah(value, series, kategori);
                },

                error: function(data) {
                    return 0;
                    alert('Terjadi Kesalahan Pada Server');
                },

            });
        }

        function chart_op_wilayah(data_op, series, kategori) {
            var tahun = $('#tahun').val();
            var kategori = $('#kategori').val();
            var options = {
                series: [{
                    name: kategori,
                    data: data_op
                }],
                chart: {
                    type: 'bar',
                    height: 360
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '40%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: series,
                },
                yaxis: {
                    show: true,
                    title: {
                        text: 'Rp. (Rupiah)'
                    },
                    labels: {
                        formatter: function(val) {
                            if (kategori == 'nominal') {
                                return bulatkanAngka(val)
                            } else {
                                return val;
                            }
                        }
                    }
                },

                fill: {
                    opacity: 1,
                    colors: ['#f44336', '#ff9800', '#4caf50', '#00bcd4'],
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        type: 'vertical',
                        shadeIntensity: 0.4,
                        inverseColors: false,
                        opacityFrom: 0.9,
                        opacityTo: 0.8,
                        stops: [0, 100]
                    }
                },
                colors: ['#f44336', '#ff9800', '#4caf50', '#00bcd4'],
                tooltip: {
                    y: {
                        formatter: function(val) {
                            if (kategori == 'nominal') {
                                return formatRupiah(val) + " Rupiah"
                            } else {
                                return val;
                            }
                        }
                    }
                }
            };

            var chartlinechart4 = new ApexCharts(document.querySelector("#chart-line"), options);
            chartlinechart4.render();
            chartlinechart4.updateOptions(options);
        }

        function datatable_jumlah_transaksi_pbb(tahun = currentYear,metode_pembayaran = "BANK JATIM"){
            var table = $(".table-jumlah-transaksi-pbb").DataTable({
                dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'>>" +
                    // dengan Button
                    "<'row'<'col-sm-12'tr>>" + // Add table
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Export to Excel',
                    "filename": 'Jumlah Transaksi PBB ',
                    "action": newexportaction
                }, ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                aLengthMenu: [
                    [25, 50, 100, 200, -1],
                    [25, 50, 100, 200, "All"]
                ],
                ajax: {
                    url: '{{ route('pbb.op.datatable_jumlah_transaksi_pbb') }}',
                    type: 'GET',
                    data: {
                        "tahun": tahun,
                        "metode_pembayaran": metode_pembayaran
                    }
                },
                columns: [
                    {
                        data: 'jam',
                        name: 'jam',
                        render: function (data, type, row, meta) {
                            if (data <= 9) {
                                data = "0" + data + ".00";
                            } else {
                                data = data + ".00";
                            }
                            return data;
                        }
                    },
                    {
                        data: 'senin',
                        name: 'senin',
                        render: function(data, type, row, meta) {
                            console.log("row = " + data)

                            if (data === null) {
                                data = 0;
                            }

                            if (data <= 25) {
                                return '<div style="width:100% !important;height:100% !important; text-align:center !important; padding:2px !important;border-radius:20px !important; background: #588157 !important;color:#FFF !important;">' + data + '</div>';
                            } else if (data > 25 && data <= 50) {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #ffbe0b !important;color:#FFF !important;">' + data + '</div>';
                            } else if (data > 50 && data <= 75) {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #fb5607 !important;color:#FFF !important;">' + data + '</div>';
                            } else {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #e63946 !important;color:#FFF !important;">' + data + '</div>';
                            }
                        }
                    },
                    {
                        data: 'selasa',
                        name: 'selasa',
                        render: function(data, type, row, meta) {
                            console.log("row = " + row)
                            console.log("row = " + data)

                            if (data === null) {
                                data = 0;
                            }
                            if (data <= 25) {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #588157 !important;color:#FFF !important;">' + data + '</div>';
                            } else if (data > 25 && data <= 50) {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #ffbe0b !important;color:#FFF !important;">' + data + '</div>';
                            } else if (data > 50 && data <= 75) {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #fb5607 !important;color:#FFF !important;">' + data + '</div>';
                            } else {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #e63946 !important;color:#FFF !important;">' + data + '</div>';
                            }
                        }
                    },
                    {
                        data: 'rabu',
                        name: 'rabu',
                        render: function(data, type, row, meta) {
                            console.log("row = " + row)
                            console.log("row = " + data)
                            
                            if (data === null) {
                                data = 0;
                            }
                            if (data <= 25) {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #588157 !important;color:#FFF !important;">' + data + '</div>';
                            } else if (data > 25 && data <= 50) {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #ffbe0b !important;color:#FFF !important;">' + data + '</div>';
                            } else if (data > 50 && data <= 75) {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #fb5607 !important;color:#FFF !important;">' + data + '</div>';
                            } else {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #e63946 !important;color:#FFF !important;">' + data + '</div>';
                            }
                        }
                    },
                    {
                        data: 'kamis',
                        name: 'kamis',
                        render: function(data, type, row, meta) {
                            console.log("row = " + row)
                            console.log("row = " + data)

                            if (data === null) {
                                data = 0;
                            }
                            if (data <= 25) {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #588157 !important;color:#FFF !important;">' + data + '</div>';
                            } else if (data > 25 && data <= 50) {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #ffbe0b !important;color:#FFF !important;">' + data + '</div>';
                            } else if (data > 50 && data <= 75) {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #fb5607 !important;color:#FFF !important;">' + data + '</div>';
                            } else {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #e63946 !important;color:#FFF !important;">' + data + '</div>';
                            }
                        }
                    },
                    {
                        data: 'jumat',
                        name: 'jumat',
                        render: function(data, type, row, meta) {
                            console.log("row = " + row)
                            console.log("row = " + data)

                            if (data === null) {
                                data = 0;
                            }
                            if (data <= 25) {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #588157 !important;color:#FFF !important;">' + data + '</div>';
                            } else if (data > 25 && data <= 50) {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #ffbe0b !important;color:#FFF !important;">' + data + '</div>';
                            } else if (data > 50 && data <= 75) {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #fb5607 !important;color:#FFF !important;">' + data + '</div>';
                            } else {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #e63946 !important;color:#FFF !important;">' + data + '</div>';
                            }
                        }
                    },
                    {
                        data: 'sabtu',
                        name: 'sabtu',
                        render: function(data, type, row, meta) {
                            console.log("row = " + row)
                            console.log("row = " + data)

                            if (data === null) {
                                data = 0;
                            }
                            if (data <= 25) {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #588157 !important;color:#FFF !important;">' + data + '</div>';
                            } else if (data > 25 && data <= 50) {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #ffbe0b !important;color:#FFF !important;">' + data + '</div>';
                            } else if (data > 50 && data <= 75) {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #fb5607 !important;color:#FFF !important;">' + data + '</div>';
                            } else {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #e63946 !important;color:#FFF !important;">' + data + '</div>';
                            }
                        }
                    },
                    {
                        data: 'minggu',
                        name: 'minggu',
                        render: function(data, type, row, meta) {
                            console.log("row = " + row)
                            console.log("row = " + data)
                            if (data == null) {
                                data = 0;
                            }
                            if (data <= 25) {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #588157 !important;color:#FFF !important;">' + data + '</div>';
                            } else if (data > 25 && data <= 50) {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #ffbe0b !important;color:#FFF !important;">' + data + '</div>';
                            } else if (data > 50 && data <= 75) {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #fb5607 !important;color:#FFF !important;">' + data + '</div>';
                            } else {
                                return '<div style="width:100% !important;height:100% !important;  text-align:center !important; padding:2px !important;border-radius:20px !important;background: #e63946 !important;color:#FFF !important;">' + data + '</div>';
                            }
                        }
                    }
                ],
                order: [
                    [0, 'ASC']
                ],
            });
        }
        function filterJTB() {
            var tahun_jtb = $('#filter_tahun_jumlah_transaksi_pbb').val();
            var metode_pembayaran_jtb = $('#filter_metode_pembayaran_jumlah_transaksi_pbb').val();

            $(".table-jumlah-transaksi-pbb").DataTable().destroy();
            datatable_jumlah_transaksi_pbb(tahun_jtb,metode_pembayaran_jtb);
        }

        $(document).ready(function() {

            table_kepatuhan_wp();
            table_pembayaran_awal();
            table_pembayaran_tinggi();
            table_op_wilayah();
            get_chart_op_wilayah();
            filterWilayah();
            var tahun_jtb = $('#filter_tahun_jumlah_transaksi_pbb').val();
            var metode_pembayaran_jtb = $('#filter_metode_pembayaran_jumlah_transaksi_pbb').val();
            datatable_jumlah_transaksi_pbb(tahun_jtb,metode_pembayaran_jtb);

        })
    </script>
@endsection
