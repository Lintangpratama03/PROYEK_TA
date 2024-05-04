@extends('admin.layout.main')
@section('title', 'Log - Smart Dashboard')

@section('content')

    <style>
      .card {
            border-radius: 20px !important;
            box-shadow: inset 0 -1px 0 0 rgba(0, 0, 0, 0.1) !important;
      }


    </style>
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 style="font-size: 30px;font-weight: bold;">Log</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" style="font-size: 20px;font-weight: 600;"><a href="#">Log</a></li>
                        <li class="breadcrumb-item active" style="font-size: 20px;font-weight: 500;">index</li>
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
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card o-hidden">
                            <div class="card-header pb-0">
                                <h6 style="font-size: 18px;font-weight: bold;">Log Scheduler Data</h6>
                                {{-- <div class="mb-3 draggable">
                                    <div class="input-group">
                                        <div class="col-xl-4">
                                            <select id="bulan_retribusi_daerah" name="bulan_retribusi_daerah" class="col-sm-12">
                                                <optgroup label="Bulan">
                                                    @foreach (getMonthList() as $index => $value)
                                                        <option value="{{ $index }}">{{ $value }}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-xl-4">
                                            <select name="tahun_retribusi_daerah" id="tahun_retribusi_daerah"
                                                class="form-control">
                                                @foreach (array_combine(range(date('Y'), 2018), range(date('Y'), 2018)) as $year)
                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="input-group-btn btn btn-square p-0">
                                                <a type="button" class="btn btn-primary btn-square" type="button"
                                                    onclick="#">Terapkan<span class="caret"></span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table style="font-size: 16px;font-weight: normal;" id="tabel-log-scheduler" class="table table-sm tabel-log-scheduler">
                                        <thead>                                           
                                            <tr>
                                                <th>No</th>
                                                <th>Jenis Data</th>
                                                <th>Total Data</th>
                                                <th>Status</th>
                                                <th>Keterangan</th>
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
    </div>
    <!-- Container-fluid Ends-->
@endsection

@section('js')                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script>

        const day = new Date(); 
        var currentYear = day.getFullYear();
        var currentMonth = day.getMonth() + 1;
        let pencapaianBulan = (100 / 12) * currentMonth;
        let pencapaianBulanLalu = (100 / 12) * (currentMonth - 1);

        function newexportaction(e, dt, button, config) {
            var self = this;
            var oldStart = dt.settings()[0]._iDisplayStart;
            dt.one('preXhr', function(e, s, data) {
                data.start = 0;
                data.length = 2147483647;
                dt.one('preDraw', function(e, settings) {
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
                        settings._iDisplayStart = oldStart;
                        data.start = oldStart;
                    });
                    setTimeout(dt.ajax.reload, 0);
                    return false;
                });
            });
            dt.ajax.reload();
        };
        function datatable_log_scheduler() {
            let table = $(".tabel-log-scheduler").DataTable({
                dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Export to Excel',
                    "filename": 'Target dan Realisasi Pajak',
                    "action": newexportaction
                }, ],

                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: "{{ route('log.datatable_log_scheduler') }}",
                    type: 'GET'
                },
                columns: [
                    { 
                        data: 'DT_RowIndex', 
                        name: 'DT_RowIndex', 
                        searchable: false 
                    },
                    {
                        data: 'jenis_data',
                        name: 'jenis_data'
                    },
                    {
                        data: 'total_data',
                        name: 'total_data'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    }
                ],
                order: [
                    [0, 'desc']
                ],
            });
        }

        $(document).ready(function() {
            datatable_log_scheduler();
        });
    </script>
@endsection
