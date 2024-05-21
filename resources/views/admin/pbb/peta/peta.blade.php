@extends('admin.layout.main')
@section('title', 'Peta Tunggakan PBB - Smart Dashboard')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-">
                    <h3>Peta Tunggakan PBB</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">PBB</a></li>
                        <li class="breadcrumb-item active">Peta Tunggakan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <button id="toggleMap" class="btn btn-map-on">Turn Map Off</button>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-8">
            <div id="map"></div>
            <style>
                .btn-map-on {
                    background-color: #28a745;
                    /* Hijau */
                    color: white;
                }

                .btn-map-off {
                    background-color: #dc3545;
                    /* Merah */
                    color: white;
                }

                #map {
                    height: 750px;
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
                <h4>Peta Kota Senyum</h4>
            </div>
            <div id="map-legend">
                <ul>
                    <ul>
                        <li><span class="color-box green">Hijau:</span> Daerah dengan jumlah
                            tunggakan
                            nop dibawah rata-rata dan nominal tunggakan dibawah rata-rata.</li><br>
                        <li><span class="color-box yellow">Kuning:</span> Daerah dengan jumlah
                            tunggakan(nop)
                            dibawah rata-rata dan nominal tunggakan diatas rata-rata.</li><br>
                        <li><span class="color-box orange">Orange:</span> Daerah dengan jumlah
                            tunggakan(nop)
                            diatas rata-rata dan nominal dibawah rata-rata.</li><br>
                        <li><span class="color-box red">Merah:</span> Daerah dengan jumlah
                            tunggakan(nop)
                            diatas rata-rata dan nominal diatas rata-rata.</li>
                    </ul>
                    <style>
                        .color-box {
                            display: inline-block;
                            padding: 2px;
                            font-weight: bold;
                        }

                        .yellow {
                            background-color: #FFFF00;
                            color: black;
                        }

                        .green {
                            background-color: #00FF00;
                            color: black;
                        }

                        .orange {
                            background-color: #fd9a29;
                            color: black;
                        }

                        .red {
                            background-color: #FF0000;
                            color: white;
                        }
                    </style>
                    <div id="legend-items"></div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Tunggakan Wilayah</h4>
                </div>
                <div class="card-body">
                    <div id="detail-wilayah">
                        <!-- Konten detail akan ditampilkan di sini -->
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
    <script src="https://unpkg.com/leaflet.vectorgrid@latest/dist/Leaflet.VectorGrid.bundled.js"></script>

    <script>
        var map = L.map('map').setView([-7.629523, 111.532680], 14);

        var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var toggleButton = document.getElementById('toggleMap');

        function toggleMapLayer() {
            if (map.hasLayer(osmLayer)) {
                map.removeLayer(osmLayer);
                toggleButton.classList.remove('btn-map-on');
                toggleButton.classList.add('btn-map-off');
                toggleButton.textContent = 'Turn Map On';
            } else {
                osmLayer.addTo(map);
                toggleButton.classList.remove('btn-map-off');
                toggleButton.classList.add('btn-map-on');
                toggleButton.textContent = 'Turn Map Off';
            }
        }

        toggleButton.addEventListener('click', toggleMapLayer);

        // Mengambil data dari controller
        var data = @json($query_total);

        // Membuat layer GeoJSON dari data
        var geoJsonLayer = L.geoJson(data.map(function(feature) {
            return {
                type: 'Feature',
                geometry: JSON.parse(feature.geometry),
                properties: {
                    kecamatan: feature.kecamatan,
                    kelurahan: feature.kelurahan,
                    total_jumlah_tunggakan: feature.total_jumlah_tunggakan,
                    total_nominal_tunggakan: feature.total_nominal_tunggakan,
                    total_jumlah_nop: feature.total_jumlah_nop,
                    backgroundColor: feature.backgroundColor,
                    borderColor: feature.borderColor,
                    cluster: feature.cluster
                }
            };
        }), {
            style: function(feature) {
                return {
                    fillColor: feature.properties.backgroundColor,
                    fillOpacity: 0.6,
                    color: feature.properties.borderColor,
                    weight: 2
                }
            },
            onEachFeature: function(feature, layer) {
                layer.bindPopup(`
            <strong>Kecamatan:</strong> ${feature.properties.kecamatan}<br>
            <strong>Kelurahan:</strong> ${feature.properties.kelurahan}<br>
            <strong>Total Tunggakan:</strong> ${feature.properties.total_jumlah_tunggakan}<br>
            <strong>Total Nominal Tunggakan:</strong> Rp ${feature.properties.total_nominal_tunggakan.toLocaleString()}<br>
            <strong>Total NOP(Menunggak):</strong> ${feature.properties.total_jumlah_nop}
        `);
            }
        }).addTo(map);

        // Membuat legenda
        var legend = L.control({
            position: 'bottomright'
        });
        legend.onAdd = function(map) {
            var div = L.DomUtil.create('div', 'info legend');
            var grades = ['Hijau', 'Kuning', 'Orange', 'Merah'];
            var labels = [];

            grades.forEach(function(grade, index) {
                var backgroundColor = data[0][grade.toLowerCase() + 'Color'];
                div.innerHTML += `<i style="background-color: ${backgroundColor}"></i> ${grade}<br>`;
            });

            return div;
        };
        legend.addTo(map);
        geoJsonLayer.on('click', function(e) {
            var layer = e.layer;
            var props = layer.feature.properties;

            // Tampilkan detail di panel
            $('#detail-wilayah').html(`
                <strong>Kecamatan:</strong> ${props.kecamatan}<br>
                <strong>Kelurahan:</strong> ${props.kelurahan}<br>
                <strong>Total Tunggakan:</strong> ${props.total_jumlah_tunggakan}<br>
                <strong>Total Nominal Tunggakan:</strong> Rp ${props.total_nominal_tunggakan.toLocaleString()}<br>
                <strong>Total NOP(Menunggak):</strong> ${props.total_jumlah_nop}
            `);
        });
    </script>
@endsection
