@extends('admin.layout.main')
@section('title', 'Dashboard Tunggakan PBB - Smart Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Dashboard Tunggakan PBB</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">PBB</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
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
                    @foreach (getKecamatan() as $kecamatan)
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
                            <h6>Tunggakan PBB per Bulan</h6>
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
                    url: '{{ route('pbb.tunggakan.get_wilayah') }}',
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


        function get_tunggakan_perbulan(tahun = [], bulan = [], kecamatan = curKecamatan,
            kelurahan = curKelurahan) {
            console.log(kecamatan);
            let url_submit = "{{ route('dashboard.tunggakan.tunggakan_perbulan') }}";
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
                    bulan = data.bulan;
                    tunggakan = data.tunggakan;
                    chart_tunggakan_perbulan(tunggakan, bulan);
                },

                error: function(data) {
                    return 0;
                    alert('Terjadi Kesalahan Pada Server');
                },

            });
        }

        function chart_tunggakan_perbulan(tunggakan, bulan) {
            // console.log("tunggakan function",tunggakan);
            var kelurahan = $('#kelurahan').val();
            var kecamatan = $('#kecamatan').val();
            let arrSeries = []
            $.each(tunggakan, function(index, value) {
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

                            window.location.href = '{{ url('pdl/tunggakan/detail_tunggakan_perbulan') }}' + '/' +
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
                        text: 'Jumlah Tunggakan'
                    },
                    labels: {
                        formatter: function(val) {
                            return (val) + " "
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
                            return (val) + " Tunggakan"
                        }
                    }
                }
            };

            var chartlinechart4 = new ApexCharts(document.querySelector("#chart-line"), options);
            chartlinechart4.render();
            chartlinechart4.updateOptions(options);
        }



        var table;

        function filterGrafikBulanAkumulasi() {
            let tahun = $('#tahun').val();
            let bulan = $('#bulan').val();
            let kecamatan = $('#kecamatan').val();
            let kelurahan = $('#kelurahan').val();
            get_tunggakan_perbulan(tahun, bulan, kecamatan, kelurahan);
        }

        $(document).ready(function() {
            filterWilayah()
            $("#tahun").select2({
                placeholder: "Pilih Tahun (Bisa Multi Tahun)"
            });

            $("#bulan").select2({
                placeholder: "Pilih Bulan (Bisa Multi Bulan)"
            });
            let tahun = $('#tahun').val();
            let bulan = $('#bulan').val()
            get_tunggakan_perbulan();
        })
    </script>
@endsection
