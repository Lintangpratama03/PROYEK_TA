@extends('admin.layout.main')
@section('title', 'Penerimaan PDL - Smart Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Penerimaan PDL</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">PDL</a></li>
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
            <div class="col-xl-4">
                <div class="mb-2">
                    <!-- <label class="col-form-label">Pilih Jenis Pajak</label> -->
                    <select id="jenis_pajak" name="jenis_pajak" class="col-sm-12">
                        <optgroup label="Jenis Pajak">
                            <!-- <option value="">Pilih Jenis Pajak</option> -->
                            @foreach (getJenisPajak() as $item)
                                <option value="{{ $item->group }}">{{ $item->nama_rekening }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="col-xl-3">
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
            <div class="col-xl-3">
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
            <div class="col-xl-3">
                <select name="kecamatan" id="kecamatan" class="form-control btn-square js-example-basic-single col-sm-12"
                    style="border: 1px solid #808080;">
                    <option value="">Pilih Kecamatan</option>
                    @foreach ($data as $kecamatan)
                        <option value="{{ $kecamatan->nama_kecamatan }}">{{ $kecamatan->nama_kecamatan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-3">
                <select name="kelurahan" id="kelurahan"class="form-control btn-square js-example-basic-single col-sm-12"
                    style="border: 1px solid #808080;">
                    <option value="" class = "d-flex align-items-center">Pilih Kelurahan</option>
                </select>
            </div>
            <div class="col-xl-2">
                <a class='btn btn-primary btn-sm' onclick='filterGrafikBulanAkumulasi()'><i class='fa fa-search'></i>
                    Tampilkan</a>
            </div>

            <div class="col-xl-12">
                <div class="col-xl-12">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6>Penerimaan PDL per Bulan</h6>
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
                            <h6>Penerimaan PDL Akumulasi</h6>
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
    </div>
    <!-- Container-fluid Ends-->
@endsection

@section('js')
    <script>
        const day = new Date();
        var currentYear = day.getFullYear();
        var currentMonth = day.getMonth() + 1;
        var curKelurahan = $('#kelurahan').val();
        var curKecamatan = $('#kecamatan').val();

        function filterWilayah() {
            $('#kecamatan').on('change', function() {
                var kecamatan = this.value;
                $("#kelurahan").html('');
                $.ajax({
                    url: '{{ route('pdl.penerimaan.get_wilayah') }}',
                    type: "GET",
                    data: {
                        "wilayah": 'kecamatan',
                        "data": kecamatan
                    },
                    dataType: 'json',
                    success: function(result) {
                        //console.log(result);
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

        function get_penerimaan_akumulasi(jenis_pajak, tahun = [], bulan = []) {
            let url_submit = "{{ route('pdl.penerimaan.penerimaan_akumulasi') }}";
            // let mingguSearch = $("#from-minggu-penerimaan-search").val();
            // let bulanSearch = $("#from-bulan-penerimaan-search").val();
            $.ajax({
                type: 'GET',
                url: url_submit,
                data: {
                    "jenis_pajak": jenis_pajak,
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
                console.log(index);
                let object = {
                    name: index,
                    data: value
                }
                arrSeries.push(object)
            })
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
                    categories: bulan,
                },
                yaxis: {
                    show: true,
                    opposite: false,
                    min: 0,
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
                colors: ['#f44336', '#4caf50', '#00bcd4', '#ff9800', '#3f51b5', '#9c27b0', '#fcba03']
            }

            var chart1 = new ApexCharts(
                document.querySelector("#chart-area"),
                options1
            );

            chart1.render();
            chart1.updateOptions(options1);

        }

        function get_penerimaan_perbulan(jenis_pajak, tahun = [], bulan = [], kecamatan = curKecamatan,
            kelurahan = curKelurahan) {
            console.log(kecamatan);
            let url_submit = "{{ route('pdl.penerimaan.penerimaan_perbulan') }}";
            $.ajax({
                type: 'GET',
                url: url_submit,
                data: {
                    "jenis_pajak": jenis_pajak,
                    "tahun": tahun,
                    "bulan": bulan,
                    "kecamatan": kecamatan,
                    "kelurahan": kelurahan,
                },
                cache: false,
                contentType: false,
                processData: true,
                success: function(data) {
                    // console.log(data);

                    bulan = data.bulan;
                    penerimaan = data.penerimaan;
                    // console.log(bulan)
                    // console.log(penerimaan)
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
            var pajak = $('#jenis_pajak').val();
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

            var options = {
                series: arrSeries,
                chart: {
                    type: 'bar',
                    height: 360,
                    events: {
                        dataPointSelection: function(event, chartContext, config) {
                            var tahun = chartContext.w.config.series[config.seriesIndex].name;
                            var bulan = config.dataPointIndex + 1;
                            //console.log(tahun, bulan);

                            window.location.href = '{{ url('pdl/penerimaan/detail_penerimaan_perbulan') }}' + '/' +
                                pajak + '/' + tahun + '/' + bulan + '/' + kecamatan + '/' + kelurahan;
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


        function get_penerimaan_harian(jenis_pajak, kecamatan = curKecamatan, kelurahan =
            curKelurahan) {
            let url_submit = "{{ route('pdl.penerimaan.penerimaan_harian') }}";
            var jenispajak_ = jenis_pajak

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
            // var currentDay = new Date(day).toLocaleDateString('en-US');
            // var firstDay = new Date(day.getFullYear(), day.getMonth(), 1).toLocaleDateString('en-US');
            // var dateInput = document.getElementById('daterange');
            // dateInput.value = firstDay + " - " + currentDay;
            // let tanggal = dateInput.value;

            // console.log(tanggal);

            $('#daterange').daterangepicker({
                linkedCalendars: false
            });

            // console.log("masuk");

            $.ajax({
                type: 'GET',
                url: url_submit,
                data: {
                    "tanggal": tanggal,
                    "jenis_pajak": jenispajak_,
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
                console.log("new", newDate);
                $.ajax({
                    type: 'GET',
                    url: url_submit,
                    data: {
                        "tanggal": newDate,
                        "jenis_pajak": jenispajak_,
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
                            var pajak = $('#jenis_pajak').val();
                            var tanggal = chartContext.w.config.labels[dataPointIndex];
                            let kel = $('#kelurahan').val();
                            let kec = $('#kecamatan').val();
                            window.location.href = '{{ url('pdl/penerimaan/detail_penerimaan_harian') }}' + '/' +
                                tanggal + '/' + pajak + '/' + kec + '/' + kel;
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
                    formatter: function(val) {
                        return bulatkanAngka(val) + " "
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

        function filterGrafikBulanAkumulasi() {
            let jenis_pajak = $('#jenis_pajak').val();
            let tahun = $('#tahun').val();
            let bulan = $('#bulan').val();
            let kecamatan = $('#kecamatan').val();
            let kelurahan = $('#kelurahan').val();
            get_penerimaan_perbulan(jenis_pajak, tahun, bulan, kecamatan, kelurahan);
            get_penerimaan_akumulasi(jenis_pajak, tahun, bulan);
            get_penerimaan_harian(jenis_pajak, kecamatan, kelurahan);
        }

        $(document).ready(function() {
            filterWilayah()
            $("#jenis_pajak").select2();

            $("#tahun").select2({
                placeholder: "Pilih Tahun (Bisa Multi Tahun)"
            });

            $("#bulan").select2({
                placeholder: "Pilih Bulan (Bisa Multi Bulan)"
            });
            let tahun = $('#tahun').val();
            let bulan = $('#bulan').val()
            let jenis_pajak = $('#jenis_pajak').val();
            get_penerimaan_perbulan(jenis_pajak);
            get_penerimaan_akumulasi(jenis_pajak, tahun, bulan);
            get_penerimaan_harian(jenis_pajak);
        })
    </script>
@endsection
