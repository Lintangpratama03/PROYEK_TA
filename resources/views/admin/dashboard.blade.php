@extends('admin.layout.main')
@section('title', 'Home - Smart Dashboard')

@section('content')
<div class="container-fluid">
  <div class="page-header">
    <div class="row">
      <div class="col-sm-6">
        <h3>Apex Chart</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Charts</li>
          <li class="breadcrumb-item active">Apex Chart</li>
        </ol>
      </div>
      <div class="col-sm-6">
        <!-- Bookmark Start-->
        <!-- <div class="bookmark">
          <ul>
            <li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover" data-placement="top" title="" data-original-title="Tables"><i data-feather="inbox"></i></a></li>
            <li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover" data-placement="top" title="" data-original-title="Chat"><i data-feather="message-square"></i></a></li>
            <li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover" data-placement="top" title="" data-original-title="Icons"><i data-feather="command"></i></a></li>
            <li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover" data-placement="top" title="" data-original-title="Learning"><i data-feather="layers"></i></a></li>
            <li><a href="javascript:void(0)"><i class="bookmark-search" data-feather="star"></i></a>
              <form class="form-inline search-form">
                <div class="form-group form-control-search">
                  <input type="text" placeholder="Search..">
                </div>
              </form>
            </li>
          </ul>
        </div> -->
        <!-- Bookmark Ends-->
      </div>
    </div>
  </div>
</div>
<!-- Container-fluid starts-->
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12 col-xl-6 box-col-6">
      <div class="card">
        <div class="card-header pb-0">
          <h5>Basic Area Chart </h5>
        </div>
        <div class="card-body">
          <div id="basic-apex"></div>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-xl-6 box-col-6">
      <div class="card">
        <div class="card-header pb-0">
          <h5>Area Spaline Chart </h5>
        </div>
        <div class="card-body">
          <div id="area-spaline"></div>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-xl-6 box-col-6">
      <div class="card">
        <div class="card-header pb-0">
          <h5>Bar chart</h5>
        </div>
        <div class="card-body">
          <div id="basic-bar"></div>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-xl-6 box-col-6">
      <div class="card">
        <div class="card-header pb-0">
          <h5>Column Chart </h5>
        </div>
        <div class="card-body">
          <div id="column-chart"></div>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-xl-6 box-col-12">
      <div class="card">
        <div class="card-header pb-0">
          <h5>
            3d Bubble Chart </h5>
        </div>
        <div class="card-body">
          <div id="chart-bubble"></div>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-xl-6 box-col-12">
      <div class="card">
        <div class="card-header pb-0">
          <h5>Candlestick Chart </h5>
        </div>
        <div class="card-body">
          <div id="candlestick"></div>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-xl-6 box-col-6">
      <div class="card">
        <div class="card-header pb-0">
          <h5>Stepline Chart </h5>
        </div>
        <div class="card-body">
          <div id="stepline"></div>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-xl-6 box-col-6">
      <div class="card">
        <div class="card-header pb-0">
          <h5>Column Chart</h5>
        </div>
        <div class="card-body">
          <div id="annotationchart"></div>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-xl-6 box-col-6">
      <div class="card">
        <div class="card-header pb-0">
          <h5>Pie Chart </h5>
        </div>
        <div class="card-body apex-chart">
          <div id="piechart"></div>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-xl-6 box-col-6">
      <div class="card">
        <div class="card-header pb-0">
          <h5>Donut Chart</h5>
        </div>
        <div class="card-body apex-chart">
          <div id="donutchart"></div>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-xl-12 box-col-12">
      <div class="card">
        <div class="card-header pb-0">
          <h5>Mixed Chart</h5>
        </div>
        <div class="card-body">
          <div id="mixedchart"></div>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-xl-6 box-col-6">
      <div class="card">
        <div class="card-header pb-0">
          <h5>Radar Chart </h5>
        </div>
        <div class="card-body">
          <div id="radarchart"></div>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-xl-6 box-col-6">
      <div class="card">
        <div class="card-header pb-0">
          <h5>Radial Bar Chart</h5>
        </div>
        <div class="card-body">
          <div id="circlechart"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Container-fluid Ends-->
@endsection