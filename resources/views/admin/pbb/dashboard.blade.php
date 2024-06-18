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
                    <label for="start-year">Pilih Tahun Mulai</label>
                    <select id="start-year" name="start-year" class="form-control">
                        <option value="">Pilih Tahun</option>
                        @foreach (range(date('Y') - 10, date('Y')) as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="mb-2">
                    <label for="end-year">Pilih Tahun Akhir</label>
                    <select id="end-year" name="end-year" class="form-control">
                        <option value="{{ date('Y') }}">Pilih Tahun</option>
                        @foreach (range(date('Y'), 2010) as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xl-3">
                <label for="start-year">Kecamatan</label>
                <select name="kecamatan" id="kecamatan" class="form-control btn-square js-example-basic-single col-sm-12"
                    style="border: 1px solid #808080;">
                    <option value="">Pilih Kecamatan</option>
                    @foreach (getKecamatan() as $kecamatan)
                        <option value="{{ $kecamatan->nama_kecamatan }}">{{ $kecamatan->nama_kecamatan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-3">
                <label for="start-year">Kelurahan</label>
                <select name="kelurahan" id="kelurahan"class="form-control btn-square js-example-basic-single col-sm-12"
                    style="border: 1px solid #808080;">
                    <option value="" class = "d-flex align-items-center">Pilih Kelurahan</option>
                </select>
            </div>
            <div class="col-xl-2">
                <a class='btn btn-primary btn-sm' onclick='filterGrafik()'><i class='fa fa-search'></i>
                    Tampilkan</a>
            </div>

            <div class="col-xl-12">
                <div class="col-xl-12">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6>Grafik Tunggakan PBB Per Tahun</h6>
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
                        $('#kelurahan').append(
                            '<option value="" "class = "d-flex align-items-center">Pilih Kelurahan</option>'
                        );
                        $.each(result, function(key, value) {
                            $("#kelurahan").append('<option value="' + value.kelurahan +
                                '" class="d-flex align-items-center">' + value.kelurahan +
                                '</option>');
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
                return isMin + 'Rp. ' + angka;
            } else {
                return 'Rp. 0';
            }
        }

        var chart = null; // Declare the chart variable outside the function scope

        function get_tunggakan_pertahun(startYear, endYear, kecamatan = curKecamatan, kelurahan = curKelurahan) {
            let url_submit = "{{ route('dashboard.tunggakan.tunggakan_pertahun') }}";
            $.ajax({
                type: 'GET',
                url: url_submit,
                data: {
                    "start_year": startYear,
                    "end_year": endYear,
                    "kecamatan": kecamatan,
                    "kelurahan": kelurahan,
                },
                success: function(data) {
                    let years = Object.keys(data.tunggakan);
                    let tunggakanData = years.map(year => data.tunggakan[year]);
                    chart_tunggakan_pertahun(tunggakanData, years);
                },
                error: function(data) {
                    alert('Terjadi Kesalahan Pada Server');
                },
            });
        }

        function chart_tunggakan_pertahun(tunggakan, tahun) {
            var kelurahan = $('#kelurahan').val() || 'all';
            var kecamatan = $('#kecamatan').val() || 'all';

            let seriesData = [{
                name: 'Total Tunggakan',
                data: tunggakan
            }];

            var options = {
                series: seriesData,
                chart: {
                    type: 'line',
                    height: 360,
                    events: {
                        markerClick: function(event, chartContext, {
                            dataPointIndex
                        }) {
                            var selectedYear = tahun[dataPointIndex];
                            var selectedKecamatan = kecamatan !== 'all' ? kecamatan : '';
                            var selectedKelurahan = kelurahan !== 'all' ? kelurahan : '';
                            var detailUrl = '{{ url('dashboard/tunggakan/detail_tunggakan_pertahun') }}' +
                                '/' + selectedYear + '/' + selectedKecamatan + '/' + selectedKelurahan;
                            window.location.href = detailUrl;
                        }
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 2,
                },
                xaxis: {
                    categories: tahun,
                    title: {
                        text: 'Tahun'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Jumlah Tunggakan'
                    },
                    labels: {
                        formatter: function(val) {
                            return val;
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val;
                        }
                    }
                }
            };

            if (chart) {
                chart.destroy(); // Destroy the existing chart if it exists
            }

            chart = new ApexCharts(document.querySelector("#chart-line"), options);
            chart.render();
        }

        function filterGrafik() {
            let startYear = $('#start-year').val();
            let endYear = $('#end-year').val();
            let kecamatan = $('#kecamatan').val();
            let kelurahan = $('#kelurahan').val();
            get_tunggakan_pertahun(startYear, endYear, kecamatan, kelurahan);
        }

        $(document).ready(function() {
            filterWilayah();

            // Initialize the year selects
            $("#start-year, #end-year").select2({
                placeholder: "Pilih Tahun"
            });

            // Load the data for the initial range
            let currentYear = new Date().getFullYear();
            get_tunggakan_pertahun(currentYear - 9, currentYear);
        });
    </script>
@endsection
