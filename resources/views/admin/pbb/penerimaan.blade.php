@extends('admin.layout.main')
@section('title', 'Penerimaan PBB - Smart Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-">
                    <h3>Penerimaan PBB</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">PBB</a></li>
                        <li class="breadcrumb-item active">Penerimaan</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid chart-widget">
        <div class="row">
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="mb-3 draggable card">
                    <div class="input-group card-header">
                        <div class="row">
                            <div class="col-xl-6 mb-3 col-sm-12 col-md-6">
                                <select name="tahun_rekap" id="tahun_rekap" class="form-control btn-square col-sm-12">
                                    @foreach (array_combine(range(date('Y'), 2000), range(date('Y'), 2000)) as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-2 mb-3 col-sm-12 col-md-6">
                                <div class="input-group-btn btn btn-square p-0">
                                    <a type="button" class="btn btn-primary btn-square btn_filter_tahun_rekap"
                                        type="button">Terapkan<span class="caret"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-border-vertical table_rekap_penerimaan">
                                <thead style="background-color:#f3e8ae">
                                    <tr>
                                        <th rowspan="2" style="text-align: center; vertical-align: middle;">No</th>
                                        <th rowspan="2" style="text-align: center; vertical-align: middle;">Tahun</th>
                                        <th rowspan="2" style="text-align: center; vertical-align: middle;">KECAMATAN
                                            DESA/KEL</th>
                                        <th colspan="2" style="text-align: center; vertical-align: middle;">KETETAPAN
                                        </th>
                                        <th colspan="3" style="text-align: center; vertical-align: middle;">REALISASI
                                        </th>
                                        <th colspan="3" style="text-align: center; vertical-align: middle;">SISA</th>
                                    </tr>
                                    <tr>
                                        <th>SPPT</th>
                                        <th>TARGET</th>
                                        <th>STTS</th>
                                        <th>SETORAN</th>
                                        <th>%</th>
                                        <th>SPPT</th>
                                        <th>TARGET</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th colspan="3">Total Keseluruhan</th>
                                        <th id="qty_ketetapan_sppt"></th>
                                        <th id="qty_ketetapan_target"></th>
                                        <th id="qty_realisasi_stts"></th>
                                        <th id="qty_realisasi_setoran"></th>
                                        <th id="qty_realisasi_persen"></th>
                                        <th id="qty_sisa_sppt"></th>
                                        <th id="qty_sisa_target"></th>
                                        <th id="qty_sisa_persen"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-3 mb-2 col-md-4 col-sm-6 ">
                            <select name="bulan[]" id="bulan"class="form-control btn-square col-sm-12 "
                                multiple="multiple">
                                @foreach (getMonthList() as $key => $value)
                                    <option value="{{ $key }}" class = "d-flex align-items-center">
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-3 mb-2 col-md-4 col-sm-6">
                            <select name="tahun_all[]" id="tahun-all" class="form-control btn-square col-sm-12"
                                multiple="multiple">
                                @foreach (array_combine(range(date('Y'), 1900), range(date('Y'), 1900)) as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-3 mb-2 col-md-4 col-sm-6">
                            <select name="kecamatan"
                                id="kecamatan"class="form-control btn-square js-example-basic-single col-sm-12"
                                style="border: 1px solid #808080;">
                                <option value="" class = "d-flex align-items-center">Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div class="col-xl-3 mb-2 col-md-4 col-sm-6">
                            <select name="kelurahan"
                                id="kelurahan"class="form-control btn-square js-example-basic-single col-sm-12"
                                style="border: 1px solid #808080;">
                                <option value="" class = "d-flex align-items-center">Pilih Kelurahan</option>
                            </select>
                        </div>
                        <div class="col-xl-3 mb-2 col-md-4 col-sm-6">
                            <div class="input-group-btn btn btn-square p-0">
                                <a class="btn btn-primary btn-square" type="button"
                                    onclick="filterTahunMul()">Terapkan<span class="caret"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="col-xl-12">
                        <div class="card o-hidden">
                            <div class="card-header pb-0">
                                <h6>Penerimaan PBB per Bulan</h6>
                            </div>
                            <div class="bar-chart-widget">
                                <div class="bottom-content card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div style="overflow-x: scroll;">
                                                <div id="chart-line"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-12">
                        <div class="card o-hidden">
                            <div class="card-header pb-0">
                                <h6>Penerimaan PBB Akumulasi</h6>
                            </div>
                            <div class="bar-chart-widget">
                                <div class="bottom-content card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div style="overflow-x: scroll;">
                                                <div id="chart-area"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6 id="judul-header">Penerimaan pada tahun {{ date('Y') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 draggable">
                                <div class="input-group">
                                    <select name="role_code" id="tahun" class="form-control btn-square">
                                        <option value="{{ date('Y') }}">Pilih Tahun Bayar</option>
                                        <!-- <option value="">Keseluruhan Data</option> -->
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
                            <div class="table-responsive">
                                <table class="table dtTable">
                                    <thead>
                                        <tr>
                                            <th>Tahun SPPT</th>
                                            <th>Nominal Bayar</th>
                                            <th>NOP Bayar</th>
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
        </div>
    @endsection

    @section('js')
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
        <script src="
        https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.all.min.js
        "></script>
        <!-- Plugins JS Ends-->
        <script>
            const day = new Date();
            var currentYear = day.getFullYear();
            var currentMonth = day.getMonth() + 1;
            var curKelurahan = $('#kelurahan').val();
            var curKecamatan = $('#kecamatan').val();

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
            // console.log("tgl",day);
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
                    return isMin + 'Rp. ' + (angka / 1000000000000).toFixed(2) + ' T';
                } else if (angka >= 1000000000) {
                    return isMin + 'Rp. ' + (angka / 1000000000).toFixed(2) + ' M';

                } else if (angka >= 1000000) {
                    return isMin + 'Rp. ' + (angka / 1000000).toFixed(2) + ' Juta';
                } else if (angka >= 100000 || angka >= 10000 || angka >= 1000) {
                    return isMin + 'Rp. ' + (angka / 1000).toFixed(2) + ' Ribu';
                } else {
                    return 'Rp. 0'
                }
            }

            function get_penerimaan_akumulasi(tahun = [], bulan = [], kecamatan = curKecamatan, kelurahan =
                curKelurahan) {
                let url_submit = "{{ route('pbb.penerimaan.penerimaan_akumulasi') }}";
                $.ajax({
                    type: 'GET',
                    url: url_submit,
                    data: {
                        "tahun": tahun,
                        "bulan": bulan,
                        "kecamatan": kecamatan,
                        "kelurahan": kelurahan,
                    },
                    cache: false,
                    contentType: false,
                    processData: true,
                    success: function(data) {
                        penerimaan = data.penerimaan;
                        bulan = data.bulan;


                        chart_akumulasi_penerimaan(penerimaan, bulan);
                    },

                    error: function(data) {
                        return 0;
                        alert('Terjadi Kesalahan Pada Server');
                    },

                });
            }

            function chart_akumulasi_penerimaan(penerimaan, bulan) {
                let arrSeries = []
                $.each(penerimaan, function(index, value) {
                    let object = {
                        name: index,
                        data: value
                    }
                    arrSeries.push(object)
                })
                // console.log("arrSeries",arrSeries)
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

            function get_penerimaan_perbulan(tahun = [], bulan = [], kecamatan = curKecamatan, kelurahan =
                curKelurahan) {
                let url_submit = "{{ route('pbb.penerimaan.penerimaan_perbulan') }}";
                $.ajax({
                    type: 'GET',
                    url: url_submit,
                    data: {
                        "tahun": tahun,
                        "bulan": bulan,
                        "kecamatan": kecamatan,
                        "kelurahan": kelurahan,
                    },
                    cache: false,
                    contentType: false,
                    processData: true,
                    success: function(data) {
                        console.log("data", data);
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
                // console.log("penerimaan function",penerimaan);
                var kelurahan = $('#kelurahan').val();
                var kecamatan = $('#kecamatan').val();
                let arrSeries = []
                $.each(penerimaan, function(index, value) {
                    let object = {
                        name: index,
                        data: value
                    }
                    arrSeries.push(object)
                })
                // console.log("arrSeries",arrSeries)

                var options = {
                    series: arrSeries,
                    chart: {
                        type: 'bar',
                        height: 360,
                        events: {
                            dataPointSelection: function(event, chartContext, config) {
                                var tahun = chartContext.w.config.series[config.seriesIndex].name;
                                var bulan = chartContext.w.globals.labels[config.dataPointIndex];
                                //console.log(tahun, bulan);

                               
                                if(kecamatan == null || kecamatan == ""){
                                    Swal.fire({
                                        icon: "info",
                                        title: "Notifikasi",
                                        text: "Pilih Kecamatan terlebih dahulu !",
                                    });
                                }else{
                                    window.location.href = '{{ url('pbb/penerimaan/detail_penerimaan_perbulan') }}' + '/' +
                                    tahun + '/' + bulan + '/' + kecamatan + '/' + kelurahan;
                                }
                          
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
            let curTanggal = dateInput.value;

            function get_penerimaan_harian(tanggal = curTanggal, kecamatan = curKecamatan, kelurahan = curKelurahan) {
                let url_submit = "{{ route('pbb.penerimaan.penerimaan_harian') }}";
                var options = {
                    month: '2-digit',
                    day: '2-digit',
                    year: 'numeric'
                };
                var days = new Date().toLocaleDateString('en-US', options);

                var today = new Date();
                var thirtyDaysAgo = new Date(today);
                thirtyDaysAgo.setDate(today.getDate() - 30);

                var options2 = {
                    month: '2-digit',
                    day: '2-digit',
                    year: 'numeric'
                };
                var thirtyDaysAgoFormatteds = thirtyDaysAgo.toLocaleDateString('en-US', options2);
                var dateInputs = document.getElementById('daterange');
                dateInputs.value = thirtyDaysAgoFormatteds + " - " + days;
                let tanggals = dateInputs.value;

                $('#daterange').daterangepicker({
                    linkedCalendars: false
                });
                $.ajax({
                    type: 'GET',
                    url: url_submit,
                    data: {
                        "tanggal": tanggals,
                        "kecamatan": kecamatan,
                        "kelurahan": kelurahan,
                    },
                    cache: false,
                    contentType: false,
                    processData: true,
                    success: function(data) {
                        tanggal = data.tanggal;
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
                var kecamatan = $('#kecamatan').val();
                var kelurahan = $('#kelurahan').val();
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
                                console.log(kecamatan+"chart harian");
                                if(kecamatan == null || kecamatan == ""){
                                    Swal.fire({
                                        icon: "info",
                                        title: "Notifikasi",
                                        text: "Pilih Kecamatan  terlebih dahulu !",
                                    });
                                }else{
                                    window.location.href = '{{ url('pbb/penerimaan/detail_penerimaan_harian') }}' + '/' +
                                        tanggal + '/' + kecamatan + '/' + kelurahan;
                                }
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

            function table_penerimaan_tahun(tahun = currentYear) {
                if (tahun == null) {
                    $('#judul-header').text(`Penerimaan pada tahun ${currentYear}`);
                } else {
                    $('#judul-header').text(`Penerimaan pada tahun ${tahun}`);
                }
                table = $(".dtTable").DataTable({
                    dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'>>" +
                        "<'row'<'col-sm-12'tr>>" + // Add table
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    buttons: [{
                        "extend": 'excel',
                        "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                        "titleAttr": 'Export to Excel',
                        "filename": 'Penerimaan Bedasarkan Tahun Bayar',
                        "action": newexportaction
                    }, ],
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    searchDelay: 2000,
                    ajax: {
                        url: "{{ route('pbb.penerimaan.datatable_penerimaan_tahun') }}",
                        type: 'GET',
                        data: {
                            "tahun": tahun
                        }
                    },
                    columns: [{
                            data: 'tahun',
                            name: 'tahun'
                        },
                        {
                            data: 'nominal',
                            name: 'nominal'
                        },
                        {
                            data: 'nop',
                            name: 'nop'
                        }
                    ],
                    order: [
                        [0, 'desc']
                    ],
                });
            }

            function filterTahun() {
                var tahun = $('#tahun').val();
                if (tahun !== null) {
                    $(".dtTable").DataTable().destroy();
                    table_penerimaan_tahun(tahun);
                }
            }

            function filterTahunMul() {
                var tahun_all = $('#tahun-all').val();
                var bulan = $('#bulan').val();
                var kecamatan = $('#kecamatan').val();
                var kelurahan = $('#kelurahan').val();
                get_penerimaan_perbulan(tahun_all, bulan, kecamatan, kelurahan);
                get_penerimaan_akumulasi(tahun_all, bulan, kecamatan, kelurahan);
                get_penerimaan_harian( kecamatan, kelurahan);
            }

            function filterWilayah() {
                $("#kecamatan").html('');
                $.ajax({
                    url: '{{ route('pbb.penerimaan.get_wilayah') }}',
                    type: "GET",
                    data: {
                        "wilayah": 'uptd'
                    },
                    dataType: 'json',
                    success: function(result) {
                        // console.log(result);
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

            $('body').on('click', '.btn_filter_tahun_rekap', function() {
                console.log("masuk filter");
                filterTahunRekap();
            });

            function filterTahunRekap() {
                let tahun = $('#tahun_rekap').val();
                $(".table_rekap_penerimaan").DataTable().destroy();
                table_rekap_penerimaan(tahun);
                show_qty_penerimaan(tahun);
            }

            function show_qty_penerimaan(tahun = currentYear) {
                $.ajax({
                    url: '{{ route('pbb.penerimaan.show_qty_penerimaan') }}',
                    type: 'GET',
                    cache: false,
                    contentType: false,
                    processData: true,
                    data: {
                        "tahun": tahun
                    },
                    success: function(response) {
                        $('#qty_ketetapan_sppt').text(response.qty_ketetapan_sppt);
                        $('#qty_ketetapan_target').text(response.qty_ketetapan_target);
                        $('#qty_realisasi_stts').text(response.qty_realisasi_stts);
                        $('#qty_realisasi_setoran').text(response.qty_realisasi_setoran);
                        $('#qty_realisasi_persen').text(response.qty_realisasi_persen);
                        $('#qty_sisa_sppt').text(response.qty_sisa_sppt);
                        $('#qty_sisa_target').text(response.qty_sisa_target);
                        $('#qty_sisa_persen').text(response.qty_sisa_persen);
                    }
                });
            }
            var table_rekap;

            function table_rekap_penerimaan(tahun = currentYear) {

                table_rekap = $(".table_rekap_penerimaan").DataTable({
                    dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'f>>" +
                        "<'row'<'col-sm-12'tr>>" + // Add table
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    buttons: [{
                        "extend": 'excel',
                        "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                        "titleAttr": 'Excel',
                        "filename": 'Rekap Penerimaan PBB per Kecamatan',
                        "action": newexportaction,
                        customize: function(xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            var index_jumlah = $('row', sheet).length;
                            var row = '<row r="' + (index_jumlah + 1) + '">';
                            row += '<c t="inlineStr" r="A' + (index_jumlah + 1) +
                                '" s="22"><is><t></t></is></c>';
                            row += '<c t="inlineStr" r="B' + (index_jumlah + 1) +
                                '" s="22"><is><t>Total Keseluruhan</t></is></c>';
                            row += '<c t="inlineStr" r="C' + (index_jumlah + 1) +
                                '" s="22"><is><t></t></is></c>';
                            row += '<c t="inlineStr" r="D' + (index_jumlah + 1) +
                                '" s="22"><is><t>' + $(
                                    '#qty_ketetapan_sppt').text() + '</t></is></c>';
                            row += '<c t="inlineStr" r="E' + (index_jumlah + 1) +
                                '" s="22"><is><t>' + $(
                                    '#qty_ketetapan_target').text() + '</t></is></c>';
                            row += '<c t="inlineStr" r="F' + (index_jumlah + 1) +
                                '" s="22"><is><t>' + $(
                                    '#qty_realisasi_stts').text() + '</t></is></c>';
                            row += '<c t="inlineStr" r="G' + (index_jumlah + 1) +
                                '" s="22"><is><t>' + $(
                                    '#qty_realisasi_setoran').text() + '</t></is></c>';
                            row += '<c t="inlineStr" r="H' + (index_jumlah + 1) +
                                '" s="22"><is><t>' + $(
                                    '#qty_realisasi_persen').text() + '</t></is></c>';
                            row += '<c t="inlineStr" r="I' + (index_jumlah + 1) +
                                '" s="22"><is><t>' + $(
                                    '#qty_sisa_sppt').text() + '</t></is></c>';
                            row += '<c t="inlineStr" r="J' + (index_jumlah + 1) +
                                '" s="22"><is><t>' + $(
                                    '#qty_sisa_target').text() + '</t></is></c>';
                            row += '<c t="inlineStr" r="k' + (index_jumlah + 1) +
                                '" s="22"><is><t>' + $(
                                    '#qty_sisa_persen').text() + '</t></is></c>';
                            row += '</row>';
                            $('sheetData', sheet).append(row);
                        }
                    }, ],
                    aLengthMenu: [
                        [10, 25, 50, 100, 200, -1],
                        [10, 25, 50, 100, 200, "All"]
                    ],
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    searchDelay: 2000,
                    ajax: {
                        url: "{{ route('pbb.penerimaan.datatable_rekap_penerimaan') }}",
                        type: 'GET',
                        data: {
                            "tahun": tahun
                        }
                    },
                    columns: [{
                            data: 'no',
                            name: 'no'
                        },
                        {
                            data: 'tahun',
                            name: 'tahun'
                        },
                        {
                            data: 'kecamatan',
                            name: 'kecamatan'
                        },
                        {
                            data: 'ketetapan_sppt',
                            name: 'ketetapan_sppt'
                        },
                        {
                            data: 'ketetapan_target',
                            name: 'ketetapan_target'
                        },
                        {
                            data: 'realisasi_stts',
                            name: 'realisasi_stts'
                        },
                        {
                            data: 'realisasi_setoran',
                            name: 'realisasi_setoran'
                        },
                        {
                            data: 'realisasi_persen',
                            name: 'realisasi_persen'
                        },
                        {
                            data: 'sisa_sppt',
                            name: 'sisa_sppt'
                        },
                        {
                            data: 'sisa_target',
                            name: 'sisa_target'
                        },
                        {
                            data: 'sisa_persen',
                            name: 'sisa_persen'
                        }
                    ],
                });
            }

            $(document).ready(function() {
                $("#tahun-all").select2({
                    placeholder: "Pilih Tahun (Bisa Multi Tahun)"
                });

                $("#bulan").select2({
                    placeholder: "Pilih Bulan (Bisa Multi Bulan)"
                });

                $("#tahun_rekap").select2({
                    placeholder: "Pilih Tahun"
                });

                table_rekap_penerimaan();
                get_penerimaan_perbulan();
                get_penerimaan_akumulasi();
                get_penerimaan_harian();
                table_penerimaan_tahun();
                filterTahun();
                filterWilayah();
                let tahun = $('#tahun_rekap').val();
                show_qty_penerimaan(tahun);
            })
        </script>
    @endsection
