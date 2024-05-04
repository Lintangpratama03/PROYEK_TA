@extends('admin.layout.main')
@section('title', 'Penerimaan Retribusi - Smart Dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Penerimaan Retribusi</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Retribusi</a></li>
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
        <div class="col-xl-12">
            <div class="mb-2">
            <label class="col-form-label">Pilih Jenis Retribusi</label>
            <select class="js-example-basic-single col-sm-12">
                <optgroup label="Jenis Retribusi">
                <option value="AL">Retribus Jasa Umum</option>
                <option value="WY">Retribusi Jasa Usaha</option>
                <option value="WY">Retribusi Perizinan Tertentu</option>
                </optgroup>
            </select>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="col-xl-12">
                <div class="card o-hidden">
                    <div class="card-header pb-0">
                        <h6>Penerimaan Retribusi per Bulan</h6>
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
                        <h6>Penerimaan Retribusi Akumulasi</h6>
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
                    <h6>Penerimaan Harian</h6>
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

    function formatRupiah(angka){
        var options = {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2,
        };
        var formattedNumber = angka.toLocaleString('ID', options);
        return formattedNumber;
    }

    function get_penerimaan_akumulasi(){
        let url_submit = "{{ route('retribusi.penerimaan.penerimaan_akumulasi') }}";
        // let mingguSearch = $("#from-minggu-penerimaan-search").val();
        // let bulanSearch = $("#from-bulan-penerimaan-search").val();
        $.ajax({
            type:'GET',
            url: url_submit,
            // data: {
            //     "jenis_pajak": jenis_pajak,
            //     "mingguSearch": mingguSearch,
            //     "bulanSearch": bulanSearch
            // },
            cache:false,
            contentType: false,
            processData: true,
            success: function(data){
                console.log(data);

                var penerimaan_2021 = data.p2021;
                var penerimaan_2022 = data.p2022;
                var penerimaan_2023 = data.p2023;
               
                chart_akumulasi_penerimaan(penerimaan_2021,penerimaan_2022,penerimaan_2023);
            },

            error: function(data){
                return 0;
                alert('Terjadi Kesalahan Pada Server');
            },
            
        });
    }

    function chart_akumulasi_penerimaan(penerimaan_2021,penerimaan_2022,penerimaan_2023){
        // area spaline chart
        var options1 = {
            chart: {
                height: 350,
                type: 'area',
                toolbar:{
                show: true
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            series: [
                {
                    name: '2021',
                    data: penerimaan_2021
                }, {
                    name: '2022',
                    data: penerimaan_2022
                }, {
                    name: '2023',
                    data: penerimaan_2023
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
                    formatter: function (val) {
                        return  formatRupiah(val) + " Rupiah"
                    }
                }
            },
            colors:['#f44336', '#ff9800', '#4caf50', '#00bcd4', '#3f51b5', '#9c27b0']
        }

        var chart1 = new ApexCharts(
            document.querySelector("#chart-area"),
            options1
        );

        chart1.render();

    }

    function get_penerimaan_perbulan(){
        let url_submit = "{{ route('retribusi.penerimaan.penerimaan_perbulan') }}";
        // let mingguSearch = $("#from-minggu-penerimaan-search").val();
        // let bulanSearch = $("#from-bulan-penerimaan-search").val();
        $.ajax({
            type:'GET',
            url: url_submit,
            // data: {
            //     "jenis_pajak": jenis_pajak,
            //     "mingguSearch": mingguSearch,
            //     "bulanSearch": bulanSearch
            // },
            cache:false,
            contentType: false,
            processData: true,
            success: function(data){
                console.log(data);

                // var penerimaan_2018 = data.p2018;
                // var penerimaan_2019 = data.p2019;
                // var penerimaan_2020 = data.p2020;
                var penerimaan_2021 = data.p2021;
                var penerimaan_2022 = data.p2022;
                var penerimaan_2023 = data.p2023;
               
                chart_penerimaan_perbulan(penerimaan_2021,penerimaan_2022,penerimaan_2023);
            },

            error: function(data){
                return 0;
                alert('Terjadi Kesalahan Pada Server');
            },
            
        });
    }

    function chart_penerimaan_perbulan(penerimaan_2021,penerimaan_2022,penerimaan_2023){
        var options = {
        series: [
            {
                name: '2021',
                data: penerimaan_2021
            }, {
                name: '2022',
                data: penerimaan_2022
            }, {
                name: '2023',
                data: penerimaan_2023
            } 
        ],
            chart: {
            type: 'bar',
            height: 360
        },
        plotOptions: {
            bar: {
            horizontal: false,
            columnWidth: '55%',
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
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des'],
        },
        yaxis: {
            title: {
            text: 'Rp (Rupiah)'
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
            formatter: function (val) {
                return  formatRupiah(val) + " Rupiah"
            }
            }
        }
        };

      var chartlinechart4 = new ApexCharts(document.querySelector("#chart-line"), options);
      chartlinechart4.render();

    }

    function get_penerimaan_harian(){
        let url_submit = "{{ route('retribusi.penerimaan.penerimaan_harian') }}";
        // let mingguSearch = $("#from-minggu-penerimaan-search").val();
        // let bulanSearch = $("#from-bulan-penerimaan-search").val();
        $.ajax({
            type:'GET',
            url: url_submit,
            // data: {
            //     "jenis_pajak": jenis_pajak,
            //     "mingguSearch": mingguSearch,
            //     "bulanSearch": bulanSearch
            // },
            cache:false,
            contentType: false,
            processData: true,
            success: function(data){
                console.log(data);

                var tanggal = data.tanggal;
                var penerimaan = data.penerimaan;
               
                chart_penerimaan_harian(tanggal,penerimaan);
            },

            error: function(data){
                return 0;
                alert('Terjadi Kesalahan Pada Server');
            },
            
        });
    }

    function chart_penerimaan_harian(tanggal,penerimaan){
         // Turnover chart
        var optionsturnoverchart = {
            chart: {
                height: 320,
                type: 'area',
                zoom: {
                    enabled: false
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
                min:0
            },
            legend: {
                horizontalAlign: 'left'
            },
            tooltip: {
            y: {
                formatter: function (val) {
                    return  formatRupiah(val) + " Rupiah"
                }
            }
        }
        }
        var chartturnoverchart = new ApexCharts(document.querySelector("#chart-penerimaan-harian"), optionsturnoverchart);
        chartturnoverchart.render();

    }

 
    var table;

    function table_penerimaan_tahun(){
        table = $(".dtTable").DataTable({
            "dom": 'rtip',
			processing: true,
	        serverSide: true,
	        responsive: true,
	        searchDelay: 2000,
	        ajax: '{{ route('pbb.penerimaan.datatable_penerimaan_tahun') }}',
	        columns: [
	            {data: 'tahun', name: 'tahun'},
	            {data: 'nominal', name: 'nominal'},
	            {data: 'nop', name: 'nop'}
	        ],
            order: [[0, 'desc']],
		});
    }

	$(document).ready(function(){

        get_penerimaan_perbulan();
        get_penerimaan_akumulasi();
        get_penerimaan_harian();
        table_penerimaan_tahun();

        // chart_penerimaan_harian();
        // chart_akumulasi_penerimaan();
        // chart_penerimaan_perbulan();

        
	})
</script>
@endsection