@extends('admin.layout.main')
@section('title', 'Penerimaan BPHTB - Smart Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Penerimaan BPHTB</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">BPHTB</a></li>
                        <li class="breadcrumb-item active">Penerimaan</li>
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
            <div class="col-xl-5">
                <div class="mb-2">
                    <!-- <label class="col-form-label">Pilih Tahun</label> -->
                    <select id="tahun" name="tahun[]" class="col-sm-12" multiple="multiple">
                        <optgroup label="Tahun">
                            @foreach (array_combine(range(date('Y'), 2018), range(date('Y'), 2018)) as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="mb-2">
                    <!-- <label class="col-form-label">Pilih Bulan</label> -->
                    <select id="bulan" name="bulan[]" class="col-sm-12" multiple="multiple">
                        <optgroup label="Bulan">
                            @foreach (getMonthList() as $index => $value)
                                <option value="{{ $index }}">{{ $value }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="col-xl-2">
                <a class='btn btn-primary btn-sm' onclick='filterGrafikBulanAkumulasi()'><i class='fa fa-search'></i>
                    Tampilkan</a>
            </div>
            <div class="col-xl-12">
                <div class="col-xl-12">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6>Penerimaan BPHTB per Bulan</h6>
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
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6>Penerimaan BPHTB Akumulasi</h6>
                        </div>
                        <div class="bar-chart-widget">
                            <div class="bottom-content card-body">
                                <div class="row">
                                    <div class="col-12">

                                        <div id="chart-area"></div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card o-hidden">
                    <div class="card-header pb-0">
                        <div class ="row">
                            <div class="col-xl-6">
                                <h6>Penerimaan Harian</h6>
                            </div>
                            <div class="col-xl-6">
                                <div class="daterange-card">
                                    <div class="theme-form">
                                        <div class="form-group">
                                            <input class="form-control digits" type="text" name="daterange"
                                                id="daterange" value="">
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

                                    <div id="chart-penerimaan-harian"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card o-hidden">
                    <div class="card-header pb-0">
                        <h6>Penerimaan Bedasarkan Notaris</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table dtTable">
                                <thead>
                                    <tr>
                                        <th>Notaris</th>
                                        <th>Tahun</th>
                                        <th>Bulan</th>
                                        <th>Jumlah Transaksi</th>
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
        var currentMonth = day.getMonth() + 1;

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

        function formatRupiah(angka) {
            var options = {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2,
            };
            var formattedNumber = angka.toLocaleString('ID', options);
            return formattedNumber;
        }

        function get_penerimaan_akumulasi(tahun = [], bulan = []) {
            let url_submit = "{{ route('bphtb.penerimaan.penerimaan_akumulasi') }}";
            // let mingguSearch = $("#from-minggu-penerimaan-search").val();
            // let bulanSearch = $("#from-bulan-penerimaan-search").val();
            $.ajax({
                type: 'GET',
                url: url_submit,
                data: {
                    "tahun": tahun,
                    "bulan": bulan,
                },
                cache: false,
                contentType: false,
                processData: true,
                success: function(data) {
                    console.log(data);

                    bulan = data.bulan;
                    penerimaan = data.penerimaan;
                    chart_akumulasi_penerimaan(penerimaan, bulan);
                },

                error: function(data) {
                    return 0;
                    alert('Terjadi Kesalahan Pada Server');
                },

            });
        }

        function chart_akumulasi_penerimaan(penerimaan, bulan) {
            let arrSeries = [];
            $.each(penerimaan, function(index, value) {
                let object = {
                    name: index,
                    data: value
                };
                arrSeries.push(object);
            });
            // area spaline chart
            var options1 = {
                chart: {
                    height: 350,
                    type: 'area',
                    toolbar: {
                        show: true
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                series: arrSeries,

                xaxis: {
                    type: 'text',
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des'],
                },
                yaxis: {
                    show: true,
                    title: {
                        text: 'Rp. (Rupiah)'
                    },
                    labels: {
                        formatter: function(val) {
                            return bulatkanAngka(val) + " "
                        }
                    }
                },
                tooltip: {
                    x: {
                        // format: 'dd/MM/yy HH:mm'
                    },
                    y: {
                        formatter: function(val) {
                            return formatRupiah(val) + " Rupiah"
                        }
                    }
                },
                colors: ['#f44336', '#ff9800', '#4caf50', '#00bcd4', '#3f51b5', '#9c27b0']
            }

            var chart1 = new ApexCharts(
                document.querySelector("#chart-area"),
                options1
            );

            chart1.render();
            chart1.updateOptions(options1);
        }

        function get_penerimaan_perbulan(tahun = [], bulan = []) {
            let url_submit = "{{ route('bphtb.penerimaan.penerimaan_perbulan') }}";
            // let mingguSearch = $("#from-minggu-penerimaan-search").val();
            // let bulanSearch = $("#from-bulan-penerimaan-search").val();
            $.ajax({
                type: 'GET',
                url: url_submit,
                data: {
                    "tahun": tahun,
                    "bulan": bulan,
                },
                cache: false,
                contentType: false,
                processData: true,
                success: function(data) {
                    // console.log(data);

                    bulan = data.bulan;
                    penerimaan = data.penerimaan;
                    chart_penerimaan_perbulan(penerimaan, bulan);
                },

                error: function(data) {
                    return 0;
                    alert('Terjadi Kesalahan Pada Server');
                },

            });
        }

        function chart_penerimaan_perbulan(penerimaan, bulan) {
            let arrSeries = [];
            $.each(penerimaan, function(index, value) {
                let object = {
                    name: index,
                    data: value
                };
                arrSeries.push(object);
            });

            var options = {
                series: arrSeries,
                chart: {
                    type: 'bar',
                    height: 360,
                    events: {
                        dataPointSelection: function(event, chartContext, config) {
                            var tahun = chartContext.w.config.series[config.seriesIndex].name;
                            var bulan = chartContext.w.globals.labels[config.dataPointIndex];
                            console.log(tahun, bulan);

                            window.location.href = '/bphtb/penerimaan/detail_penerimaan_perbulan' + '/' + tahun +
                                '/' + bulan;
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '70%',
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
                    categories: bulan,
                },
                yaxis: {
                    show: true,
                    title: {
                        text: 'Rp. (Rupiah)'
                    },
                    labels: {
                        formatter: function(val) {
                            return bulatkanAngka(val) + " "
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
                            return formatRupiah(val) + ""
                        }
                    }
                }
            };

            var chartlinechart4 = new ApexCharts(document.querySelector("#chart-line"), options);
            chartlinechart4.render();
            chartlinechart4.updateOptions(options);


        }

        var currentDay = new Date(day).toLocaleDateString('en-US');
        var firstDay = new Date(day.getFullYear(), day.getMonth(), 1).toLocaleDateString('en-US');
        var dateInput = document.getElementById('daterange');
        dateInput.value = firstDay + " - " + currentDay;;
        let tanggal = dateInput.value;

        function get_penerimaan_harian() {
            let url_submit = "{{ route('bphtb.penerimaan.penerimaan_harian') }}";
            var options = {
                    month: '2-digit',
                    day: '2-digit',
                    year: 'numeric'
                };
                var day = new Date().toLocaleDateString('en-US', options);

                var today = new Date();
                var thirtyDaysAgo = new Date(today);
                thirtyDaysAgo.setDate(today.getDate() - 30);

                var options2 = {
                    month: '2-digit',
                    day: '2-digit',
                    year: 'numeric'
                };
                var thirtyDaysAgoFormatted = thirtyDaysAgo.toLocaleDateString('en-US', options2);
                var dateInput = document.getElementById('daterange');
                dateInput.value = thirtyDaysAgoFormatted + " - " + day;
                let tanggal = dateInput.value;

                $('#daterange').daterangepicker({
                    linkedCalendars: false
                });

            $.ajax({
                type: 'GET',
                url: url_submit,
                data: {
                    "tanggal": tanggal,
                },
                cache: false,
                contentType: false,
                processData: true,
                success: function(data) {
                    console.log(data);

                    var tanggal = data.tanggal;
                    var penerimaan = data.penerimaan;

                    chart_penerimaan_harian(tanggal, penerimaan);
                },

                error: function(data) {
                    return 0;
                    alert('Terjadi Kesalahan Pada Server');
                },

            });

            $("#daterange").on('apply.daterangepicker', function() {
                let newDate = $(this).val();
                console.log("new", newDate);
                $.ajax({
                    type: 'GET',
                    url: url_submit,
                    data: {
                        "tanggal": newDate,
                    },
                    cache: false,
                    contentType: false,
                    processData: true,
                    success: function(data) {

                        tanggal = data.tanggal;
                        var penerimaan = data.penerimaan;

                        console.log("tanggal", tanggal);

                        chart_penerimaan_harian(tanggal, penerimaan);
                    },

                    error: function(data) {
                        return 0;
                        alert('Terjadi Kesalahan Pada Server');
                    },

                });
            })
        }

        function chart_penerimaan_harian(tanggal, penerimaan) {
            // Turnover chart
            var optionsturnoverchart = {
                chart: {
                    height: 320,
                    type: 'area',
                    zoom: {
                        enabled: false
                    },
                    events: {
                        markerClick: function(event, chartContext, {
                            seriesIndex,
                            dataPointIndex,
                            config
                        }) {
                            var tanggal = chartContext.w.config.labels[dataPointIndex];
                            window.location.href = '/bphtb/penerimaan/detail_penerimaan_harian' + '/' + tanggal;
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight'
                },
                fill: {
                    colors: [vihoAdminConfig.primary],
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
                series: [{
                    name: "Penerimaan",
                    data: penerimaan
                }],
                colors: [vihoAdminConfig.primary],
                labels: tanggal,
                xaxis: {
                    type: 'datetime'
                },
                yaxis: {
                    opposite: false,
                    min: 0,
                    labels: {
                        formatter: function(val) {
                            return bulatkanAngka(val) + ""
                        }
                    }
                },
                legend: {
                    horizontalAlign: 'left'
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return formatRupiah(val) + " Rupiah"
                        }
                    }
                }
            }
            var chartturnoverchart = new ApexCharts(document.querySelector("#chart-penerimaan-harian"),
                optionsturnoverchart);
            chartturnoverchart.render();
            chartturnoverchart.updateOptions(optionsturnoverchart);

        }


        var table;

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

        function table_penerimaan_notaris() {
            table = $(".dtTable").DataTable({
                "dom": 'Bfrtip',
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Excel',
                    "filename": 'Rekap Penerimaan Bedasarkan Notaris',
                    "action": newexportaction,
                }, ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: '{{ route('bphtb.penerimaan.datatable_penerimaan_notaris') }}',
                columns: [{
                        data: 'notaris',
                        name: 'notaris'
                    },
                    {
                        data: 'tahun',
                        name: 'tahun'
                    },
                    {
                        data: 'bulan',
                        name: 'bulan'
                    },
                    {
                        data: 'jumlah_transaksi',
                        name: 'jumlah_transaksi'
                    },
                    {
                        data: 'nominal',
                        name: 'nominal'
                    }
                ]
            });
        }

        function filterGrafikBulanAkumulasi() {
            // var jenis_pajak = $('#jenis_pajak').val();
            var tahun = $('#tahun').val();
            var bulan = $('#bulan').val();
            // console.log(bulan);
            // console.log(tahun);
            // console.log(tahun)
            get_penerimaan_perbulan(tahun, bulan);
            get_penerimaan_akumulasi(tahun, bulan);
            // if(tahun.length > 0 && bulan.length > 0){
            // }else{
            //     swal("MOHON PILIH TAHUN DAN BULAN !", {
            //         icon: "warning",
            //     });
            // }
        }

        $(document).ready(function() {

            $("#tahun").select2({
                placeholder: "Pilih Tahun (Bisa Multi Tahun)"
            });

            $("#bulan").select2({
                placeholder: "Pilih Bulan (Bisa Multi Bulan)"
            });

            get_penerimaan_perbulan();
            get_penerimaan_akumulasi();
            get_penerimaan_harian();
            table_penerimaan_notaris();

            // chart_penerimaan_harian();
            // chart_akumulasi_penerimaan();
            // chart_penerimaan_perbulan();


        })
    </script>
@endsection
