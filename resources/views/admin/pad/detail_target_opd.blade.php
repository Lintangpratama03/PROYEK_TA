@extends('admin.layout.main')
@section('title', 'Detail Retribusi OPD')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 style="font-size: 20px;font-weight: bold;">Detail Retribusi Setiap OPD </h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" style="font-size: 16px;font-weight: bold;"><a href="#">Retribusi</a></li>
                        <li class="breadcrumb-item active" style="font-size: 16px;font-weight: bold;">Detail Realisasi</li>
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
            <div class="col-xl-8">
                <div class="col-xl-12">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6 style="font-size: 20px;font-weight: bold;">Realisasi Per OPD</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table style="font-size: 16px;font-weight: normal;" class="table table-detail">
                                    <thead>
                                        <tr>
                                            <th>Nama Retribusi</th>
                                            <th>Nama OPD</th>
                                            <th>Keterangan OPD</th>
                                            <th>Tahun</th>
                                            <th>Target Murni</th>
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
            <div class="col-xl-4">
                <div class="col-xl-12">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6 style="font-size: 20px;font-weight: bold;">Detail Realisasi Per Bulan</h6>
                            <span style="font-size: 14px;font-weight: normal;" id="selectedOpdName"></span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table style="font-size: 16px;font-weight: normal;" class="table table-detail-opd">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Bulan</th>
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
        var opdName = '';

        $(document).on('click', '.btn-info', function() {
            opdName = $(this).closest('tr').find('td:nth-child(2)').text().trim();
            var tahun = "{{ $tahun }}";
            var id_retribusi = "{{ $id_retribusi }}";

            // Memperbarui nama OPD yang ditampilkan
            $('#selectedOpdName').text(' - ' + opdName);

            // Memperbarui data tabel dengan filter nama OPD
            var table = $(".table-detail-opd").DataTable();
            table.clear().draw();
            table.ajax.url('{{ route('pad.datatable_detail_target_opd_bulan') }}?tahun=' + tahun +
                '&id_retribusi=' + id_retribusi + '&nama_opd=' + encodeURIComponent(opdName)).load();
        });

        function newexportaction(e, dt, button, config) {
            var self = this;
            var oldStart = dt.settings()[0]._iDisplayStart;
            dt.one('preXhr', function(e, s, data) {
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
                    return false;
                });
            });
            dt.ajax.reload();
            config.filename = 'Detail Realisasi Per OPD ' + opdName;
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


        function table_detail() {
            let tahun = "{{ $tahun }}";
            let id_retribusi = "{{ $id_retribusi }}";
            var table = $(".table-detail").DataTable({
                dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'>>" +
                    // dengan Button
                    "<'row'<'col-sm-12'tr>>" + // Add table
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Export to Excel',
                    "filename": 'Detail Realisasi Per OPD ',
                    "action": newexportaction
                }, ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: '{{ route('pad.datatable_detail_target_opd') }}',
                    type: 'GET',
                    data: {
                        "tahun": tahun,
                        "id_retribusi": id_retribusi
                    }
                },
                columns: [{
                        data: 'nama_retribusi',
                        name: 'nama_retribusi'
                    },
                    {
                        data: 'nama_opd',
                        name: 'nama_opd'
                    },
                    {
                        data: 'ket_opd',
                        name: 'ket_opd'
                    },
                    {
                        data: 'tahun',
                        name: 'tahun'
                    },
                    {
                        data: 'target_murni',
                        name: 'target_murni'
                    },
                    {
                        data: 'target_perubahan',
                        name: 'target_perubahan'
                    },
                    {
                        data: 'realisasi',
                        name: 'realisasi'
                    }
                ],
                // order: [[0, 'desc']],
            });
        }

        function table_detail_bulan() {
            let tahun = "{{ $tahun }}";
            let id_retribusi = "{{ $id_retribusi }}";
            var table = $(".table-detail-opd").DataTable({
                dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'>>" +
                    // dengan Button
                    "<'row'<'col-sm-12'tr>>" + // Add table
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Export to Excel',
                    "filename": 'Detail Realisasi OPD per Bulan ',
                    "action": newexportaction
                }, ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                lengthChange: false,
                ajax: {
                    url: '{{ route('pad.datatable_detail_target_opd_bulan') }}',
                    type: 'GET',
                    data: {
                        "tahun": tahun,
                        "id_retribusi": id_retribusi,
                    }
                },
                columns: [{
                        data: 'no'
                    }, {
                        data: 'bulan',
                        name: 'bulan'
                    },
                    {
                        data: 'realisasi',
                        name: 'realisasi'
                    }
                ],
                // order: [[0, 'desc']],
            });
        }
        $(document).on('click', '.btn-info', function() {
            var opdName = $(this).closest('tr').find('td:nth-child(2)').text().trim();
            var tahun = "{{ $tahun }}";
            var id_retribusi = "{{ $id_retribusi }}";
            $('#selectedOpdName').text(' - ' + opdName);
            var table = $(".table-detail-opd").DataTable();
            table.clear().draw();
            table.ajax.url('{{ route('pad.datatable_detail_target_opd_bulan') }}?tahun=' + tahun +
                '&id_retribusi=' + id_retribusi + '&nama_opd=' + encodeURIComponent(opdName)).load();
        });


        $(document).ready(function() {

            table_detail();
            table_detail_bulan();
        })
    </script>
@endsection
