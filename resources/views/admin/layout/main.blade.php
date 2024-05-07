<!DOCTYPE html>
<html lang="en" style="--theme-deafult: #d97f3d; --theme-secondary: #f25f4c;">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SMART DASHBOARD KOTA MADIUN">
    <meta name="keywords" content="smart dashboard, kota madiun, grafik, data">
    <meta name="author" content="AgSatu">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="icon" href="{{ asset('assets') }}/images/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets') }}/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <title> @yield('title')</title>

    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/fontawesome.css">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/icofont.css">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/themify.css">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/flag-icon.css">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/feather-icon.css">

    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/sweetalert2.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/select2.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/date-picker.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/daterange-picker.css">
    <!-- Plugins css Ends-->

    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/style.css">
    <link id="color" rel="stylesheet" href="{{ asset('assets') }}/css/color-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/responsive.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" />
</head>
<!-- <body class="dark-only"> -->

<body class="dark-sidebar">
    <!-- <body> -->

    @php
        $sessions = getSession();
    @endphp

    <!-- Loader starts-->
    {{-- <div class="loader-wrapper">
      <div class="theme-loader">    
        <div class="loader-p"></div>
      </div>
    </div> --}}
    <!-- Loader ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper" id="pageWrapper">

        <!-- Page Header Start-->
        @include('admin.layout.header')
        <!-- Page Header Ends-->

        <!-- Page Body Start-->
        <div class="page-body-wrapper horizontal-menu">

            <!-- Page Sidebar Start-->
            @include('admin.layout.menubar')
            <!-- Page Sidebar Ends-->

            <div class="page-body">
                @yield('content')
            </div>

            <!-- footer start-->
            @include('admin.layout.footer')
            <!-- footer end-->

        </div>
    </div>
    <!-- latest jquery-->
    <script src="{{ asset('assets') }}/js/jquery-3.5.1.min.js"></script>
    <!-- feather icon js-->
    <script src="{{ asset('assets') }}/js/icons/feather-icon/feather.min.js"></script>
    <script src="{{ asset('assets') }}/js/icons/feather-icon/feather-icon.js"></script>
    <!-- Sidebar jquery-->
    <script src="{{ asset('assets') }}/js/sidebar-menu.js"></script>
    <script src="{{ asset('assets') }}/js/config.js"></script>
    <!-- Bootstrap js-->
    <script src="{{ asset('assets') }}/js/bootstrap/popper.min.js"></script>
    <script src="{{ asset('assets') }}/js/bootstrap/bootstrap.min.js"></script>
    <!-- Plugins JS start-->
    <script src="{{ asset('assets') }}/js/chart/apex-chart/apex-chart.js"></script>
    <script src="{{ asset('assets') }}/js/chart/apex-chart/stock-prices.js"></script>
    <!-- <script src="{{ asset('assets') }}/js/chart/apex-chart/chart-custom.js"></script> -->

    <script src="{{ asset('assets') }}/js/chart/google/google-chart-loader.js"></script>
    <script src="{{ asset('assets') }}/js/chart/google/google-chart.js"></script>

    <script src="{{ asset('assets') }}/js/datatable/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets') }}/js/datatable/datatables/datatable.custom.js"></script>
    <script src="{{ asset('assets') }}/js/tooltip-init.js"></script>

    <script src="{{ asset('assets') }}/js/sweet-alert/sweetalert.min.js"></script>
    <script src="{{ asset('assets') }}/js/select2/select2.full.min.js"></script>
    <script src="{{ asset('assets') }}/js/select2/select2-custom.js"></script>

    <script src="{{ asset('assets') }}/js/datepicker/daterange-picker/moment.min.js"></script>
    <script src="{{ asset('assets') }}/js/datepicker/daterange-picker/daterangepicker.js"></script>
    <script src="{{ asset('assets') }}/js/datepicker/daterange-picker/daterange-picker.custom.js"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{ asset('assets') }}/js/script.js"></script>
    {{-- <!-- <script src="{{asset("assets")}}/js/theme-customizer/customizer.js"></script> --> --}}
    <!-- login js-->
    <!-- Plugin used-->

    @yield('js')

</body>

</html>
