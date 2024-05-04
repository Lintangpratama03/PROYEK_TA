@extends('admin.layout.main')
@section('title', 'Ketetapan BPHTB - Smart Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Ketetapan BPHTB</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">BPHTB</a></li>
                        <li class="breadcrumb-item active">ketetapan</li>
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
                <div class="card o-hidden">

                    <div class="input-group p-3">
                        <select name="" class="form-control" id="kategoriPerolehanBphtb">
                            <option value="">-- Pilih Kategori -- </option>
                            <option value="nominal">Nominal</option>
                            <option value="jumlah_transaksi">Jumlah Transaksi</option>
                        </select>
                        <select name="" class="form-control" id="tahunPerolehanBphtb">
                            <option value="">-- Pilih Tahun --</option>
                            @foreach (array_combine(range(date('Y'), 2018), range(date('Y'), 2018)) as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-success" onclick="filterPerolehanBphtb()">Terapkan</button>
                    </div>
                    <div class="card-header pb-0">
                        <h6>Ketetapan Berdasarkan Perolehan BPHTB</h6>
                    </div>
                    <div class="bar-chart-widget">
                        <div class="bottom-content card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div style="overflow-x: scroll;">
                                        <div id="chart-perolehanBpthb"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-xl-12">
                <div class="card o-hidden">
                    <div class="card-header pb-0">
                        <h6>Ketetapan Berdasarkan Peruntukan BPHTB</h6>
                    </div>
                    <div class="bar-chart-widget">
                        <div class="bottom-content card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div style="overflow-x: scroll;">
                                        <div id="chart-peruntukanBpthb"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- 
            <div class="col-xl-12">
                <div class="card o-hidden">
                    <div class="input-group p-3">
                        <select name="" class="form-control" id="tahunValidasiBphtb">
                            <option value="">-- Pilih Tahun --</option>
                            @foreach (array_combine(range(date('Y'), 2018), range(date('Y'), 2018)) as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-success" onclick="filterValidasiBphtb()">Terapkan</button>
                    </div>
                    <div class="card-header pb-0">
                        <h6>Ketetapan Berdasarkan Validasi BPHTB</h6>
                    </div>
                    <div class="bar-chart-widget">
                        <div class="bottom-content card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div style="overflow-x: scroll;">
                                        <div id="chart-validasiBPHTB"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="col-xl-12">
                <div class="card o-hidden">
                    <div class="input-group p-3">
                        <select name="" class="form-control" id="tahunValidasiBphtb">
                            <option value="">-- Pilih Tahun --</option>
                            @foreach (array_combine(range(date('Y'), 2018), range(date('Y'), 2018)) as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-success" onclick="filterValidasiBphtb()">Terapkan</button>
                    </div>
                    <div class="card-header pb-0">
                        <h6>Ketetapan Berdasarkan Nihil Bayar BPHTB</h6>
                    </div>
                    <div class="bar-chart-widget">
                        <div class="bottom-content card-body">
                            <div class="row">
                                <div class="col-12 overflow-x">
                                    <div style="overflow-x: scroll;">
                                        <div id="chart-nihilBayarBPHTB"></div>
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
                        <h6>Pelaporan Bedasarkan PPAT</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <select id="tahunPelaporanPpat" name="tahun[]" class="select2basic" multiple>
                                    @foreach (array_combine(range(date('Y'), 2018), range(date('Y'), 2018)) as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="bulanPelaporanPpat" name="bulan[]" class="select2basic" multiple="multiple">
                                    @foreach (getMonthList() as $index => $value)
                                        <option value="{{ $index }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-success"
                                    onclick="filterTablePelaporanPpat()">Terapkan</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="datatablePelaporanPpat">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>PPAT</th>
                                        <th>Tahun</th>
                                        <th>Bulan</th>
                                        <th>Jumlah Laporan</th>
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
        function formatRupiah(angka) {
            var options = {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2,
            };
            var formattedNumber = angka.toLocaleString('ID', options);
            return formattedNumber;
        }

        function getPerolehanBphtb() {
            let url_submit = "{{ route('bphtb.ketetapan.perolehan') }}";
            let tahunSearch = $("#tahunPerolehanBphtb").val();

            $.ajax({
                type: 'GET',
                url: url_submit,
                data: {
                    "tahun": tahunSearch,
                },
                cache: false,
                contentType: false,
                processData: true,
                success: function(data) {
                    // console.log(data);

                    // var penerimaan_2021 = data.p2021;
                    // var penerimaan_2022 = data.p2022;
                    // var penerimaan_2023 = data.p2023;

                    chartPerolehanBphtb(data);
                },

                error: function(data) {
                    return 0;
                    alert('Terjadi Kesalahan Pada Server');
                },

            });
        }

        // function getPeruntukanBphtb() {
        //     let url_submit = "{{ route('bphtb.ketetapan.peruntukan') }}";
        //     let tahunSearch = $("#tahunPerolehanBphtb").val();

        //     $.ajax({
        //         type: 'GET',
        //         url: url_submit,
        //         data: {
        //             "tahun": tahunSearch,
        //         },
        //         cache: false,
        //         contentType: false,
        //         processData: true,
        //         success: function(data) {

        //             chartPeruntukanBphtb(data);
        //         },

        //         error: function(data) {
        //             return 0;
        //             alert('Terjadi Kesalahan Pada Server');
        //         },

        //     });
        // }

        // function getValidasiBphtb() {
        //     let url_submit = "{{ route('bphtb.ketetapan.validasi') }}";
        //     let tahunSearch = $("#tahunValidasiBphtb").val();

        //     $.ajax({
        //         type: 'GET',
        //         url: url_submit,
        //         data: {
        //             "tahun": tahunSearch,
        //         },
        //         cache: false,
        //         contentType: false,
        //         processData: true,
        //         success: function(data) {

        //             chartValidasiBphtb(data);
        //         },

        //         error: function(data) {
        //             return 0;
        //             alert('Terjadi Kesalahan Pada Server');
        //         },

        //     });
        // }

        function getNihilBayarBphtb() {
            let url_submit = "{{ route('bphtb.ketetapan.nihil.bayar') }}";
            let tahunSearch = $("#tahunValidasiBphtb").val();

            $.ajax({
                type: 'GET',
                url: url_submit,
                data: {
                    "tahun": tahunSearch,
                },
                cache: false,
                contentType: false,
                processData: true,
                success: function(data) {
                    chartNihilBayarBphtb(data);
                },

                error: function(data) {
                    return 0;
                    alert('Terjadi Kesalahan Pada Server');
                },

            });
        }

        function chartPerolehanBphtb(data) {
            // console.log("data",data)
            // area spaline chart
            let kategori = $("#kategoriPerolehanBphtb").val()
            let arrJumlahTransaksi = []
            let arrSeries = []
            $.each(data, function(index, value) {
                // arrNominal.push(value.nominal_ketetapan)
                arrJumlahTransaksi.push(value.jumlah_transaksi)
                let arrMonthNominal = value.arrMonthNominal
                let arrMonthTransaksi = value.arrMonthTransaksi

                // console.log("arrnominal",arrMonthNominal)
                // console.log("arrjumlahtransaksi",arrMonthTransaksi)

                let arrData = []
                if (kategori == "jumlah_transaksi") {
                    $.each(arrMonthTransaksi, function(key, item) {
                        arrData.push(item)
                    })
                } else {
                    $.each(arrMonthNominal, function(key, item) {
                        arrData.push(item)
                    })
                }

                arrSeries.push({
                    name: index,
                    data: arrData
                })
            })
            var monthMapping = {
                'Jan': 1,
                'Feb': 2,
                'Mar': 3,
                'Apr': 4,
                'May': 5,
                'Jun': 6,
                'Jul': 7,
                'Aug': 8,
                'Sep': 9,
                'Oct': 10,
                'Nov': 11,
                'Des': 12
            };

            var options1 = {
                series: arrSeries,
                chart: {
                    width: "100%",
                    height: 350,
                    type: 'bar',
                    toolbar: {
                        show: true
                    },
                    events: {
                        dataPointSelection: function(event, chartContext, config) {
                            let tahunTerbaru = new Date().getFullYear();
                            var tahun = $("#tahunPerolehanBphtb").val() || tahunTerbaru;
                            var status = chartContext.w.config.series[config.seriesIndex].name;
                            var bulanText = chartContext.w.globals.labels[config.dataPointIndex];
                            var bulan = monthMapping[bulanText];
                            window.location.href = '/bphtb/ketetapan/detail_ketetapan_perolehan/' + tahun + '/' +
                                status + '/' + bulan;
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },

                xaxis: {
                    type: 'text',
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des'],
                },
                yaxis: {
                    opposite: false,
                    min: 0,
                },
                tooltip: {
                    x: {
                        // format: 'dd/MM/yy HH:mm'
                    },
                    y: {


                        formatter: function(val) {
                            if (kategori == "jumlah_transaksi") {

                                return val + " Transaksi"
                            } else {
                                return formatRupiah(val) + " Rupiah"
                            }
                        }
                    }
                },
                colors: ['#f44336', '#ff9800', '#4caf50', '#00bcd4', '#3f51b5', '#9c27b0']
            }

            $("#chart-perolehanBpthb").empty()
            var chart1 = new ApexCharts(
                document.querySelector("#chart-perolehanBpthb"),
                options1
            );
            chart1.render();
            chart1.updateOptions(options1);

        }

        // function chartPeruntukanBphtb(data) {
        //     let kategori = $("#kategoriPerolehanBphtb").val()
        //     let arrJumlahTransaksi = []
        //     let arrSeries = []

        //     $.each(data, function(index, value) {
        //         // arrNominal.push(value.nominal_ketetapan)
        //         arrJumlahTransaksi.push(value.jumlah_transaksi)
        //         let arrMonthNominal = value.arrMonthNominal
        //         let arrMonthTransaksi = value.arrMonthTransaksi
        //         let arrData = []
        //         if (kategori == "jumlah_transaksi") {
        //             $.each(arrMonthTransaksi, function(key, item) {
        //                 arrData.push(item)
        //             })
        //         } else {
        //             $.each(arrMonthNominal, function(key, item) {
        //                 arrData.push(item)
        //             })
        //         }

        //         arrSeries.push({
        //             name: index,
        //             data: arrData
        //         })
        //     })
        //     var monthMapping = {
        //         'Jan': 1,
        //         'Feb': 2,
        //         'Mar': 3,
        //         'Apr': 4,
        //         'May': 5,
        //         'Jun': 6,
        //         'Jul': 7,
        //         'Aug': 8,
        //         'Sep': 9,
        //         'Oct': 10,
        //         'Nov': 11,
        //         'Des': 12
        //     };

        //     var options1 = {

        //         series: arrSeries,
        //         chart: {
        //             height: 350,
        //             type: 'bar',
        //             toolbar: {
        //                 show: true
        //             },
        //             events: {
        //                 dataPointSelection: function(event, chartContext, config) {
        //                     let tahunTerbaru = new Date().getFullYear();
        //                     var tahun = $("#tahunPerolehanBphtb").val() || tahunTerbaru;
        //                     var status = chartContext.w.config.series[config.seriesIndex].name;
        //                     var bulanText = chartContext.w.globals.labels[config.dataPointIndex];
        //                     var bulan = monthMapping[bulanText];
        //                     window.location.href =
        //                         '/bphtb/ketetapan/detail_ketetapan_peruntukan/' + tahun + '/' +
        //                         status + '/' + bulan;
        //                 }
        //             }
        //         },

        //         dataLabels: {
        //             enabled: false
        //         },
        //         stroke: {
        //             curve: 'smooth'
        //         },

        //         xaxis: {
        //             type: 'text',
        //             categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des'],
        //         },
        //         yaxis: {
        //             opposite: false,
        //             min: 0,
        //         },
        //         tooltip: {
        //             x: {
        //                 // format: 'dd/MM/yy HH:mm'
        //             },
        //             y: {


        //                 formatter: function(val) {
        //                     if (kategori == "jumlah_transaksi") {

        //                         return val + " Transaksi"
        //                     } else {
        //                         return formatRupiah(val) + " Rupiah"
        //                     }
        //                 }
        //             }
        //         },
        //         colors: ['#f44336', '#ff9800', '#4caf50', '#00bcd4', '#3f51b5', '#9c27b0']
        //     }

        //     $("#chart-peruntukanBpthb").empty()
        //     var chart1 = new ApexCharts(
        //         document.querySelector("#chart-peruntukanBpthb"),
        //         options1
        //     );
        //     chart1.render();
        //     chart1.updateOptions(options1);
        // }

        // function chartValidasiBphtb(data) {
        //     console.log("datavalidasi", data)
        //     let arrJumlahKetetapan = []
        //     let arrSudahDivalidasi = []
        //     let arrBelumDivalidasi = []

        //     $.each(data, function(index, value) {
        //         // arrNominal.push(value.nominal_ketetapan)
        //         let arrKetetapan = value.jumlah_ketetapan
        //         let arrDivalidasi = value.sudah_divalidasi
        //         let arrBelumValidasi = value.belum_divalidasi
        //         // console.log("ketetapan",arrKetetapan)
        //         // console.log("divalidasi",arrDivalidasi)
        //         // console.log("belum validasi",arrBelumValidasi)

        //         $.each(arrKetetapan, function(index, valueKetetapan) {
        //             arrJumlahKetetapan.push(valueKetetapan)
        //         })
        //         $.each(arrDivalidasi, function(index, valueSudahValidasi) {
        //             arrSudahDivalidasi.push(valueSudahValidasi)
        //         })
        //         $.each(arrBelumValidasi, function(index, valueBelumValidasi) {
        //             arrBelumDivalidasi.push(valueBelumValidasi)
        //         })
        //     })

        //     var monthMapping = {
        //         'Jan': 1,
        //         'Feb': 2,
        //         'Mar': 3,
        //         'Apr': 4,
        //         'May': 5,
        //         'Jun': 6,
        //         'Jul': 7,
        //         'Aug': 8,
        //         'Sep': 9,
        //         'Oct': 10,
        //         'Nov': 11,
        //         'Des': 12
        //     };
        //     var options1 = {

        //         chart: {
        //             height: 350,
        //             type: 'bar',
        //             toolbar: {
        //                 show: true
        //             },
        //             events: {
        //                 dataPointSelection: function(event, chartContext, config) {
        //                     let tahunTerbaru = new Date().getFullYear();
        //                     var tahun = $("#tahunValidasiBphtb").val() || tahunTerbaru;
        //                     var status = chartContext.w.config.series[config.seriesIndex].name;
        //                     var bulanText = chartContext.w.globals.labels[config.dataPointIndex];
        //                     var bulan = monthMapping[bulanText];
        //                     window.location.href =
        //                         '/bphtb/ketetapan/detail_ketetapan_validasi/' + tahun + '/' +
        //                         status + '/' + bulan;
        //                 }
        //             }
        //         },
        //         dataLabels: {
        //             enabled: false
        //         },
        //         stroke: {
        //             curve: 'smooth'
        //         },
        //         series: [{
        //             name: "Jumlah Ketetapan",
        //             data: arrJumlahKetetapan
        //         }, {
        //             name: "Sudah Divalidasi",
        //             data: arrSudahDivalidasi
        //         }, {
        //             name: "Belum Divalidasi",
        //             data: arrBelumDivalidasi
        //         }],

        //         xaxis: {
        //             type: 'text',
        //             categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des'],
        //         },
        //         yaxis: {
        //             opposite: false,
        //             min: 0,
        //         },
        //         tooltip: {
        //             x: {
        //                 // format: 'dd/MM/yy HH:mm'
        //             },
        //             y: {


        //                 formatter: function(val) {
        //                     return val + " Ketetapan"
        //                 }
        //             }
        //         },
        //         colors: ['#f44336', '#ff9800', '#4caf50', '#00bcd4', '#3f51b5', '#9c27b0']
        //     }

        //     $("#chart-validasiBPHTB").empty()
        //     var chart1 = new ApexCharts(
        //         document.querySelector("#chart-validasiBPHTB"),
        //         options1
        //     );
        //     chart1.render();
        //     chart1.updateOptions(options1);
        // }

        function chartNihilBayarBphtb(data) {
            // console.log("datavalidasi",data)
            let arrResultJumlahTransaksi = []
            let arrResultSudahBayar = []
            let arrResultBelumBayar = []
            let arrResultNihil = []

            $.each(data, function(index, value) {
                // arrNominal.push(value.nominal_ketetapan)
                let arrJumlahTransaksi = value.jumlah_transaksi
                let arrSudahBayar = value.sudah_bayar
                let arrBelumBayar = value.belum_bayar
                let arrNihil = value.jumlah_transaksi_nihil

                $.each(arrJumlahTransaksi, function(index, valueTransaksi) {
                    arrResultJumlahTransaksi.push(valueTransaksi)
                })
                $.each(arrSudahBayar, function(index, valueSudahBayar) {
                    arrResultSudahBayar.push(valueSudahBayar)
                })
                $.each(arrBelumBayar, function(index, valueBelumBayar) {
                    arrResultBelumBayar.push(valueBelumBayar)
                })
                $.each(arrNihil, function(index, valueNihil) {
                    arrResultNihil.push(valueNihil)
                })

            })

            // console.log("ketetapan",arrJumlahKetetapan)
            //     console.log("divalidasi",arrSudahDivalidasi)
            //     console.log("belum validasi",arrBelumDivalidasi)
            // console.log("arrlabel",arrLabel)

            var monthMapping = {
                'Jan': 1,
                'Feb': 2,
                'Mar': 3,
                'Apr': 4,
                'May': 5,
                'Jun': 6,
                'Jul': 7,
                'Aug': 8,
                'Sep': 9,
                'Oct': 10,
                'Nov': 11,
                'Des': 12
            };
            var options1 = {

                chart: {
                    height: 350,
                    type: 'bar',
                    toolbar: {
                        show: true
                    },
                    events: {
                        dataPointSelection: function(event, chartContext, config) {
                            let tahunTerbaru = new Date().getFullYear();
                            var tahun = $("#tahunValidasiBphtb").val() || tahunTerbaru;
                            var status = chartContext.w.config.series[config.seriesIndex].name;
                            var bulanText = chartContext.w.globals.labels[config.dataPointIndex];
                            var bulan = monthMapping[bulanText];
                            window.location.href =
                                '/bphtb/ketetapan/detail_ketetapan_nihil_bayar/' + tahun +
                                '/' +
                                status + '/' + bulan;
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                series: [{
                        name: "Jumlah Transaksi",
                        data: arrResultJumlahTransaksi
                    },
                    {
                        name: "Sudah Bayar",
                        data: arrResultSudahBayar
                    },
                    {
                        name: "Belum Bayar",
                        data: arrResultBelumBayar
                    },
                    {
                        name: "Transaksi Nihil",
                        data: arrResultNihil
                    }
                ],

                xaxis: {
                    type: 'text',
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des'],
                },
                yaxis: {
                    opposite: false,
                    min: 0,
                },
                tooltip: {
                    x: {
                        // format: 'dd/MM/yy HH:mm'
                    },
                    y: {


                        formatter: function(val) {
                            return val + " Transaksi"
                        }
                    }
                },
                colors: ['#f44336', '#ff9800', '#4caf50', '#00bcd4', '#3f51b5', '#9c27b0']
            }

            $("#chart-nihilBayarBPHTB").empty()
            var chart1 = new ApexCharts(
                document.querySelector("#chart-nihilBayarBPHTB"),
                options1
            );
            chart1.render();
            chart1.updateOptions(options1);
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

        function datatablePelaporanPpat() {
            var tahun = $('#tahunPelaporanPpat').val();
            var bulan = $('#bulanPelaporanPpat').val();

            if ($.fn.dataTable.isDataTable('#datatablePelaporanPpat')) {
                $('#datatablePelaporanPpat').DataTable().destroy();
            }

            let tabel = $('#datatablePelaporanPpat').DataTable({
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Excel',
                    "filename": 'Rekap Pelaporan PPAT',
                    "action": newexportaction,
                }, ],
                ajax: {
                    url: "{{ route('bphtb.ketetapan.datatable.pelaporan.ppat') }}",
                    type: 'GET',
                    data: {
                        "tahun": tahun,
                        "bulan": bulan,
                    }
                },
                columns: [{
                        data: 'no',
                        name: 'no',
                    },
                    {
                        data: 'nama_ppat',
                        name: 'nama_ppat'
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
                        data: 'jumlah_laporan',
                        name: 'jumlah_laporan'
                    },
                ]
            });
        }

        function filterPerolehanBphtb() {
            getPerolehanBphtb()
            // getPeruntukanBphtb()
        }

        function filterValidasiBphtb() {
            // getValidasiBphtb()
            getNihilBayarBphtb()
        }

        function filterTablePelaporanPpat() {
            datatablePelaporanPpat()
        }


        function select2basic() {
            $(".select2basic").select2()
        }

        $(document).ready(function() {

            getPerolehanBphtb();
            // getPeruntukanBphtb();
            // getValidasiBphtb();
            getNihilBayarBphtb();
            // get_penerimaan_akumulasi();
            // get_penerimaan_harian();
            // table_penerimaan_notaris();

            // chart_penerimaan_harian();
            // chart_akumulasi_penerimaan();
            // chart_penerimaan_perbulan();

            datatablePelaporanPpat();

            select2basic()


        })
    </script>
@endsection
