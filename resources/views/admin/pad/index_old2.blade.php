@extends('admin.layout.main')
@section('title', 'PAD - Smart Dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>PAD</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">PAD</a></li>
                    <li class="breadcrumb-item active">index</li>
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

        <div class="col-sm-12 col-xl-12 box-col-12"> 
            <div class="row">
                <div class="col-xl-8">
                    <h6>Target dan Realisasi Pajak Daerah</h6>
                </div>
                <div class="col-xl-4">
                    <div class="mb-3 draggable">
                        <div class="input-group">
                            <select name="role_code" id="tahun" class="form-control btn-square">
                                <option value="">-- Filter Tahun SPPT --</option>
                                @foreach(array_combine(range(date("Y"), 2018), range(date("Y"), 2018)) as $year) 
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-btn btn btn-square p-0">
                                <a type="button" class="btn btn-primary btn-square" type="button" onclick="filterTahun()">Terapkan<span class="caret"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="judul"></h5>
                        </div>
                        <div class="modal-body">
                            <ul class="text-center">
                                <p class="text-center" id="judul"></p>
                                <li> Target <span><a type="button" id="target"></a></span></li>
                                <li> Realisasi <span><a type="button" id="realisasi"></a></span></li>
                                <li> Selisih <span><a type="button" id="selisih"></a></span></li>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="close">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            
            {{--<div class="row">
                <!-- <div class="col-xl-2"> -->

                        <div class="card o-hidden">
                            <div class="card-body">
                                <div class="text-center" id="donutchart_hotel"></div>
                                <a type="button" class="btn btn-xs btn-primary btn-air-primary" data-id="hotel" onclick="showDetail(this)">
                                    <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                    Detail
                                </a>
                            </div>
                        </div>

                        <div class="card o-hidden">
                            <div class="card-body">
                                <div class="text-center" id="donutchart_resto"></div>
                                    <a type="button" class="btn btn-xs btn-primary btn-air-primary" data-id="resto" onclick="showDetail(this)">
                                        <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card o-hidden">
                            <div class="card-body">
                                <div id="donutchart_hiburan"></div>
                                    <a type="button" class="btn btn-xs btn-primary btn-air-primary" data-id="hiburan" onclick="showDetail(this)">
                                        <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card o-hidden">
                            <div class="card-body">
                                <div id="donutchart_reklame"></div>
                                    <a type="button" class="btn btn-xs btn-primary btn-air-primary" data-id="reklame" onclick="showDetail(this)">
                                        <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card o-hidden">
                            <div class="card-body">
                                <div id="donutchart_pat"></div>
                                    <a type="button" class="btn btn-xs btn-primary btn-air-primary" data-id="pat" onclick="showDetail(this)">
                                        <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card o-hidden">
                            <div class="card-body">
                                <div id="donutchart_parkir"></div>
                                    <a type="button" class="btn btn-xs btn-primary btn-air-primary" data-id="parkir" onclick="showDetail(this)">
                                        <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card o-hidden">
                            <div class="card-body">
                                <div id="donutchart_ppj"></div>
                                    <a type="button" class="btn btn-xs btn-primary btn-air-primary" data-id="ppj" onclick="showDetail(this)">
                                        <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card o-hidden">
                            <div class="card-body">
                                <div id="donutchart_pbb"></div>
                                    <a type="button" class="btn btn-xs btn-primary btn-air-primary" data-id="pbb" onclick="showDetail(this)">
                                        <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card o-hidden">
                            <div class="card-body">
                                <div id="donutchart_bphtb"></div>
                                    <a type="button" class="btn btn-xs btn-primary btn-air-primary" data-id="bphtb" onclick="showDetail(this)">
                                        <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card o-hidden">
                            <div class="card-body">
                                <div id="donutchart_sbw"></div>
                                    <a type="button" class="btn btn-xs btn-primary btn-air-primary" data-id="sbw" onclick="showDetail(this)">
                                        <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card o-hidden">
                            <div class="card-body">
                                <div id="donutchart_mblb"></div>
                                    <a type="button" class="btn btn-xs btn-primary btn-air-primary" data-id="mblb" onclick="showDetail(this)">
                                        <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>

                <!-- </div> -->
            </div>--}}

        </div>

        <div class="col-xl-12">
            <div class="row">      

                <div class="col-sm-6 col-xl-2 xl-2 col-lg-2 box-col-2">
                    <div class="card social-widget-card">
                        <div class="card-body apex-chart">
                            <div class="text-center" id="donutchart_hotel"></div>
                                <a type="button" class="btn btn-xs btn-primary btn-block" data-id="hotel" onclick="showDetail(this)">
                                    <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>  


                <div class="col-sm-2 col-xl-2 xl-2 col-lg-2 box-col-2">
                    <div class="card">
                        <div class="card-body apex-chart">
                            <div class="chart-container">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="text-center" id="donutchart_resto"></div>
                                            <a type="button" class="btn btn-xs btn-primary btn-air-primary" data-id="resto" onclick="showDetail(this)">
                                                <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-2 col-xl-2 xl-2 col-lg-2 box-col-2">
                    <div class="card">
                        <div class="card-body apex-chart">
                            <div class="chart-container">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="text-center" id="donutchart_hiburan"></div>
                                            <a type="button" class="btn btn-xs btn-primary btn-air-primary" data-id="hiburan" onclick="showDetail(this)">
                                                <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="col-sm-6 col-xl-2 xl-2 col-lg-2 box-col-2">
                    <div class="card social-widget-card">
                        <div class="card-body text-center">
                            
                        </div>
                    </div>
                </div>  

                <div class="col-sm-6 col-xl-2 xl-2 col-lg-2 box-col-2">
                    <div class="card social-widget-card">
                        <div class="card-body text-center">
                            
                        </div>
                    </div>
                </div>  

                <div class="col-sm-6 col-xl-2 xl-2 col-lg-2 box-col-2">
                    <div class="card social-widget-card">
                        <div class="card-body text-center">
                            
                        </div>
                    </div>
                </div>  

                <div class="col-sm-6 col-xl-2 xl-2 col-lg-2 box-col-2">
                    <div class="card social-widget-card">
                        <div class="card-body text-center">
                            
                        </div>
                    </div>
                </div>  

                <div class="col-sm-6 col-xl-2 xl-2 col-lg-2 box-col-2">
                    <div class="card social-widget-card">
                        <div class="card-body text-center">
                            
                        </div>
                    </div>
                </div>  

                <div class="col-sm-6 col-xl-2 xl-2 col-lg-2 box-col-2">
                    <div class="card social-widget-card">
                        <div class="card-body text-center">
                            
                        </div>
                    </div>
                </div>  

                <div class="col-sm-6 col-xl-2 xl-2 col-lg-2 box-col-2">
                    <div class="card social-widget-card">
                        <div class="card-body text-center">
                            
                        </div>
                    </div>
                </div>  

                <div class="col-sm-6 col-xl-2 xl-2 col-lg-2 box-col-2">
                    <div class="card social-widget-card">
                        <div class="card-body text-center">
                            
                        </div>
                    </div>
                </div>  

                <div class="col-sm-6 col-xl-2 xl-2 col-lg-2 box-col-2">
                    <div class="card social-widget-card">
                        <div class="card-body text-center">
                            
                        </div>
                    </div>
                </div>  

                <div class="col-sm-6 col-xl-2 xl-2 col-lg-2 box-col-2">
                    <div class="card social-widget-card">
                        <div class="card-body text-center">
                            
                        </div>
                    </div>
                </div>  

                <div class="col-sm-6 col-xl-2 xl-2 col-lg-2 box-col-2">
                    <div class="card social-widget-card">
                        <div class="card-body text-center">
                            
                        </div>
                    </div>
                </div>  

            </div>
        </div>

        <div class="col-xl-12">
            <div class="row">

                <div class="col-xl-6">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6>Target dan Realisasi Retribusi Daerah</h6>
                        </div>
                        <div class="card-body">

                            <div class="card ecommerce-widget pro-gress">
                                <div class="card-body support-ticket-font">
                                    <div class="row">
                                    <div class="col-5">
                                        <h6>Retribusi Jasa Umum</h6>
                                        <h2 class="total-num counter" id="persen_umum"> </h2>
                                    </div>
                                    <div class="col-7">
                                        <div class="text-md-end">
                                        <ul>
                                            <li>Target<span class="product-stts txt-info ms-2"id="t_umum"></span></li>
                                            <li>Realisasi<span class="product-stts txt-success ms-2" id="r_umum"></span></li>
                                            <li>Selisih<span class="product-stts  ms-2" style="color: grey;" id="s_umum"></span></li>
                                        </ul>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="progress-showcase">
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" id="progres_umum" style="width:80%; background-color:grey;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card ecommerce-widget pro-gress">
                                <div class="card-body support-ticket-font">
                                    <div class="row">
                                    <div class="col-5">
                                        <h6>Retribusi Jasa Usaha</h6>
                                        <h2 class="total-num counter" id="persen_usaha"> </h2>
                                    </div>
                                    <div class="col-7">
                                        <div class="text-md-end">
                                        <ul>
                                            <li>Target<span class="product-stts txt-info ms-2" id="t_usaha"></span></li>
                                            <li>Realisasi<span class="product-stts txt-success ms-2" id="r_usaha"></span></li>
                                            <li>Selisih<span class="product-stts  ms-2" id="s_usaha" style="color: grey;"></span></li>
                                        </ul>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="progress-showcase">
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" id="progres_usaha" style="width:80%; background-color:grey;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card ecommerce-widget pro-gress">
                                <div class="card-body support-ticket-font">
                                    <div class="row">
                                    <div class="col-5">
                                        <h6>Retribusi Perizinan Tertentu</h6>
                                        <h2 class="total-num counter" id="persen_izin"> </h2>
                                    </div>
                                    <div class="col-7">
                                        <div class="text-md-end">
                                        <ul>
                                            <li>Target<span class="product-stts txt-info ms-2" id="t_izin"></span></li>
                                            <li>Realisasi<span class="product-stts txt-success ms-2" id="r_izin"></span></li>
                                            <li>Selisih<span class="product-stts  ms-2" id="s_izin" style="color: grey;"></span></li>
                                        </ul>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="progress-showcase">
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" id="progres_izin" style="width:80%; background-color:grey;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6>Target dan Realisasi PAD</h6>
                        </div>
                        <div class="card-body">

                            <div class="card ecommerce-widget pro-gress">
                                <div class="card-body support-ticket-font">
                                    <div class="row">
                                    <div class="col-5">
                                        <h6>PAD</h6>
                                        <h2 class="total-num counter" id="persen_pad"> </h2>
                                    </div>
                                    <div class="col-7">
                                        <div class="text-md-end">
                                        <ul>
                                            <li>Target<span class="product-stts txt-info ms-2" id="t_pad"> </span></li>
                                            <li>Realisasi<span class="product-stts txt-success ms-2" id="r_pad"> </span></li>
                                            <li>Selisih<span class="product-stts  ms-2" id="s_pad" style="color: grey;"> </span></li>
                                        </ul>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="progress-showcase">
                                        <div class="progress">
                                            <div class="progress-bar " role="progressbar" id="progres_pad" style="width:80%; background-color:grey;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card ecommerce-widget pro-gress">
                                <div class="card-body support-ticket-font">
                                    <div class="row">
                                    <div class="col-5">
                                        <h6>Pajak Daerah</h6>
                                        <h2 class="total-num counter" id="persen_pd"> </h2>
                                    </div>
                                    <div class="col-7">
                                        <div class="text-md-end">
                                        <ul>
                                            <li>Target<span class="product-stts txt-info ms-2" id="t_pd"> </span></li>
                                            <li>Realisasi<span class="product-stts txt-success ms-2" id="r_pd"> </span></li>
                                            <li>Selisih<span class="product-stts  ms-2" id="s_pd" style="color: grey;"> </span></li>
                                        </ul>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="progress-showcase">
                                        <div class="progress">
                                            <div class="progress-bar " role="progressbar" id="progres_pd" style="width:80%; background-color:grey;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card ecommerce-widget pro-gress">
                                <div class="card-body support-ticket-font">
                                    <div class="row">
                                    <div class="col-5">
                                        <h6>Retribusi Daerah</h6>
                                        <h2 class="total-num counter" id="persen_rd"> </h2>
                                    </div>
                                    <div class="col-7">
                                        <div class="text-md-end">
                                        <ul>
                                            <li>Target<span class="product-stts txt-info ms-2" id="t_rd"> </span></li>
                                            <li>Realisasi<span class="product-stts txt-success ms-2" id="r_rd"> </span></li>
                                            <li>Selisih<span class="product-stts  ms-2" id="s_rd" style="color: grey;"> </span></li>
                                        </ul>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="progress-showcase">
                                        <div class="progress">
                                            <div class="progress-bar " role="progressbar" id="progres_rd" style="width:80%; background-color:grey;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card ecommerce-widget pro-gress">
                                <div class="card-body support-ticket-font">
                                    <div class="row">
                                    <div class="col-5">
                                        <h6>Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan</h6>
                                        <h2 class="total-num counter" id="persen_kd"> </h2>
                                    </div>
                                    <div class="col-7">
                                        <div class="text-md-end">
                                        <ul>
                                            <li>Target<span class="product-stts txt-info ms-2" id="t_kd"> </span></li>
                                            <li>Realisasi<span class="product-stts txt-success ms-2" id="r_kd"> </span></li>
                                            <li>Selisih<span class="product-stts  ms-2" id="s_kd" style="color: grey;"> </span></li>
                                        </ul>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="progress-showcase">
                                        <div class="progress">
                                            <div class="progress-bar " role="progressbar" id="progres_kd" style="width:80%; background-color:grey;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card ecommerce-widget pro-gress">
                                <div class="card-body support-ticket-font">
                                    <div class="row">
                                    <div class="col-5">
                                        <h6>Lain-lain PAD yang Sah</h6>
                                        <h2 class="total-num counter" id="persen_ll"> </h2>
                                    </div>
                                    <div class="col-7">
                                        <div class="text-md-end">
                                        <ul>
                                            <li>Target<span class="product-stts txt-info ms-2" id="t_ll"> </span></li>
                                            <li>Realisasi<span class="product-stts txt-success ms-2" id="r_ll"> </span></li>
                                            <li>Selisih<span class="product-stts  ms-2" id="s_ll" style="color: grey;"> </span></li>
                                        </ul>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="progress-showcase">
                                        <div class="progress">
                                            <div class="progress-bar " role="progressbar" id="progres_ll" style="width:80%; background-color:grey;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6> Komposisi Pajak Daerah Berdasarkan Target </h6>
                        </div>
                        <div class="card-body apex-chart">
                            <div id="chart-pie-komposisi-pd"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6> Komposisi PAD Berdasarkan Target </h6>
                        </div>
                        <div class="card-body apex-chart">
                            <div id="chart-pie-komposisi"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6> Trend Target & Realisasi </h6>
                            <div class="mb-3 draggable">
                            <div class="input-group">
                                <select name="role_code" id="rekening" class="form-control btn-square">
                                    <option value="">-- Filter Rekening --</option>
                                    @foreach (getRekening() as $item)
                                        <option value="{{$item->kode_rekening}}">{{$item->nama_rekening}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-btn btn btn-square p-0">
                                    <a type="button" class="btn btn-primary btn-square" type="button" onclick="filterRekening()">Terapkan<span class="caret"></span></a>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="card-body">
                            <div id="chart-line-trend"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6>Target & Realisasi</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm target-realisasi">
                                    <thead>
                                        <tr>
                                            <th>Tahun</th>
                                            <th>Target</th>
                                            <th>Realisasi</th>
                                            <th>Persen</th>
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
</div>
<!-- Container-fluid Ends-->
@endsection

@section('js')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script> -->

<script>
    var t_hotel = 0; var t_resto = 0;var t_parkir = 0; var t_hiburan = 0;var t_reklame = 0;var t_ppj = 0;var t_pat = 0;var t_sbw = 0;var t_mblb = 0;var t_bphtb = 0;var t_pbb = 0;
    var r_hotel = 0; var r_resto = 0;var r_parkir = 0; var r_hiburan = 0;var r_reklame = 0;var r_ppj = 0;var r_pat = 0;var r_sbw = 0;var r_mblb = 0;var r_bphtb = 0;var r_pbb = 0;
    var s_hotel = 0; var s_resto = 0;var s_parkir = 0; var s_hiburan = 0;var s_reklame = 0;var s_ppj = 0;var s_pat = 0;var s_sbw = 0;var s_mblb = 0;var s_bphtb = 0;var s_pbb = 0;
    
    var t_umum = 0; var r_umum=0; var s_umum=0; var p_umum=0;
    var t_usaha = 0; var r_usaha=0; var s_usaha=0; var p_usaha=0;
    var t_izin = 0; var r_izin=0; var s_izin=0; var p_izin=0;

    var t_pd = 0; var t_rd = 0; var t_kd = 0; var t_ll = 0;

    const day = new Date();
    var currentYear = day.getFullYear();
    var currentMonth = day.getMonth() + 1;
    let pencapaianBulan = (100 / 12) * currentMonth;
    let pencapaianBulanLalu = (100 / 12) * (currentMonth - 1); 

    console.log(currentYear);

    function formatRupiah(angka){
        var options = {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2,
        };
        var formattedNumber = angka.toLocaleString('ID', options);
        return formattedNumber;
    }

    function bulatkanAngka(angka){
        var isMin = angka < 0 ? '-' : ''; 
        angka = Math.abs(angka); 

        if(angka >= 1000000000000){
            return isMin + 'Rp. ' + (angka / 1000000000000).toFixed(2) + ' Triliun'; 
        }else if(angka >= 1000000000){
            return isMin + 'Rp. ' + (angka / 1000000000).toFixed(2) + ' Miliar'; 

        }else if(angka >= 1000000){
            return isMin + 'Rp. ' + (angka / 1000000).toFixed(2) + ' Juta'; 
        }else if(angka >= 100000 || angka >= 10000 || angka >= 1000 ){
            return isMin + 'Rp. ' + angka
        }else{
            return 'Rp. 0'
        }
    }

    function showAngka(this_){
        let id = $(this_).data("id");
        
        if(id === "target"){
            $('#target').html(formatRupiah(target));
        }else if(id === "selisih"){
            $('#selisih').html(formatRupiah(selisih));
        }else{
            $('#realisasi').html(formatRupiah(target));
        }
    }

    function showDetail(this_){
        let id = $(this_).data("id");
        let tahun = $('#tahun').val()
        $.ajax({
            type:'GET',
            url: "{{ route('pad.get_detail') }}",
            data: {
                "id" : id,
                "tahun":tahun

            },
            beforeSend: function() {
                    $('#loader').show();
            },
            success: function(data) {
                target = data.target;
                realisasi = data.realisasi;
                selisih = data.selisih;
                judul = data.judul;

               
                $('#judul').text(`DETAIL PAJAK ${judul}`);
                $('#target').html(bulatkanAngka(target));
                $('#realisasi').html(bulatkanAngka(realisasi));
                $('#selisih').html(bulatkanAngka(selisih));
                $('#modal').modal("show");

                $('#target').on('click', function () {
                    $('#target').html(formatRupiah(target));
                })
                
                $('#selisih').on('click', function () {
                    $('#selisih').html(formatRupiah(selisih));
                })

                $('#realisasi').on('click', function () {
                    $('#realisasi').html(formatRupiah(realisasi));
                })
            },
            complete: function() {
                $('#loader').hide();
            }
        });
    }

    function filterTahun(){
        let tahun = $('#tahun').val();
        if(tahun !== null){
            get_target_realisasi_pajak(tahun);
            get_target_realisasi_retribusi(tahun);
            get_target_realisasi_pad(tahun);
            get_komposisi_pad(tahun);
            get_komposisi_pajak(tahun);


        }else{
            get_target_realisasi_pajak();
            get_target_realisasi_retribusi();
            get_target_realisasi_pad();
            get_komposisi_pad();
            get_komposisi_pajak();

        }
    }

    function filterRekening(){
        let rekening = $('#rekening').val();
        if(rekening !== null){
            get_trend_target_realisasi(rekening);
            $(".target-realisasi").DataTable().destroy();
            datatable_target_realisasi(rekening);
        }else{
            get_trend_target_realisasi();
            datatable_target_realisasi();
        }
    }

    function change_chart_color(tahun, persen){
        if(tahun == currentYear){
            if(pencapaianBulanLalu >= persen){
                return "#BE3144";
            }else if(pencapaianBulan >= persen){
                return "#F1EB90"; 
            }else{
                return "#557C55";
            }
        }else{
            if(persen < 100){
                return "#BE3144";
            }else{
                return "#557C55";
            }
        }
    }

    function chart(data, tahun){
        console.log(data );
                var  prsn_hotel = data.prsn_hotel;
                var  prsn_resto = data.prsn_resto;
                var  prsn_parkir = data.prsn_parkir; 
                var  prsn_hiburan = data.prsn_hiburan;
                var  prsn_reklame = data.prsn_reklame;
                var  prsn_ppj = data.prsn_ppj;
                var  prsn_pat = data.prsn_pat;
                var  prsn_sbw = data.prsn_sbw;
                var  prsn_mblb = data.prsn_mblb;
                var  prsn_bphtb = data.prsn_bphtb;
                var  prsn_pbb = data.prsn_pbb;

                chart_pajak_daerah_hotel(tahun, prsn_hotel);
                chart_pajak_daerah_resto(tahun, prsn_resto);
                chart_pajak_daerah_hiburan(tahun, prsn_hiburan);
                chart_pajak_daerah_parkir(tahun, prsn_parkir);
                chart_pajak_daerah_pat(tahun, prsn_pat);
                chart_pajak_daerah_ppj(tahun, prsn_ppj);
                chart_pajak_daerah_reklame(tahun, prsn_reklame);
                chart_pajak_daerah_mblb(tahun, prsn_mblb);
                chart_pajak_daerah_sbw(tahun, prsn_sbw);
                chart_pajak_daerah_pbb(tahun, prsn_pbb);
                chart_pajak_daerah_bphtb(tahun, prsn_bphtb);

                $("#t_resto").append(formatRupiah(t_resto)); $("#r_resto").append(formatRupiah(r_resto));  $("#s_resto").append(formatRupiah(s_resto)); 
                $("#t_hiburan").append(formatRupiah(t_hiburan)); $("#r_hiburan").append(formatRupiah(r_hiburan));  $("#s_hiburan").append(formatRupiah(s_hiburan)); 
                $("#t_parkir").append(formatRupiah(t_parkir)); $("#r_parkir").append(formatRupiah(r_parkir));  $("#s_parkir").append(formatRupiah(s_parkir)); 
                $("#t_pat").append(formatRupiah(t_pat)); $("#r_pat").append(formatRupiah(r_pat));  $("#s_pat").append(formatRupiah(s_pat)); 
                $("#t_ppj").append(formatRupiah(t_ppj)); $("#r_ppj").append(formatRupiah(r_ppj));  $("#s_ppj").append(formatRupiah(s_ppj)); 
                $("#t_sbw").append(formatRupiah(t_sbw)); $("#r_sbw").append(formatRupiah(r_sbw));  $("#s_sbw").append(formatRupiah(s_sbw)); 
                $("#t_mblb").append(formatRupiah(t_mblb)); $("#r_mblb").append(formatRupiah(r_mblb));  $("#s_mblb").append(formatRupiah(s_mblb)); 
                $("#t_reklame").append(formatRupiah(t_reklame)); $("#r_reklame").append(formatRupiah(r_reklame));  $("#s_reklame").append(formatRupiah(s_reklame)); 
                $("#t_pbb").append(formatRupiah(t_pbb)); $("#r_pbb").append(formatRupiah(r_pbb));  $("#s_pbb").append(formatRupiah(s_pbb)); 
                $("#t_bphtb").append(formatRupiah(t_bphtb)); $("#r_bphtb").append(formatRupiah(r_bphtb));  $("#s_bphtb").append(formatRupiah(s_bphtb)); 

    }

    function get_target_realisasi_pajak(tahun = currentYear){
        console.log("data tahun", tahun);
        let url_submit = "{{ route('pad.target_realisasi_pajak') }}";
        // let mingguSearch = $("#from-minggu-penerimaan-search").val();
        // let bulanSearch = $("#from-bulan-penerimaan-search").val();
        // console.log(bulanSearch);
        $.ajax({
            type:'GET',
            url: url_submit,
            data: {
                "tahun": tahun,
            },
            cache:false,
            contentType: false,
            processData: true,
            success: function(data){
                chart(data, tahun)
            },

            error: function(data){
                // callback(jumlah_nominal)
                return 0;
                alert('Terjadi Kesalahan Pada Server');
            },
            
        });

    } 

    function get_target_realisasi_retribusi(tahun = currentYear){
        let url_submit = "{{ route('pad.target_realisasi_retribusi') }}";
        // let mingguSearch = $("#from-minggu-penerimaan-search").val();
        // let bulanSearch = $("#from-bulan-penerimaan-search").val();
        $.ajax({
            type:'GET',
            url: url_submit,
            data: {
                "tahun": tahun,
            },
            cache:false,
            contentType: false,
            processData: true,
            success: function(data){
                // console.log(data );
                t_umum = data.t_umum; t_usaha = data.t_usaha; t_izin = data.t_izin;
                r_umum = data.r_umum; r_usaha = data.r_usaha; r_izin = data.r_izin;
                s_umum = data.s_umum; s_usaha = data.s_usaha; s_izin = data.s_izin;
                p_umum = data.p_umum; p_usaha = data.p_usaha; p_izin = data.p_izin; 

                var progres_umum = document.getElementById("progres_umum");
                progres_umum.style.width = p_umum+"%";

                var progres_usaha = document.getElementById("progres_usaha");
                progres_usaha.style.width = p_usaha+"%";
                
                var progres_izin = document.getElementById("progres_izin");
                progres_izin.style.width = p_izin+"%";

                var v_umum = change_chart_color(tahun, p_umum);
                progres_umum.style.backgroundColor = v_umum;

                var v_usaha = change_chart_color(tahun, p_usaha);
                progres_usaha.style.backgroundColor = v_usaha;

                var v_izin = change_chart_color(tahun, p_izin);
                progres_izin.style.backgroundColor = v_izin;


                if(p_umum >= 100){
                    $('#s_umum').css('color', 'green');
                }else{
                    $('#s_umum').css('color', 'red');
                }

                if(p_usaha >= 100){
                    $('#s_usaha').css('color', 'green');
                }else{
                    $('#s_usaha').css('color', 'red');
                }

                if(p_izin >= 100){
                    $('#s_izin').css('color', 'green');
                }else{
                    $('#s_izin').css('color', 'red');
                }
                $("#persen_umum").empty(p_umum + " %"); $("#t_umum").empty(bulatkanAngka(t_umum)); $("#r_umum").empty(bulatkanAngka(r_umum));  $("#s_umum").empty(bulatkanAngka(s_umum)); 
                $("#persen_usaha").empty(p_usaha + " %"); $("#t_usaha").empty(bulatkanAngka(t_usaha)); $("#r_usaha").empty(bulatkanAngka(r_usaha));  $("#s_usaha").empty(bulatkanAngka(s_usaha)); 
                $("#persen_izin").empty(p_izin + " %"); $("#t_izin").empty(bulatkanAngka(t_izin)); $("#r_izin").empty(bulatkanAngka(r_izin));  $("#s_izin").empty(bulatkanAngka(s_izin)); 

                $("#persen_umum").append(p_umum + " %"); $("#t_umum").append(bulatkanAngka(t_umum)); $("#r_umum").append(bulatkanAngka(r_umum));  $("#s_umum").append(bulatkanAngka(s_umum)); 
                $("#persen_usaha").append(p_usaha + " %"); $("#t_usaha").append(bulatkanAngka(t_usaha)); $("#r_usaha").append(bulatkanAngka(r_usaha));  $("#s_usaha").append(bulatkanAngka(s_usaha)); 
                $("#persen_izin").append(p_izin + " %"); $("#t_izin").append(bulatkanAngka(t_izin)); $("#r_izin").append(bulatkanAngka(r_izin));  $("#s_izin").append(bulatkanAngka(s_izin)); 

                

                $('#t_umum').on('click', function () {
                    $('#t_umum').html(formatRupiah(t_umum));
                })
                
                $('#r_umum').on('click', function () {
                    $('#r_umum').html(formatRupiah(r_umum));
                })

                $('#s_umum').on('click', function () {
                    $('#s_umum').html(formatRupiah(s_umum));
                })
                $('#t_usaha').on('click', function () {
                    $('#t_usaha').html(formatRupiah(t_usaha));
                })
                
                $('#r_usaha').on('click', function () {
                    $('#r_usaha').html(formatRupiah(r_usaha));
                })

                $('#s_usaha').on('click', function () {
                    $('#s_usaha').html(formatRupiah(s_usaha));
                })
                $('#t_izin').on('click', function () {
                    $('#t_izin').html(formatRupiah(t_izin));
                })
                
                $('#r_izin').on('click', function () {
                    $('#r_izin').html(formatRupiah(r_izin));
                })

                $('#s_izin').on('click', function () {
                    $('#s_izin').html(formatRupiah(s_izin));
                })

                
            },

            error: function(data){
                // callback(jumlah_nominal)
                return 0;
                alert('Terjadi Kesalahan Pada Server');
            },
            
        });
    }


    function get_target_realisasi_pad(tahun = currentYear){
        let url_submit = "{{ route('pad.target_realisasi_pad') }}";
        // let mingguSearch = $("#from-minggu-penerimaan-search").val();
        // let bulanSearch = $("#from-bulan-penerimaan-search").val();
        $.ajax({
            type:'GET',
            url: url_submit,
            data: {
                "tahun": tahun,
            },
            cache:false,
            contentType: false,
            processData: true,
            success: function(data){
                console.log(data );
                t_pd = data.t_pd; t_rd = data.t_rd; t_kd = data.t_kd; t_ll = data.t_ll; t_pad = data.t_pad;
                r_pd = data.r_pd; r_rd = data.r_rd; r_kd = data.r_kd; r_ll = data.r_ll; r_pad = data.r_pad;
                s_pd = data.s_pd; s_rd = data.s_rd; s_kd = data.s_kd; s_ll = data.s_ll; s_pad = data.s_pad;
                p_pd = data.p_pd; p_rd = data.p_rd; p_kd = data.p_kd; p_ll = data.p_ll; p_pad = data.p_pad;

                var progres_pd = document.getElementById("progres_pd");
                progres_pd.style.width = p_pd+"%";

                var progres_rd = document.getElementById("progres_rd");
                progres_rd.style.width = p_rd+"%";
                
                var progres_kd = document.getElementById("progres_kd");
                progres_kd.style.width = p_kd+"%";

                var progres_ll = document.getElementById("progres_ll");
                progres_ll.style.width = p_ll+"%";

                var progres_pad = document.getElementById("progres_pad");
                progres_pad.style.width = p_pad+"%";

                var v_pd = change_chart_color(tahun, p_pd);
                progres_pd.style.backgroundColor = v_pd;

                var v_rd = change_chart_color(tahun, p_rd);
                progres_rd.style.backgroundColor = v_rd;

                var v_kd = change_chart_color(tahun, p_kd);
                progres_kd.style.backgroundColor = v_kd;

                var v_ll = change_chart_color(tahun, p_ll);
                progres_ll.style.backgroundColor = v_ll;

                var v_pad = change_chart_color(tahun, p_pad);
                progres_pad.style.backgroundColor = v_pad;

                if(p_pd >= 100){
                    $('#s_pd').css('color', 'green');
                }else{
                    $('#s_rd').css('color', 'red');
                }

                if(p_rd >= 100){
                    $('#s_rd').css('color', 'green');
                }else{
                    $('#s_rd').css('color', 'red');
                }

                if(p_kd >= 100){
                    $('#s_kd').css('color', 'green');
                }else{
                    $('#s_kd').css('color', 'red');
                }

                if(p_ll >= 100){
                    $('#s_ll').css('color', 'green');
                }else{
                    $('#s_ll').css('color', 'red');
                }

                if(p_pad >= 100){
                    $('#s_pad').css('color', 'green');
                }else{
                    $('#s_pad').css('color', 'red');
                }

                $("#persen_pd").empty(p_pd + " %"); $("#t_pd").empty(bulatkanAngka(t_pd)); $("#r_pd").empty(bulatkanAngka(r_pd));  $("#s_pd").empty(bulatkanAngka(s_pd)); 
                $("#persen_rd").empty(p_rd + " %"); $("#t_rd").empty(bulatkanAngka(t_rd)); $("#r_rd").empty(bulatkanAngka(r_rd));  $("#s_rd").empty(bulatkanAngka(s_rd)); 
                $("#persen_kd").empty(p_kd + " %"); $("#t_kd").empty(bulatkanAngka(t_kd)); $("#r_kd").empty(bulatkanAngka(r_kd));  $("#s_kd").empty(bulatkanAngka(s_kd));
                $("#persen_ll").empty(p_ll + " %"); $("#t_ll").empty(bulatkanAngka(t_ll)); $("#r_ll").empty(bulatkanAngka(r_ll));  $("#s_ll").empty(bulatkanAngka(s_ll)); 
                $("#persen_pad").empty(p_pad + " %"); $("#t_pad").empty(bulatkanAngka(t_pad)); $("#r_pad").empty(bulatkanAngka(r_pad));  $("#s_pad").empty(bulatkanAngka(s_pad)); 


                $("#persen_pd").append(p_pd + " %"); $("#t_pd").append(bulatkanAngka(t_pd)); $("#r_pd").append(bulatkanAngka(r_pd));  $("#s_pd").append(bulatkanAngka(s_pd)); 
                $("#persen_rd").append(p_rd + " %"); $("#t_rd").append(bulatkanAngka(t_rd)); $("#r_rd").append(bulatkanAngka(r_rd));  $("#s_rd").append(bulatkanAngka(s_rd)); 
                $("#persen_kd").append(p_kd + " %"); $("#t_kd").append(bulatkanAngka(t_kd)); $("#r_kd").append(bulatkanAngka(r_kd));  $("#s_kd").append(bulatkanAngka(s_kd));
                $("#persen_ll").append(p_ll + " %"); $("#t_ll").append(bulatkanAngka(t_ll)); $("#r_ll").append(bulatkanAngka(r_ll));  $("#s_ll").append(bulatkanAngka(s_ll)); 
                $("#persen_pad").append(p_pad + " %"); $("#t_pad").append(bulatkanAngka(t_pad)); $("#r_pad").append(bulatkanAngka(r_pad));  $("#s_pad").append(bulatkanAngka(s_pad)); 


                
                $('#t_pd').on('click', function () {
                    $('#t_pd').html(formatRupiah(t_pd));
                })
                
                $('#r_pd').on('click', function () {
                    $('#r_pd').html(formatRupiah(r_pd));
                })

                $('#s_pd').on('click', function () {
                    $('#s_pd').html(formatRupiah(s_pd));
                })
                $('#t_rd').on('click', function () {
                    $('#t_rd').html(formatRupiah(t_rd));
                })
                
                $('#r_rd').on('click', function () {
                    $('#r_rd').html(formatRupiah(r_rd));
                })

                $('#s_rd').on('click', function () {
                    $('#s_rd').html(formatRupiah(s_rd));
                })
                $('#t_kd').on('click', function () {
                    $('#t_kd').html(formatRupiah(t_kd));
                })
                
                $('#r_kd').on('click', function () {
                    $('#r_kd').html(formatRupiah(r_kd));
                })

                $('#s_kd').on('click', function () {
                    $('#s_kd').html(formatRupiah(s_kd));
                })
                $('#t_ll').on('click', function () {
                    $('#t_ll').html(formatRupiah(t_ll));
                })
                
                $('#r_ll').on('click', function () {
                    $('#r_ll').html(formatRupiah(r_ll));
                })

                $('#s_ll').on('click', function () {
                    $('#s_ll').html(formatRupiah(s_ll));
                })
                $('#t_pad').on('click', function () {
                    $('#t_pad').html(formatRupiah(t_pad));
                })
                
                $('#r_pad').on('click', function () {
                    $('#r_pad').html(formatRupiah(r_pad));
                })

                $('#s_pad').on('click', function () {
                    $('#s_pad').html(formatRupiah(s_pad));
                })
                
                // chart_komposisi_pad(t_pd,t_rd,t_kd,t_ll);
            },

            error: function(data){
                // callback(jumlah_nominal)
                return 0;
                alert('Terjadi Kesalahan Pada Server');
            },
            
        });
    }

    function chart_pajak_daerah_hotel(tahun, persen){
        var options = {
          series: [persen],
          chart: {
          height: 230,
          type: 'radialBar'
        //   offsetY: -10
        },
        plotOptions: {
          radialBar: {
            startAngle: -150,
            endAngle: 150,
            dataLabels: {
              name: {
                fontSize: '12px',
                // offsetY: 100
              },
              value: {
                // offsetY: 60,
                fontSize: '14px',
                formatter: function (val) {
                  return val + "%";
                }
              }
            }
          }
        },
        colors : [
            function(persen){
                return change_chart_color(tahun, persen.value);    
            }
        ],
        stroke: {
          dashArray: 4
        },
        labels: ['Pajak Hotel'],
        };

        var chart = new ApexCharts(document.querySelector("#donutchart_hotel"), options);
        chart.render();
        chart.updateOptions(options);
    }

    function chart_pajak_daerah_resto(tahun, persen){
        var options = {
          series: [persen],
          chart: {
          height: 250,
          type: 'radialBar'
        //   offsetY: -10
        },
        plotOptions: {
          radialBar: {
            startAngle: -150,
            endAngle: 150,
            dataLabels: {
              name: {
                fontSize: '12px',
                // offsetY: 100
              },
              value: {
                // offsetY: 60,
                fontSize: '14px',
                formatter: function (val) {
                  return val + "%";
                }
              }
            }
          }
        },
        colors : [
            function(persen){
                return change_chart_color(tahun, persen.value);    
            }
        ],
        stroke: {
          dashArray: 4
        },
        labels: ['Pajak Restoran'],
        };

        var chart = new ApexCharts(document.querySelector("#donutchart_resto"), options);
        chart.render();
        chart.updateOptions(options);
    }

    function chart_pajak_daerah_hiburan(tahun, persen){
        var options = {
          series: [persen],
          chart: {
          height: 250,
          type: 'radialBar'
        //   offsetY: -10
        },
        plotOptions: {
          radialBar: {
            startAngle: -150,
            endAngle: 150,
            dataLabels: {
              name: {
                fontSize: '12px',
                // offsetY: 100
              },
              value: {
                // offsetY: 60,
                fontSize: '14px',
                formatter: function (val) {
                  return val + "%";
                }
              }
            }
          }
        },
        colors : [
            function(persen){
                return change_chart_color(tahun, persen.value);    
            }
        ],
        stroke: {
          dashArray: 4
        },
        labels: ['Pajak Hiburan'],
        };

        var chart = new ApexCharts(document.querySelector("#donutchart_hiburan"), options);
        chart.render();
        chart.updateOptions(options);
    }

    function chart_pajak_daerah_reklame(tahun, persen){
        var options = {
          series: [persen],
          chart: {
          height: 250,
          type: 'radialBar'
        //   offsetY: -10
        },
        plotOptions: {
          radialBar: {
            startAngle: -150,
            endAngle: 150,
            dataLabels: {
              name: {
                fontSize: '12px',
                // offsetY: 100
              },
              value: {
                // offsetY: 60,
                fontSize: '14px',
                formatter: function (val) {
                  return val + "%";
                }
              }
            }
          }
        },
        colors : [
            function(persen){
                return change_chart_color(tahun, persen.value);    
            }
        ],
        stroke: {
          dashArray: 4
        },
        labels: ['Pajak Reklame'],
        };

        var chart = new ApexCharts(document.querySelector("#donutchart_reklame"), options);
        chart.render();
        chart.updateOptions(options);
    }

    function chart_pajak_daerah_parkir(tahun, persen){
        var options = {
          series: [persen],
          chart: {
          height: 250,
          type: 'radialBar'
        //   offsetY: -10
        },
        plotOptions: {
          radialBar: {
            startAngle: -150,
            endAngle: 150,
            dataLabels: {
              name: {
                fontSize: '12px',
                // offsetY: 100
              },
              value: {
                // offsetY: 60,
                fontSize: '14px',
                formatter: function (val) {
                  return val + "%";
                }
              }
            }
          }
        },
        colors : [
            function(persen){
                return change_chart_color(tahun, persen.value);    
            }
        ],
        stroke: {
          dashArray: 4
        },
        labels: ['Pajak Parkir'],
        };

        var chart = new ApexCharts(document.querySelector("#donutchart_parkir"), options);
        chart.render();
        chart.updateOptions(options);

    }

    function chart_pajak_daerah_ppj(tahun, persen){

        var options = {
          series: [persen],
          chart: {
          height: 250,
          type: 'radialBar'
        //   offsetY: -10
        },
        plotOptions: {
          radialBar: {
            startAngle: -150,
            endAngle: 150,
            dataLabels: {
              name: {
                fontSize: '12px',
                // offsetY: 100
              },
              value: {
                // offsetY: 60,
                fontSize: '14px',
                formatter: function (val) {
                  return val + "%";
                }
              }
            }
          }
        },
        colors : [
            function(persen){
                return change_chart_color(tahun, persen.value);    
            }
        ],
        stroke: {
          dashArray: 4
        },
        labels: ['Pajak PPJ'],
        };

        var chart = new ApexCharts(document.querySelector("#donutchart_ppj"), options);
        chart.render();
        chart.updateOptions(options);
    }

    function chart_pajak_daerah_pat(tahun, persen){

        var options = {
          series: [persen],
          chart: {
          height: 250,
          type: 'radialBar'
        //   offsetY: -10
        },
        plotOptions: {
          radialBar: {
            startAngle: -150,
            endAngle: 150,
            dataLabels: {
              name: {
                fontSize: '12px',
                // offsetY: 100
              },
              value: {
                // offsetY: 60,
                fontSize: '14px',
                formatter: function (val) {
                  return val + "%";
                }
              }
            }
          }
        },
        colors : [
            function(persen){
                return change_chart_color(tahun, persen.value);    
            }
        ],
        stroke: {
          dashArray: 4
        },
        labels: ['Pajak Air Tanah'],
        };

        var chart = new ApexCharts(document.querySelector("#donutchart_pat"), options);
        chart.render();
        chart.updateOptions(options);
    }

    function chart_pajak_daerah_sbw(tahun, persen){

        var options = {
          series: [persen],
          chart: {
          height: 250,
          type: 'radialBar'
        //   offsetY: -10
        },
        plotOptions: {
          radialBar: {
            startAngle: -150,
            endAngle: 150,
            dataLabels: {
              name: {
                fontSize: '12px',
                // offsetY: 100
              },
              value: {
                // offsetY: 60,
                fontSize: '14px',
                formatter: function (val) {
                  return val + "%";
                }
              }
            }
          }
        },
        colors : [
            function(persen){
                return change_chart_color(tahun, persen.value);    
            }
        ],
        stroke: {
          dashArray: 4
        },
        labels: ['Pajak SBW'],
        };

        var chart = new ApexCharts(document.querySelector("#donutchart_sbw"), options);
        chart.render();
        chart.updateOptions(options);
    }

    function chart_pajak_daerah_mblb(tahun, persen){
        var options = {
          series: [persen],
          chart: {
          height: 250,
          type: 'radialBar'
        //   offsetY: -10
        },
        plotOptions: {
          radialBar: {
            startAngle: -150,
            endAngle: 150,
            dataLabels: {
              name: {
                fontSize: '12px',
                // offsetY: 100
              },
              value: {
                // offsetY: 60,
                fontSize: '14px',
                formatter: function (val) {
                  return val + "%";
                }
              }
            }
          }
        },
        colors : [
            function(persen){
                return change_chart_color(tahun, persen.value);    
            }
        ],
        stroke: {
          dashArray: 4
        },
        labels: ['Pajak MBLB'],
        };

        var chart = new ApexCharts(document.querySelector("#donutchart_mblb"), options);
        chart.render();
        chart.updateOptions(options);
    }

    function chart_pajak_daerah_pbb(tahun, persen){
        var options = {
          series: [persen],
          chart: {
          height: 250,
          type: 'radialBar'
        //   offsetY: -10
        },
        plotOptions: {
          radialBar: {
            startAngle: -150,
            endAngle: 150,
            dataLabels: {
              name: {
                fontSize: '12px',
                // offsetY: 100
              },
              value: {
                // offsetY: 60,
                fontSize: '14px',
                formatter: function (val) {
                  return val + "%";
                }
              }
            }
          }
        },
        colors : [
            function(persen){
                return change_chart_color(tahun, persen.value);    
            }
        ],
        stroke: {
          dashArray: 4
        },
        labels: ['Pajak PBB'],
        };

        var chart = new ApexCharts(document.querySelector("#donutchart_pbb"), options);
        chart.render();
        chart.updateOptions(options);
    }

    function chart_pajak_daerah_bphtb(tahun, persen){
        var options = {
          series: [persen],
          chart: {
          height: 250,
          type: 'radialBar'
        //   offsetY: -10
        },
        plotOptions: {
          radialBar: {
            startAngle: -150,
            endAngle: 150,
            dataLabels: {
              name: {
                fontSize: '12px',
                // offsetY: 100
              },
              value: {
                // offsetY: 60,
                fontSize: '14px',
                formatter: function (val) {
                  return val + "%";
                }
              }
            }
          }
        },
        colors : [
            function(persen){
                return change_chart_color(tahun, persen.value);    
            }
        ],
        stroke: {
          dashArray: 4
        },
        labels: ['Pajak BPHTB'],
        };

        var chart = new ApexCharts(document.querySelector("#donutchart_bphtb"), options);
        chart.render();
        chart.updateOptions(options);
    }

    function get_trend_target_realisasi(rekening = null){
        let url_submit = "{{ route('pad.trend_target_realisasi') }}";
        // let mingguSearch = $("#from-minggu-penerimaan-search").val();
        // let bulanSearch = $("#from-bulan-penerimaan-search").val();
        $.ajax({
            type:'GET',
            url: url_submit,
            data: {
                "rekening": rekening,
            },
            cache:false,
            contentType: false,
            processData: true,
            success: function(data){
                console.log("trend",data);

                var tahun_trend = data.tahun;
                var target_trend = data.target;
                var realisasi_trend = data.realisasi;
                var persen_trend = data.persen;

                chart_trend_target_realisasi(tahun_trend,target_trend,realisasi_trend);
            },

            error: function(data){
                // callback(jumlah_nominal)
                return 0;
                alert('Terjadi Kesalahan Pada Server');
            },
            
        });
    }

    function chart_trend_target_realisasi(tahun_trend,target_trend,realisasi_trend){

        var options = {
            series: [{
            name: 'Target',
            data: target_trend
        }, {
            name: 'Realisasi',
            data: realisasi_trend
        }],
          chart: {
          height: 350,
          type: 'line',
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
        grid: {
          row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
          },
        },
        markers: {
          size: 1
        },
        xaxis: {
          categories: tahun_trend,
        },
        yaxis: {
            show: true,
            title: {
            text: 'Rp. (Rupiah)'
            },
            labels: {
                formatter: function (val) {
                    return bulatkanAngka(val) + " Rupiah"
                }
            }
        },
        fill: {
            opacity: 1,
            colors: [vihoAdminConfig.primary, vihoAdminConfig.secondary],
        },  
        colors: [vihoAdminConfig.primary, vihoAdminConfig.secondary],
        tooltip: {
            y: {
                formatter: function (val) {
                    return bulatkanAngka(val) + " Rupiah"
                }
            }
        }
        };
        var chart = new ApexCharts(document.querySelector("#chart-line-trend"), options);
        chart.render();
        chart.updateOptions(options);

    }

    function datatable_target_realisasi(rekening = null){
        let table = $(".target-realisasi").DataTable({
            "dom": 'rtip',
			processing: true,
	        serverSide: true,
	        responsive: true,
	        searchDelay: 2000,
            ajax: {
                url: "{{ route('pad.datatable_target_realisasi') }}",
                type: 'GET',
                data: {
                  "rekening": rekening,
                }
            },
	        columns: [
	            {data: 'tahun', name: 'tahun'},
	            {data: 'target', name: 'target'},
	            {data: 'realisasi', name: 'realisasi'},
	            {data: 'persen', name: 'persen'}
	        ],
            order: [[0, 'desc']],
		});
    }

    function get_komposisi_pad(tahun = currentYear){
        let url_submit = "{{ route('pad.komposisi_pad') }}";
        // let mingguSearch = $("#from-minggu-penerimaan-search").val();
        // let bulanSearch = $("#from-bulan-penerimaan-search").val();
        $.ajax({
            type:'GET',
            url: url_submit,
            data: {
                "tahun": tahun,
            },
            cache:false,
            contentType: false,
            processData: true,
            success: function(data){
                console.log(data );
                var nama_rekening_komposisi = data.nama_rekening;
                var target_komposisi = data.target;
               
                chart_komposisi_pad(nama_rekening_komposisi,target_komposisi);
            },

            error: function(data){
                // callback(jumlah_nominal)
                return 0;
                alert('Terjadi Kesalahan Pada Server');
            },
            
        });
    }

    function chart_komposisi_pad(rekening,target){
        // Chart pie komposisi PAD
        var options8 = {
            chart: {
                width: 420,
                type: 'pie',
            },
            legend: {
                show: true,
                position: 'bottom',
            },
            labels: rekening,
            series: target,
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 300
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
            colors:[vihoAdminConfig.primary, vihoAdminConfig.secondary, '#222222', '#717171']
        }

        // $("#chart-pie-komposisi").empty()
        var chart8 = new ApexCharts(
            document.querySelector("#chart-pie-komposisi"),
            options8
        );
        chart8.render();
        chart8.updateOptions(options8);
    }

    function get_komposisi_pajak(tahun = currentYear){
        let url_submit = "{{ route('pad.komposisi_pajak') }}";
        // let mingguSearch = $("#from-minggu-penerimaan-search").val();
        // let bulanSearch = $("#from-bulan-penerimaan-search").val();
        $.ajax({
            type:'GET',
            url: url_submit,
            data: {
                "tahun": tahun,
            },
            cache:false,
            contentType: false,
            processData: true,
            success: function(data){
                console.log(data );
                var nama_rekening_komposisi = data.nama_rekening;
                var target_komposisi = data.target;
               
                chart_komposisi_pajak(nama_rekening_komposisi,target_komposisi);
            },

            error: function(data){
                // callback(jumlah_nominal)
                return 0;
                alert('Terjadi Kesalahan Pada Server');
            },
            
        });
    }

    function chart_komposisi_pajak(rekening, target){
        var options_pd = {
            chart: {
                width: 420,
                type: 'pie',
            },
            legend: {
                show: true,
                position: 'bottom',
            },
            labels: rekening,
            series: target,
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 300
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
            colors:[vihoAdminConfig.primary, vihoAdminConfig.secondary, '#222222', '#717171']
        }

        // $("#chart-pie-komposisi-pd").empty()
        var chart_pd = new ApexCharts(
            document.querySelector("#chart-pie-komposisi-pd"),
            options_pd
        );
        chart_pd.render();
        chart_pd.updateOptions(options_pd);
    }
	$(document).ready(function(){

        get_target_realisasi_pajak();
        get_target_realisasi_retribusi();
        get_target_realisasi_pad();
        get_komposisi_pad();
        get_komposisi_pajak();
        get_trend_target_realisasi();
        datatable_target_realisasi();
	})
</script>
@endsection