@extends('admin.layout.main')
@section('title', 'Tunggakan PDL - Smart Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Tunggakan PDL</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">PDL</a></li>
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
                <div class="row mb-3 draggable">
                    <div class="col-xl-4 col-md-6 col-sm-6 mb-2">
                        <select name="tahun_all" id="tahun-all" class="form-control btn-square col-sm-12"
                            multiple="multiple">
                            <option value="">Pilih Tahun Bayar</option>
                            @foreach (array_combine(range(date('Y'), 1900), range(date('Y'), 1900)) as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xl-4 col-md-6 col-sm-6 mb-2">
                        <select name="nama_rekening" id="nama_rekening" class="form-control btn-square col-sm-12">
                            <option value="">Pilih Nama Rekening</option>
                            @foreach (getRek() as $item)
                                <option value="{{ $item->nama_rekening }}">{{ $item->nama_rekening }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xl-4 col-md-6 col-sm-6 mb-2">
                        <select name="kecamatan" id="kecamatan"
                            class="form-control btn-square js-example-basic-single col-sm-12"
                            style="border: 1px solid #808080;">
                            <option value="">Pilih Kecamatan</option>
                            @foreach ($data as $kecamatan)
                                <option value="{{ $kecamatan->nama_kecamatan }}">{{ $kecamatan->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xl-4 mb-2">
                        <select name="kelurahan"
                            id="kelurahan"class="form-control btn-square js-example-basic-single col-sm-12"
                            style="border: 1px solid #808080;">
                            <option value="" class = "d-flex align-items-center">Pilih Kelurahan</option>
                        </select>
                    </div>
                    <div class="col-xl-4 mb-2">
                        <div class="input-group-btn btn btn-square p-0">
                            <a class="btn btn-primary btn-square" type="button" onclick="filterTahunRekap()"><i
                                    class="fa fa-search"></i> Terapkan</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-7">
                <div class="col-xl-12">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6>Rekapitulasi Tunggakan</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-tunggakan-pdl">
                                    <thead>
                                        <tr>
                                            <th>Tahun</th>
                                            <th>Nama Rekening</th>
                                            <th>Jumlah</th>
                                            <th>Nominal</th>
                                            <th>Kecamatan</th>
                                            <th>Kelurahan</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="card o-hidden">
                    <div class="card-header pb-0">
                        <h6>Objek Pajak Belum Bayar</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-tunggakan-wp">
                                <thead>
                                    <tr>
                                        <th>Tahun</th>
                                        <th>Wajib Pajak</th>
                                        <th>Objek Pajak</th>
                                        <th>Jumlah Tunggakan SPT</th>
                                        <th>Kecamatan</th>
                                        <th>Kelurahan</th>
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

    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script>
        function formatRupiah(angka) {
            var options = {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2,
            };
            var formattedNumber = angka.toLocaleString('ID', options);
            return formattedNumber;
        }

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

        function table_tunggakan_rekap(tahun = [], nama_rekening = null, kecamatan = null, kelurahan = null) {
            var table = $(".table-tunggakan-pdl").DataTable({
                "dom": 'Brtip',
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Excel',
                    "filename": 'Rekap Tunggakan PDL',
                    "action": newexportaction
                }, ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: '{{ route('pdl.tunggakan.datatable_tunggakan_pdl') }}',
                    type: 'GET',
                    data: {
                        "tahun": tahun,
                        "nama_rekening": nama_rekening,
                        "kecamatan": kecamatan,
                        "kelurahan": kelurahan,
                    }
                },
                columns: [{
                        data: 'tahun',
                        name: 'tahun'
                    },
                    {
                        data: 'nama_rekening',
                        name: 'nama_rekening'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'nominal',
                        name: 'nominal'
                    },
                    {
                        data: 'kecamatan',
                        name: 'kecamatan'
                    },
                    {
                        data: 'kelurahan',
                        name: 'kelurahan'
                    }
                ],
                order: [
                    [0, 'desc']
                ],
            });
        }

        function table_tunggakan_wp(tahun = [], nama_rekening = null, kecamatan = null, kelurahan = null) {
            let table = $(".table-tunggakan-wp").DataTable({
                "dom": 'Bfrtip',
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Excel',
                    "filename": 'Objek Pajak Belum Bayar PDL',
                    "action": newexportaction
                }, ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: '{{ route('pdl.tunggakan.datatable_tunggakan_wp') }}',
                    type: 'GET',
                    data: {
                        "tahun": tahun,
                        "nama_rekening": nama_rekening,
                        "kecamatan": kecamatan,
                        "kelurahan": kelurahan,
                    }
                },
                columns: [{
                        data: 'tahun',
                        name: 'tahun'
                    },
                    {
                        data: 'wp',
                        name: 'wp'
                    },
                    {
                        data: 'op',
                        name: 'op'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'kecamatan',
                        name: 'kecamatan'
                    },
                    {
                        data: 'kelurahan',
                        name: 'kelurahan'
                    }
                ],
                order: [
                    [3, 'desc']
                ],
            });
        }

        function filterTahunRekap() {
            let tahun_all = $('#tahun-all').val();
            let nama_rekening = $('#nama_rekening').val();
            let kecamatan = $('#kecamatan').val();
            let kelurahan = $('#kelurahan').val();

            if (tahun_all !== null && nama_rekening !== null) {
                $(".table-tunggakan-pdl").DataTable().destroy();
                $(".table-tunggakan-wp").DataTable().destroy();
                table_tunggakan_rekap(tahun_all, nama_rekening, kecamatan, kelurahan);
                table_tunggakan_wp(tahun_all, nama_rekening, kecamatan, kelurahan);
            }
        }

        function filterWilayah() {
            $('#kecamatan').on('change', function() {
                var kecamatan = this.value;
                $("#kelurahan").html('');
                $.ajax({
                    url: '{{ route('pdl.tunggakan.get_wilayah') }}',
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

        $(document).ready(function() {

            $("#tahun-all").select2({
                placeholder: "Pilih Tahun (Bisa Multi Tahun)"
            });

            let tahun_all = $('#tahun-all').val();
            let nama_rekening = $('#nama_rekening').val();
            let kecamatan = $('#kecamatan').val();
            let kelurahan = $('#kelurahan').val();
            $("#nama_rekening").select2();
            table_tunggakan_rekap(tahun_all, nama_rekening, kecamatan, kelurahan);
            table_tunggakan_wp(tahun_all, nama_rekening, kecamatan, kelurahan);
            filterWilayah();
        })
    </script>
@endsection
