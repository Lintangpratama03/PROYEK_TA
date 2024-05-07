@extends('admin.layout.main')
@section('title', 'Peta Tunggakan PBB - Smart Dashboard')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Peta Tunggakan PBB</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">PBB</a></li>
                        <li class="breadcrumb-item active">Peta Tunggakan</li>
                    </ol>
                </div>
                <div class="col-sm-12">
                    <div id="map"></div>
                    <style>
                        #map {
                            height: 800px;
                            position: relative;
                        }

                        #map-title {
                            position: absolute;
                            top: 10px;
                            left: 10px;
                            background-color: rgba(255, 255, 255, 0.8);
                            padding: 5px 10px;
                            border-radius: 5px;
                            z-index: 1000;
                        }

                        #map-legend {
                            position: absolute;
                            bottom: 10px;
                            right: 10px;
                            background-color: rgba(255, 255, 255, 0.8);
                            padding: 10px;
                            border-radius: 5px;
                            z-index: 1000;
                        }
                    </style>
                    <div id="map-title">
                        <h4>Jumlah Total Tunggakan</h4>
                        <p>Berat: {{ $formattedWilayah->sum('tunggakanData.BERAT.jumlah') }}</p>
                        <p>Sedang: {{ $formattedWilayah->sum('tunggakanData.SEDANG.jumlah') }}</p>
                        <p>Ringan: {{ $formattedWilayah->sum('tunggakanData.RINGAN.jumlah') }}</p>
                    </div>
                    <div id="map-legend">
                        <p>Persentase Parah: 50% Berat + 30% Sedang + 20% Ringan</p>
                        <p><span style="color: #800000; font-weight: bold;">Merah:</span> Persentase Parah Terbanyak</p>
                        <p><span style="color: #808000; font-weight: bold;">Kuning:</span> Persentase Parah Sedang</p>
                        <p><span style="color: #008000; font-weight: bold;">Hijau:</span> Persentase Parah Sedikit</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        var map = L.map('map').setView([-7.894834, 110.152936], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var totalData = {{ $formattedWilayah->count() }};
        var sortedData = {!! $formattedWilayah->sortByDesc('totalScore')->values() !!};
        var redThreshold = Math.ceil(totalData * 0.2); // Top 20% get red color
        var yellowThreshold = Math.ceil(totalData * 0.6); // Next 40% get yellow color

        @foreach ($formattedWilayah as $key => $wilayah)
            var geometry = {!! $wilayah['geometry'] !!};
            var tunggakanData = {!! json_encode($wilayah['tunggakanData']) !!};
            var popupContent = '<b>Kec :</b> {{ $wilayah['kecamatan'] }}<br>' +
                '<b>Kel :</b> {{ $wilayah['kelurahan'] }}<br><br>' +
                '<b>Data Tunggakan</b><br>' +
                'Berat : ' + tunggakanData.BERAT.jumlah + '<br>' +
                'Sedang : ' + tunggakanData.SEDANG.jumlah + '<br>' +
                'Ringan : ' + tunggakanData.RINGAN.jumlah + '<br>';
            var color;
            var rank = sortedData.findIndex(function(item) {
                return item.kecamatan === '{{ $wilayah['kecamatan'] }}' && item.kelurahan ===
                    '{{ $wilayah['kelurahan'] }}';
            }) + 1;
            if (rank <= redThreshold) {
                color = '#800000';
            } else if (rank <= yellowThreshold) {
                color = '#808000';
            } else {
                color = '#008000';
            }
            L.geoJSON(geometry, {
                style: function(feature) {
                    return {
                        fillColor: color,
                        weight: 2,
                        opacity: 1,
                        color: 'white',
                        dashArray: '3',
                        fillOpacity: 0.7
                    };
                }
            }).addTo(map).bindPopup(popupContent);
        @endforeach
    </script>
@endsection
