@extends('admin.layout.main')
@section('title', 'PAD - Smart Dashboard')

@section('content')

    <style>
        .card {
            border-radius: 20px !important;
            box-shadow: inset 0 -1px 0 0 rgba(0, 0, 0, 0.1) !important;
        }
    </style>
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 style="font-size: 30px;font-weight: bold;">PAD</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" style="font-size: 20px;font-weight: 600;"><a href="#">PAD</a></li>
                        <li class="breadcrumb-item active" style="font-size: 20px;font-weight: 500;">index</li>
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
            @php
                $session = Session::get('user_app');
                $id = decrypt($session['group_id']);
                $id_user = decrypt($session['user_id']);
                $data = DB::table('auth.user_group')->select('*')->where('id', $id)->first();
                // echo "id nya : $id_user";
            @endphp

            @if ($data->nama_group == 'OPD' || $data->nama_group == 'opd')
                <div class="col-xl-12">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6 style="font-size: 18px;font-weight: bold;">Penerimaan Retribusi Per OPD</h6>
                            <div class="row">
                                <div class="col-xl-4">
                                    <div class="mb-2">
                                        <select id="retribusi_filter" name="retribusi_filter" class="col-sm-12">
                                            <optgroup label="Retribusi">
                                                <option value="" selected>Semua Retribusi</option>
                                                @foreach (getOpdRetribusi() as $item)
                                                    {{-- {{ dd($item) }} --}}
                                                    <option value="{{ $item->id }}">{{ $item->nama_retribusi }}</option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <div class="mb-2">
                                        <select id="tahun_filter" name="tahun_filter" class="col-sm-12">
                                            @foreach (array_combine(range(date('Y'), 2018), range(date('Y'), 2018)) as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <a class='btn btn-primary btn-sm' onclick='filterGrafikPenerimaanOpd()'><i
                                            class='fa fa-search'></i>
                                        Tampilkan</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
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
            @else
                <div class="col-sm-12 col-xl-12 box-col-12">
                    <div class="row">
                        <div class="col-xl-8">
                            <h6 style="font-size: 20px;font-weight: bold;">Target dan Realisasi Pajak Daerah</h6>
                        </div>
                        <div class="col-xl-4">
                            <div class="mb-3 draggable">
                                <div class="input-group">
                                    <select name="role_code" id="tahun" class="form-control btn-square"
                                        style="font-size: 16px;font-weight: normal;">
                                        @foreach (array_combine(range(date('Y'), 2018), range(date('Y'), 2018)) as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-btn btn btn-square p-0">
                                        <a type="button" class="btn btn-primary btn-square" type="button"
                                            style="font-size: 16px;font-weight: bold;" onclick="filterTahun()">Terapkan<span
                                                class="caret"></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="judul" style="font-size: 18px;font-weight: bold;"></h5>
                                </div>
                                <div class="modal-body">
                                    <ul class="text-center" style="font-size: 16px;font-weight: normal;">
                                        <p class="text-center" id="judul"></p>
                                        <li> Target <span><a type="button" id="target"></a></span></li>
                                        <li> Realisasi <span><a type="button" id="realisasi"></a></span></li>
                                        <li> Selisih <span><a type="button" id="selisih"></a></span></li>
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                        id="close">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 col-xl-3">
                            <div class="card o-hidden">
                                <div class="card-body apex-chart">
                                    <!-- <p class="text-center">PAJAK HOTEL</p> -->
                                    <div class="text-center" id="donutchart_hotel"></div>
                                    <div class="text-center">
                                        <a type="button" style="font-size: 16px;font-weight: bold;"
                                            class="btn btn-xs btn-primary btn-air-primary" data-id="hotel"
                                            onclick="showDetail(this)">
                                            <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-xl-3">
                            <div class="card o-hidden">
                                <div class="card-body apex-chart">
                                    <!-- <p class="text-center">PAJAK RESTORAN</p> -->
                                    <div id="donutchart_resto"></div>
                                    <div class="text-center">
                                        <a type="button" style="font-size: 16px;font-weight: bold;"
                                            class="btn btn-xs btn-primary btn-air-primary" data-id="resto"
                                            onclick="showDetail(this)">
                                            <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-xl-3">
                            <div class="card o-hidden">
                                <div class="card-body apex-chart">
                                    <!-- <p class="text-center">PAJAK HIBURAN</p> -->
                                    <div id="donutchart_hiburan"></div>
                                    <div class="text-center">
                                        <a type="button" style="font-size: 16px;font-weight: bold;"
                                            class="btn btn-xs btn-primary btn-air-primary" data-id="hiburan"
                                            onclick="showDetail(this)">
                                            <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-xl-3">
                            <div class="card o-hidden">
                                <div class="card-body apex-chart">
                                    <!-- <p class="text-center">PAJAK PARKIR</p> -->
                                    <div id="donutchart_parkir"></div>
                                    <div class="text-center">
                                        <a type="button" style="font-size: 16px;font-weight: bold;"
                                            class="btn btn-xs btn-primary btn-air-primary" data-id="parkir"
                                            onclick="showDetail(this)">
                                            <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-xl-3">
                            <div class="card o-hidden">
                                <div class="card-body apex-chart">
                                    <!-- <p class="text-center">PAJAK PENERANGAN JALAN</p> -->
                                    <div id="donutchart_ppj"></div>
                                    <div class="text-center">
                                        <a type="button" style="font-size: 16px;font-weight: bold;"
                                            class="btn btn-xs btn-primary btn-air-primary" data-id="ppj"
                                            onclick="showDetail(this)">
                                            <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-xl-3">
                            <div class="card o-hidden">
                                <div class="card-body apex-chart">
                                    <!-- <p class="text-center">PAJAK REKLAME</p> -->
                                    <div id="donutchart_reklame"></div>
                                    <div class="text-center">
                                        <a type="button" style="font-size: 16px;font-weight: bold;"
                                            class="btn btn-xs btn-primary btn-air-primary" data-id="reklame"
                                            onclick="showDetail(this)">
                                            <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-xl-3">
                            <div class="card o-hidden">
                                <div class="card-body apex-chart">
                                    <!-- <p class="text-center">PAJAK AIR TANAH</p> -->
                                    <div id="donutchart_pat"></div>
                                    <div class="text-center">
                                        <a type="button" style="font-size: 16px;font-weight: bold;"
                                            class="btn btn-xs btn-primary btn-air-primary" data-id="pat"
                                            onclick="showDetail(this)">
                                            <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-4 col-xl-3">
                            <div class="card o-hidden">
                                <div class="card-body apex-chart">
                                    <!-- <p class="text-center">PAJAK BUMI DAN BANGUNAN</p> -->
                                    <div id="donutchart_pbb"></div>
                                    <div class="text-center">
                                        <a type="button" style="font-size: 16px;font-weight: bold;"
                                            class="btn btn-xs btn-primary btn-air-primary" data-id="pbb"
                                            onclick="showDetail(this)">
                                            <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-xl-3">
                            <div class="card o-hidden">
                                <div class="card-body apex-chart">
                                    <!-- <p class="text-center">PAJAK BPHTB</p> -->
                                    <div id="donutchart_bphtb"></div>
                                    <div class="text-center">
                                        <a type="button" style="font-size: 16px;font-weight: bold;"
                                            class="btn btn-xs btn-primary btn-air-primary" data-id="bphtb"
                                            onclick="showDetail(this)">
                                            <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="row">

                        <div class="col-xl-6 d-none">
                            <div class="card o-hidden">
                                <div class="card-header pb-0">
                                    <h6>Target dan Realisasi Retribusi Daerah</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="card ecommerce-widget pro-gress">
                                                <div class="card-body support-ticket-font">
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <h6 class="f-14">Retribusi Jasa Umum</h6>
                                                            <h2 class="total-num counter" id="persen_umum"> </h2>
                                                        </div>
                                                        <div class="col-7">
                                                            <div class="text-md-end">
                                                                <ul>
                                                                    <li class="f-12">Target<span
                                                                            class="product-stts txt-info ms-2"id="t_umum"></span>
                                                                    </li>
                                                                    <li class="f-12">Realisasi<span
                                                                            class="product-stts txt-success ms-2"
                                                                            id="r_umum"></span></li>
                                                                    <li class="f-12">Selisih<span
                                                                            class="product-stts  ms-2"
                                                                            style="color: grey;" id="s_umum"></span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="progress-showcase"> -->
                                                    <!-- <div class="progress"> -->
                                                    <input type="hidden" id="progres_umum">
                                                    <!-- <div class="progress-bar" role="progressbar" id="progres_umum" style="width:80%; background-color:grey;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div> -->
                                                    <!-- </div> -->
                                                    <!-- </div> -->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-12">
                                            <div class="card ecommerce-widget pro-gress">
                                                <div class="card-body support-ticket-font">
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <h6 class="f-14">Retribusi Jasa Usaha</h6>
                                                            <h2 class="total-num counter" id="persen_usaha"> </h2>
                                                        </div>
                                                        <div class="col-7">
                                                            <div class="text-md-end">
                                                                <ul>
                                                                    <li class="f-12">Target<span
                                                                            class="product-stts txt-info ms-2"
                                                                            id="t_usaha"></span></li>
                                                                    <li class="f-12">Realisasi<span
                                                                            class="product-stts txt-success ms-2"
                                                                            id="r_usaha"></span></li>
                                                                    <li class="f-12">Selisih<span
                                                                            class="product-stts  ms-2" id="s_usaha"
                                                                            style="color: grey;"></span></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="progress-showcase"> -->
                                                    <!-- <div class="progress"> -->
                                                    <input type="hidden" id="progres_usaha">
                                                    <!-- <div class="progress-bar" role="progressbar" id="progres_usaha" style="width:80%; background-color:grey;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div> -->
                                                    <!-- </div> -->
                                                    <!-- </div> -->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-12">
                                            <div class="card ecommerce-widget pro-gress">
                                                <div class="card-body support-ticket-font">
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <h6 class="f-14">Retribusi izin Tertentu</h6>
                                                            <h2 class="total-num counter" id="persen_izin"> </h2>
                                                        </div>
                                                        <div class="col-7">
                                                            <div class="text-md-end">
                                                                <ul>
                                                                    <li class="f-12">Target<span
                                                                            class="product-stts txt-info ms-2"
                                                                            id="t_izin"></span></li>
                                                                    <li class="f-12">Realisasi<span
                                                                            class="product-stts txt-success ms-2"
                                                                            id="r_izin"></span></li>
                                                                    <li class="f-12">Selisih<span
                                                                            class="product-stts  ms-2" id="s_izin"
                                                                            style="color: grey;"></span></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="progress-showcase"> -->
                                                    <!-- <div class="progress"> -->
                                                    <input type="hidden" id="progres_izin">
                                                    <!-- <div class="progress-bar" role="progressbar" id="progres_izin" style="width:80%; background-color:grey;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div> -->
                                                    <!-- </div> -->
                                                    <!-- </div> -->
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 d-none">
                            <div class="card o-hidden">
                                <div class="card-header pb-0">
                                    <h6>Target dan Realisasi PAD</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="card ecommerce-widget pro-gress">
                                                <div class="card-body support-ticket-font">
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <h6 class="f-14">PAD</h6>
                                                            <h2 class="total-num counter" id="persen_pad"> </h2>
                                                        </div>
                                                        <div class="col-7">
                                                            <div class="text-md-end">
                                                                <ul>
                                                                    <li class="f-12">Target<span
                                                                            class="product-stts txt-info ms-2"
                                                                            id="t_pad">
                                                                        </span></li>
                                                                    <li class="f-12">Realisasi<span
                                                                            class="product-stts txt-success ms-2"
                                                                            id="r_pad"> </span></li>
                                                                    <li class="f-12">Selisih<span
                                                                            class="product-stts  ms-2" id="s_pad"
                                                                            style="color: grey;"> </span></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="progress-showcase"> -->
                                                    <!-- <div class="progress"> -->
                                                    <input type="hidden" id="progres_pad">
                                                    <!-- <div class="progress-bar " role="progressbar" id="progres_pad" style="width:80%; background-color:grey;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div> -->
                                                    <!-- </div> -->
                                                    <!-- </div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="card ecommerce-widget pro-gress">
                                                <div class="card-body support-ticket-font">
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <h6 class="f-14">Pajak Daerah</h6>
                                                            <h2 class="total-num counter" id="persen_pd"> </h2>
                                                        </div>
                                                        <div class="col-7">
                                                            <div class="text-md-end">
                                                                <ul>
                                                                    <li class="f-12">Target<span
                                                                            class="product-stts txt-info ms-2"
                                                                            id="t_pd">
                                                                        </span></li>
                                                                    <li class="f-12">Realisasi<span
                                                                            class="product-stts txt-success ms-2"
                                                                            id="r_pd"> </span></li>
                                                                    <li class="f-12">Selisih<span
                                                                            class="product-stts  ms-2" id="s_pd"
                                                                            style="color: grey;"> </span></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="progress-showcase"> -->
                                                    <!-- <div class="progress"> -->
                                                    <input type="hidden" id="progres_pd">
                                                    <!-- <div class="progress-bar " role="progressbar" id="progres_pd" style="width:80%; background-color:grey;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div> -->
                                                    <!-- </div> -->
                                                    <!-- </div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="card ecommerce-widget pro-gress">
                                                <div class="card-body support-ticket-font">
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <h6 class="f-14">Retribusi Daerah</h6>
                                                            <h2 class="total-num counter" id="persen_rd"> </h2>
                                                        </div>
                                                        <div class="col-7">
                                                            <div class="text-md-end">
                                                                <ul>
                                                                    <li class="f-12">Target<span
                                                                            class="product-stts txt-info ms-2"
                                                                            id="t_rd">
                                                                        </span></li>
                                                                    <li class="f-12">Realisasi<span
                                                                            class="product-stts txt-success ms-2"
                                                                            id="r_rd"> </span></li>
                                                                    <li class="f-12">Selisih<span
                                                                            class="product-stts  ms-2" id="s_rd"
                                                                            style="color: grey;"> </span></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="progress-showcase"> -->
                                                    <!-- <div class="progress"> -->
                                                    <input type="hidden" id="progres_rd">
                                                    <!-- <div class="progress-bar " role="progressbar" id="progres_rd" style="width:80%; background-color:grey;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div> -->
                                                    <!-- </div> -->
                                                    <!-- </div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="card ecommerce-widget pro-gress">
                                                <div class="card-body support-ticket-font">
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <h6 class="f-14">Hasil Pengelolaan Kekayaan Daerah yang
                                                                Dipisahkan</h6>
                                                            <h2 class="total-num counter" id="persen_kd"> </h2>
                                                        </div>
                                                        <div class="col-7">
                                                            <div class="text-md-end">
                                                                <ul>
                                                                    <li class="f-12">Target<span
                                                                            class="product-stts txt-info ms-2"
                                                                            id="t_kd">
                                                                        </span></li>
                                                                    <li class="f-12">Realisasi<span
                                                                            class="product-stts txt-success ms-2"
                                                                            id="r_kd"> </span></li>
                                                                    <li class="f-12">Selisih<span
                                                                            class="product-stts  ms-2" id="s_kd"
                                                                            style="color: grey;"> </span></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="progress-showcase"> -->
                                                    <!-- <div class="progress"> -->
                                                    <input type="hidden" id="progres_kd">
                                                    <!-- <div class="progress-bar " role="progressbar" id="progres_kd" style="width:80%; background-color:grey;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div> -->
                                                    <!-- </div> -->
                                                    <!-- </div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="card ecommerce-widget pro-gress">
                                                <div class="card-body support-ticket-font">
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <h6 class="f-14">Lain-lain PAD yang Sah</h6>
                                                            <h2 class="total-num counter" id="persen_ll"> </h2>
                                                        </div>
                                                        <div class="col-7">
                                                            <div class="text-md-end">
                                                                <ul>
                                                                    <li class="f-12">Target<span
                                                                            class="product-stts txt-info ms-2"
                                                                            id="t_ll">
                                                                        </span></li>
                                                                    <li class="f-12">Realisasi<span
                                                                            class="product-stts txt-success ms-2"
                                                                            id="r_ll"> </span></li>
                                                                    <li class="f-12">Selisih<span
                                                                            class="product-stts  ms-2" id="s_ll"
                                                                            style="color: grey;"> </span></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="progress-showcase"> -->
                                                    <!-- <div class="progress"> -->
                                                    <input type="hidden" id="progres_ll">
                                                    <!-- <div class="progress-bar " role="progressbar" id="progres_ll" style="width:80%; background-color:grey;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div> -->
                                                    <!-- </div> -->
                                                    <!-- </div> -->
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
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <h6 style="font-size: 20px;font-weight: bold;"> Akumulasi Pajak</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body apex-chart">
                                    <div id="donutchart_akumulasi"></div>
                                    <div class="text-center">
                                        <a type="button" style="font-size: 16px;font-weight: bold;"
                                            class="btn btn-xs btn-primary btn-air-primary" data-id="akumulasi"
                                            onclick="showDetailAkumulasiPajak(this)">
                                            <i data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"></i>
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-12">
                            <div class="card o-hidden">
                                <div class="card-header pb-0">
                                    <h6 style="font-size: 20px;font-weight: bold;"> Kontribusi Pajak Daerah Berdasarkan
                                        Target </h6>
                                </div>
                                <div class="card-body apex-chart">
                                    <div id="chart-pie-komposisi-pd"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 d-none">
                            <div class="card o-hidden">
                                <div class="card-header pb-0">
                                    <h6> Kontribusi PAD Berdasarkan Target </h6>
                                </div>
                                <div class="card-body apex-chart">
                                    <div id="chart-pie-komposisi"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="card o-hidden">
                                <div class="card-header pb-0">
                                    <h6 style="font-size: 18px;font-weight: bold;"> Trend Target & Realisasi </h6>
                                    <div class="mb-3 draggable">
                                        <div class="input-group">
                                            <div class="col-xl-8">
                                                <select name="role_code" id="rekening" class="form-control btn-square">
                                                    <!-- <option value="">-- Filter Rekening --</option> -->
                                                    @foreach (getRekeningPAD() as $item)
                                                        <option value="{{ $item->kode_rekening }}">
                                                            {{ $item->nama_rekening }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="input-group-btn btn btn-square p-0">
                                                    <a type="button" class="btn btn-primary btn-square" type="button"
                                                        onclick="filterRekening()">Terapkan<span
                                                            class="caret"></span></a>
                                                </div>
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
                                    <h6 style="font-size: 18px;font-weight: bold;">Target & Realisasi</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table style="font-size: 14px;font-weight: normal;"
                                            class="table table-sm target-realisasi">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2"
                                                        style="text-align: center; vertical-align: middle;">
                                                        Tahun</th>
                                                    <th rowspan="2"
                                                        style="text-align: center; vertical-align: middle;">
                                                        REALISASI</th>
                                                    <th colspan="2"
                                                        style="text-align: center; vertical-align: middle;">
                                                        TARGET MURNI
                                                    </th>
                                                    <th colspan="2"
                                                        style="text-align: center; vertical-align: middle;">
                                                        TARGET
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Nominal</th>
                                                    <th>Persen</th>
                                                    <th>Nominal</th>
                                                    <th>Persen</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="card o-hidden">
                                <div class="card-header pb-0">
                                    <h6 style="font-size: 18px;font-weight: bold;">Target dan Realisasi Retribusi</h6>
                                    <div class="mb-3 draggable">
                                        <div class="input-group">
                                            <div class="col-xl-4">
                                                <select name="role_code" id="retribusi" class="form-control btn-square">
                                                    <option value="">Semua Retribusi</option>
                                                    @foreach (getretribusiPAD() as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama_retribusi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xl-4">
                                                <select name="role_code" id="tahun_retribusi"
                                                    class="form-control btn-square">
                                                    @foreach (array_combine(range(date('Y'), 2018), range(date('Y'), 2018)) as $year)
                                                        <option value="{{ $year }}">{{ $year }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="input-group-btn btn btn-square p-0">
                                                    <a type="button" class="btn btn-primary btn-square" type="button"
                                                        onclick="filterretribusi()">Terapkan<span
                                                            class="caret"></span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table style="font-size: 16px;font-weight: normal;"
                                            class="table table-sm target-opd">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2"
                                                        style="text-align: center; vertical-align: middle;">
                                                        Nama Retribusi</th>
                                                    <th rowspan="2"
                                                        style="text-align: center; vertical-align: middle;">
                                                        Jenis Retribusi</th>
                                                    <th rowspan="2"
                                                        style="text-align: center; vertical-align: middle;">
                                                        Tahun</th>
                                                    <th rowspan="2"
                                                        style="text-align: center; vertical-align: middle;">
                                                        REALISASI</th>
                                                    <th colspan="2"
                                                        style="text-align: center; vertical-align: middle;">
                                                        TARGET MURNI
                                                    </th>
                                                    <th colspan="2"
                                                        style="text-align: center; vertical-align: middle;">
                                                        TARGET
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Nominal</th>
                                                    <th>Persen</th>
                                                    <th>Nominal</th>
                                                    <th>Persen</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-xl-12">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6 style="font-size: 18px;font-weight: bold;">Realisasi Retribusi Daerah</h6>
                            <div class="mb-3 draggable">
                                <div class="input-group">
                                    <div class="col-xl-4">
                                        <select id="bulan_retribusi_daerah" name="bulan_retribusi_daerah" class="col-sm-12">
                                            <optgroup label="Bulan">
                                                @foreach (getMonthList() as $index => $value)
                                                    <option value="{{ $index }}">{{ $value }}</option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="col-xl-4">
                                        <select name="tahun_retribusi_daerah" id="tahun_retribusi_daerah"
                                            class="form-control">
                                            @foreach (array_combine(range(date('Y'), 2018), range(date('Y'), 2018)) as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="input-group-btn btn btn-square p-0">
                                            <a type="button" class="btn btn-primary btn-square" type="button"
                                                onclick="filterRetribusiDaerah()">Terapkan<span class="caret"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table style="font-size: 16px;font-weight: normal;" id="tabel-retribusi-daerah" class="table table-sm tabel-retribusi-daerah">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" style="text-align: center; vertical-align: middle;">No</th>
                                            <th rowspan="2" style="text-align: center; vertical-align: middle;">Kode Rekening</th>
                                            <th rowspan="2" style="text-align: center; vertical-align: middle;">Nama OPD</th>
                                            <th  rowspan="2" style="text-align: center; vertical-align: middle;">Jenis Retribusi</th>
                                            <th rowspan="2" style="text-align: center; vertical-align: middle;">Target APBD</th>                                               
                                            <th colspan="3" style="text-align: center; vertical-align: middle;">Realisasi Retribusi</th>                                               
                                            <th  rowspan="2" style="text-align: center; vertical-align: middle;">Presentase</th>
                                        </tr>
                                        <tr>
                                            <th>s/d Bulan Lalu</th>
                                            <th>Bulan</th>
                                            <th>s/d Bulan Ini</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> --}}

                        <div class="col-xl-12 d-none">
                            <div class="card o-hidden">
                                <div class="card-header pb-0">
                                    <h6>Target Pajak</h6>
                                    <div class="mb-3 draggable">
                                        <div class="row">

                                            <div class="col-xl-5">
                                                <select id="filter-tahun-target" class="form-control btn-square">
                                                    @foreach (array_combine(range(date('Y'), 2000), range(date('Y'), 2000)) as $year)
                                                        <option value="{{ $year }}">{{ $year }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xl-5">
                                                <select id="filter-triwulan" class="form-control btn-square">
                                                    <option value="tw1">Triwulan 1</option>
                                                    <option value="tw2">Triwulan 2</option>
                                                    <option value="tw3">Triwulan 3</option>
                                                    <option value="tw4">Triwulan 4</option>
                                                </select>
                                            </div>
                                            <div class="col-xl-2">
                                                <div class="input-group-btn btn btn-square p-0">
                                                    <a type="button"
                                                        class="btn btn-primary btn-square btn-filter-tahun-triwulan"
                                                        type="button">Terapkan<span class="caret"></span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm datatable-target-pajak"
                                                id="datatable-target-pajak">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Jenis Pajak</th>
                                                        <th>Target</th>
                                                        <th>Realisasi</th>
                                                        <th>Target TW </th>
                                                        <th>TW %</th>
                                                        <th>Tahun %</th>
                                                        <th>Selisih target TW</th>
                                                        <th>Selisih target tahun</th>
                                                        <th>Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="2">Jumlah</th>
                                                        <th id="qty_target"></th>
                                                        <th id="qty_realisasi"></th>
                                                        <th id="qty_target_tw"></th>
                                                        <th id="qty_tw"></th>
                                                        <th id="qty_tahun"></th>
                                                        <th id="qty_selisih_target_tw"></th>
                                                        <th id="qty_selisih_target_tahun"></th>
                                                        <th id=""></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            @endif


        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@section('js')
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script>
        var t_hotel = 0;
        var t_resto = 0;
        var t_parkir = 0;
        var t_hiburan = 0;
        var t_reklame = 0;
        var t_ppj = 0;
        var t_pat = 0;
        var t_sbw = 0;
        var t_mblb = 0;
        var t_bphtb = 0;
        var t_pbb = 0;
        var r_hotel = 0;
        var r_resto = 0;
        var r_parkir = 0;
        var r_hiburan = 0;
        var r_reklame = 0;
        var r_ppj = 0;
        var r_pat = 0;
        var r_sbw = 0;
        var r_mblb = 0;
        var r_bphtb = 0;
        var r_pbb = 0;
        var s_hotel = 0;
        var s_resto = 0;
        var s_parkir = 0;
        var s_hiburan = 0;
        var s_reklame = 0;
        var s_ppj = 0;
        var s_pat = 0;
        var s_sbw = 0;
        var s_mblb = 0;
        var s_bphtb = 0;
        var s_pbb = 0;

        var t_umum = 0;
        var r_umum = 0;
        var s_umum = 0;
        var p_umum = 0;
        var t_usaha = 0;
        var r_usaha = 0;
        var s_usaha = 0;
        var p_usaha = 0;
        var t_izin = 0;
        var r_izin = 0;
        var s_izin = 0;
        var p_izin = 0;

        var t_pd = 0;
        var t_rd = 0;
        var t_kd = 0;
        var t_ll = 0;

        const day = new Date();
        var currentYear = day.getFullYear();
        var currentMonth = day.getMonth() + 1;
        let pencapaianBulan = (100 / 12) * currentMonth;
        let pencapaianBulanLalu = (100 / 12) * (currentMonth - 1);

        // console.log(currentYear);

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

        function showAngka(this_) {
            let id = $(this_).data("id");

            if (id === "target") {
                $('#target').html(formatRupiah(target));
            } else if (id === "selisih") {
                $('#selisih').html(formatRupiah(selisih));
            } else {
                $('#realisasi').html(formatRupiah(target));
            }
        }

        function showDetail(this_) {
            let id = $(this_).data("id");
            let tahun = $('#tahun').val()
            $.ajax({
                type: 'GET',
                url: "{{ route('pad.get_detail') }}",
                data: {
                    "id": id,
                    "tahun": tahun

                },
                beforeSend: function() {
                    $('#loader').show();
                },
                success: function(data) {
                    target = data.target;
                    realisasi = data.realisasi;
                    selisih = data.selisih;
                    judul = data.judul;


                    $('#judul').text(`DETAIL ${judul}`);
                    $('#target').html(bulatkanAngka(target));
                    $('#realisasi').html(bulatkanAngka(realisasi));
                    $('#selisih').html(bulatkanAngka(selisih));
                    $('#modal').modal("show");

                    $('#target').on('click', function() {
                        $('#target').html(formatRupiah(target));
                    })

                    $('#selisih').on('click', function() {
                        $('#selisih').html(formatRupiah(selisih));
                    })

                    $('#realisasi').on('click', function() {
                        $('#realisasi').html(formatRupiah(realisasi));
                    })
                },
                complete: function() {
                    $('#loader').hide();
                }
            });
        }

        function filterTahun() {
            let tahun = $('#tahun').val();
            if (tahun !== null) {
                get_target_realisasi_pajak(tahun);
                get_target_realisasi_retribusi(tahun);
                get_target_realisasi_pad(tahun);
                get_komposisi_pad(tahun);
                get_komposisi_pajak(tahun);
                get_akumulasi_pajak_pad(tahun);
            } else {
                get_target_realisasi_pajak();
                get_target_realisasi_retribusi();
                get_target_realisasi_pad();
                get_komposisi_pad();
                get_komposisi_pajak();

            }
        }

        function filterRekening() {
            let rekening = $('#rekening').val();
            if (rekening !== null) {
                get_trend_target_realisasi(rekening);
                $(".target-realisasi").DataTable().destroy();
                datatable_target_realisasi(rekening);
            } else {
                get_trend_target_realisasi();
                datatable_target_realisasi();
            }
        }

        function filterretribusi() {
            let retribusi = $('#retribusi').val();
            let tahun = $('#tahun_retribusi').val();
            if (retribusi !== null || tahun !== null) {
                $(".target-opd").DataTable().destroy();
                datatable_target_opd(retribusi, tahun);
            } else {
                datatable_target_opd();
            }
        }

        $('body').on('click', '.btn-filter-tahun-triwulan', function() {
            console.log("masuk filter");
            filterTahunTriwulan();
        });

        function filterTahunTriwulan() {
            let tahun = $('#filter-tahun-target').val();
            let triwulan = $('#filter-triwulan').val();
            $("#datatable-target-pajak").DataTable().destroy();
            datatable_target_pajak(tahun, triwulan);
            show_qty_target_pajak(tahun, triwulan);
        }

        function show_qty_target_pajak(tahun, triwulan) {
            $.ajax({
                url: '{{ route('pad.show_qty_target_pajak') }}',
                type: 'GET',
                cache: false,
                contentType: false,
                processData: true,
                data: {
                    "tahun": tahun,
                    "triwulan": triwulan
                },
                success: function(response) {
                    $('#qty_target').text(response.qty_target_tahun);
                    $('#qty_realisasi').text(response.qty_realisasi);
                    $('#qty_target_tw').text(response.qty_target_tw_dipilih);
                    $('#qty_tw').text(response.qty_tw_dipilih_persen);
                    $('#qty_tahun').text(response.qty_tahun_persen);
                    $('#qty_selisih_target_tw').text(response.qty_selisih_target_tw);
                    $('#qty_selisih_target_tahun').text(response.qty_selisih_target_tahun);
                }
            });
        }


        function datatable_target_pajak(tahun, triwulan) {
            let table = $("#datatable-target-pajak").DataTable({
                dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'f>>" +
                    // dengan Button
                    "<'row'<'col-sm-12'tr>>" + // Add table
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

                // buttons: [
                //     {
                //         extend: 'excel',
                //         text: 'Export to Excel',
                //         filename: 'Rekap Penerimaan Hotel', // Set your custom file name here
                //         className: 'btn btn-success',
                //     },
                //     'print'
                // ],
                buttons: [{
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    filename: 'Rekap Target Pajak per Triwulan', // Set your custom file name here
                    className: 'btn btn-success',
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        var index_jumlah = $('row', sheet).length;
                        var row = '<row r="' + (index_jumlah + 1) + '">';
                        row += '<c t="inlineStr" r="A' + (index_jumlah + 1) +
                            '" s="22"><is><t></t></is></c>';
                        row += '<c t="inlineStr" r="B' + (index_jumlah + 1) +
                            '" s="22"><is><t>Jumlah</t></is></c>';
                        row += '<c t="inlineStr" r="C' + (index_jumlah + 1) + '" s="22"><is><t>' + $(
                            '#qty_target').text() + '</t></is></c>';
                        row += '<c t="inlineStr" r="D' + (index_jumlah + 1) + '" s="22"><is><t>' + $(
                            '#qty_realisasi').text() + '</t></is></c>';
                        row += '<c t="inlineStr" r="E' + (index_jumlah + 1) + '" s="22"><is><t>' + $(
                            '#qty_target_tw').text() + '</t></is></c>';
                        row += '<c t="inlineStr" r="F' + (index_jumlah + 1) + '" s="22"><is><t>' + $(
                            '#qty_tw').text() + '</t></is></c>';
                        row += '<c t="inlineStr" r="G' + (index_jumlah + 1) + '" s="22"><is><t>' + $(
                            '#qty_tahun').text() + '</t></is></c>';
                        row += '<c t="inlineStr" r="H' + (index_jumlah + 1) + '" s="22"><is><t>' + $(
                            '#qty_selisih_target_tw').text() + '</t></is></c>';
                        row += '<c t="inlineStr" r="I' + (index_jumlah + 1) + '" s="22"><is><t>' + $(
                            '#qty_selisih_target_tahun').text() + '</t></is></c>';
                        row += '</row>';
                        $('sheetData', sheet).append(row);
                    }
                }, 'print'],

                aLengthMenu: [
                    [-1, 10, 25, 50, 100, 200],
                    ["All", 10, 25, 50, 100, 200]
                ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: "{{ route('pad.datatable_target_pajak') }}",
                    type: 'GET',
                    data: {
                        tahun: tahun,
                        triwulan: triwulan,
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'jenis_pajak',
                        name: 'jenis_pajak'
                    },
                    {
                        data: 'target_tahun',
                        name: 'target_tahun',
                    },
                    {
                        data: 'realisasi',
                        name: 'realisasi'
                    },
                    {
                        data: 'target_tw_dipilih',
                        name: 'target_tw_dipilih'
                    },
                    {
                        data: 'tw_dipilih_persen',
                        name: 'tw_dipilih_persen'
                    },
                    {
                        data: 'tahun_persen',
                        name: 'tahun_persen'
                    },
                    {
                        data: 'selisih_target_tw',
                        name: 'selisih_target_tw'
                    },
                    {
                        data: 'selisih_target_tahun',
                        name: 'selisih_target_tahun'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                ],
                order: [
                    [0, 'desc']
                ],
            });

        }


        function change_chart_color(tahun, persen) {
            if (tahun == currentYear) {
                if (pencapaianBulanLalu >= persen) {
                    return "#BE3144";
                } else if (pencapaianBulan >= persen) {
                    return "#F1EB90";
                } else {
                    return "#557C55";
                }
            } else {
                if (persen < 100) {
                    return "#BE3144";
                } else {
                    return "#557C55";
                }
            }
        }

        function chart(data, tahun) {
            console.log(data);
            var prsn_hotel = data.prsn_hotel;
            var prsn_resto = data.prsn_resto;
            var prsn_parkir = data.prsn_parkir;
            var prsn_hiburan = data.prsn_hiburan;
            var prsn_reklame = data.prsn_reklame;
            var prsn_ppj = data.prsn_ppj;
            var prsn_pat = data.prsn_pat;
            // var prsn_sbw = data.prsn_sbw;
            // var prsn_mblb = data.prsn_mblb;
            var prsn_bphtb = data.prsn_bphtb;
            var prsn_pbb = data.prsn_pbb;

            chart_pajak_daerah_hotel(tahun, prsn_hotel);
            chart_pajak_daerah_resto(tahun, prsn_resto);
            chart_pajak_daerah_hiburan(tahun, prsn_hiburan);
            chart_pajak_daerah_parkir(tahun, prsn_parkir);
            chart_pajak_daerah_pat(tahun, prsn_pat);
            chart_pajak_daerah_ppj(tahun, prsn_ppj);
            chart_pajak_daerah_reklame(tahun, prsn_reklame);
            // chart_pajak_daerah_mblb(tahun, prsn_mblb);
            // chart_pajak_daerah_sbw(tahun, prsn_sbw);
            chart_pajak_daerah_pbb(tahun, prsn_pbb);
            chart_pajak_daerah_bphtb(tahun, prsn_bphtb);

            $("#t_resto").append(formatRupiah(t_resto));
            $("#r_resto").append(formatRupiah(r_resto));
            $("#s_resto").append(formatRupiah(s_resto));
            $("#t_hiburan").append(formatRupiah(t_hiburan));
            $("#r_hiburan").append(formatRupiah(r_hiburan));
            $("#s_hiburan").append(formatRupiah(s_hiburan));
            $("#t_parkir").append(formatRupiah(t_parkir));
            $("#r_parkir").append(formatRupiah(r_parkir));
            $("#s_parkir").append(formatRupiah(s_parkir));
            $("#t_pat").append(formatRupiah(t_pat));
            $("#r_pat").append(formatRupiah(r_pat));
            $("#s_pat").append(formatRupiah(s_pat));
            $("#t_ppj").append(formatRupiah(t_ppj));
            $("#r_ppj").append(formatRupiah(r_ppj));
            $("#s_ppj").append(formatRupiah(s_ppj));
            // $("#t_sbw").append(formatRupiah(t_sbw));
            // $("#r_sbw").append(formatRupiah(r_sbw));
            // $("#s_sbw").append(formatRupiah(s_sbw));
            // $("#t_mblb").append(formatRupiah(t_mblb));
            // $("#r_mblb").append(formatRupiah(r_mblb));
            // $("#s_mblb").append(formatRupiah(s_mblb));
            $("#t_reklame").append(formatRupiah(t_reklame));
            $("#r_reklame").append(formatRupiah(r_reklame));
            $("#s_reklame").append(formatRupiah(s_reklame));
            $("#t_pbb").append(formatRupiah(t_pbb));
            $("#r_pbb").append(formatRupiah(r_pbb));
            $("#s_pbb").append(formatRupiah(s_pbb));
            $("#t_bphtb").append(formatRupiah(t_bphtb));
            $("#r_bphtb").append(formatRupiah(r_bphtb));
            $("#s_bphtb").append(formatRupiah(s_bphtb));

        }

        function get_target_realisasi_pajak(tahun = currentYear) {
            console.log("data tahun", tahun);
            let url_submit = "{{ route('pad.target_realisasi_pajak') }}";
            // let mingguSearch = $("#from-minggu-penerimaan-search").val();
            // let bulanSearch = $("#from-bulan-penerimaan-search").val();
            // console.log(bulanSearch);
            $.ajax({
                type: 'GET',
                url: url_submit,
                data: {
                    "tahun": tahun,
                },
                cache: false,
                contentType: false,
                processData: true,
                success: function(data) {
                    chart(data, tahun)
                },

                error: function(data) {
                    // callback(jumlah_nominal)
                    return 0;
                    alert('Terjadi Kesalahan Pada Server');
                },

            });

        }

        function get_target_realisasi_retribusi(tahun = currentYear) {
            let url_submit = "{{ route('pad.target_realisasi_retribusi') }}";
            // let mingguSearch = $("#from-minggu-penerimaan-search").val();
            // let bulanSearch = $("#from-bulan-penerimaan-search").val();
            $.ajax({
                type: 'GET',
                url: url_submit,
                data: {
                    "tahun": tahun,
                },
                cache: false,
                contentType: false,
                processData: true,
                success: function(data) {
                    // console.log(data );
                    t_umum = data.t_umum;
                    t_usaha = data.t_usaha;
                    t_izin = data.t_izin;
                    r_umum = data.r_umum;
                    r_usaha = data.r_usaha;
                    r_izin = data.r_izin;
                    s_umum = data.s_umum;
                    s_usaha = data.s_usaha;
                    s_izin = data.s_izin;
                    p_umum = data.p_umum;
                    p_usaha = data.p_usaha;
                    p_izin = data.p_izin;

                    var progres_umum = document.getElementById("progres_umum");
                    progres_umum.style.width = p_umum + "%";

                    var progres_usaha = document.getElementById("progres_usaha");
                    progres_usaha.style.width = p_usaha + "%";

                    var progres_izin = document.getElementById("progres_izin");
                    progres_izin.style.width = p_izin + "%";

                    var v_umum = change_chart_color(tahun, p_umum);
                    progres_umum.style.backgroundColor = v_umum;

                    var v_usaha = change_chart_color(tahun, p_usaha);
                    progres_usaha.style.backgroundColor = v_usaha;

                    var v_izin = change_chart_color(tahun, p_izin);
                    progres_izin.style.backgroundColor = v_izin;


                    if (p_umum >= 100) {
                        $('#s_umum').css('color', 'green');
                    } else {
                        $('#s_umum').css('color', 'red');
                    }

                    if (p_usaha >= 100) {
                        $('#s_usaha').css('color', 'green');
                    } else {
                        $('#s_usaha').css('color', 'red');
                    }

                    if (p_izin >= 100) {
                        $('#s_izin').css('color', 'green');
                    } else {
                        $('#s_izin').css('color', 'red');
                    }
                    $("#persen_umum").empty(p_umum + " %");
                    $("#t_umum").empty(bulatkanAngka(t_umum));
                    $("#r_umum").empty(bulatkanAngka(r_umum));
                    $("#s_umum").empty(bulatkanAngka(s_umum));
                    $("#persen_usaha").empty(p_usaha + " %");
                    $("#t_usaha").empty(bulatkanAngka(t_usaha));
                    $("#r_usaha").empty(bulatkanAngka(r_usaha));
                    $("#s_usaha").empty(bulatkanAngka(s_usaha));
                    $("#persen_izin").empty(p_izin + " %");
                    $("#t_izin").empty(bulatkanAngka(t_izin));
                    $("#r_izin").empty(bulatkanAngka(r_izin));
                    $("#s_izin").empty(bulatkanAngka(s_izin));

                    $("#persen_umum").append(p_umum + " %");
                    $("#t_umum").append(formatRupiah(t_umum));
                    $("#r_umum").append(formatRupiah(r_umum));
                    $("#s_umum").append(formatRupiah(s_umum));
                    $("#persen_usaha").append(p_usaha + " %");
                    $("#t_usaha").append(formatRupiah(t_usaha));
                    $("#r_usaha").append(formatRupiah(r_usaha));
                    $("#s_usaha").append(formatRupiah(s_usaha));
                    $("#persen_izin").append(p_izin + " %");
                    $("#t_izin").append(formatRupiah(t_izin));
                    $("#r_izin").append(formatRupiah(r_izin));
                    $("#s_izin").append(formatRupiah(s_izin));



                    $('#t_umum').on('click', function() {
                        $('#t_umum').html(formatRupiah(t_umum));
                    })

                    $('#r_umum').on('click', function() {
                        $('#r_umum').html(formatRupiah(r_umum));
                    })

                    $('#s_umum').on('click', function() {
                        $('#s_umum').html(formatRupiah(s_umum));
                    })
                    $('#t_usaha').on('click', function() {
                        $('#t_usaha').html(formatRupiah(t_usaha));
                    })

                    $('#r_usaha').on('click', function() {
                        $('#r_usaha').html(formatRupiah(r_usaha));
                    })

                    $('#s_usaha').on('click', function() {
                        $('#s_usaha').html(formatRupiah(s_usaha));
                    })
                    $('#t_izin').on('click', function() {
                        $('#t_izin').html(formatRupiah(t_izin));
                    })

                    $('#r_izin').on('click', function() {
                        $('#r_izin').html(formatRupiah(r_izin));
                    })

                    $('#s_izin').on('click', function() {
                        $('#s_izin').html(formatRupiah(s_izin));
                    })


                },

                error: function(data) {
                    // callback(jumlah_nominal)
                    return 0;
                    alert('Terjadi Kesalahan Pada Server');
                },

            });
        }


        function get_target_realisasi_pad(tahun = currentYear) {
            let url_submit = "{{ route('pad.target_realisasi_pad') }}";
            // let mingguSearch = $("#from-minggu-penerimaan-search").val();
            // let bulanSearch = $("#from-bulan-penerimaan-search").val();
            $.ajax({
                type: 'GET',
                url: url_submit,
                data: {
                    "tahun": tahun,
                },
                cache: false,
                contentType: false,
                processData: true,
                success: function(data) {
                    console.log(data);
                    t_pd = data.t_pd;
                    t_rd = data.t_rd;
                    t_kd = data.t_kd;
                    t_ll = data.t_ll;
                    t_pad = data.t_pad;
                    r_pd = data.r_pd;
                    r_rd = data.r_rd;
                    r_kd = data.r_kd;
                    r_ll = data.r_ll;
                    r_pad = data.r_pad;
                    s_pd = data.s_pd;
                    s_rd = data.s_rd;
                    s_kd = data.s_kd;
                    s_ll = data.s_ll;
                    s_pad = data.s_pad;
                    p_pd = data.p_pd;
                    p_rd = data.p_rd;
                    p_kd = data.p_kd;
                    p_ll = data.p_ll;
                    p_pad = data.p_pad;

                    var progres_pd = document.getElementById("progres_pd");
                    progres_pd.style.width = p_pd + "%";

                    var progres_rd = document.getElementById("progres_rd");
                    progres_rd.style.width = p_rd + "%";

                    var progres_kd = document.getElementById("progres_kd");
                    progres_kd.style.width = p_kd + "%";

                    var progres_ll = document.getElementById("progres_ll");
                    progres_ll.style.width = p_ll + "%";

                    var progres_pad = document.getElementById("progres_pad");
                    progres_pad.style.width = p_pad + "%";

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

                    if (p_pd >= 100) {
                        $('#s_pd').css('color', 'green');
                    } else {
                        $('#s_rd').css('color', 'red');
                    }

                    if (p_rd >= 100) {
                        $('#s_rd').css('color', 'green');
                    } else {
                        $('#s_rd').css('color', 'red');
                    }

                    if (p_kd >= 100) {
                        $('#s_kd').css('color', 'green');
                    } else {
                        $('#s_kd').css('color', 'red');
                    }

                    if (p_ll >= 100) {
                        $('#s_ll').css('color', 'green');
                    } else {
                        $('#s_ll').css('color', 'red');
                    }

                    if (p_pad >= 100) {
                        $('#s_pad').css('color', 'green');
                    } else {
                        $('#s_pad').css('color', 'red');
                    }

                    $("#persen_pd").empty(p_pd + " %");
                    $("#t_pd").empty(bulatkanAngka(t_pd));
                    $("#r_pd").empty(bulatkanAngka(r_pd));
                    $("#s_pd").empty(bulatkanAngka(s_pd));
                    $("#persen_rd").empty(p_rd + " %");
                    $("#t_rd").empty(bulatkanAngka(t_rd));
                    $("#r_rd").empty(bulatkanAngka(r_rd));
                    $("#s_rd").empty(bulatkanAngka(s_rd));
                    $("#persen_kd").empty(p_kd + " %");
                    $("#t_kd").empty(bulatkanAngka(t_kd));
                    $("#r_kd").empty(bulatkanAngka(r_kd));
                    $("#s_kd").empty(bulatkanAngka(s_kd));
                    $("#persen_ll").empty(p_ll + " %");
                    $("#t_ll").empty(bulatkanAngka(t_ll));
                    $("#r_ll").empty(bulatkanAngka(r_ll));
                    $("#s_ll").empty(bulatkanAngka(s_ll));
                    $("#persen_pad").empty(p_pad + " %");
                    $("#t_pad").empty(bulatkanAngka(t_pad));
                    $("#r_pad").empty(bulatkanAngka(r_pad));
                    $("#s_pad").empty(bulatkanAngka(s_pad));


                    $("#persen_pd").append(p_pd + " %");
                    $("#t_pd").append(formatRupiah(t_pd));
                    $("#r_pd").append(formatRupiah(r_pd));
                    $("#s_pd").append(formatRupiah(s_pd));
                    $("#persen_rd").append(p_rd + " %");
                    $("#t_rd").append(formatRupiah(t_rd));
                    $("#r_rd").append(formatRupiah(r_rd));
                    $("#s_rd").append(formatRupiah(s_rd));
                    $("#persen_kd").append(p_kd + " %");
                    $("#t_kd").append(formatRupiah(t_kd));
                    $("#r_kd").append(formatRupiah(r_kd));
                    $("#s_kd").append(formatRupiah(s_kd));
                    $("#persen_ll").append(p_ll + " %");
                    $("#t_ll").append(formatRupiah(t_ll));
                    $("#r_ll").append(formatRupiah(r_ll));
                    $("#s_ll").append(formatRupiah(s_ll));
                    $("#persen_pad").append(p_pad + " %");
                    $("#t_pad").append(formatRupiah(t_pad));
                    $("#r_pad").append(formatRupiah(r_pad));
                    $("#s_pad").append(formatRupiah(s_pad));



                    $('#t_pd').on('click', function() {
                        $('#t_pd').html(formatRupiah(t_pd));
                    })

                    $('#r_pd').on('click', function() {
                        $('#r_pd').html(formatRupiah(r_pd));
                    })

                    $('#s_pd').on('click', function() {
                        $('#s_pd').html(formatRupiah(s_pd));
                    })
                    $('#t_rd').on('click', function() {
                        $('#t_rd').html(formatRupiah(t_rd));
                    })

                    $('#r_rd').on('click', function() {
                        $('#r_rd').html(formatRupiah(r_rd));
                    })

                    $('#s_rd').on('click', function() {
                        $('#s_rd').html(formatRupiah(s_rd));
                    })
                    $('#t_kd').on('click', function() {
                        $('#t_kd').html(formatRupiah(t_kd));
                    })

                    $('#r_kd').on('click', function() {
                        $('#r_kd').html(formatRupiah(r_kd));
                    })

                    $('#s_kd').on('click', function() {
                        $('#s_kd').html(formatRupiah(s_kd));
                    })
                    $('#t_ll').on('click', function() {
                        $('#t_ll').html(formatRupiah(t_ll));
                    })

                    $('#r_ll').on('click', function() {
                        $('#r_ll').html(formatRupiah(r_ll));
                    })

                    $('#s_ll').on('click', function() {
                        $('#s_ll').html(formatRupiah(s_ll));
                    })
                    $('#t_pad').on('click', function() {
                        $('#t_pad').html(formatRupiah(t_pad));
                    })

                    $('#r_pad').on('click', function() {
                        $('#r_pad').html(formatRupiah(r_pad));
                    })

                    $('#s_pad').on('click', function() {
                        $('#s_pad').html(formatRupiah(s_pad));
                    })

                    // chart_komposisi_pad(t_pd,t_rd,t_kd,t_ll);
                },

                error: function(data) {
                    // callback(jumlah_nominal)
                    return 0;
                    alert('Terjadi Kesalahan Pada Server');
                },

            });
        }

        function chart_pajak_daerah_hotel(tahun, persen) {
            const options = {
                chart: {
                    type: "radialBar",
                    height: 200,
                },
                series: [persen],
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "65%", // Set the size of the hollow center
                        },
                        startAngle: -150,
                        endAngle: 150,
                        dataLabels: {
                            name: {
                                fontSize: '16px',
                                fontWeight: 'bold',
                                fontFamily: 'Arial'
                            },
                            value: {
                                fontSize: '18px',
                                fontFamily: 'Arial',
                                formatter: function(val) {
                                    return val + "%";
                                }
                            }
                        },
                    },
                },
                stroke: {
                    dashArray: 4
                },
                labels: ["PBJT Hotel"],
                colors: [
                    function(persen) {
                        return change_chart_color(tahun, persen.value);
                    }
                ],
            };
            // Render the chart
            const chart = new ApexCharts(document.querySelector("#donutchart_hotel"), options);
            chart.render();
            chart.updateOptions(options);
        }

        function chart_pajak_daerah_resto(tahun, persen) {
            // donut chart
            // var options9 = {
            //     chart: {
            //         width: 200,
            //         type: 'donut',
            //     },
            //     legend: {
            //         show: false
            //     },
            //     labels: ['Target','Realisasi', 'Selisih'],
            //     series: [ t_resto,r_resto, s_resto],
            //     responsive: [{
            //         breakpoint: 200,
            //         options: {
            //             chart: {
            //                 width: 200
            //             },
            //             legend: {
            //                 position: 'bottom'
            //             }
            //         }
            //     }],
            //     colors:['#9e9e9e', '#4caf50', '#f44336']
            // }
            // var chart9 = new ApexCharts(
            //     document.querySelector("#donutchart_resto"),
            //     options9
            // );
            // chart9.render();

            const options = {
                chart: {
                    type: "radialBar",
                    height: 200,
                },
                series: [persen],
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "65%", // Set the size of the hollow center
                        },
                        startAngle: -150,
                        endAngle: 150,
                        dataLabels: {
                            name: {
                                fontSize: '16px',
                                // offsetY: 85,
                                fontWeight: 'bold',
                                fontFamily: 'Arial'
                            },
                            value: {
                                // offsetY: -10,
                                fontSize: '18px',
                                fontFamily: 'Arial',
                                formatter: function(val) {
                                    return val + "%";
                                }
                            }
                        }
                    },
                },
                stroke: {
                    dashArray: 4
                },
                labels: ["PBJT Mamin"],
                colors: [
                    function(persen) {
                        return change_chart_color(tahun, persen.value);
                    }
                ],
            };
            // Render the chart
            const chart = new ApexCharts(document.querySelector("#donutchart_resto"), options);
            chart.render();
            chart.updateOptions(options);
        }

        function chart_pajak_daerah_hiburan(tahun, persen) {
            // donut chart
            // var options9 = {
            //     chart: {
            //         width: 200,
            //         type: 'donut',
            //     },
            //     legend: {
            //         show: false
            //     },
            //     labels: ['Target','Realisasi', 'Selisih'],
            //     series: [ t_hiburan,r_hiburan, s_hiburan],
            //     responsive: [{
            //         breakpoint: 200,
            //         options: {
            //             chart: {
            //                 width: 200
            //             },
            //             legend: {
            //                 position: 'bottom'
            //             }
            //         }
            //     }],
            //     colors:['#9e9e9e', '#4caf50', '#f44336']
            // }
            // var chart9 = new ApexCharts(
            //     document.querySelector("#donutchart_hiburan"),
            //     options9
            // );
            // chart9.render();

            const options = {
                chart: {
                    type: "radialBar",
                    height: 200,
                },
                series: [persen],
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "65%", // Set the size of the hollow center
                        },
                        startAngle: -150,
                        endAngle: 150,
                        dataLabels: {
                            name: {
                                fontSize: '16px',
                                // offsetY: 85,
                                fontWeight: 'bold',
                                fontFamily: 'Arial'
                            },
                            value: {
                                // offsetY: -10,
                                fontSize: '16px',
                                fontFamily: 'Arial',
                                formatter: function(val) {
                                    return val + "%";
                                }
                            }
                        }
                    },
                },
                stroke: {
                    dashArray: 4
                },
                labels: ["PBJT Hiburan"],
                colors: [
                    function(persen) {
                        return change_chart_color(tahun, persen.value);
                    }
                ],
            };
            // Render the chart
            // const destroy = new ApexCharts(document.querySelector("#donutchart_hiburan"), options);
            // chart.render();
            const chart = new ApexCharts(document.querySelector("#donutchart_hiburan"), options);
            chart.render();
            chart.updateOptions(options);
        }

        function chart_pajak_daerah_reklame(tahun, persen) {
            // donut chart
            // var options9 = {
            //     chart: {
            //         width: 200,
            //         type: 'donut',
            //     },
            //     legend: {
            //         show: false
            //     },
            //     labels: ['Target','Realisasi', 'Selisih'],
            //     series: [ t_reklame,r_reklame, s_reklame],
            //     responsive: [{
            //         breakpoint: 200,
            //         options: {
            //             chart: {
            //                 width: 200
            //             },
            //             legend: {
            //                 position: 'bottom'
            //             }
            //         }
            //     }],
            //     colors:['#9e9e9e', '#4caf50', '#f44336']
            // }
            // var chart9 = new ApexCharts(
            //     document.querySelector("#donutchart_reklame"),
            //     options9
            // );
            // chart9.render();

            const options = {
                chart: {
                    type: "radialBar",
                    height: 200,
                },
                series: [persen],
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "65%", // Set the size of the hollow center
                        },
                        startAngle: -150,
                        endAngle: 150,
                        dataLabels: {
                            name: {
                                fontSize: '16px',
                                // offsetY: 85,
                                fontWeight: 'bold',
                                fontFamily: 'Arial'
                            },
                            value: {
                                // offsetY: -10,
                                fontSize: '18px',
                                fontFamily: 'Arial',
                                formatter: function(val) {
                                    return val + "%";
                                }
                            }
                        }
                    },
                },
                stroke: {
                    dashArray: 4
                },
                labels: ["Pajak Reklame"],
                colors: [
                    function(persen) {
                        return change_chart_color(tahun, persen.value);
                    }
                ],
            };
            // Render the chart
            const chart = new ApexCharts(document.querySelector("#donutchart_reklame"), options);
            chart.render();
            chart.updateOptions(options);
        }

        function chart_pajak_daerah_parkir(tahun, persen) {
            // donut chart
            // var options9 = {
            //     chart: {
            //         width: 200,
            //         type: 'donut',
            //     },
            //     legend: {
            //         show: false
            //     },
            //     labels: ['Target','Realisasi', 'Selisih'],
            //     series: [ t_parkir,r_parkir, s_parkir],
            //     responsive: [{
            //         breakpoint: 200,
            //         options: {
            //             chart: {
            //                 width: 200
            //             },
            //             legend: {
            //                 position: 'bottom'
            //             }
            //         }
            //     }],
            //     colors:['#9e9e9e', '#4caf50', '#f44336']
            // }
            // var chart9 = new ApexCharts(
            //     document.querySelector("#donutchart_parkir"),
            //     options9
            // );
            // chart9.render();

            const options = {
                chart: {
                    type: "radialBar",
                    height: 200,
                },
                series: [persen],
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "65%", // Set the size of the hollow center
                        },
                        startAngle: -150,
                        endAngle: 150,
                        dataLabels: {
                            name: {
                                fontSize: '16px',
                                // offsetY: 85,
                                fontWeight: 'bold',
                                fontFamily: 'Arial'
                            },
                            value: {
                                // offsetY: -10,
                                fontSize: '18px',
                                fontFamily: 'Arial',
                                formatter: function(val) {
                                    return val + "%";
                                }
                            }
                        }
                    },
                },
                stroke: {
                    dashArray: 4
                },
                labels: ["PBJT Parkir"],
                colors: [
                    function(persen) {
                        return change_chart_color(tahun, persen.value);
                    }
                ],
            };
            // Render the chart
            const chart = new ApexCharts(document.querySelector("#donutchart_parkir"), options);
            chart.render();
            chart.updateOptions(options);

        }

        function chart_pajak_daerah_ppj(tahun, persen) {

            const options = {
                chart: {
                    type: "radialBar",
                    height: 200,
                },
                series: [persen],
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "65%",
                        },
                        startAngle: -150,
                        endAngle: 150,
                        dataLabels: {
                            name: {
                                fontSize: '15px',
                                fontWeight: 'bold',
                                fontFamily: 'Arial',

                            },
                            value: {
                                fontSize: '18px',
                                fontFamily: 'Arial',
                                formatter: function(val) {
                                    return val + "%";
                                }
                            }
                        }
                    },
                },
                stroke: {
                    dashArray: 4
                },
                labels: ["PBJT Tenaga Listrik"],
                colors: [
                    function(persen) {
                        return change_chart_color(tahun, persen.value);
                    }
                ],
            };
            // Render the chart
            const chart = new ApexCharts(document.querySelector("#donutchart_ppj"), options);
            chart.render();
            chart.updateOptions(options);
        }

        function chart_pajak_daerah_pat(tahun, persen) {
            const options = {
                chart: {
                    type: "radialBar",
                    height: 200,
                },
                series: [persen],
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "65%", // Set the size of the hollow center
                        },
                        startAngle: -150,
                        endAngle: 150,
                        dataLabels: {
                            name: {
                                fontSize: '16px',
                                // offsetY: 85,
                                fontWeight: 'bold',
                                fontFamily: 'Arial'
                            },
                            value: {
                                // offsetY: -10,
                                fontSize: '18px',
                                fontFamily: 'Arial',
                                formatter: function(val) {
                                    return val + "%";
                                }
                            }
                        }
                    },
                },
                stroke: {
                    dashArray: 4
                },
                labels: ["Pajak Air Tanah"],
                colors: [
                    function(persen) {
                        return change_chart_color(tahun, persen.value);
                    }
                ],
            };
            // Render the chart
            const chart = new ApexCharts(document.querySelector("#donutchart_pat"), options);
            chart.render();
            chart.updateOptions(options);
        }

        function chart_pajak_daerah_sbw(tahun, persen) {
            // donut chart
            // var options9 = {
            //     chart: {
            //         width: 200,
            //         type: 'donut',
            //     },
            //     legend: {
            //         show: false
            //     },
            //     labels: ['Target','Realisasi', 'Selisih'],
            //     series: [ t_sbw,r_sbw, s_sbw],
            //     responsive: [{
            //         breakpoint: 200,
            //         options: {
            //             chart: {
            //                 width: 200
            //             },
            //             legend: {
            //                 position: 'bottom'
            //             }
            //         }
            //     }],
            //     colors:['#9e9e9e', '#4caf50', '#f44336']
            // }
            // var chart9 = new ApexCharts(
            //     document.querySelector("#donutchart_sbw"),
            //     options9
            // );
            // chart9.render();
            const options = {
                chart: {
                    type: "radialBar",
                    height: 200,
                },
                series: [persen],
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "65%", // Set the size of the hollow center
                        },
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
                                formatter: function(val) {
                                    return val + "%";
                                }
                            }
                        }
                    },
                },
                stroke: {
                    dashArray: 4
                },
                labels: ["Pajak SBW"],
                colors: [
                    function(persen) {
                        return change_chart_color(tahun, persen.value);
                    }
                ],
            };
            // Render the chart
            const chart = new ApexCharts(document.querySelector("#donutchart_sbw"), options);
            chart.render();
            chart.updateOptions(options);
        }

        function chart_pajak_daerah_mblb(tahun, persen) {
            // donut chart
            // var options9 = {
            //     chart: {
            //         width: 200,
            //         type: 'donut',
            //     },
            //     legend: {
            //         show: false
            //     },
            //     labels: ['Target','Realisasi', 'Selisih'],
            //     series: [ t_mblb,r_mblb, s_mblb],
            //     responsive: [{
            //         breakpoint: 200,
            //         options: {
            //             chart: {
            //                 width: 200
            //             },
            //             legend: {
            //                 position: 'bottom'
            //             }
            //         }
            //     }],
            //     colors:['#9e9e9e', '#4caf50', '#f44336']
            // }
            // var chart9 = new ApexCharts(
            //     document.querySelector("#donutchart_mblb"),
            //     options9
            // );
            // chart9.render();
            const options = {
                chart: {
                    type: "radialBar",
                    height: 200,
                },
                series: [persen],
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "65%", // Set the size of the hollow center
                        },
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
                                formatter: function(val) {
                                    return val + "%";
                                }
                            }
                        }
                    },
                },
                stroke: {
                    dashArray: 4
                },
                labels: ["Pajak MBLB"],
                colors: [
                    function(persen) {
                        return change_chart_color(tahun, persen.value);
                    }
                ],
            };
            // Render the chart
            const chart = new ApexCharts(document.querySelector("#donutchart_mblb"), options);
            chart.render();
            chart.updateOptions(options);
        }

        function chart_pajak_daerah_pbb(tahun, persen) {
            // donut chart
            // var options9 = {
            //     chart: {
            //         width: 200,
            //         type: 'donut',
            //     },
            //     legend: {
            //         show: false
            //     },
            //     labels: ['Target','Realisasi', 'Selisih'],
            //     series: [ t_pbb,r_pbb, s_pbb],
            //     responsive: [{
            //         breakpoint: 200,
            //         options: {
            //             chart: {
            //                 width: 200
            //             },
            //             legend: {
            //                 position: 'bottom'
            //             }
            //         }
            //     }],
            //     colors:['#9e9e9e', '#4caf50', '#f44336']
            // }
            // var chart9 = new ApexCharts(
            //     document.querySelector("#donutchart_pbb"),
            //     options9
            // );
            // chart9.render();
            const options = {
                chart: {
                    type: "radialBar",
                    height: 200,
                },
                series: [persen],
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "65%", // Set the size of the hollow center
                        },
                        startAngle: -150,
                        endAngle: 150,
                        dataLabels: {
                            name: {
                                fontSize: '16px',
                                // offsetY: 85,
                                fontWeight: 'bold',
                                fontFamily: 'Arial'
                            },
                            value: {
                                // offsetY: -10,
                                fontSize: '18px',
                                fontFamily: 'Arial',
                                formatter: function(val) {
                                    return val + "%";
                                }
                            }
                        }
                    },
                },
                stroke: {
                    dashArray: 4
                },
                labels: ["Pajak PBB"],
                colors: [
                    function(persen) {
                        return change_chart_color(tahun, persen.value);
                    }
                ],
            };
            // Render the chart
            const chart = new ApexCharts(document.querySelector("#donutchart_pbb"), options);
            chart.render();
            chart.updateOptions(options);
        }

        function chart_pajak_daerah_bphtb(tahun, persen) {
            // donut chart
            // var options9 = {
            //     chart: {
            //         width: 200,
            //         type: 'donut',
            //     },
            //     legend: {
            //         show: false
            //     },
            //     labels: ['Target','Realisasi', 'Selisih'],
            //     series: [ t_bphtb,r_bphtb, s_bphtb],
            //     responsive: [{
            //         breakpoint: 200,
            //         options: {
            //             chart: {
            //                 width: 200
            //             },
            //             legend: {
            //                 position: 'bottom'
            //             }
            //         }
            //     }],
            //     colors:['#9e9e9e', '#4caf50', '#f44336']
            // }
            // var chart9 = new ApexCharts(
            //     document.querySelector("#donutchart_bphtb"),
            //     options9
            // );
            // chart9.render();
            const options = {
                chart: {
                    type: "radialBar",
                    height: 200,
                },
                series: [persen],
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "65%", // Set the size of the hollow center
                        },
                        startAngle: -150,
                        endAngle: 150,
                        dataLabels: {
                            name: {
                                fontSize: '16px',
                                // offsetY: 85,
                                fontWeight: 'bold',
                                fontFamily: 'Arial'
                            },
                            value: {
                                // offsetY: -10,
                                fontSize: '18px',
                                fontFamily: 'Arial',
                                formatter: function(val) {
                                    return val + "%";
                                }
                            }
                        }
                    },
                },
                stroke: {
                    dashArray: 4
                },
                labels: ["Pajak BPHTB"],
                colors: [
                    function(persen) {
                        return change_chart_color(tahun, persen.value);
                    }
                ],
            };
            // Render the chart
            const chart = new ApexCharts(document.querySelector("#donutchart_bphtb"), options);
            chart.render();
            chart.updateOptions(options);
        }

        function get_trend_target_realisasi(rekening = null) {
            let url_submit = "{{ route('pad.trend_target_realisasi') }}";
            // let mingguSearch = $("#from-minggu-penerimaan-search").val();
            // let bulanSearch = $("#from-bulan-penerimaan-search").val();
            $.ajax({
                type: 'GET',
                url: url_submit,
                data: {
                    "rekening": rekening,
                },
                cache: false,
                contentType: false,
                processData: true,
                success: function(data) {
                    console.log("trend", data);

                    var tahun_trend = data.tahun;
                    var target_trend = data.target;
                    var target_awal_trend = data.target_awal;
                    var realisasi_trend = data.realisasi;
                    var persen_trend = data.persen;

                    chart_trend_target_realisasi(tahun_trend, target_trend, target_awal_trend, realisasi_trend);
                },

                error: function(data) {
                    // callback(jumlah_nominal)
                    return 0;
                    alert('Terjadi Kesalahan Pada Server');
                },

            });
        }

        function chart_trend_target_realisasi(tahun_trend, target_trend, target_awal_trend, realisasi_trend) {

            var options = {
                series: [{
                        name: 'Target',
                        data: target_trend
                    }, {
                        name: 'Target Murni',
                        data: target_awal_trend
                    },
                    {
                        name: 'Realisasi',
                        data: realisasi_trend
                    }
                ],
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
                        colors: ['#f3f3f3', 'transparent'],
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
                        formatter: function(val) {
                            return bulatkanAngka(val) + " "
                        }
                    }
                },
                fill: {
                    opacity: 1,
                    colors: [vihoAdminConfig.primary, vihoAdminConfig.secondary, '#148df6'],
                },
                colors: [vihoAdminConfig.primary, vihoAdminConfig.secondary, '#148df6'],
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return bulatkanAngka(val) + " Rupiah"
                        }
                    }
                }
            };
            var chart = new ApexCharts(document.querySelector("#chart-line-trend"), options);
            chart.render();
            chart.updateOptions(options);

        }

        function datatable_target_realisasi(rekening = null) {
            let table = $(".target-realisasi").DataTable({
                dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'>>" +
                    // dengan Button
                    "<'row'<'col-sm-12'tr>>" + // Add table
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

                // buttons: [
                //     {
                //         extend: 'excel',
                //         text: 'Export to Excel',
                //         filename: 'Rekap Penerimaan Hotel', // Set your custom file name here
                //         className: 'btn btn-success',
                //     },
                //     'print'
                // ],
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Export to Excel',
                    "filename": 'Target dan Realisasi Pajak',
                    "action": newexportaction
                }, ],

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
                columns: [{
                        data: 'tahun',
                        name: 'tahun'
                    },
                    {
                        data: 'realisasi',
                        name: 'realisasi'
                    },
                    {
                        data: 'target_awal_tahun',
                        name: 'target_awal_tahun'
                    },
                    {
                        data: 'persen_awal',
                        name: 'persen_awal'
                    },
                    {
                        data: 'target',
                        name: 'target'
                    },
                    {
                        data: 'persen',
                        name: 'persen'
                    }
                ],
                order: [
                    [0, 'desc']
                ],
            });
        }

        function datatable_target_opd(retribusi = null, tahun = currentYear) {
            let table = $(".target-opd").DataTable({
                dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    "titleAttr": 'Export to Excel',
                    "filename": 'Target dan Realisasi Pajak',
                    "action": newexportaction
                }, ],

                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: "{{ route('pad.datatable_target_opd') }}",
                    type: 'GET',
                    data: {
                        "retribusi": retribusi,
                        "tahun": tahun,
                    }
                },
                columns: [{
                        data: 'nama_retribusi',
                        name: 'nama_retribusi'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'tahun',
                        name: 'tahun'
                    },
                    {
                        data: 'realisasi',
                        name: 'realisasi'
                    },
                    {
                        data: 'total_target_murni',
                        name: 'total_target_murni'
                    },
                    {
                        data: 'persen',
                        name: 'persen'
                    },
                    {
                        data: 'total_target_perubahan',
                        name: 'total_target_perubahan'
                    },
                    {
                        data: 'persen_awal',
                        name: 'persen_awal'
                    }
                ],
                order: [
                    [0, 'desc']
                ],
            });
        }

        function get_komposisi_pad(tahun = currentYear) {
            let url_submit = "{{ route('pad.komposisi_pad') }}";
            // let mingguSearch = $("#from-minggu-penerimaan-search").val();
            // let bulanSearch = $("#from-bulan-penerimaan-search").val();
            $.ajax({
                type: 'GET',
                url: url_submit,
                data: {
                    "tahun": tahun,
                },
                cache: false,
                contentType: false,
                processData: true,
                success: function(data) {
                    console.log(data);
                    var nama_rekening_komposisi = data.nama_rekening;
                    var target_komposisi = data.target;

                    chart_komposisi_pad(nama_rekening_komposisi, target_komposisi);
                },

                error: function(data) {
                    // callback(jumlah_nominal)
                    return 0;
                    alert('Terjadi Kesalahan Pada Server');
                },

            });
        }

        function chart_komposisi_pad(rekening, target) {
            // Chart pie komposisi PAD
            var options_komposisi = {
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
                // plotOptions: {
                //     pie: {
                //         dataLabels: {
                //             fontSize: '40px',
                //             fontWeight: 'bold',
                //             fontFamily: 'Arial' 
                //         }
                //     }
                // },
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
                colors: ['#ba895d', '#148df6', '#51bb25', '#ff5f24', '#fd2e64', '#e8ebf2', '#2c323f', '#fac5be',
                    '#ec2e70', '#3db39d', '#dfc8b3'
                ]
            }

            $("#chart-pie-komposisi").empty()
            var chart_komposisi = new ApexCharts(
                document.querySelector("#chart-pie-komposisi"),
                options_komposisi
            );
            chart_komposisi.render();
            chart_komposisi.updateOptions(options_komposisi);
        }

        function get_komposisi_pajak(tahun = currentYear) {
            let url_submit = "{{ route('pad.komposisi_pajak') }}";
            // let mingguSearch = $("#from-minggu-penerimaan-search").val();
            // let bulanSearch = $("#from-bulan-penerimaan-search").val();
            $.ajax({
                type: 'GET',
                url: url_submit,
                data: {
                    "tahun": tahun,
                },
                cache: false,
                contentType: false,
                processData: true,
                success: function(data) {
                    console.log(data);
                    var nama_rekening_komposisi = data.nama_rekening;
                    var target_komposisi = data.target;

                    chart_komposisi_pajak(nama_rekening_komposisi, target_komposisi);
                },

                error: function(data) {
                    // callback(jumlah_nominal)
                    return 0;
                    alert('Terjadi Kesalahan Pada Server');
                },

            });
        }

        function chart_komposisi_pajak(rekening, target) {
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
                colors: [vihoAdminConfig.primary, vihoAdminConfig.secondary, '#148df6', '#51bb25', '#ff5f24', '#fd2e64',
                    '#e8ebf2', '#2c323f', '#fac5be', '#dfc8b3', '#d43535'
                ]
            }

            $("#chart-pie-komposisi-pd").empty()
            var chart_pd = new ApexCharts(
                document.querySelector("#chart-pie-komposisi-pd"),
                options_pd
            );
            chart_pd.render();
            chart_pd.updateOptions(options_pd);
        }



        var cur_filter_retribusi = $("#retribusi_filter").val();
        var cur_tahun_filter = $("#tahun_filter").val();

        function get_penerimaan_peropd(retribu_opd = cur_filter_retribusi, tahun = cur_tahun_filter) {
            // console.log(kecamatan);
            let url_submit = "{{ route('pad.penerimaan_peropd') }}";
            $.ajax({
                type: 'GET',
                url: url_submit,
                data: {
                    "retribu_opd": retribu_opd,
                    "tahun": tahun,
                },
                cache: false,
                contentType: false,
                processData: true,
                success: function(data) {
                    // console.log(data);

                    bulan = data.bulan;
                    penerimaan = data.penerimaan;
                    chart_penerimaan_peropd(penerimaan, bulan);
                },

                error: function(data) {
                    return 0;
                    alert('Terjadi Kesalahan Pada Server');
                },

            });
        }



        // Contoh penggunaan
        // console.log(convertMonthNumberToString(1));  // Output: Januari
        // console.log(convertMonthNumberToString(2));  // Output: Februari


        function chart_penerimaan_peropd(penerimaan, bulan) {
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
                    height: 360
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

        function filterGrafikPenerimaanOpd() {
            var retribusi_filter = $("#retribusi_filter").val();
            var tahun_filter = $("#tahun_filter").val();
            get_penerimaan_peropd(retribusi_filter, tahun_filter);
        }

        function convertMonthNumberToString(monthNumber) {
            const months = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            if (monthNumber < 1 || monthNumber > 12) {
                return 'Invalid month number';
            }
            return months[monthNumber - 1];
        }

        function datatable_retribusi_daerah(tahun, bulan) {
            let table = $("#tabel-retribusi-daerah").DataTable({
                dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'f>>" +
                    // dengan Button
                    "<'row'<'col-sm-12'tr>>" + // Add table
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

                buttons: [{
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o" style="color: white;"> Export Excel</i>',
                    filename: 'Laporan Realisasi Retribusi Daerah',
                    className: 'btn btn-success',
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        var sheetData = $('sheetData', sheet);
                        var index_jumlah = $('row', sheet).length;

                        var titleRow = '<row r="1">';
                        titleRow +=
                            '<c t="inlineStr" r="A1" s="51"><is><t>LAPORAN REALISASI RETRIBUSI DAERAH</t></is></c>';
                        titleRow += '</row>';

                        var yearRow = '<row r="2">';
                        yearRow +=
                        `<c t="inlineStr" r="A2" s="51"><is><t>Tahun : ${tahun}</t></is></c>`;
                        yearRow += '</row>';

                        var monthRow = '<row r="3">';
                        monthRow +=
                            `<c t="inlineStr" r="A3" s="51"><is><t>Bulan: ${convertMonthNumberToString(bulan)}</t></is></c>`;
                        monthRow += '</row>';
                        $(sheetData).prepend(monthRow);
                        $(sheetData).prepend(yearRow);
                        $(sheetData).prepend(titleRow);

                        var mergeCells = $('mergeCells', sheet);
                        if (mergeCells.length === 0) {
                            mergeCells = $('<mergeCells count="1"></mergeCells>');
                            $(sheet).children('worksheet').append(mergeCells);
                        }

                        var mergeCell = `<mergeCell ref="A2:I2"/>`;
                        mergeCell += `<mergeCell ref="A3:I3"/>`;
                        mergeCell += `<mergeCell ref="A4:I4"/>`;


                        mergeCells.append(mergeCell);
                        mergeCells.attr('count', parseInt(mergeCells.attr('count')) + 1);
                        $('row', sheetData).each(function(index, elem) {
                            if (index > 2) {
                                var currentRowNum = parseInt($(elem).attr('r'));
                                $(elem).attr('r', currentRowNum + 3);
                                $('c', elem).each(function() {
                                    var cellRef = $(this).attr('r');
                                    var newCellRef = cellRef.replace(/[0-9]+/, function(
                                        match) {
                                        return parseInt(match) + 3;
                                    });
                                    $(this).attr('r', newCellRef);
                                });
                            }
                        });

                    }
                }, 'print'],

                aLengthMenu: [
                    [-1, 10, 25, 50, 100, 200],
                    ["All", 10, 25, 50, 100, 200]
                ],
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: "{{ route('pad.datatable_laporan_realisasi_retribusi_daerah') }}",
                    type: 'GET',
                    data: {
                        tahun: tahun,
                        bulan: bulan
                    },
                },
                columns: [{
                        data: 'no',
                        name: 'no',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode_rekening',
                        name: 'kode_rekening'
                    },
                    {
                        data: 'nama_opd',
                        name: 'nama_opd'
                    },
                    {
                        data: 'nama_retribusi',
                        name: 'nama_retribusi',
                    },
                    {
                        data: 'target_apbd',
                        name: 'target_apbd'

                    },
                    {
                        data: 'sd_bln_lalu',
                        name: 'sd_bln_lalu'
                    },
                    {
                        data: 'bln_ini',
                        name: 'bln_ini'
                    },
                    {
                        data: 'sd_bln_ini',
                        name: 'sd_bln_ini'
                    },
                    {
                        data: 'persen',
                        name: 'persen'
                    },
                ],
                order: [
                    [0, 'desc']
                ],
                createdRow: function(row, data, dataIndex) {
                    if (data.nama_opd == 'Jumlah') {
                        // Apply bold styling to the row
                        $(row).css('font-weight', 'bold');
                    }
                    if (data.nama_opd == 'Jumlah Total') {
                        $(row).css('font-weight', 'bold');
                    }
                }
            });

        }

        function filterRetribusiDaerah() {
            var bulan_retribusi_daerah = $('#bulan_retribusi_daerah').val();
            var tahun_retribusi_daerah = $('#tahun_retribusi_daerah').val();
            $("#tabel-retribusi-daerah").DataTable().destroy();
            datatable_retribusi_daerah(tahun_retribusi_daerah, bulan_retribusi_daerah);
        }


        // function filterChartAkumulasiPajak(){
        //     var tahun_filter_akumulasi_pajak = $("#tahun_filter_akumulasi_pajak").val();
        //     get_akumulasi_pajak_pad(tahun_filter_akumulasi_pajak);
        // }
        function get_akumulasi_pajak_pad(tahun = currentYear) {
            // console.log("data tahun", tahun);
            // let url_submit = "{{ route('pad.get_akumulasi_pajak_pad') }}";
            $.ajax({
                url: "{{ route('pad.get_akumulasi_pajak_pad') }}",
                type: 'GET',
                data: {
                    "tahun": tahun,
                },
                cache: false,
                contentType: false,
                processData: true,
                success: function(data) {
                    chart_akumulasi_pajak(tahun, data.persen)
                },

                error: function(data) {
                    // callback(jumlah_nominal)
                    return 0;
                    alert('Terjadi Kesalahan Pada Server');
                },

            });

        }

        function chart_akumulasi_pajak(tahun, persen) {
            const options = {
                chart: {
                    type: "radialBar",
                    height: 350,
                    width: 350,
                },
                series: [persen],
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "70%", // Set the size of the hollow center
                        },
                        startAngle: -150,
                        endAngle: 150,
                        dataLabels: {
                            name: {
                                fontSize: '16px',
                                // offsetY: 85,
                                fontWeight: 'bold',
                                fontFamily: 'Arial'
                            },
                            value: {
                                // offsetY: -10,
                                fontSize: '18px',
                                fontFamily: 'Arial',
                                formatter: function(val) {
                                    return val + "%";
                                }
                            }
                        }
                    },
                },
                stroke: {
                    dashArray: 4
                },
                labels: ["Akumulasi Pajak"],
                colors: [
                    function(persen) {
                        return change_chart_color(tahun, persen.value);
                    }
                ],
            };
            // Render the chart
            const chart = new ApexCharts(document.querySelector("#donutchart_akumulasi"), options);
            chart.render();
            chart.updateOptions(options);
        }

        function showDetailAkumulasiPajak() {
            var tahun = $("#tahun").val();
            $.ajax({
                url: "{{ route('pad.get_akumulasi_pajak_pad') }}",
                type: 'GET',
                data: {
                    "tahun": tahun,
                },
                cache: false,
                contentType: false,
                processData: true,
                success: function(data) {
                    $('#judul').text(`DETAIL Akumulasi`);
                    $('#target').html(bulatkanAngka(data.target));
                    $('#realisasi').html(bulatkanAngka(data.realisasi));
                    $('#selisih').html(bulatkanAngka(data.selisih));
                    $('#modal').modal("show");
                },

                error: function(data) {
                    // callback(jumlah_nominal)
                    return 0;
                    alert('Terjadi Kesalahan Pada Server');
                },

            });
        }


        $(document).ready(function() {
            $("#rekening").select2({
                placeholder: "Pilih Pajak"
            });
            $("#retribusi").select2({
                placeholder: "Pilih Retribusi"
            });
            $("#tahun_retribusi").select2({
                placeholder: "Pilih Tahun"
            });
            $("#tahun_filter_akumulasi_pajak").select2({
                placeholder: "Pilih Tahun"
            });

            $("#retribusi_filter").select2();

            $("#tahun_filter").select2({
                placeholder: "Pilih Tahun"
            });
            let tahun_pd = $('#tahun').val();
            get_akumulasi_pajak_pad(tahun_pd);

            $("#bulan_retribusi_daerah").select2();
            $("#tahun_retribusi_daerah").select2();

            get_target_realisasi_pajak();
            get_target_realisasi_retribusi();
            get_target_realisasi_pad();
            get_komposisi_pad();
            get_komposisi_pajak();
            // get_trend_target_realisasi();
            let rekening = $('#rekening').val();
            // let retribusi = $('#retribusi').val();
            // console.log("rekening :"+rekening);
            get_trend_target_realisasi(rekening);
            datatable_target_realisasi(rekening);
            datatable_target_opd();
            let tahun = $('#filter-tahun-target').val();
            let triwulan = $('#filter-triwulan').val();
            datatable_target_pajak(tahun, triwulan);
            show_qty_target_pajak(tahun, triwulan);

            var retribusi_filter = $("#retribusi_filter").val();
            var tahun_filter = $("#tahun_filter").val();
            get_penerimaan_peropd(retribusi_filter, tahun_filter);

            var bulan_retribusi_daerah = $('#bulan_retribusi_daerah').val();
            var tahun_retribusi_daerah = $('#tahun_retribusi_daerah').val();
            datatable_retribusi_daerah(tahun_retribusi_daerah, bulan_retribusi_daerah);
        });
    </script>
@endsection
