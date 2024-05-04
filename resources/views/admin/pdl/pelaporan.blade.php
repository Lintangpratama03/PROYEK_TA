@extends('admin.layout.main')
@section('title', 'Pelaporan PDL - Smart Dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Pelaporan PDL</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">PDL</a></li>
                    <li class="breadcrumb-item active">Pelaporan</li>
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

        <div class="col-xl-6">
            <div class="card o-hidden">
                <div class="card-header pb-0">
                    <h6>Pelaporan Pajak</h6>
                    <br>
                    <div class="row">
                        <div class="col-xl-5">
                            <div class="mb-2">
                            <select id="jenis_pajak" name="jenis_pajak" class="col-sm-12">
                                <optgroup label="Jenis Pajak">
                                <!-- <option value="">Pilih Jenis Pajak</option> -->
                                @foreach (getJenisPajakPDLPelaporan() as $item)
                                    <option value="{{$item->kode_rekening}}">{{$item->nama_rekening}}</option>
                                @endforeach
                                </optgroup>
                            </select>
                            </div>
                        </div>
                        <div class="col-xl-5">
                            <div class="mb-2">
                            <!-- <label class="col-form-label">Pilih Tahun</label> -->
                            <select id="tahun" name="tahun" class="col-sm-12">
                                <optgroup label="Tahun">
                                @foreach(array_combine(range(date("Y"), 2018), range(date("Y"), 2018)) as $year) 
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                                </optgroup>
                            </select>
                            </div>
                        </div>
                        <div class="col-xl-2">
                                <a class='btn btn-primary btn-sm' onclick='filterGrafikPelaporan()'><i class='fa fa-search'></i></a>
                        </div>
                    </div>
                    
                </div>
                <div class="bar-chart-widget">
                    <div class="bottom-content card-body">
                        <div class="row">
                            <div class="col-12">
                                
                                    <div id="chart-lapor-pdl"></div>
                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card o-hidden">
                <div class="card-header pb-0">
                    <h6>Objek Pajak Belum Lapor</h6>
                    <div class="row">
                        <div class="col-xl-4">
                            <div class="mb-2">
                                <select id="jenis_pajak_belum_lapor" name="jenis_pajak_belum_lapor" class="col-sm-12">
                                    <optgroup label="Jenis Pajak">
                                    <!-- <option value="">Pilih Jenis Pajak</option> -->
                                    @foreach (getJenisPajakSimpadamav2() as $item )
                                        <option value="{{$item['id']}}">{{$item['nama_pajak']}}</option>
                                    @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="mb-2">
                                <select id="tahun_belum_lapor" name="tahun_belum_lapor" class="col-sm-12">
                                    <optgroup label="tahun_belum_lapor">
                                    @foreach(array_combine(range(date("Y"), 2018), range(date("Y"), 2018)) as $year) 
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-4">
                                <a class='btn btn-primary btn-sm' onclick='filterBelumLapor()'><i class='fa fa-search'></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-op-belumlapor">
                            <thead>
                                <tr>
                                    <th>Jenis Pajak</th>
                                    <th>Objek Pajak</th>
                                    <th>Belum Lapor</th>
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

    function get_pelaporan_pdl(jenis_pajak=null,tahun=null){
        let url_submit = "{{ route('pdl.pelaporan.pelaporan_pdl') }}";
        // let mingguSearch = $("#from-minggu-penerimaan-search").val();
        // let bulanSearch = $("#from-bulan-penerimaan-search").val();
        if(jenis_pajak==null && tahun==null){
            var jenis_pajak = $('#jenis_pajak').val();
            var tahun = $('#tahun').val();
        }
        // console.log(jenis_pajak);
        $.ajax({
            type:'GET',
            url: url_submit,
            data: {
                "jenis_pajak": jenis_pajak,
                "tahun": tahun
            },
            cache:false,
            contentType: false,
            processData: true,
            success: function(data){
                // console.log(data);

                var sudahbayar = data.sudahbayar;
                var sudahlapor = data.sudahlapor;
                var belumlapor = data.belumlapor;
                var bulan = data.bulan;
               
                chart_pelaporan_pdl(sudahbayar,sudahlapor,belumlapor,bulan);
            },

            error: function(data){
                return 0;
                alert('Terjadi Kesalahan Pada Server');
            },
            
        });
    }

    function chart_pelaporan_pdl(sudahbayar,sudahlapor,belumlapor,bulan){
        let jenis_pajak_d = $('#jenis_pajak').val();
        let tahun_d = $('#tahun').val();
        var options = {

            series: [
                {
                name: 'Sudah Bayar',
                data: sudahbayar
                }, {
                name: 'Lapor & Belum Bayar',
                data: sudahlapor
                }, {
                name: 'Belum Lapor',
                data: belumlapor
                },
            ],

            chart: {
                type: 'bar',
                height: 450,
                stacked: true,
                events: {
                    dataPointSelection: function (event, chartContext, config) {
                        // 'config' contains information about the clicked bar
                        console.log(config.seriesIndex); // Series index
                        // console.log(); // Data point index
                        // console.log(config.w.globals.series[config.seriesIndex][config.dataPointIndex]); // Value of the clicked bar
                        
                        window.location.href = '{{ url("pdl/pelaporan/detail_pelaporan") }}'+'/'+jenis_pajak_d+'/'+tahun_d+'/'+(config.dataPointIndex)+'/'+config.seriesIndex;
                    }
                }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    dataLabels: {
                    total: {
                        enabled: true,
                        offsetX: 0,
                        style: {
                        fontSize: '13px',
                        fontWeight: 900
                        }
                    }
                    }
                },
            },
            stroke: {
                width: 1,
                colors: ['#fff']
            },
            title: {
                text: ''
            },
            xaxis: {
                categories: bulan,
                labels: {
                    formatter: function (val) {
                    return val
                    }
                }
            },
            yaxis: {
                title: {
                    text: undefined
                },
            },
            tooltip: {
            y: {
                    formatter: function (val) {
                    return val
                    }
                }
            },
            fill: {
                opacity: 1,
                colors: ['#4caf50', '#ff9800', '#f44336'],
            },
                legend: {
                position: 'bottom',
                horizontalAlign: 'left',
                offsetX: 40
            },
            colors: ['#4caf50', '#ff9800', '#f44336'],
        };

        var chart = new ApexCharts(document.querySelector("#chart-lapor-pdl"), options);
        chart.render();
        chart.updateOptions(options);
    }

    function filterBelumLapor(){
        var jenis_pajak_belum_lapor = $('#jenis_pajak_belum_lapor').val();
        var tahun_belum_lapor = $('#tahun_belum_lapor').val();
        $(".table-op-belumlapor").DataTable().destroy();
        table_op_belumlapor(jenis_pajak_belum_lapor,tahun_belum_lapor)

    }
    var cur_jenis_pajak_belum_lapor = $('#jenis_pajak_belum_lapor').val();
    var cur_tahun_belum_lapor = $('#tahun_belum_lapor').val();

    function table_op_belumlapor(jenis_pajak = cur_jenis_pajak_belum_lapor,tahun_belum_lapor = cur_tahun_belum_lapor){
        let table = $(".table-op-belumlapor").DataTable({
            "dom": 'frtip',
			processing: true,
	        serverSide: true,
	        responsive: true,
	        searchDelay: 2000,
            ajax: {
                url: '{{ route('pdl.pelaporan.datatable_op_belumlapor') }}',
                type: 'GET',
                data: {
                  "jenis_pajak":jenis_pajak,
                  "tahun":tahun_belum_lapor
                }
            },
	        columns: [
                {data: 'nama_rekening', name: 'nama_rekening'},
	            {data: 'nama_objek_pajak', name: 'nama_objek_pajak'},
	            {data: 'tidak_lapor', name: 'tidak_lapor'}
	        ],
            order: [[2, 'desc']],
		});
    }

    function filterGrafikPelaporan(){
        var jenis_pajak = $('#jenis_pajak').val();
        var tahun = $('#tahun').val();
        // console.log(tahun)
        // if(tahun.length > 0){
            get_pelaporan_pdl(jenis_pajak, tahun);
        // }else{
        //     swal("MOHON PILIH TAHUN !", {
        //         icon: "warning",
        //     });
        // }
    }
   
   
	$(document).ready(function(){
        $("#jenis_pajak_belum_lapor").select2(); 
        $("#jenis_pajak").select2();
        $("#tahun").select2({
            placeholder: "Pilih Tahun"
        });
        $("#tahun_belum_lapor").select2({
            placeholder: "Pilih Tahun Belum Lapor"
        });
        var jenis_pajak_belum_lapor = $('#jenis_pajak_belum_lapor').val();
        var tahun_belum_lapor = $('#tahun_belum_lapor').val();
        get_pelaporan_pdl();
        table_op_belumlapor(jenis_pajak_belum_lapor,tahun_belum_lapor);
        // chart_pelaporan_pdl();
 
	})
</script>
@endsection