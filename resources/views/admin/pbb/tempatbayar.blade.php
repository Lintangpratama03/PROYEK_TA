@extends('admin.layout.main')
@section('title', 'Tempat Bayar PBB - Smart Dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Tempat Bayar PBB</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">PBB</a></li>
                    <li class="breadcrumb-item active">Tempat Bayar</li>
                </ol>
            </div>
            <div class="col-sm-6">
            </div>

        </div>
    </div>
</div>
<!-- Container-fluid starts-->
<div class="container-fluid chart-widget">
    <!-- <div class="row">
        <div class="col-xl-4">
            <div class="col-xl-12">
                <div class="card o-hidden">
                    <div class="card-header pb-0">
                        <h6>Proporsi Tempat Bayar</h6>
                    </div>
                    <div class="bar-chart-widget">
                        <div class="bottom-content card-body">
                            <div class="row">
                                <div class="col-12">
                                <div id="chart-pie-proporsi"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card o-hidden">
                <div class="card-header pb-0">
                    <h6>Penerimaan Bedasarkan Tempat Bayar</h6>
                </div>
                <div class="card-body">
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
    </div> -->

    <div class="row">

        <div class="col-xl-12">
            <div class="card o-hidden">
                <div class="card-header pb-0">
                    <h6>Jam Pembayaran</h6>
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

        <!-- <div class="col-xl-12">
            <div class="card o-hidden">
                <div class="card-header pb-0">
                    <h6>Perbandingan Perbulan</h6>
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
        </div> -->

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

    function get_penerimaan_perbulan(){
        let url_submit = "{{ route('pbb.penerimaan.penerimaan_perbulan') }}";
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

                var penerimaan_2018 = data.p2018;
                var penerimaan_2019 = data.p2019;
                var penerimaan_2020 = data.p2020;
                var penerimaan_2021 = data.p2021;
                var penerimaan_2022 = data.p2022;
                var penerimaan_2023 = data.p2023;
               
                chart_penerimaan_perbulan(penerimaan_2018,penerimaan_2019,penerimaan_2020,penerimaan_2021,penerimaan_2022,penerimaan_2023);
            },

            error: function(data){
                return 0;
                alert('Terjadi Kesalahan Pada Server');
            },
            
        });
    }

    function chart_penerimaan_perbulan(penerimaan_2018,penerimaan_2019,penerimaan_2020,penerimaan_2021,penerimaan_2022,penerimaan_2023){
        var options = {
        series: [
            {
                name: '2020',
                data: [1,1,4,5,2,4,11,3,1,1,3,4,14,8,13,15,30,12,4,3,3,5,11,1,8]
            }, {
                name: '2021',
                data: [2,3,3,5,2,6,16,6,6,4,3,4,24,15,13,25,20,11,12,8,3,4,1,2,3]
            }, {
                name: '2022',
                data: [3,1,1,5,2,4,11,3,1,1,3,4,14,8,13,15,35,19,8,3,3,5,8,2,8]
            }, {
                name: '2023',
                data: [1,2,1,5,2,4,11,3,1,1,3,4,14,8,13,15,40,21,19,8,5,15,2,3,4]
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
            categories: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18','19', '20', '21', '22', '23'],
        },
        yaxis: {
            title: {
            text: 'Jumlah Transaksi'
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
                return  val + " transaksi"
            }
            }
        }
        };

      var chartlinechart4 = new ApexCharts(document.querySelector("#chart-line"), options);
      chartlinechart4.render();

    }

   
   
	$(document).ready(function(){

        // get_penerimaan_perbulan();
        chart_penerimaan_perbulan();
 
	})
</script>
@endsection