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
        <button id="toggleMap" class="btn btn-map-off">Turn Map On</button>
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

                .color-box {
                    display: inline-block;
                    width: 12px;
                    height: 12px;
                    margin-right: 5px;
                }

                .kelurahan-label {
                    border-radius: 5px;
                    padding: 2px 5px;
                    white-space: nowrap;
                }
            </style>
            <div id="map-title">
                <h4>Peta Kota Senyum</h4>
            </div>
            <div id="map-legend">
                <ul id="legend-items"></ul>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Tunggakan Wilayah</h4>
                </div>
                <div class="card-body">
                    <div id="detail-wilayah">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Wilayah</td>
                                    <td>Kota Senyum</td>
                                </tr>
                                <tr>
                                    <td>Total Jumlah Tunggakan</td>
                                    <td>{{ $totalData['total_jumlah_tunggakan'] }}</td>
                                </tr>
                                <tr>
                                    <td>Total Nominal Tunggakan</td>
                                    <td>Rp {{ number_format($totalData['total_nominal_tunggakan'], 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Total NOP (Menunggak)</td>
                                    <td>{{ $totalData['total_jumlah_nop'] }}</td>
                                </tr>
                                <tr>
                                    <td>Level Ringan</td>
                                    <td>{{ $totalData['nop_ringan'] }}</td>
                                </tr>
                                <tr>
                                    <td>Level Sedang</td>
                                    <td>{{ $totalData['nop_sedang'] }}</td>
                                </tr>
                                <tr>
                                    <td>Level Berat</td>
                                    <td>{{ $totalData['nop_berat'] }}</td>
                                </tr>
                            </tbody>
                        </table>
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
        });

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
                    nop_ringan: feature.nop_ringan,
                    nop_sedang: feature.nop_sedang,
                    nop_berat: feature.nop_berat,
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
            <strong>Level Ringan:</strong> ${feature.properties.nop_ringan}<br>
            <strong>Level Sedang:</strong> ${feature.properties.nop_sedang}<br>
            <strong>Level Berat:</strong> ${feature.properties.nop_berat}<br>
        `);
            }
        }).addTo(map);

        // Menambahkan label kelurahan di tengah blok geojson
        geoJsonLayer.eachLayer(function(layer) {
            var centroid = layer.getBounds().getCenter();
            if (layer.feature.properties.kelurahan === "Rejo") {
                centroid.lng += 0.004; // Adjust this value to move the label to the right
            }
            L.marker(centroid, {
                icon: L.divIcon({
                    className: 'kelurahan-label',
                    html: `<div>${layer.feature.properties.kelurahan}</div>`,
                    iconSize: [100, 40]
                })
            }).addTo(map);
        });

        // Membuat legenda
        var legend = L.control({
            position: 'bottomright'
        });
        legend.onAdd = function(map) {
            var div = L.DomUtil.create('div', 'info legend');
            var kecamatanSet = new Set();
            var legendItems = data.map(function(item) {
                if (!kecamatanSet.has(item.kecamatan)) {
                    kecamatanSet.add(item.kecamatan);
                    return `
                        <li>
                            <i class="color-box" style="background-color: ${item.backgroundColor};"></i>
                            Kecamatan ${item.kecamatan}
                        </li>
                    `;
                }
                return '';
            }).join('');
            div.innerHTML = `<ul>${legendItems}</ul>`;
            return div;
        };
        legend.addTo(map);

        geoJsonLayer.on('click', function(e) {
            var layer = e.layer;
            var props = layer.feature.properties;

            // Tampilkan detail di panel
            $('#detail-wilayah').html(`
                <table class="table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Kecamatan</td>
                            <td>${props.kecamatan}</td>
                        </tr>
                        <tr>
                            <td>Kelurahan</td>
                            <td>${props.kelurahan}</td>
                        </tr>
                        <tr>
                            <td>Total Tunggakan</td>
                            <td>${props.total_jumlah_tunggakan}</td>
                        </tr>
                        <tr>
                            <td>Total Nominal Tunggakan</td>
                            <td>Rp ${props.total_nominal_tunggakan.toLocaleString()}</td>
                        </tr>
                        <tr>
                            <td>Total NOP (Menunggak)</td>
                            <td>${props.total_jumlah_nop}</td>
                        </tr>
                        <tr>
                            <td>Level Ringan</td>
                            <td>${props.nop_ringan}</td>
                        </tr>
                        <tr>
                            <td>Level Sedang</td>
                            <td>${props.nop_sedang}</td>
                        </tr>
                        <tr>
                            <td>Level Berat</td>
                            <td>${props.nop_berat}</td>
                        </tr>
                    </tbody>
                </table>
            `);
        });
        var filterOption = document.getElementById('filterOption');
        filterOption.addEventListener('change', function() {
            var option = this.value;
            var filteredData;

            if (option === 'nop_berat') {
                filteredData = data.sort((a, b) => b.nop_berat - a.nop_berat);
            } else if (option === 'nop_sedang') {
                filteredData = data.sort((a, b) => b.nop_sedang - a.nop_sedang);
            } else if (option === 'nop_ringan') {
                filteredData = data.sort((a, b) => b.nop_ringan - a.nop_ringan);
            } else {
                filteredData = data;
            }

            // Hapus layer GeoJSON sebelumnya
            map.removeLayer(geoJsonLayer);

            // Buat layer GeoJSON baru dengan data yang sudah difilter
            geoJsonLayer = L.geoJson(filteredData.map(function(feature) {
                return {
                    type: 'Feature',
                    geometry: JSON.parse(feature.geometry),
                    properties: {
                        kecamatan: feature.kecamatan,
                        kelurahan: feature.kelurahan,
                        total_jumlah_tunggakan: feature.total_jumlah_tunggakan,
                        total_nominal_tunggakan: feature.total_nominal_tunggakan,
                        total_jumlah_nop: feature.total_jumlah_nop,
                        nop_ringan: feature.nop_ringan,
                        nop_sedang: feature.nop_sedang,
                        nop_berat: feature.nop_berat,
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
                <strong>Level Ringan:</strong> ${feature.properties.nop_ringan}<br>
                <strong>Level Sedang:</strong> ${feature.properties.nop_sedang}<br>
                <strong>Level Berat:</strong> ${feature.properties.nop_berat}<br>
            `);
                }
            }).addTo(map);

            // Hapus label kelurahan sebelumnya
            map.eachLayer(function(layer) {
                if (layer instanceof L.Marker && layer.options.icon && layer.options.icon.options.html) {
                    map.removeLayer(layer);
                }
            });

            // Tambahkan label kelurahan baru
            geoJsonLayer.eachLayer(function(layer) {
                var centroid = layer.getBounds().getCenter();
                if (layer.feature.properties.kelurahan === "Rejo") {
                    centroid.lng += 0.004; // Adjust this value to move the label to the right
                }
                L.marker(centroid, {
                    icon: L.divIcon({
                        className: 'kelurahan-label',
                        html: `<div>${layer.feature.properties.kelurahan}</div>`,
                        iconSize: [100, 40]
                    })
                }).addTo(map);
            });
        });
    </script>
@endsection
