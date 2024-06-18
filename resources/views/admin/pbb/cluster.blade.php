@extends('admin.layout.main')
@section('title', 'Cluster Tunggakan PBB - Smart Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Cluster Tunggakan PBB</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">PBB</a></li>
                        <li class="breadcrumb-item active">Cluster Tunggakan</li>
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
                    <div class="card-header pb-0">
                        <h6>Cluster Tunggakan (Wilayah)</h6>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="row">
                                    <div class="col-xl-3 mb-2 col-md-4 col-sm-6">
                                        <select name="kecamatan" id="kecamatan"
                                            class="form-control btn-square js-example-basic-single col-sm-12 "
                                            style="border: 1px solid #808080;border-radius:5px;">
                                            <option value="" class = "d-flex align-items-center">Pilih Kecamatan
                                            </option>
                                            @foreach (getKecamatan() as $item)
                                                <option value="{{ $item->nama_kecamatan }}">{{ $item->nama_kecamatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-xl-3 mb-2 col-md-4 col-sm-6">
                                        <select name="kelurahan"
                                            id="kelurahan"class="form-control btn-square js-example-basic-single col-sm-12"
                                            style="border: 1px solid #808080;">
                                            <option value="" class = "d-flex align-items-center">Pilih Kelurahan
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-xl-2 mb-2 col-md-4 col-sm-6">
                                        <a class="btn btn-primary btn-square" type="button"
                                            onclick="filterWilayah()">Terapkan<span class="caret"></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-tunggakan-wilayah">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="text-align: center; vertical-align: middle;">Kecamatan
                                        </th>
                                        <th rowspan="2" style="text-align: center; vertical-align: middle;">Kelurahan
                                        </th>
                                        <th colspan="3" style="text-align: center;background-color:#9eb0d1">
                                            Jumlah NOP</th>
                                        <th colspan="3" style="text-align: center;background-color:#f3e8ae">
                                            Jumlah Tunggakan</th>
                                        <th colspan="3" style="text-align: center;background-color:#cecece">
                                            Nominal Tunggakan</th>

                                    </tr>
                                    <tr>
                                        <th style="text-align: center;">Ringan</th>
                                        <th style="text-align: center;">Sedang</th>
                                        <th style="text-align: center;">Berat</th>
                                        <th style="text-align: center;">Ringan</th>
                                        <th style="text-align: center;">Sedang</th>
                                        <th style="text-align: center;">Berat</th>
                                        <th style="text-align: center;">Ringan</th>
                                        <th style="text-align: center;">Sedang</th>
                                        <th style="text-align: center;">Berat</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-xl-8 col-md-6 col-lg-3">

                <div class="col-xl-12">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6>Grafik Tunggakan</h6>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="row">
                                        <div class="col-xl-3 mb-2 col-md-4 col-sm-6">
                                            <select name="nop_tunggakan" id="nop_tunggakan"
                                                class="form-control btn-square js-example-basic-single col-sm-12 "
                                                style="border: 1px solid #808080;border-radius:5px;">
                                                <option value="total_jumlah_nop" class = "d-flex align-items-center">
                                                    Pilih Level Nop
                                                </option>
                                                <option value="nop_ringan" class = "d-flex align-items-center">
                                                    Level Ringan NOP
                                                </option>
                                                <option value="nop_sedang" class = "d-flex align-items-center">
                                                    Level Sedang NOP
                                                </option>
                                                <option value="nop_berat" class = "d-flex align-items-center">
                                                    Level Berat NOP
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-xl-3 mb-2 col-md-4 col-sm-6">
                                            <select name="tunggakan"
                                                id="tunggakan"class="form-control btn-square js-example-basic-single col-sm-12"
                                                style="border: 1px solid #808080;">
                                                <option value="total_jumlah_tunggakan" class = "d-flex align-items-center">
                                                    Pilih Level Tunggakan
                                                </option>
                                                <option value="total_jumlah_tunggakan_ringan"
                                                    class = "d-flex align-items-center">
                                                    Level Ringan Tunggakan
                                                </option>
                                                <option value="total_jumlah_tunggakan_sedang"
                                                    class = "d-flex align-items-center">
                                                    Level Sedang Tunggakan
                                                </option>
                                                <option value="total_jumlah_tunggakan_berat"
                                                    class = "d-flex align-items-center">
                                                    Level Berat Tunggakan
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-xl-2 mb-2 col-md-4 col-sm-6">
                                            <a class="btn btn-primary btn-square" type="button"
                                                onclick="filterGrafik()">Terapkan<span class="caret"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card-body">
                                    <canvas id="clusterChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div>
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6>Total Tunggakan (Wilayah)</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm dtTable table-tunggakan-wilayah-cluster">
                                    <thead>
                                        <tr>
                                            <th>Kecamatan</th>
                                            <th>Kelurahan</th>
                                            <th>Jumlah NOP</th>
                                            <th>Jumlah Tunggakan</th>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Plugins JS Ends-->
    <script>
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
        var curKelurahan = $('#kelurahan').val();
        var curKecamatan = $('#kecamatan').val();
        var curNop = $('#nop_tunggakan').val();
        var curTunggakan = $('#tunggakan').val();

        function filterKel() {
            $('#kecamatan').on('change', function() {
                var kecamatan = this.value;
                $("#kelurahan").html('');
                $.ajax({
                    url: '{{ route('pbb.cluster.get_wilayah') }}',
                    type: "GET",
                    data: {
                        "wilayah": 'kecamatan',
                        "data": kecamatan
                    },
                    dataType: 'json',
                    success: function(result) {
                        // console.log(result);
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

        function filterWilayah() {
            var kecamatan = $('#kecamatan').val();
            var kelurahan = $('#kelurahan').val();
            if (kecamatan !== null || kelurahan !== null) {
                $(".table-tunggakan-wilayah").DataTable().destroy();
                $(".table-tunggakan-wilayah-cluster").DataTable().destroy();
                $(".table-cluster-hasil").DataTable().destroy();
                table_tunggakan_wilayah(kecamatan, kelurahan);
                table_tunggakan_wilayah_cluster(kecamatan, kelurahan);
                // table_tunggakan_cluster_hasil(kecamatan, kelurahan);
                // loadClusterChart(kecamatan, kelurahan);
                // loadClusterChart1(kecamatan, kelurahan);
                loadClusterChartNop(kecamatan, kelurahan);
            }
        }

        function filterGrafik() {
            var nop_tunggakan = $('#nop_tunggakan').val();
            var tunggakan = $('#tunggakan').val();
            if (nop_tunggakan !== null || tunggakan !== null) {
                loadClusterChartNop(nop_tunggakan, tunggakan);
            }
        }

        function table_tunggakan_wilayah(kecamatan = curKecamatan, kelurahan = curKelurahan) {

            var table = $(".table-tunggakan-wilayah").DataTable({
                dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'>>" +
                    // dengan Button
                    "<'row'<'col-sm-12'tr>>" + // Add table
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Export to Excel',
                    "filename": 'Tunggakan (Wilayah) PBB ',
                    "action": newexportaction
                }, ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: '{{ route('pbb.cluster.datatable_tunggakan_wilayah') }}',
                    type: 'GET',
                    data: {
                        "kecamatan": kecamatan,
                        "kelurahan": kelurahan
                    }
                },
                columns: [{
                        data: 'kecamatan',
                        name: 'kecamatan'
                    },
                    {
                        data: 'kelurahan',
                        name: 'kelurahan'
                    },
                    {
                        data: 'nop_ringan',
                        className: 'dt-center',
                        name: 'nop_ringan'
                    },
                    {
                        data: 'nop_sedang',
                        className: 'dt-center',
                        name: 'nop_sedang'
                    },
                    {
                        data: 'nop_berat',
                        className: 'dt-center',
                        name: 'nop_berat'
                    },
                    {
                        data: 'total_jumlah_tunggakan_ringan',
                        className: 'dt-center',
                        name: 'total_jumlah_tunggakan_ringan'
                    },
                    {
                        data: 'total_jumlah_tunggakan_sedang',
                        className: 'dt-center',
                        name: 'total_jumlah_tunggakan_sedang'
                    },
                    {
                        data: 'total_jumlah_tunggakan_berat',
                        className: 'dt-center',
                        name: 'total_jumlah_tunggakan_berat'
                    },
                    {
                        data: 'nominal_ringan',
                        name: 'nominal_ringan'
                    },
                    {
                        data: 'nominal_sedang',
                        name: 'nominal_sedang'
                    },
                    {
                        data: 'nominal_berat',
                        name: 'nominal_berat'
                    },
                ],

                order: [
                    [0, 'desc']
                ],
                lengthMenu: [5, 10, 15, 25],
                pageLength: 5
            });
        }

        function table_tunggakan_wilayah_cluster(kecamatan = curKecamatan, kelurahan = curKelurahan) {

            var table = $(".table-tunggakan-wilayah-cluster").DataTable({
                dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'>>" +
                    // dengan Button
                    "<'row'<'col-sm-12'tr>>" + // Add table
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Export to Excel',
                    "filename": 'Tunggakan (Wilayah) PBB ',
                    "action": newexportaction
                }, ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: '{{ route('pbb.cluster.datatable_tunggakan_wilayah_cluster') }}',
                    type: 'GET',
                    data: {
                        "kecamatan": kecamatan,
                        "kelurahan": kelurahan
                    }
                },
                columns: [{
                        data: 'kecamatan',
                        name: 'kecamatan'
                    },
                    {
                        data: 'kelurahan',
                        name: 'kelurahan'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'jumlah_tunggakan',
                        name: 'jumlah_tunggakan'
                    },
                ],

                order: [
                    [0, 'desc']
                ],
                lengthMenu: [5, 10, 15, 25],
                pageLength: 5
            });
        }


        $(document).ready(function() {
            filterKel();
            table_tunggakan_wilayah();
            table_tunggakan_wilayah_cluster();
            // loadClusterChart();
            // loadClusterChart1();
            loadClusterChartNop();
        });

        // function loadClusterChart1(kecamatan = curKecamatan, kelurahan = curKelurahan) {
        //     $.ajax({
        //         url: '{{ route('pbb.cluster.data_tunggakan_wilayah_cluster_1') }}',
        //         type: 'GET',
        //         data: {
        //             "kecamatan": kecamatan,
        //             "kelurahan": kelurahan
        //         },
        //         dataType: 'json',
        //         success: function(data) {
        //             const clusterLabels = ['Hijau', 'Kuning', 'Orange', 'Merah'];
        //             const clusters = [
        //                 [],
        //                 [],
        //                 [],
        //                 []
        //             ];
        //             data.forEach((item) => {
        //                 let clusterIndex = clusterLabels.indexOf(item.cluster);
        //                 clusters[clusterIndex].push({
        //                     x: item.total_jumlah_tunggakan,
        //                     y: item.total_nominal_tunggakan,
        //                     z: item.kelurahan,
        //                     backgroundColor: item.backgroundColor,
        //                     borderColor: item.borderColor
        //                 });
        //             });

        //             const ctx = document.getElementById('clusterChart1').getContext('2d');
        //             const clusterChart = new Chart(ctx, {
        //                 type: 'scatter',
        //                 data: {
        //                     datasets: clusterLabels.map((label, index) => ({
        //                         label: label,
        //                         data: clusters[index],
        //                         backgroundColor: clusters[index].map(d => d
        //                             .backgroundColor),
        //                         borderColor: clusters[index].map(d => d.borderColor),
        //                         borderWidth: 1
        //                     }))
        //                 },
        //                 options: {
        //                     scales: {
        //                         x: {
        //                             title: {
        //                                 display: true,
        //                                 text: 'Jumlah Tunggakan (NOP)'
        //                             },
        //                             ticks: {
        //                                 beginAtZero: true
        //                             }
        //                         },
        //                         y: {
        //                             title: {
        //                                 display: true,
        //                                 text: 'Total Nominal Tunggakan'
        //                             },
        //                             ticks: {
        //                                 beginAtZero: true
        //                             }
        //                         }
        //                     },
        //                     plugins: {
        //                         legend: {
        //                             display: true
        //                         },
        //                         tooltip: {
        //                             callbacks: {
        //                                 label: function(context) {
        //                                     let label = context.raw.z || '';
        //                                     if (label) {
        //                                         label += ': ';
        //                                     }
        //                                     label += '(' + context.raw.x + ', ' + new Intl
        //                                         .NumberFormat('id-ID', {
        //                                             style: 'currency',
        //                                             currency: 'IDR'
        //                                         }).format(context.raw.y) +
        //                                         ')';
        //                                     return label;
        //                                 }
        //                             }
        //                         }
        //                     }
        //                 }
        //             });
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error loading chart data:', error);
        //         }
        //     });
        // }

        // function loadClusterChart(kecamatan = curKecamatan, kelurahan = curKelurahan) {
        //     $.ajax({
        //         url: '{{ route('pbb.cluster.data_tunggakan_wilayah_cluster') }}',
        //         type: 'GET',
        //         data: {
        //             "kecamatan": kecamatan,
        //             "kelurahan": kelurahan
        //         },
        //         dataType: 'json',
        //         success: function(data) {
        //             const clusterColors = [{
        //                     label: 'Cluster 0',
        //                     backgroundColor: 'rgba(0, 128, 0, 0.6)',
        //                     borderColor: 'rgba(0, 128, 0, 1)'
        //                 },
        //                 {
        //                     label: 'Cluster 1',
        //                     backgroundColor: 'rgba(255, 255, 0, 0.6)',
        //                     borderColor: 'rgba(255, 255, 0, 1)'
        //                 },
        //                 {
        //                     label: 'Cluster 2',
        //                     backgroundColor: 'rgba(255, 0, 0, 0.6)',
        //                     borderColor: 'rgba(255, 0, 0, 1)'
        //                 }
        //             ];

        //             const datasets = clusterColors.map(color => ({
        //                 label: color.label,
        //                 data: [],
        //                 backgroundColor: color.backgroundColor,
        //                 borderColor: color.borderColor,
        //                 borderWidth: 1
        //             }));

        //             data.forEach(item => {
        //                 const clusterIndex = item.cluster;
        //                 datasets[clusterIndex].data.push({
        //                     // x: item.total_jumlah_tunggakan,
        //                     // y: item.total_nominal_tunggakan,
        //                     // z: item.kelurahan
        //                 });
        //             });

        //             const ctx = document.getElementById('clusterChart').getContext('2d');
        //             const clusterChart = new Chart(ctx, {
        //                 type: 'scatter',
        //                 data: {
        //                     datasets: datasets
        //                 },
        //                 options: {
        //                     scales: {
        //                         // x: {
        //                         //     title: {
        //                         //         display: true,
        //                         //         text: 'Jumlah Tunggakan'
        //                         //     },
        //                         //     ticks: {
        //                         //         beginAtZero: true
        //                         //     }
        //                         // },
        //                         // y: {
        //                         //     title: {
        //                         //         display: true,
        //                         //         text: 'Total Nominal Tunggakan'
        //                         //     },
        //                         //     ticks: {
        //                         //         beginAtZero: true
        //                         //     }
        //                         // }
        //                     },
        //                     plugins: {
        //                         legend: {
        //                             display: true
        //                         },
        //                         // tooltip: {
        //                         //     callbacks: {
        //                         //         label: function(context) {
        //                         //             let label = context.raw.z || '';
        //                         //             if (label) {
        //                         //                 label += ': ';
        //                         //             }
        //                         //             label += '(' + context.raw.x + ', ' + new Intl
        //                         //                 .NumberFormat('id-ID', {
        //                         //                     style: 'currency',
        //                         //                     currency: 'IDR'
        //                         //                 }).format(context.raw.y) + ')';
        //                         //             return label;
        //                         //         }
        //                         //     }
        //                         // }
        //                     }
        //                 }
        //             });
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error loading chart data:', error);
        //         }
        //     });
        // }

        function loadClusterChartNop(nop_tunggakan = curNop, tunggakan = curTunggakan) {
            for (const instance of Object.values(Chart.instances)) {
                instance.destroy();
            }
            $.ajax({
                url: '{{ route('pbb.cluster.data_tunggakan_wilayah_cluster_nop') }}',
                type: 'GET',
                data: {
                    "nop_tunggakan": nop_tunggakan,
                    "tunggakan": tunggakan
                },
                dataType: 'json',
                success: function(data) {
                    const clusterColors = [{
                            label: 'Cluster 0',
                            backgroundColor: 'rgba(0, 128, 0, 0.6)',
                            borderColor: 'rgba(0, 128, 0, 1)'
                        },
                        {
                            label: 'Cluster 1',
                            backgroundColor: 'rgba(255, 255, 0, 0.6)',
                            borderColor: 'rgba(255, 255, 0, 1)'
                        },
                        {
                            label: 'Cluster 2',
                            backgroundColor: 'rgba(255, 0, 0, 0.6)',
                            borderColor: 'rgba(255, 0, 0, 1)'
                        }
                    ];

                    const datasets = clusterColors.map(color => ({
                        label: color.label,
                        data: [],
                        backgroundColor: color.backgroundColor,
                        borderColor: color.borderColor,
                        borderWidth: 1
                    }));

                    data.forEach(item => {
                        const clusterIndex = item.cluster;
                        datasets[clusterIndex].data.push({
                            x: item.total_jumlah_tunggakan,
                            y: item.total_jumlah_nop,
                            a: item.nop_ringan,
                            b: item.nop_sedang,
                            c: item.nop_berat,
                            z: item.kelurahan
                        });
                    });

                    const ctx = document.getElementById('clusterChart').getContext('2d');
                    const clusterChart = new Chart(ctx, {
                        type: 'scatter',
                        data: {
                            datasets: datasets
                        },
                        options: {
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Jumlah Tunggakan'
                                    },
                                    ticks: {
                                        beginAtZero: true
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: 'Total Nop (Tunggakan)'
                                    },
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.raw.z || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            label += '(jumlah tunggakan :' + context.raw.x +
                                                ',total nop : ' + context.raw.y +
                                                ')';
                                            return label;
                                        }
                                    }
                                }
                            }
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error loading chart data:', error);
                }
            });
        }
    </script>
    {{-- <script>
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
        var curKelurahan = $('#kelurahan').val();
        var curKecamatan = $('#kecamatan').val();

        function filterKel() {
            $('#kecamatan').on('change', function() {
                var kecamatan = this.value;
                $("#kelurahan").html('');
                $.ajax({
                    url: '{{ route('pbb.cluster.get_wilayah') }}',
                    type: "GET",
                    data: {
                        "wilayah": 'kecamatan',
                        "data": kecamatan
                    },
                    dataType: 'json',
                    success: function(result) {
                        // console.log(result);
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

        function filterWilayah() {
            var kecamatan = $('#kecamatan').val();
            var kelurahan = $('#kelurahan').val();
            if (kecamatan !== null || kelurahan !== null) {
                $(".table-tunggakan-wilayah").DataTable().destroy();
                $(".table-tunggakan-wilayah-cluster").DataTable().destroy();
                $(".table-cluster-hasil").DataTable().destroy();
                table_tunggakan_wilayah(kecamatan, kelurahan);
                table_tunggakan_wilayah_cluster(kecamatan, kelurahan);
                table_tunggakan_cluster_hasil(kecamatan, kelurahan);
                loadClusterChart(kecamatan, kelurahan);
            }
        }

        function table_tunggakan_wilayah(kecamatan = curKecamatan, kelurahan = curKelurahan) {

            var table = $(".table-tunggakan-wilayah").DataTable({
                dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'>>" +
                    // dengan Button
                    "<'row'<'col-sm-12'tr>>" + // Add table
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Export to Excel',
                    "filename": 'Tunggakan (Wilayah) PBB ',
                    "action": newexportaction
                }, ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: '{{ route('pbb.cluster.datatable_tunggakan_wilayah') }}',
                    type: 'GET',
                    data: {
                        "kecamatan": kecamatan,
                        "kelurahan": kelurahan
                    }
                },
                columns: [{
                        data: 'kecamatan',
                        name: 'kecamatan'
                    },
                    {
                        data: 'kelurahan',
                        name: 'kelurahan'
                    },
                    {
                        data: 'nop_ringan',
                        className: 'dt-center',
                        name: 'nop_ringan'
                    },
                    {
                        data: 'nop_sedang',
                        className: 'dt-center',
                        name: 'nop_sedang'
                    },
                    {
                        data: 'nop_berat',
                        className: 'dt-center',
                        name: 'nop_berat'
                    },
                    {
                        data: 'total_jumlah_tunggakan_ringan',
                        className: 'dt-center',
                        name: 'total_jumlah_tunggakan_ringan'
                    },
                    {
                        data: 'total_jumlah_tunggakan_sedang',
                        className: 'dt-center',
                        name: 'total_jumlah_tunggakan_sedang'
                    },
                    {
                        data: 'total_jumlah_tunggakan_berat',
                        className: 'dt-center',
                        name: 'total_jumlah_tunggakan_berat'
                    },
                    {
                        data: 'nominal_ringan',
                        name: 'nominal_ringan'
                    },
                    {
                        data: 'nominal_sedang',
                        name: 'nominal_sedang'
                    },
                    {
                        data: 'nominal_berat',
                        name: 'nominal_berat'
                    },
                ],

                order: [
                    [0, 'desc']
                ],
                lengthMenu: [5, 10, 15, 25],
                pageLength: 5
            });
        }

        function table_tunggakan_wilayah_cluster(kecamatan = curKecamatan, kelurahan = curKelurahan) {

            var table = $(".table-tunggakan-wilayah-cluster").DataTable({
                dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'>>" +
                    // dengan Button
                    "<'row'<'col-sm-12'tr>>" + // Add table
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Export to Excel',
                    "filename": 'Tunggakan (Wilayah) PBB ',
                    "action": newexportaction
                }, ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: '{{ route('pbb.cluster.datatable_tunggakan_wilayah_cluster') }}',
                    type: 'GET',
                    data: {
                        "kecamatan": kecamatan,
                        "kelurahan": kelurahan
                    }
                },
                columns: [{
                        data: 'kecamatan',
                        name: 'kecamatan'
                    },
                    {
                        data: 'kelurahan',
                        name: 'kelurahan'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'nominal',
                        name: 'nominal'
                    },
                ],

                order: [
                    [0, 'desc']
                ],
                lengthMenu: [5, 10, 15, 25],
                pageLength: 5
            });
        }

        function table_tunggakan_cluster_hasil(kecamatan = curKecamatan, kelurahan = curKelurahan) {

            var table = $(".table-cluster-hasil").DataTable({
                dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'>>" +
                    // dengan Button
                    "<'row'<'col-sm-12'tr>>" + // Add table
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Export to Excel',
                    "filename": 'Tunggakan (Wilayah) PBB ',
                    "action": newexportaction
                }, ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: '{{ route('pbb.cluster.datatable_tunggakan_cluster_hasil') }}',
                    type: 'GET',
                    data: {
                        "kecamatan": kecamatan,
                        "kelurahan": kelurahan
                    }
                },
                columns: [{
                        data: 'cluster',
                        name: 'cluster'
                    },
                    {
                        data: 'kecamatan',
                        name: 'kecamatan'
                    },
                    {
                        data: 'kelurahan',
                        name: 'kelurahan'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'jumlah_tunggakan',
                        name: 'jumlah_tunggakan'
                    },
                    {
                        data: 'nominal',
                        name: 'nominal'
                    },
                ],

                order: [
                    [0, 'desc']
                ],
                lengthMenu: [5, 10, 15, 25],
                pageLength: 5
            });
        }


        $(document).ready(function() {
            filterKel();
            table_tunggakan_wilayah();
            table_tunggakan_wilayah_cluster();
            table_tunggakan_cluster_hasil();
            loadClusterChart();
        });

        function loadClusterChart(kecamatan = curKecamatan, kelurahan = curKelurahan) {
            $.ajax({
                url: '{{ route('pbb.cluster.data_tunggakan_wilayah_cluster') }}',
                type: 'GET',
                data: {
                    "kecamatan": kecamatan,
                    "kelurahan": kelurahan
                },
                dataType: 'json',
                success: function(data) {
                    const clusterLabels = ['Hijau', 'Kuning', 'Orange', 'Merah'];
                    const clusters = [
                        [],
                        [],
                        [],
                        []
                    ];
                    data.forEach((item) => {
                        let clusterIndex = clusterLabels.indexOf(item.cluster);
                        clusters[clusterIndex].push({
                            x: item.total_jumlah_tunggakan,
                            y: item.total_nominal_tunggakan,
                            z: item.kelurahan,
                            backgroundColor: item.backgroundColor,
                            borderColor: item.borderColor
                        });
                    });

                    const ctx = document.getElementById('clusterChart').getContext('2d');
                    const clusterChart = new Chart(ctx, {
                        type: 'scatter',
                        data: {
                            datasets: clusterLabels.map((label, index) => ({
                                label: label,
                                data: clusters[index],
                                backgroundColor: clusters[index].map(d => d
                                    .backgroundColor),
                                borderColor: clusters[index].map(d => d.borderColor),
                                borderWidth: 1
                            }))
                        },
                        options: {
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Jumlah Tunggakan (NOP)'
                                    },
                                    ticks: {
                                        beginAtZero: true
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: 'Total Nominal Tunggakan'
                                    },
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.raw.z || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            label += '(' + context.raw.x + ', ' + new Intl
                                                .NumberFormat('id-ID', {
                                                    style: 'currency',
                                                    currency: 'IDR'
                                                }).format(context.raw.y) +
                                                ')';
                                            return label;
                                        }
                                    }
                                }
                            }
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error loading chart data:', error);
                }
            });
        }
    </script> --}}
@endsection
