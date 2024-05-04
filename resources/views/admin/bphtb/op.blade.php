@extends('admin.layout.main')
@section('title', 'Objek Pajak BPHTB - Smart Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Objek Pajak BPHTB</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">BPHTB</a></li>
                        <li class="breadcrumb-item active">Objek Pajak</li>
                    </ol>
                </div>
                <div class="col-sm-6">
                </div>

            </div>
        </div>
    </div>
    <div class="container-fluid chart-widget">
        <div class="row">
            <div class="col-xl-12">
                <div class="row mb-4">
                    <h5>Filter Tahun SPPT</h5>
                    <div class="col-xl-3">
                        <select name="role_code" id="tahun_filter" class="form-control btn-square">
                            <option value="">Pilih Tahun SPPT</option>
                            @foreach (range(date('Y'), 1900) as $year)
                                <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                    {{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xl-2">
                        <div class="input-group-btn btn btn-square p-0">
                            <a class="btn btn-primary btn-square" type="button" onclick="filterTahun()">Terapkan<span
                                    class="caret"></span></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card o-hidden border-0">
                    <div class="bg-success b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="shopping-bag"></i></div>
                            <div class="media-body">
                                <span class="m-0">Jumlah Notaris</span>
                                <h4 id="totalNotaris" class="mb-0 counter"></h4>
                                <i class="icon-bg" data-feather="shopping-bag"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card o-hidden border-0">
                    <div class="bg-danger b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="shopping-bag"></i></div>
                            <div class="media-body">
                                <span class="m-0">Jumlah NOP</span>
                                <h4 id="totalNOP" class="mb-0 counter"></h4>
                                <i class="icon-bg" data-feather="shopping-bag"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card o-hidden">
                    <div class="card-header pb-0">
                        <h6>Kontribusi Terbesar Notaris</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-kontribusi-op">
                                <thead>
                                    <tr>
                                        <th>Tahun SPPT</th>
                                        <th>Notaris</th>
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
@endsection


@section('js')
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script>
        var currentYear = new Date().getFullYear();

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

        function table_kontribusi_op(tahun) {
            var table = $(".table-kontribusi-op").DataTable({
                "dom": 'Bfrtip',
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Excel',
                    "filename": 'Rekap Kontribusi Terbesar Notaris',
                    "action": newexportaction,
                }, ],
                paging: false,
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: '{{ route('bphtb.op.datatable_kontribusi_op') }}' + '?tahun=' + tahun,
                columns: [{
                        data: 'tahun',
                        name: 'tahun'
                    },
                    {
                        data: 'notaris',
                        name: 'notaris'
                    },
                    {
                        data: 'penerimaan',
                        name: 'penerimaan'
                    },
                ],
                order: [
                    [0, 'desc']
                ],
            });
        }

        function get_total_nop(tahun) {
            return $.get("{{ route('bphtb.op.get_total_nop') }}", {
                tahun: tahun
            }, function(data) {
                $("#totalNOP").text(data);
            });
        }

        function get_total_notaris(tahun) {
            return $.get("{{ route('bphtb.op.get_total_notaris') }}", {
                tahun: tahun
            }, function(data) {
                $("#totalNotaris").text(data);
            });
        }

        function filterTahun() {
            var tahun = $('#tahun_filter').val();
            if (tahun !== null) {
                $(".table-kontribusi-op").DataTable().destroy();
                get_total_notaris(tahun);
                get_total_nop(tahun);
                table_kontribusi_op(tahun);
            }
        }

        $(document).ready(function() {
            filterTahun();
        });
    </script>
@endsection
