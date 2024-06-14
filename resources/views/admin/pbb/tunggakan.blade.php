@extends('admin.layout.main')
@section('title', 'Tunggakan PBB - Smart Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Tunggakan PBB</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">PBB</a></li>
                        <li class="breadcrumb-item active">Tunggakan</li>
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

            <div class="col-xl-12">
                <div class="card o-hidden">
                    <div class="card-header pb-0">
                        <h6>Tunggakan (NOP)</h6>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="row">
                                    <div class="col-xl-3 mb-2 col-md-4 col-sm-6">
                                        <select name="tahun_all[]" id="tahun-all" class="form-control btn-square col-sm-12"
                                            multiple="multiple">
                                            @foreach (array_combine(range(date('Y'), 1900), range(date('Y'), 1900)) as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-xl-3 mb-2 col-md-4 col-sm-6">
                                        <select name="kecamatan" id="kecamatan"
                                            class="form-control btn-square js-example-basic-single col-sm-12 "
                                            style="border: 1px solid #808080;border-radius:5px;">
                                            <option value="" class = "d-flex align-items-center">Pilih Kecamatan
                                            </option>
                                            @foreach (getKecamatan() as $item)
                                                <option value="{{ $item->nama_kecamatan }}">{{ $item->nama_kecamatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-xl-3 mb-2 col-md-4 col-sm-6">
                                        <select name="kelurahan"
                                            id="kelurahan"class="form-control btn-square js-example-basic-single col-sm-12"
                                            style="border: 1px solid #808080;">
                                            <option value="" class = "d-flex align-items-center">Pilih Kelurahan
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-xl-2 mb-2 col-md-4 col-sm-6">
                                        <a class="btn btn-primary btn-square" type="button"
                                            onclick="filterWilayah()">Terapkan<span class="caret"></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-tunggakan-nop">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="text-align: center; vertical-align: middle;">Tahun</th>
                                        <th rowspan="2" style="text-align: center; vertical-align: middle;">Bulan</th>
                                        <th colspan="3" style="text-align: center;background-color:#f3e8ae">
                                            IDENTITAS</th>
                                        <th colspan="3" style="text-align: center;background-color:#cecece">
                                            NOMINAL</th>

                                    </tr>
                                    <tr>
                                        <th>NOP</th>
                                        <th>Nama</th>
                                        <th>Alamat Pajak</th>
                                        <th>Nominal Ketetapan</th>
                                        <th>Denda</th>
                                        <th>Total Tunggakan</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-xl-8 col-md-6 col-lg-3">
                <div class="col-xl-12">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-xl-5">
                                    <h6>Tunggakan Bedasarkan Level</h6>
                                </div>
                                <div class="col-xl-7">
                                    <div class="mb-3 draggable">
                                        <div class="input-group">
                                            <div class="col-xl-7">
                                                <select name="level-filter" id="level-filter"class="form-control btn-square"
                                                    style="border: 1px solid #808080;">
                                                    <option value="" class = "d-flex align-items-center">Pilih
                                                        Kategori Level</option>
                                                    <option value="Berat" class = "d-flex align-items-center">Berat
                                                    </option>
                                                    <option value="Sedang" class = "d-flex align-items-center">Sedang
                                                    </option>
                                                    <option value="Ringan" class = "d-flex align-items-center">Ringan
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="input-group-btn btn btn-square p-0">
                                                <a class="btn btn-primary btn-square" type="button"
                                                    onclick="filterWilayahV()">Terapkan<span class="caret"></span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-tunggakan-level">
                                    <thead>
                                        <tr>
                                            <th>NOP</th>
                                            <th>Nama</th>
                                            <th>jumlah Tungakan</th>
                                            <th>Nominal Tunggakan</th>
                                            <th>Level</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div>
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6>5 Tunggakan Paling Tinggi (NOP)</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 draggable">
                                <div class="input-group">
                                    <input type="hidden" id="tunggakan" value="tunggakan">
                                    <select name="role_code" id="tahun_v" class="form-control btn-square">
                                        <option value="">Pilih Tahun SPPT</option>
                                        {{-- <input type="text" id="default_date" value="{{ date('Y') - 1}}"> --}}
                                        @foreach (array_combine(range(date('Y'), 1970), range(date('Y'), 1970)) as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-btn btn btn-square p-0">
                                        <a class="btn btn-primary btn-square" type="button"
                                            onclick="filterTahun()">Terapkan<span class="caret"></span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm dtTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>NOP</th>
                                            <th>Jumlah Tunggakan</th>
                                            <th>Nominal Tunggakan</th>
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

        const day = new Date();
        var currentYear = day.getFullYear();
        var curKelurahan = $('#kelurahan').val();
        var curKecamatan = $('#kecamatan').val();

        function filterKel() {
            $('#kecamatan').on('change', function() {
                var kecamatan = this.value;
                // console.log(kecamatan);
                $("#kelurahan").html('');
                $.ajax({
                    url: '{{ route('pbb.tunggakan.get_wilayah') }}',
                    type: "GET",
                    data: {
                        "wilayah": 'kecamatan',
                        "data": kecamatan
                    },
                    dataType: 'json',
                    success: function(result) {
                        // console.log(result);
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

        function formatRupiah(angka) {
            var options = {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2,
            };
            var formattedNumber = angka.toLocaleString('ID', options);
            return formattedNumber;
        }

        function filterTahun() {
            var tahun = $('#tahun_v').val();
            if (tahun !== null) {
                $(".dtTable").DataTable().destroy();
                table_pembayaran_tunggakan(tahun);
            }
        }

        function filterWilayah() {
            var kecamatan = $('#kecamatan').val();
            var kelurahan = $('#kelurahan').val();
            var tahun_all = $('#tahun-all').val();
            // console.log(wilayah);
            if (kecamatan !== null || tahun_all !== null || kelurahan !== null) {
                $(".table-tunggakan-nop").DataTable().destroy();
                table_tunggakan_nop(kecamatan, kelurahan, tahun_all);
            }
        }

        function filterWilayahV() {
            var level = $('#level-filter').val();
            // console.log(level);
            if (level !== null) {
                $(".table-tunggakan-level").DataTable().destroy();
                table_tunggakan_level(level);
            }
        }


        function table_tunggakan_nop(kecamatan = curKecamatan, kelurahan = curKelurahan, tahun = []) {

            var table = $(".table-tunggakan-nop").DataTable({
                dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'>>" +
                    // dengan Button
                    "<'row'<'col-sm-12'tr>>" + // Add table
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Export to Excel',
                    "filename": 'Tunggakan (NOP) PBB ',
                    "action": newexportaction
                }, ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: '{{ route('pbb.tunggakan.datatable_tunggakan_nop') }}',
                    type: 'GET',
                    data: {
                        "kecamatan": kecamatan,
                        "kelurahan": kelurahan,
                        "tahun": tahun
                    }
                },
                columns: [{
                        data: 'tahun',
                        name: 'tahun'
                    },
                    {
                        data: 'bulan',
                        name: 'bulan'
                    },
                    {
                        data: 'nop',
                        name: 'nop'
                    },
                    {
                        data: 'nama_subjek_pajak',
                        name: 'nama_subjek_pajak'
                    },
                    {
                        data: 'alamat_objek_pajak',
                        name: 'alamat_objek_pajak'
                    },
                    {
                        data: 'nominal_ketetapan',
                        name: 'nominal_ketetapan'
                    },
                    {
                        data: 'nominal_denda',
                        name: 'nominal_denda'
                    },
                    {
                        data: 'nominal_tunggakan',
                        name: 'nominal_tunggakan'
                    },
                ],
                order: [
                    [0, 'desc']
                ],
            });
        }

        function table_tunggakan_level(level = null) {
            var table = $(".table-tunggakan-level").DataTable({
                dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'>>" +
                    // dengan Button
                    "<'row'<'col-sm-12'tr>>" + // Add table
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Export to Excel',
                    "filename": 'Tunggakan PBB Berdasarkan Level ',
                    "action": newexportaction
                }, ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: '{{ route('pbb.tunggakan.datatable_tunggakan_level') }}',
                    type: 'GET',
                    data: {
                        "level": level,
                    }
                },
                columns: [{
                        data: 'nop',
                        name: 'nop'
                    },
                    {
                        data: 'nama_subjek_pajak',
                        name: 'nama_subjek_pajak'
                    },
                    {
                        data: 'jumlah_tunggakan',
                        name: 'jumlah_tunggakan'
                    },
                    {
                        data: 'nominal_tunggakan',
                        name: 'nominal_tunggakan'
                    },
                    {
                        data: 'level',
                        name: 'level'
                    },
                ],
                order: [
                    [0, 'asc'],
                    [1, 'asc']
                ],
            });
        }

        function table_pembayaran_tunggakan(tahun = null) {
            var id = $('#tunggakan').val();
            let table = $(".dtTable").DataTable({
                dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'>>" +
                    // dengan Button
                    "<'row'<'col-sm-12'tr>>" + // Add table
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Export to Excel',
                    "filename": '5 Tertinggi Tunggakan PBB ',
                    "action": newexportaction
                }, ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: "{{ route('pbb.tunggakan.datatable_tunggakan_paling_tinggi') }}",
                    type: 'GET',
                    data: {
                        "tahun": tahun,
                        "id": id
                    }
                },
                columns: [{
                        data: 'no',
                        name: 'no'
                    }, {
                        data: 'nop',
                        name: 'nop'
                    },
                    {
                        data: 'jumlah_tunggakan',
                        name: 'jumlah_tunggakan'
                    },
                    {
                        data: 'nominal',
                        name: 'nominal'
                    },
                ],
                order: [
                    [0, 'asc']
                ],
            });
        }

        $(document).ready(function() {
            $("#tahun-all").select2({
                placeholder: "Pilih Tahun (Bisa Multi Tahun)"
            });
            filterKel();
            table_tunggakan_nop();
            table_pembayaran_tunggakan();
            table_tunggakan_level();
        })
    </script>
@endsection
