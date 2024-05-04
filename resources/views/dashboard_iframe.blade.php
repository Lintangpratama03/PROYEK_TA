@extends('layouts.app_iframe')
@section('content')
<div class="page-content container-fluid">
  <div class="col-md-12">
    <div class="panel-group" id="exampleAccordionDefault" aria-multiselectable="true" role="tablist">
      @php 
        $sessions = getSession();
      @endphp

      <div class="row">
         <!-- informasi umum  -->
        <div class="col-md-12">
          <div class="panel">
            <div class="panel-heading" id="headingInformasiUmum" role="tab">
              <a class="panel-title bg-grey-800 text-white" data-toggle="collapse" href="#panelInformasiUmum" data-parent="#panelInformasiUmum" aria-expanded="true" aria-controls="exampleCollapseDefaultOne">
                <h4 class="text-white">Informasi Penerimaan Pajak</h4>
              </a>
            </div>
            <div class="panel-collapse collapse show" id="panelInformasiUmum" aria-labelledby="exampleHeadingDefaultOne" role="tabpanel">
              <div class="panel-body">
              <div class="col-md-12">
                  <div class="btn-group">
                    <!-- <button type="button" class="btn bg-teal-900 text-white" onclick="filterInformasiPenerimaanBy('minggu')"> <i class="fa fa-calendar"></i> Tampilkan Minggu</button> -->
                    <!-- <button type="button" class="btn bg-pink-900 text-white" onclick="filterInformasiPenerimaanBy('bulan')"><i class="fa fa-calendar"></i> Tampilkan Bulan</button> -->
                  </div>
                  <hr>
                  <form class="form-inline">
                    <div class="input-group mb-3">
                      
                        <div class="input-group-prepend">
                          
                        </div>
                        <div class="input-group-prepend">
                          <div class="form-group">
                            {{-- <label class="sr-only" for="bulan-rekap-search">Bulan</label> --}}
                            <div id="filterInformasiPenerimaan">

                              <select id="from-bulan-penerimaan-search" class="form-control w-full">
                                  <option value="">Total Sampai {{date('d-m-Y')}}</option>
                                  @foreach (getMonthList() as $key => $value)
                                      <option value="{{ $key }}">{{ $value }}</option>
                                  @endforeach
                              </select>

                            </div>
                            
                            
                          </div>
                        </div>
                        <div class="input-group-append">
                          <div class="form-group">
                              <div class="btn-group">
                                <button type="button" class="btn btn-primary btn-outline" onclick="getPenerimaan()"><i class="fa fa-filter"></i> Filter</button>
                                <!-- <button type="button" target="blank" class="btn bg-red-900 text-white" onclick="printRealisasi()"><i class="fa fa-print"></i> Print PDF</button> -->
                              </div>
                          </div>
                        </div>
                    </div>
                  </form>
                </div>
                <br>

                <div class="row" data-plugin="matchHeight" data-by-row="true">
                  <div class="col-md-4">
                    <!-- Card -->
                    <div class="card shadow">
                      <div class="card-header bg-green-600 text-center pt-25">
                        <h3>
                          <div class="grey-50 text-capitalize"><b><i class="icon md-hotel"></i> PAJAK HOTEL</b></div>
                        </h3>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-4 text-center border-right">
                            <h1>
                              <br>
                              <span class="jumlahPersentase" id="persentaseHotel"> 50% </span>
                            </h1>
                          </div>
                          <div class="col-md-8">
                            <span class="mb-0">Realisasi </span>
                            <h3 class="mt-0"><span class="jumlahPenerimaan" id="jumlahPenerimaanHotel">Rp. 2500000 </span></h3>
                            <hr>
                            <span class="mb-0">Target Realisasi </span>
                            <h4 class="mt-0"><span class="jumlahTargetRealisasi" id="jumlahTargetHotel">Rp. 2500000 </span></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- End Card -->
                  </div>

                  <div class="col-md-4">
                    <div class="card shadow">
                      <div class="card-header bg-green-600 text-center pt-25">
                        <h3>
                          <div class="grey-50 text-capitalize"><b><i class="icon fa-spoon"></i> PAJAK RESTORAN</b></div>
                        </h3>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-4 text-center border-right">
                            <h1>
                              <br>
                              <span class="jumlahPersentase" id="persentaseRestoran"> 50% </span>
                            </h1>
                          </div>
                          <div class="col-md-8">
                            <span class="mb-0">Realisasi </span>
                            <h3 class="mt-0"><span class="jumlahPenerimaan" id="jumlahPenerimaanRestoran">Rp. 2500000 </span></h3>
                            <hr>
                            <span class="mb-0">Target Realisasi </span>
                            <h4 class="mt-0"><span class="jumlahTargetRealisasi" id="jumlahTargetRestoran">Rp. 2500000 </span></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="card shadow">
                      <div class="card-header bg-green-600 text-center pt-25">
                        <h3>
                          <div class="grey-50 text-capitalize"><b><i class="icon icon md-tv-alt-play"></i> PAJAK HIBURAN</b></div>
                        </h3>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-4 text-center border-right">
                            <h1>
                              <br>
                              <span class="jumlahPersentase" id="persentaseHiburan"> 50% </span>
                            </h1>
                          </div>
                          <div class="col-md-8">
                            <span class="mb-0">Realisasi </span>
                            <h3 class="mt-0"><span class="jumlahPenerimaan" id="jumlahPenerimaanHiburan">Rp. 2500000 </span></h3>
                            <hr>
                            <span class="mb-0">Target Realisasi </span>
                            <h4 class="mt-0"><span class="jumlahTargetRealisasi" id="jumlahTargetHiburan">Rp. 2500000 </span></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="card shadow">
                      <div class="card-header bg-green-600 text-center pt-25">
                        <h3>
                          <div class="grey-50 text-capitalize"><b><i class="icon icon md-parking"></i> PAJAK PARKIR</b></div>
                        </h3>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-4 text-center border-right">
                            <h1>
                              <br>
                              <span class="jumlahPersentase" id="persentaseParkir"> 50% </span>
                            </h1>
                          </div>
                          <div class="col-md-8">
                            <span class="mb-0">Realisasi </span>
                            <h3 class="mt-0"><span class="jumlahPenerimaan" id="jumlahPenerimaanParkir">Rp. 2500000 </span></h3>
                            <hr>
                            <span class="mb-0">Target Realisasi </span>
                            <h4 class="mt-0"><span class="jumlahTargetRealisasi" id="jumlahTargetParkir">Rp. 2500000 </span></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="card shadow">
                      <div class="card-header bg-blue-600 text-center pt-25">
                        <h3>
                          <div class="grey-50 text-capitalize"><b><i class="icon md-image-alt"></i> PAJAK REKLAME</b></div>
                        </h3>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-4 text-center border-right">
                            <h1>
                              <br>
                              <span class="jumlahPersentase" id="persentaseReklame"> 50% </span>
                            </h1>
                          </div>
                          <div class="col-md-8">
                            <span class="mb-0">Realisasi </span>
                            <h3 class="mt-0"><span class="jumlahPenerimaan" id="jumlahPenerimaanReklame">Rp. 2500000 </span></h3>
                            <hr>
                            <span class="mb-0">Target Realisasi </span>
                            <h4 class="mt-0"><span class="jumlahTargetRealisasi" id="jumlahTargetReklame">Rp. 2500000 </span></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="card shadow">
                      <div class="card-header bg-blue-600 text-center pt-25">
                        <h3>
                          <div class="grey-50 text-capitalize"><b><i class="icon md-nature"></i> PAJAK AIR TANAH</b></div>
                        </h3>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-4 text-center border-right">
                            <h1>
                              <br>
                              <span class="jumlahPersentase" id="persentaseAirTanah"> 50% </span>
                            </h1>
                          </div>
                          <div class="col-md-8">
                            <span class="mb-0">Realisasi </span>
                            <h3 class="mt-0"><span class="jumlahPenerimaan" id="jumlahPenerimaanAirTanah">Rp. 2500000 </span></h3>
                            <hr>
                            <span class="mb-0">Target Realisasi </span>
                            <h4 class="mt-0"><span class="jumlahTargetRealisasi" id="jumlahTargetAirTanah">Rp. 2500000 </span></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="card shadow">
                      <div class="card-header bg-blue-600 text-center pt-25">
                        <h3>
                          <div class="grey-50 text-capitalize"><b><i class="icon md-nature"></i> PAJAK PENERANGAN JALAN</b></div>
                        </h3>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-4 text-center border-right">
                            <h1>
                              <br>
                              <span class="jumlahPersentase" id="persentasePpj"> 50% </span>
                            </h1>
                          </div>
                          <div class="col-md-8">
                            <span class="mb-0">Realisasi </span>
                            <h3 class="mt-0"><span class="jumlahPenerimaan" id="jumlahPenerimaanPpj">Rp. 2500000 </span></h3>
                            <hr>
                            <span class="mb-0">Target Realisasi </span>
                            <h4 class="mt-0"><span class="jumlahTargetRealisasi" id="jumlahTargetPpj">Rp. 2500000 </span></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="card shadow">
                      <div class="card-header bg-orange-600 text-center pt-25">
                        <h3>
                          <div class="grey-50 text-capitalize"><b><i class="icon md-balance"></i> PBB</b></div>
                        </h3>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-4 text-center border-right">
                            <h1>
                              <br>
                              <span class="jumlahPersentase" id="persentasePbb"> 50% </span>
                            </h1>
                          </div>
                          <div class="col-md-8">
                            <span class="mb-0">Realisasi </span>
                            <h3 class="mt-0"><span class="jumlahPenerimaan" id="jumlahPenerimaanPbb">Rp. 2500000 </span></h3>
                            <hr>
                            <span class="mb-0">Target Realisasi </span>
                            <h4 class="mt-0"><span class="jumlahTargetRealisasi" id="jumlahTargetPbb">Rp. 2500000 </span></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="card shadow">
                      <div class="card-header bg-orange-600 text-center pt-25">
                        <h3>
                          <div class="grey-50 text-capitalize"><b><i class="icon md-assignment"></i> BPHTB</b></div>
                        </h3>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-4 text-center border-right">
                            <h1>
                              <br>
                              <span class="jumlahPersentase" id="persentaseBphtb"> 50% </span>
                            </h1>
                          </div>
                          <div class="col-md-8">
                            <span class="mb-0">Realisasi </span>
                            <h3 class="mt-0"><span class="jumlahPenerimaan" id="jumlahPenerimaanBphtb">Rp. 2500000 </span></h3>
                            <hr>
                            <span class="mb-0">Target Realisasi </span>
                            <h4 class="mt-0"><span class="jumlahTargetRealisasi" id="jumlahTargetBphtb">Rp. 2500000 </span></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  {{-- <div class="col-md-12">
                    
                    <!-- Card -->
                    <div class="card card-block p-30 bg-red-600">
                      <div class="card-watermark darker font-size-80 m-15"><i class="icon " aria-hidden="true"></i></div>
                      <div class="counter counter-md counter-inverse text-left">
                        <div class="counter-number-group">    
                          <span class="counter-number-related text-capitalize font-size-40 jumlahPenerimaan" id="">Rp. </span>
                        </div>
                        <div class="counter-label text-capitalize font-size-30"><b></b></div>
                      </div>
                    </div>
                    <!-- End Card -->
                  </div> --}}

                  <div class="col-md-12">
                    <div class="card shadow">
                      <div class="card-header bg-red-600 text-center pt-25">
                        <h3>
                          <div class="grey-50 text-capitalize"><b><i class="icon md-money-box"></i> TOTAL REALISASI</b></div>
                        </h3>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-4 text-center border-right">
                            <h1>
                              <br>
                              <span class="jumlahPersentase" id="persentaseTotal"> 50% </span>
                            </h1>
                          </div>
                          <div class="col-md-8">
                            <span class="mb-0">Realisasi </span>
                            <h3 class="mt-0"><span class="jumlahPenerimaan" id="jumlahPenerimaanTotal">Rp. 2500000 </span></h3>
                            <hr>
                            <span class="mb-0">Target Realisasi </span>
                            <h4 class="mt-0"><span class="jumlahTargetRealisasi" id="jumlahTargetTotal">Rp. 2500000 </span></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="row">
        {{-- informasi GRAFIK penerimaan --}}      
        <div class="col-md-12">
          <div class="panel">
            <div class="panel-heading" id="headingInformasiPenerimaan" role="tab">
              <a class="panel-title collapsed bg-teal-800 text-white" data-toggle="collapse" href="#panelInformasiPenerimaan" data-parent="#exampleAccordionDefault" aria-expanded="false" aria-controls="panelInformasiPenerimaan">
                <h4 class="text-white"> Grafik Penerimaan Pajak</h4>
              </a>
            </div>
            <div class="panel-collapse collapse show" id="panelInformasiPenerimaan" aria-labelledby="exampleHeadingDefaultTwo" role="tabpanel">
              <div class="panel-body">
                <!-- Panel Bar Stacked -->
                <div id="container"></div>
                <!-- End Panel Bar Stacked -->
              </div>
              </div>
            </div>
          </div> 
        </div>
      </div>

    </div>
  </div>


</div>

@endsection
@push('js')
<script src="https://code.highcharts.com/highcharts.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/highcharts-more.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/highcharts-3d.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/stock.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/maps/modules/map.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/gantt.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/exporting.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/parallel-coordinates.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/accessibility.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/annotations-advanced.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/data.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/draggable-points.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/static-scale.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/broken-axis.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/heatmap.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/tilemap.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/timeline.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/treemap.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/treegraph.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/item-series.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/drilldown.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/histogram-bellcurve.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/bullet.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/funnel.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/funnel3d.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/pyramid3d.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/networkgraph.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/pareto.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/pattern-fill.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/price-indicator.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/sankey.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/arc-diagram.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/dependency-wheel.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/series-label.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/solid-gauge.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/sonification.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/modules/stock-tools.js" type="text/javascript"></script>
  <script src="https://code.highcharts.com/modules/streamgraph.js" type="text/javascript"></script>
  <script src="https://code.highcharts.com/modules/sunburst.js" type="text/javascript"></script>
  <script src="https://code.highcharts.com/modules/variable-pie.js" type="text/javascript"></script>
  <script src="https://code.highcharts.com/modules/variwide.js" type="text/javascript"></script>
  <script src="https://code.highcharts.com/modules/vector.js" type="text/javascript"></script>
  <script src="https://code.highcharts.com/modules/venn.js" type="text/javascript"></script>
  <script src="https://code.highcharts.com/modules/windbarb.js" type="text/javascript"></script>
  <script src="https://code.highcharts.com/modules/wordcloud.js" type="text/javascript"></script>
  <script src="https://code.highcharts.com/modules/xrange.js" type="text/javascript"></script>
  <script src="https://code.highcharts.com/modules/no-data-to-display.js" type="text/javascript"></script>
  <script src="https://code.highcharts.com/modules/drag-panes.js" type="text/javascript"></script>
  <script src="https://code.highcharts.com/modules/debugger.js" type="text/javascript"></script>
  <script src="https://code.highcharts.com/modules/dumbbell.js" type="text/javascript"></script>
  <script src="https://code.highcharts.com/modules/lollipop.js" type="text/javascript"></script>
  <script src="https://code.highcharts.com/modules/cylinder.js" type="text/javascript"></script>
  <script src="https://code.highcharts.com/modules/organization.js" type="text/javascript"></script>
  <script src="https://code.highcharts.com/modules/dotplot.js" type="text/javascript"></script>
  <script src="https://code.highcharts.com/modules/marker-clusters.js" type="text/javascript"></script>
  <script src="https://code.highcharts.com/modules/hollowcandlestick.js" type="text/javascript"></script>
  <script src="https://code.highcharts.com/modules/heikinashi.js" type="text/javascript"></script>

<script>
  var mingguInformasiPenerimaan = null;
  var p_hotel = 0;
  var p_resto = 0;
  var p_parkir = 0; 
  var p_hiburan = 0;
  var p_reklame = 0;
  var p_genset = 0;
  var p_airtanah = 0;
  var p_bphtb = 0;
  var p_pbb = 0;
  var total = 0;

  var realisasi_hotel = 0;
  var realisasi_resto = 0;
  var realisasi_parkir = 0;
  var realisasi_hiburan = 0;
  var realisasi_reklame = 0;
  var realisasi_genset = 0;
  var realisasi_airtanah = 0;
  var realisasi_bphtb = 0;
  var realisasi_pbb = 0;
  var total_realisasi = 0;

  var persentase_realisasi_hotel = 0;
  var persentase_realisasi_resto = 0;
  var persentase_realisasi_parkir = 0;
  var persentase_realisasi_hiburan = 0;
  var persentase_realisasi_reklame = 0;
  var persentase_realisasi_genset = 0;
  var persentase_realisasi_airtanah = 0;
  var persentase_realisasi_bphtb = 0;
  var persentase_realisasi_pbb = 0;
  var total_persentase = 0;

  var t_hotel = 0;
  var t_resto = 0;
  var t_parkir = 0; 
  var t_hiburan = 0;
  var t_reklame = 0;
  var t_genset = 0;
  var t_airtanah = 0;
  var t_bphtb = 0;
  var t_pbb = 0;

  var k1=0; var k2=0; var k3=0; var k4=0; var k5=0; var k6=0; var k7=0; var k8=0; var k9=0; var k10=0; var k11=0; var k12=0;
  var p1=0; var p2=0; var p3=0; var p4=0; var p5=0; var p6=0; var p7=0; var p8=0; var p9=0; var p10=0; var p11=0; var p12=0;

  function url(key) {
    let arr_url = {
        "getJumlahPenerimaan" : "{{route('penerimaan.jumlah.nominal')}}",
        "printRealisasi" : "{{route('penerimaan.print.dashboard')}}",
    }; 
    return arr_url[key];
  };

  function getDashboardBphtb(){
    
    let url_submit = "{{route('dashboard_bphtb')}}";

    // console.log(bulanSearch);
    $.ajax({
        type:'GET',
        url: url_submit,
        // data: {
        //     "mingguSearch": mingguSearch,
        //     "bulanSearch": bulanSearch
        // },
        cache:false,
        contentType: false,
        processData: true,
        success: function(data){
            console.log(data);
            
            $("#count_sspd_terbit").text(data.count_sspd_terbit);
            $("#count_jadwal_pembahasan").text(data.count_jadwal_pembahasan);
            $("#count_kabid_setuju").text(data.count_kabid_setuju);
            $("#count_review_kabid").text(data.count_review_kabid);
            $("#count_pemeriksaan_fiskus").text(data.count_pemeriksaan_fiskus);
            $("#count_usulan_lengkap").text(data.count_usulan_lengkap);
            $("#count_usulan_diajukan").text(data.count_usulan_diajukan);
            $("#count_usulan_baru").text(data.count_usulan_baru);
            $("#count_sspd_lunas").text(data.count_sspd_lunas);
            
        },
        error: function(data){
            // callback(jumlah_nominal)
            // return jumlah_nominal;
            alert('Terjadi Kesalahan Pada Server');
        },
        
    });
  }

  function JumlahPenerimaan(){
    $(".jumlahPenerimaan").empty();
    $(".jumlahTargetRealisasi").empty();
    $(".jumlahPersentase").empty();
    
    let jumlah_pajak_hotel = getJumlahPenerimaan("1");
    let jumlah_pajak_restoran = getJumlahPenerimaan("2");
    let jumlah_pajak_hiburan = getJumlahPenerimaan("3");
    let jumlah_pajak_reklame = getJumlahPenerimaan("4");
    let jumlah_pajak_ppj = getJumlahPenerimaan("5");
    let jumlah_pajak_parkir = getJumlahPenerimaan("6");
    let jumlah_pajak_airtanah = getJumlahPenerimaan("7");
    // let jumlah_pajak_catering = getJumlahPenerimaan("2");

    // console.log(jumlah_pajak_hotel);
    
  }

  function JumlahRealisasi(){
    $(".jumlahTargetRealisasi").empty();
    // jumlahTargetPbb
    getJumlahTargetRealisasi("1");
    getJumlahTargetRealisasi("2");
    getJumlahTargetRealisasi("3");
    getJumlahTargetRealisasi("4");
    getJumlahTargetRealisasi("5");
    getJumlahTargetRealisasi("6");
    getJumlahTargetRealisasi("7");
  }

  function getJumlahPenerimaan(jenis_pajak){
    let url_submit = url("getJumlahPenerimaan");
    let jumlah_nominal = 0;
    let mingguSearch = $("#from-minggu-penerimaan-search").val();
    let bulanSearch = $("#from-bulan-penerimaan-search").val();
    // console.log(bulanSearch);
    $.ajax({
        type:'GET',
        url: url_submit,
        data: {
            "jenis_pajak": jenis_pajak,
            "mingguSearch": mingguSearch,
            "bulanSearch": bulanSearch
        },
        cache:false,
        contentType: false,
        processData: true,
        success: function(data){
            jumlah_nominal = data.jumlah_nominal;
            angka_nominal = data.angka_nominal;
            jumlah_realisasi = data.jumlah_realisasi;
            angka_realisasi = data.angka_realisasi;
            persentase = data.persentase_penerimaan;
            // console.log(jumlah_nominal)
            // return jumlah_nominal;
            if (jenis_pajak == "1") {
              // penerimaan
              p_hotel = parseInt(angka_nominal);
              getPenerimaanTotal();
              // $("#jumlahPenerimaanHotel").append("Rp. 5.018.002.281");
              $("#jumlahPenerimaanHotel").append(jumlah_nominal);

              // realisasi
              realisasi_hotel = parseInt(angka_realisasi);
              $("#jumlahTargetHotel").append(jumlah_realisasi);

              //persentase
              persentase_realisasi_hotel = persentase;
              $("#persentaseHotel").append(persentase_realisasi_hotel+"%");
              
            }else if(jenis_pajak == "2"){
              //penerimaan
              p_resto = parseInt(data.angka_nominal);
              getPenerimaanTotal();
              // $("#jumlahPenerimaanRestoran").append("Rp. 13.721.992.865");
              $("#jumlahPenerimaanRestoran").append(jumlah_nominal);

              //realisasi
              realisasi_resto = parseInt(angka_realisasi);
              $("#jumlahTargetRestoran").append(jumlah_realisasi);

              //persentase
              persentase_realisasi_resto = persentase;
              $("#persentaseRestoran").append(persentase_realisasi_resto+"%");

            }else if(jenis_pajak == "3"){
              //penerimaan
              p_hiburan = parseInt(data.angka_nominal);
              getPenerimaanTotal();
              // $("#jumlahPenerimaanHiburan").append("Rp. 1.123.990.077");
              $("#jumlahPenerimaanHiburan").append(jumlah_nominal);

              //realisasi
              realisasi_hiburan = parseInt(angka_realisasi);
              $("#jumlahTargetHiburan").append(jumlah_realisasi);

              //persentase
              persentase_realisasi_hiburan = persentase;
              $("#persentaseHiburan").append(persentase_realisasi_hiburan+"%");
              
            }else if(jenis_pajak == "4"){
              //penerimaan
              p_reklame = parseInt(data.angka_nominal);
              getPenerimaanTotal();
              // $("#jumlahPenerimaanReklame").append("Rp. 1.195.272.452");
              $("#jumlahPenerimaanReklame").append(jumlah_nominal);

              //realisasi
              realisasi_reklame = parseInt(angka_realisasi);
              $("#jumlahTargetReklame").append(jumlah_realisasi);

              //persentase
              persentase_realisasi_reklame = persentase;
              $("#persentaseReklame").append(persentase_realisasi_reklame+"%");
              
            }else if(jenis_pajak == "5"){
              //penerimaan
              p_genset = parseInt(data.angka_nominal);
              getPenerimaanTotal();
              $("#jumlahPenerimaanPpj").append(jumlah_nominal);
              // $("#jumlahPenerimaanPpj").append('Rp. 11.002.780.500,00');

              //realisasi
              realisasi_genset = parseInt(angka_realisasi);
              $("#jumlahTargetPpj").append(jumlah_realisasi);

              //persentase
              persentase_realisasi_genset = persentase;
              $("#persentasePpj").append(persentase_realisasi_genset+"%");

            }else if(jenis_pajak == "6"){
              //penerimaan
              p_parkir = parseInt(data.angka_nominal);
              getPenerimaanTotal();
              // $("#jumlahPenerimaanParkir").append("Rp. 182.660.279");
              $("#jumlahPenerimaanParkir").append(jumlah_nominal);

              //realisasi
              realisasi_parkir = parseInt(angka_realisasi);
              $("#jumlahTargetParkir").append(jumlah_realisasi);

              //persentase
              persentase_realisasi_parkir = persentase;
              $("#persentaseParkir").append(persentase_realisasi_parkir+"%");
              
            }else if(jenis_pajak == "7"){
              //penerimaan
              p_airtanah = parseInt(data.angka_nominal);
              getPenerimaanTotal();
              $("#jumlahPenerimaanAirTanah").append(jumlah_nominal);
              // $("#jumlahPenerimaanAirTanah").append("Rp. 182.660.279");

              //realisasi
              realisasi_airtanah = parseInt(angka_realisasi);
              $("#jumlahTargetAirTanah").append(jumlah_realisasi);

              //persentase
              persentase_realisasi_airtanah = persentase;
              $("#persentaseAirTanah").append(persentase_realisasi_airtanah+"%");
            }

            // getPenerimaanTotal();
        },
        error: function(data){
            // callback(jumlah_nominal)
            return jumlah_nominal;
            alert('Terjadi Kesalahan Pada Server');
        },
        
    });
  }

  function filterInformasiPenerimaanBy(filterBy){
    let filterField = "";
    $("#filterInformasiPenerimaan").empty();

    if (filterBy == "minggu") {
      filterField = `<input type="week" class="form-control" id="from-minggu-penerimaan-search">`;

    }else if(filterBy == "bulan"){
      filterField = `
      <select id="from-bulan-penerirmaan-search" class="form-control w-full">
          <option value="">Bulan Sekarang</option>
          @foreach (getMonthList() as $key => $value)
              <option value="{{ $key }}">{{ $value }}</option>
          @endforeach
      </select>
      `
    };

    $("#filterInformasiPenerimaan").append(filterField);
  }

  function printRealisasi(){

    let mingguSearch = $("#from-minggu-penerimaan-search").val();
    let bulanSearch = $("#from-bulan-penerimaan-search").val();
    if (!mingguSearch) {
      mingguSearch = '';
    }
    
    if (!bulanSearch) {
      bulanSearch = '';
    }
    // console.log(mingguSearch)
    // console.log(bulanSearch)

    
    let url =`{{url("home/penerimaan/print/realisasi")}}`
    let url_get_data = `${url}?mingguSearch=${mingguSearch}&bulanSearch=${bulanSearch}`
    // $(location).attr('href',url_get_data);
    window.open(url_get_data);

  }

  function getJumlahPenerimaanBphtb(){
    $(".jumlahPenerimaan").empty();

    let url_submit = "{{route('penerimaan.nominal_bphtb')}}";
    let jumlah_nominal = 0;
    let mingguSearch = $("#from-minggu-penerimaan-search").val();
    let bulanSearch = $("#from-bulan-penerimaan-search").val();
    // console.log(bulanSearch);
    $.ajax({
        type:'GET',
        url: url_submit,
        data: {
            "mingguSearch": mingguSearch,
            "bulanSearch": bulanSearch
        },
        cache:false,
        contentType: false,
        processData: true,
        success: function(data){
            jumlah_nominal = data.jumlah_nominal;
            angka_nominal = data.angka_nominal;
            jumlah_realisasi = data.jumlah_realisasi;
            angka_realisasi = data.angka_realisasi;
            persentase = data.persentase_penerimaan;

            //penerimaan
            p_bphtb = parseInt(angka_nominal);
            
            // return jumlah_nominal;
            $("#jumlahPenerimaanBphtb").append(jumlah_nominal);

            // realisasi
            realisasi_bphtb = parseInt(angka_realisasi);
            $("#jumlahTargetBphtb").append(jumlah_realisasi);

            //persentase
            persentase_realisasi_bphtb = persentase;
            $("#persentaseBphtb").append(persentase_realisasi_bphtb+"%");
            getPenerimaanTotal();
            
        },
        error: function(data){
            // callback(jumlah_nominal)
            return jumlah_nominal;
            alert('Terjadi Kesalahan Pada Server');
        },
        
    });
  }

  function getJumlahPenerimaanPbb(){
    $(".jumlahPenerimaan").empty();

    let url_submit = "{{route('penerimaan.nominal_pbb')}}";
    let jumlah_nominal = 0;
    let mingguSearch = $("#from-minggu-penerimaan-search").val();
    let bulanSearch = $("#from-bulan-penerimaan-search").val();
    // console.log(bulanSearch);
    $.ajax({
        type:'GET',
        url: url_submit,
        data: {
            "mingguSearch": mingguSearch,
            "bulanSearch": bulanSearch
        },
        cache:false,
        contentType: false,
        processData: true,
        success: function(data){
            jumlah_nominal = data.jumlah_nominal;
            angka_nominal = data.angka_nominal;
            jumlah_realisasi = data.jumlah_realisasi;
            angka_realisasi = data.angka_realisasi;
            persentase = data.persentase_penerimaan;

            // penerimaan
            p_pbb = parseInt(angka_nominal);
            
            // console.log(data)
            // return jumlah_nominal;
            $("#jumlahPenerimaanPbb").append(jumlah_nominal);

            // realisasi
            realisasi_pbb = parseInt(angka_realisasi);
            $("#jumlahTargetPbb").append(jumlah_realisasi);

            //persentase
            persentase_realisasi_pbb = persentase;
            $("#persentasePbb").append(persentase_realisasi_pbb+"%");
            getPenerimaanTotal();
            
        },
        error: function(data){
            // callback(jumlah_nominal)
            return jumlah_nominal;
            alert('Terjadi Kesalahan Pada Server');
        },
        
    });
  }

  function getPenerimaanTotal(){
    total_penerimaan = p_hotel+p_resto+p_parkir+ p_hiburan+p_reklame+p_genset+p_airtanah+p_bphtb+p_pbb;
    total_realisasi = realisasi_hotel+realisasi_resto+realisasi_parkir+ realisasi_hiburan+realisasi_reklame+realisasi_genset+realisasi_airtanah+realisasi_bphtb+realisasi_pbb;
    total_persentase = total_penerimaan/total_realisasi*100;

    // console.log("realisasi_hotel " + realisasi_hotel);
    // console.log("realisasi_resto " + realisasi_resto);
    // console.log("realisasi_parkir " + realisasi_parkir);
    // console.log("realisasi_hiburan " + realisasi_hiburan);
    // console.log("realisasi_reklame " + realisasi_reklame);
    // console.log("realisasi_genset " + realisasi_genset);
    // console.log("realisasi_airtanah " + realisasi_airtanah);
    // console.log("realisasi_bphtb " + realisasi_bphtb);
    // console.log("realisasi_pbb " + realisasi_pbb);

    //  console.log(total);
    $("#jumlahPenerimaanTotal").empty();
    $("#jumlahTargetTotal").empty();
    $("#persentaseTotal").empty();
    
    $("#jumlahPenerimaanTotal").append(formatRupiah(total_penerimaan));
    $("#jumlahTargetTotal").append(formatRupiah(total_realisasi));
    $("#persentaseTotal").append(total_persentase.toFixed(2)+"%");
    
    
    chart();
  }

  function getNominalTarget(){
    let url_submit = "{{route('target')}}";
    $.ajax({
        type:'GET',
        url: url_submit,
        // data: {
        //     "mingguSearch": mingguSearch,
        //     "bulanSearch": bulanSearch
        // },
        cache:false,
        contentType: false,
        processData: true,
        success: function(data){
            // console.log(data);
            t_bphtb = parseInt(data.t_bphtb);
            t_pbb = parseInt(data.t_pbb);
            t_hotel = parseInt(data.t_hotel);
            t_resto = parseInt(data.t_resto);
            t_hiburan = parseInt(data.t_hiburan);
            t_parkir = parseInt(data.t_parkir);
            t_reklame = parseInt(data.t_reklame);
            t_genset = parseInt(data.t_ppj);
            t_airtanah = parseInt(data.t_abt);
            chart();
        },
        error: function(data){
            alert('Terjadi Kesalahan Pada Server');
        },
        
    });
  }

  function formatRupiah(angka){
    var options = {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 2,
    };
    var formattedNumber = angka.toLocaleString('ID', options);


    return formattedNumber;
  }
  function getPenerimaan(){
    JumlahPenerimaan();
    getJumlahPenerimaanBphtb();
    getJumlahPenerimaanPbb();
    getPenerimaanTotal();
    chart();
  }

 function chart() {
  Highcharts.chart('container', {
      chart: {
          type: 'bar'
      },
      title: {
          text: 'Target dan Realisasi'
      },
      xAxis: {
          categories: ['Hotel', 'Restoran', 'Hiburan', 'Parkir', 'Reklame', 'Air Tanah', 'PPJ', 'BPHTB', 'PBB']
      },
      tooltip: {
          formatter: function() {
              return 'Rp ' + Highcharts.numberFormat(this.y, 0, ',', '.');
          }
      },
      yAxis: {
          title: {
              text: 'Nominal'
          },
          labels: {
              formatter: function() {
                  return 'Rp ' + Highcharts.numberFormat(this.value, 0, ',', '.');
              }
          }
      },
      series: [{
          name: 'Target',
          color: 'red',
          data: [t_hotel,t_resto,t_hiburan,t_parkir,t_reklame,t_airtanah,t_genset,t_bphtb,t_pbb]
      }, {
          name: 'Realisasi',
          color: 'green',
          data: [p_hotel,p_resto,p_hiburan,p_parkir,p_reklame,p_airtanah,p_genset,p_bphtb,p_pbb]
      }]
  });
 } 

 function getNominalGrafikWP(){

    let url_submit = "{{route('grafik_wp')}}";
    // console.log(bulanSearch);
    $.ajax({
        type:'GET',
        url: url_submit,
        // data: {
        //     "mingguSearch": mingguSearch,
        //     "bulanSearch": bulanSearch
        // },
        cache:false,
        contentType: false,
        processData: true,
        success: function(data){
          // console.log(data)
            k1=parseInt(data.k1); k2=parseInt(data.k2); k3=parseInt(data.k3); k4=parseInt(data.k4); k5=parseInt(data.k5); k6=parseInt(data.k6); k7=parseInt(data.k7); k8=parseInt(data.k8); k9=parseInt(data.k9); k10=parseInt(data.k10); k11=parseInt(data.k11); k12=parseInt(data.k12);
            p1=parseInt(data.p1); p2=parseInt(data.p2); p3=parseInt(data.p3); p4=parseInt(data.p4); p5=parseInt(data.p5); p6=parseInt(data.p6); p7=parseInt(data.p7); p8=parseInt(data.p8); p9=parseInt(data.p9); p10=parseInt(data.p10); p11=parseInt(data.p11); p12=parseInt(data.p12);
            
            chart_wp();
        },
        error: function(data){
            // return jumlah_nominal;
            alert('Terjadi Kesalahan Pada Server');
        },
        
    });
}

 function chart_wp() {
  Highcharts.chart('grafik_wp', {
      chart: {
          type: 'bar'
      },
      title: {
          text: 'Tagihan dan Pajak Lunas'
      },
      xAxis: {
          categories: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober' ,'November', 'Desember']
      },
      tooltip: {
          formatter: function() {
              return 'Rp ' + Highcharts.numberFormat(this.y, 0, ',', '.');
          }
      },
      yAxis: {
          title: {
              text: 'Nominal'
          },
          labels: {
              formatter: function() {
                  return 'Rp ' + Highcharts.numberFormat(this.value, 0, ',', '.');
              }
          }
      },
      series: [{
          name: 'TAGIHAN',
          color: 'red',
          data: [k1,k2,k3,k4,k5,k6,k7,k8,k9,k10,k11,k12]
      }, {
          name: 'PAJAK LUNAS',
          color: 'green',
          data: [p1,p2,p3,p4,p5,p6,p7,p8,p9,p10,p11,p12]
      }]
  });
 } 

  $(document).ready(function() {
    getPenerimaan();
    getNominalTarget();
    chart();

    setInterval(function() {
      JumlahPenerimaan();
      getJumlahPenerimaanBphtb();
      getJumlahPenerimaanPbb();
      getPenerimaanTotal();
      getNominalTarget();
      chart();
    }, 300000);

  });
</script>
@endpush