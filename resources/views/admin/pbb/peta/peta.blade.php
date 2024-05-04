@extends('admin.layout.main')
@section('title', 'Peta Tunggakan PBB - Smart Dashboard')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<!-- Make sure you put this AFTER Leaflet's CSS -->
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
                            height: 1000px;
                        }
                    </style>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid chart-widget">

    </div>
    <!-- Container-fluid Ends-->
@endsection

@section('js')
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    {{-- menggunakan pointer --}}
    {{-- <script>
        var map = L.map('map').setView([{{ $koordinat->first()->latitude }}, {{ $koordinat->first()->longitude }}], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        @foreach ($koordinat as $data)
            var marker = L.marker([{{ $data->latitude }}, {{ $data->longitude }}])
                .bindPopup('<b>{{ $data->kecamatan }}</b><br>{{ $data->kelurahan }}');

            @foreach ($tunggakan as $item)
                @if ($item->kecamatan == $data->kecamatan && $item->kelurahan == $data->kelurahan)
                    marker.bindPopup(marker.getPopup().getContent() +
                        '<br>Level: {{ $item->level }}<br>Jumlah Tunggakan: {{ $item->jumlah_tunggakan }}<br>Nominal Tunggakan: {{ number_format($item->nominal_tunggakan, 0, ',', '.') }}'
                    );
                @endif
            @endforeach

            marker.addTo(map);
        @endforeach
    </script> --}}
    {{-- lama --}}
    {{-- <script>
        var map = L.map('map').setView([{{ $koordinat->first()->latitude }}, {{ $koordinat->first()->longitude }}], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        @php
            $kelurahanData = [];
            foreach ($tunggakan as $item) {
                $kecamatan = $item->kecamatan;
                $kelurahan = $item->kelurahan;
                if (!isset($kelurahanData[$kecamatan][$kelurahan])) {
                    $kelurahanData[$kecamatan][$kelurahan] = [
                        'jumlah_tunggakan' => 0,
                        'nominal_tunggakan' => 0,
                    ];
                }
                $kelurahanData[$kecamatan][$kelurahan]['jumlah_tunggakan'] += $item->jumlah_tunggakan;
                $kelurahanData[$kecamatan][$kelurahan]['nominal_tunggakan'] += $item->nominal_tunggakan;
            }

            $sortedData = [];
            foreach ($kelurahanData as $kecamatan => $kelurahan) {
                foreach ($kelurahan as $nama => $data) {
                    $sortedData[] = [
                        'kecamatan' => $kecamatan,
                        'kelurahan' => $nama,
                        'jumlah_tunggakan' => $data['jumlah_tunggakan'],
                        'nominal_tunggakan' => $data['nominal_tunggakan'],
                    ];
                }
            }

            usort($sortedData, function ($a, $b) {
                if ($a['jumlah_tunggakan'] == $b['jumlah_tunggakan']) {
                    return $a['nominal_tunggakan'] > $b['nominal_tunggakan'] ? 1 : -1;
                }
                return $a['jumlah_tunggakan'] > $b['jumlah_tunggakan'] ? -1 : 1;
            });
        @endphp

        @foreach ($koordinat as $data)
            @php
                $found = false;
                $warna = '';
                foreach ($sortedData as $key => $item) {
                    if ($item['kecamatan'] == $data->kecamatan && $item['kelurahan'] == $data->kelurahan) {
                        $found = true;
                        $rank = $key + 1;
                        if ($rank <= 2) {
                            $warna = 'red';
                        } elseif ($rank <= round(0.7 * count($sortedData))) {
                            $warna = 'yellow';
                        } else {
                            $warna = 'green';
                        }
                        break;
                    }
                }
            @endphp

            @if ($found)
                L.circle([{{ $data->latitude }}, {{ $data->longitude }}], {
                    color: '{{ $warna }}',
                    fillColor: '{{ $warna }}',
                    fillOpacity: 0.5,
                    radius: 500
                }).bindPopup('<b>{{ $data->kecamatan }}</b><br>{{ $data->kelurahan }}').addTo(map);
            @endif
        @endforeach
    </script> --}}
    <script>
        var map = L.map('map').setView([{{ $koordinat->first()->latitude }}, {{ $koordinat->first()->longitude }}], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        @php
            $kelurahanData = [];
            foreach ($tunggakan as $item) {
                $kecamatan = $item->kecamatan;
                $kelurahan = $item->kelurahan;
                $level = $item->level;
                if (!isset($kelurahanData[$kecamatan][$kelurahan])) {
                    $kelurahanData[$kecamatan][$kelurahan] = [
                        'BERAT' => ['jumlah' => 0, 'nominal' => 0],
                        'SEDANG' => ['jumlah' => 0, 'nominal' => 0],
                        'RINGAN' => ['jumlah' => 0, 'nominal' => 0],
                    ];
                }
                $kelurahanData[$kecamatan][$kelurahan][$level]['jumlah'] += $item->jumlah_tunggakan;
                $kelurahanData[$kecamatan][$kelurahan][$level]['nominal'] += $item->nominal_tunggakan;
            }

            $sortedData = [];
            foreach ($kelurahanData as $kecamatan => $kelurahan) {
                foreach ($kelurahan as $nama => $data) {
                    $jumlahTunggakan = $data['BERAT']['jumlah'] + $data['SEDANG']['jumlah'] + $data['RINGAN']['jumlah'];
                    $nominalTunggakan = $data['BERAT']['nominal'] + $data['SEDANG']['nominal'] + $data['RINGAN']['nominal'];
                    $sortedData[] = [
                        'kecamatan' => $kecamatan,
                        'kelurahan' => $nama,
                        'jumlah_tunggakan' => $jumlahTunggakan,
                        'nominal_tunggakan' => $nominalTunggakan,
                    ];
                }
            }

            usort($sortedData, function ($a, $b) {
                if ($a['jumlah_tunggakan'] == $b['jumlah_tunggakan']) {
                    return $a['nominal_tunggakan'] > $b['nominal_tunggakan'] ? 1 : -1;
                }
                return $a['jumlah_tunggakan'] > $b['jumlah_tunggakan'] ? -1 : 1;
            });
        @endphp

        @foreach ($koordinat as $data)
            @php
                $found = false;
                $warna = '';
                $popupContent = '<h4>' . $data->kecamatan . ' - ' . $data->kelurahan . '</h4><table class="table table-striped">';
                $popupContent .= '<thead><tr><th>Level</th><th>Jumlah Tunggakan</th><th>Nominal Tunggakan</th></tr></thead><tbody>';
                foreach ($sortedData as $key => $item) {
                    if ($item['kecamatan'] == $data->kecamatan && $item['kelurahan'] == $data->kelurahan) {
                        $found = true;
                        $rank = $key + 1;
                        if ($rank <= 2) {
                            $warna = 'red';
                        } elseif ($rank <= round(0.7 * count($sortedData))) {
                            $warna = 'yellow';
                        } else {
                            $warna = 'green';
                        }
                        foreach ($kelurahanData[$data->kecamatan][$data->kelurahan] as $level => $value) {
                            $popupContent .= '<tr><td>' . $level . '</td><td>' . $value['jumlah'] . '</td><td>' . number_format($value['nominal'], 0, ',', '.') . '</td></tr>';
                        }
                        break;
                    }
                }
                $popupContent .= '</tbody></table>';
            @endphp

            @if ($found)
                L.circle([{{ $data->latitude }}, {{ $data->longitude }}], {
                    color: '{{ $warna }}',
                    fillColor: '{{ $warna }}',
                    fillOpacity: 0.5,
                    radius: 500
                }).bindPopup(function(layer) {
                    var popupContent = '<b>' + '{{ $data->kecamatan }} - {{ $data->kelurahan }}' +
                        '</b><br><table class="table table-striped">';

                    @foreach ($kelurahanData[$data->kecamatan][$data->kelurahan] as $level => $value)
                        popupContent += '<tr><td>' + '{{ $level }}' + '</td><td>' +
                            '{{ $value['jumlah'] }}' + ' tunggakan</td><td>Rp. ' +
                            '{{ number_format($value['nominal'], 0, ',', '.') }}' + '</td></tr>';
                    @endforeach

                    popupContent += '</table>';
                    return popupContent;
                }).addTo(map);
            @endif
        @endforeach
    </script>
@endsection
