@extends('admin.layout.main')
@section('title', 'Detail Penerimaan Perbulan BPHTB - Smart Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Penerimaan BPHTB</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">BPHTB</a></li>
                        <li class="breadcrumb-item active">Penerimaan</li>
                        <li class="breadcrumb-item active">Detail Penerimaan Perbulan BPHTB</li>
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
                <div class="col-xl-12">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6>Detail Penerimaan Perbulan BPHTB </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-detail-penerimaan-bulanan">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NPOP</th>
                                            <th>Nama WP</th>
                                            <th>Alamat WP</th>
                                            <th>Nama PPAT</th>
                                            <th>Penerimaan</th>
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
        function formatRupiah(angka) {
            var options = {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2,
            };
            var formattedNumber = angka.toLocaleString('ID', options);
            return formattedNumber;
        }

        function bulanKeAngka(namaBulan) {
            switch (namaBulan.toLowerCase()) {
                case 'januari':
                    return 1;
                case 'februari':
                    return 2;
                case 'maret':
                    return 3;
                case 'april':
                    return 4;
                case 'mei':
                    return 5;
                case 'juni':
                    return 6;
                case 'juli':
                    return 7;
                case 'agustus':
                    return 8;
                case 'september':
                    return 9;
                case 'oktober':
                    return 10;
                case 'november':
                    return 11;
                case 'desember':
                    return 12;
                default:
                    return null; // Bulan tidak valid
            }
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

        function table_detail_penerimaan_bulanan() {
            let tahun = "{{ $tahun }}";
            let bulan = bulanKeAngka("{{ $bulan }}");
            var table = $(".table-detail-penerimaan-bulanan").DataTable({
                "dom": 'Blfrtip',
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Excel',
                    "filename": 'Detail Penerimaan Perbulan BPHTB',
                    "action": newexportaction,
                }, ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: '{{ route('bphtb.penerimaan.datatable_detail_penerimaan_perbulan') }}',
                    type: 'GET',
                    data: {
                        "tahun": tahun,
                        "bulan": bulan,
                    }
                },
                columns: [{
                        data: 'no',
                        name: 'no',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'NPOP',
                        name: 'NPOP'
                    },
                    {
                        data: 'NAMAWP',
                        name: 'NAMAWP'
                    },
                    {
                        data: 'ALAMATWP',
                        name: 'ALAMATWP'
                    },
                    {
                        data: 'NAMAPPAT',
                        name: 'NAMAPPAT'
                    },
                    {
                        data: 'PENERIMAAN',
                        name: 'PENERIMAAN'
                    }
                ],
                order: [
                    [0, 'desc']
                ],
            });
        }


        $(document).ready(function() {

            table_detail_penerimaan_bulanan();
        })
    </script>
@endsection
