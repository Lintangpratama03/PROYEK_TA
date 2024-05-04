@extends('admin.layout.main')
@section('title', 'Objek Pajak PDL - Smart Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Objek Pajak PDL</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">PDL</a></li>
                        <li class="breadcrumb-item active">Objek Pajak</li>
                    </ol>
                </div>
                <div class="col-sm-6 text-center">
                    <a class="btn btn-primary btn-sm" href="{{ route('pdl.op.search') }}"><i class="fa fa-search"></i> Cari
                        Objek Pajak</a>
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid chart-widget">

        <div class="row" id="list-count-jenis-pajak">


        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="ccol-xl-4 col-md-6 col-sm-6 mb-2">
                            <select id="jenis_pajak" name="jenis_pajak" class="col-sm-12">
                                <optgroup label="Jenis Pajak">
                                    <!-- <option value="">Pilih Jenis Pajak</option> -->
                                    @foreach (getJenisPajak() as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_rekening }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-xl-4 col-md-6 col-sm-6 mb-2">
                            <select id="tahun" name="tahun" class="col-sm-12">
                                <optgroup label="Tahun">
                                    @foreach (array_combine(range(date('Y'), 2018), range(date('Y'), 2018)) as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-xl-4 col-md-6 col-sm-6 mb-2">
                            <a class='btn btn-primary btn-sm' onclick='filter_kontribusi()'><i class='fa fa-search'></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="col-xl-12">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6>Kontribusi Terbesar Objek Pajak</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-kontribusi-op">
                                    <thead>
                                        <tr>
                                            <th>Tahun SPPT</th>
                                            <th>Jenis Pajak</th>
                                            <th>Objek Pajak</th>
                                            <th>Nama WP</th>
                                            <th>Nominal</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-7">
                <div class="card o-hidden">
                    <div class="card-header pb-0">
                        <h6>Objek Pajak Aktif</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-op-aktif-tutup">
                                <thead>
                                    <tr>
                                        <th>Jenis Pajak</th>
                                        <th>NPWPD</th>
                                        <th>NOP</th>
                                        <th>Objek Pajak</th>
                                        <th>Alamat OP</th>
                                        {{-- <th>Status</th> --}}
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card o-hidden">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-xl-6">
                                <h6>Pendaftaran</h6>
                            </div>
                            <div class="col-xl-4">
                                <div class="mb-3 draggable">
                                    <div class="input-group">
                                        <select name="tahun-op" id="tahun-op" class="form-control btn-square">
                                            <option value="">Pilih Tahun</option>
                                            <option value="">Keseluruhan Data</option>
                                            @foreach (array_combine(range(date('Y'), 1900), range(date('Y'), 1900)) as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-btn btn btn-square p-0">
                                            <a class="btn btn-primary btn-square" type="button"
                                                onclick="filterTahun()">Terapkan<span class="caret"></span></a>
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

                                    <div id="chart_daftar_tutup"></div>

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

@section('js')
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script>
        const day = new Date();
        var currentYear = day.getFullYear();

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
            var tahun = $('#tahun-op').val();
            if (tahun !== null) {
                get_daftar_tutup_op(tahun);
            }
        }

        function get_total_op() {
            $.ajax({
                type: 'GET',
                url: '{{ route('pdl.op.get_total_op') }}',
                data: {},
                dataType: "json",
                success: function(data) {
                    console.log(data.success);
                    console.log(data.success["Pajak Reklame"]);
                    $('#list-count-jenis-pajak').empty();
                    $.each(data.success, function(key, value) {
                        $('#list-count-jenis-pajak').append(
                            `
                        <div class="col-xl-3">
                            <div class="card o-hidden border-0">
                                <div class="bg-primary b-r-4 card-body">
                                <div class="media static-top-widget">
                                    <div class="align-self-center text-center"><i data-feather="trending-up"></i></div>
                                    <div class="media-body"><span class="m-0">${key}</span>
                                    <h4 class="mb-0 counter">${value}</h4><i class="icon-bg" data-feather="trending-up"></i>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        `
                        );
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
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

        function table_kontribusi_op(jenis_pajak = null, tahun = null) {
            if (jenis_pajak == null && tahun == null) {
                var jenis_pajak = $('#jenis_pajak').val();
                var tahun = $('#tahun').val();
            }
            var table = $(".table-kontribusi-op").DataTable({
                "dom": 'Bfrtip',
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Excel',
                    "filename": 'Rekap Kontribusi Terbesar Objek Pajak',
                    "action": newexportaction,
                }, ],
                paging: false,
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: '{{ route('pdl.op.datatable_kontribusi_op') }}',
                    type: 'GET',
                    data: {
                        "tahun": tahun,
                        "jenis_pajak": jenis_pajak
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
                        data: 'nama_objek_pajak',
                        name: 'nama_objek_pajak'
                    },
                    {
                        data: 'nama_subjek_pajak',
                        name: 'nama_subjek_pajak'
                    },
                    {
                        data: 'kontribusi',
                        name: 'kontribusi'
                    }
                ],
                order: [
                    [0, 'desc']
                ],
            });
        }
        // var curJnsPajak = $('#jns_pajak').val();

        function table_op_aktif_tutup(jenis_pajak = null) {
            var table = $(".table-op-aktif-tutup").DataTable({
                "dom": 'Bfrtip',
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Excel',
                    "filename": 'Rekap Objek Pajak Aktif',
                    "action": newexportaction,
                }, ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: '{{ route('pdl.op.datatable_op_aktif_tutup') }}',
                    type: 'GET',
                    data: {
                        "jenis_pajak": jenis_pajak
                    }
                },
                columns: [{
                        data: 'nama_rekening',
                        name: 'nama_rekening'
                    },
                    {
                        data: 'npwpd',
                        name: 'npwpd'
                    },
                    {
                        data: 'nop',
                        name: 'nop'
                    },
                    {
                        data: 'nama_objek_pajak',
                        name: 'nama_objek_pajak'
                    },
                    {
                        data: 'alamat_objek_pajak',
                        name: 'alamat_objek_pajak'
                    },
                    // {data: 'status', name: 'status'}
                ],
                // order: [[1, 'asc']],
            });
        }

        function table_op_wilayah() {
            var table = $(".table-op-wilayah").DataTable({
                "dom": 'rtip',
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: '{{ route('pdl.op.datatable_detail_daftar_tutup_op') }}',
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

        function get_daftar_tutup_op(tahun = null) {
            let url_submit = "{{ route('pdl.op.get_chart_op') }}";
            $.ajax({
                type: 'GET',
                url: url_submit,
                data: {
                    "tahun": tahun,
                },
                cache: false,
                contentType: false,
                processData: true,
                success: function(data) {
                    console.log("data", data);
                    pendaftaran = data.pendaftaran;
                    penutupan = data.penutupan;
                    bulan = data.bulan;
                    chart_daftar_tutup_op(pendaftaran, penutupan, bulan)
                },

                error: function(data) {
                    return 0;
                    alert('Terjadi Kesalahan Pada Server');
                },

            });
        }

        function chart_daftar_tutup_op(pendaftaran, penutupan, bulan) {
            var tahun = $('#tahun-op').val();
            var options = {
                series: [{
                        name: "Pendaftaran",
                        data: pendaftaran
                    },
                    {
                        name: "Penutupan",
                        data: penutupan
                    }
                ],
                chart: {
                    height: 350,
                    type: 'line',
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    },
                    events: {
                        markerClick: function(event, chartContext, {
                            seriesIndex,
                            dataPointIndex,
                            config
                        }) {
                            var kategori = chartContext.w.config.series[seriesIndex].name;
                            var valBulan = bulan[dataPointIndex];

                            window.location.href = '{{ url('pdl/op/detail_daftar_tutup_op') }}' + '/' + kategori +
                                '/' + valBulan + '/' + tahun;
                        }
                    }
                },
                colors: ['#77B6EA', '#545454'],
                dataLabels: {
                    enabled: true,
                },
                stroke: {
                    curve: 'smooth'
                },
                title: {
                    //   text: 'Average High & Low Temperature',
                    align: 'left'
                },
                grid: {
                    borderColor: '#e7e7e7',
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                    },
                },
                markers: {
                    size: 1
                },
                xaxis: {
                    categories: bulan,
                },
                yaxis: {
                    title: {
                        text: 'Jumlah OP'
                    },
                    min: 1,
                    //   max: 40
                },
                legend: {
                    //   show: true
                    //   position: 'bottom',
                    //   horizontalAlign: 'right',
                    //   floating: true,
                    //   offsetY: -25,
                    //   offsetX: -5
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart_daftar_tutup"), options);
            chart.render();
            chart.updateOptions(options);

        }

        function filter_kontribusi() {
            var jenis_pajak = $('#jenis_pajak').val();
            var tahun = $('#tahun').val();
            // console.log(tahun)
            // if(tahun.length > 0){
            $(".table-kontribusi-op").DataTable().destroy();
            $(".table-op-aktif-tutup").DataTable().destroy();
            table_kontribusi_op(jenis_pajak, tahun);
            table_op_aktif_tutup(jenis_pajak, tahun);
            // }else{
            //     swal("MOHON PILIH TAHUN !", {
            //         icon: "warning",
            //     });
            // }
        }

        // function filterJnsPajak(){
        //     var jenis_pajak = $('#jns_pajak').val();
        //     $(".table-op-aktif-tutup").DataTable().destroy();
        //     table_op_aktif_tutup(jenis_pajak, tahun);
        // }


        $(document).ready(function() {
            $("#jenis_pajak").select2();
            // $("#jns_pajak").select2();
            $("#tahun").select2({
                placeholder: "Pilih Tahun"
            });

            let jenis_pajak = $('#jenis_pajak').val();
            let tahun = $('#tahun').val();
            table_kontribusi_op(jenis_pajak, tahun);
            // table_op_wilayah();
            table_op_aktif_tutup(jenis_pajak);
            get_daftar_tutup_op();
            get_total_op();

        })
    </script>
@endsection
