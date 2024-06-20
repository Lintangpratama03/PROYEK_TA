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

    <div class="col-sm-12 text-end">
        <button id="toggleMap" class="btn btn-map-off">Turn Map On</button>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-xl-3 mb-2 col-md-4 col-sm-6">
                    <select name="filter_peta" id="filter_peta"
                        class="form-control btn-square js-example-basic-single col-sm-12 "
                        style="border: 1px solid #808080;border-radius:5px;">
                        <option value="1" class = "d-flex align-items-center">Tanpa Filter
                        </option>
                        <option value="2" class = "d-flex align-items-center">Menurut Nominal
                        </option>
                        <option value="3" class = "d-flex align-items-center">Menurut Tunggakan
                        </option>
                    </select>
                </div>
                <div class="col-xl-2 mb-2 col-md-4 col-sm-6">
                    <a class="btn btn-primary btn-square" type="button" onclick="filterWilayahCluster()">Terapkan<span
                            class="caret"></span></a>
                </div>
            </div>
        </div>
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
                                    <td id="total-jumlah-tunggakan"></td>
                                </tr>
                                <tr>
                                    <td>Total Nominal Tunggakan</td>
                                    <td id="total-nominal-tunggakan"></td>
                                </tr>
                                <tr>
                                    <td>Total NOP (Menunggak)</td>
                                    <td id="total-jumlah-nop"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <canvas id="clusterChart"></canvas>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var map = L.map('map').setView([-7.629523, 111.532680], 14);

        var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        });

        var toggleButton = document.getElementById('toggleMap');
        var geoJsonLayerGroup = L.layerGroup().addTo(map);

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

        var curKec = $('#filter_peta').val();

        function filterWilayahCluster() {
            var filter = $('#filter_peta').val();
            if (filter !== null) {
                // Hapus semua layer yang ada di geoJsonLayerGroup sebelum memuat data baru
                geoJsonLayerGroup.eachLayer(function(layer) {
                    geoJsonLayerGroup.removeLayer(layer);
                });
                loadMapData(filter);
            }
        }

        // Mengambil data dari controller
        function loadMapData(filter = curKec) {
            fetch("{{ route('pbb.peta-tunggakan.datapeta') }}?filter=" + filter)
                .then(response => response.json())
                .then(data => {
                    // Hapus layer GeoJSON sebelumnya
                    geoJsonLayerGroup.clearLayers();

                    // Hapus legend sebelumnya
                    $('.legend').remove();

                    // Membuat layer GeoJSON dari data
                    var geoJsonLayer = L.geoJson(data.query_total.map(function(feature) {
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
                                <strong>Jumlah Tunggakan:</strong> ${feature.properties.total_jumlah_tunggakan}<br>
                                <strong>Nominal Tunggakan:</strong> ${feature.properties.total_nominal_tunggakan}<br>
                            `);
                        }
                    });

                    // Menambahkan layer GeoJSON baru ke LayerGroup
                    geoJsonLayerGroup.addLayer(geoJsonLayer);

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
                        }).addTo(geoJsonLayerGroup);
                    });

                    // Membuat legenda baru
                    var legend = L.control({
                        position: 'bottomright'
                    });
                    legend.onAdd = function(map) {
                        var div = L.DomUtil.create('div', 'info legend');
                        var legendItems = '';

                        if (filter == 1) {
                            // Legenda berdasarkan cluster
                            var kecamatanSet = new Set();
                            legendItems = data.query_total.map(function(item) {
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
                        } else if (filter == 2) {
                            // Legenda berdasarkan tingkat nominal tunggakan
                            legendItems = `
                                <li>
                                    <i class="color-box" style="background-color: rgba(255, 0, 0, 0.6);"></i>
                                    Nominal Tunggakan Parah
                                </li>
                                <li>
                                    <i class="color-box" style="background-color: rgba(255, 255, 0, 0.6);"></i>
                                    Nominal Tunggakan Sedang
                                </li>
                                <li>
                                    <i class="color-box" style="background-color: rgba(0, 255, 0, 0.6);"></i>
                                    Nominal Tunggakan Biasa
                                </li>
                            `;
                        } else if (filter == 3) {
                            // Legenda berdasarkan tingkat jumlah tunggakan
                            legendItems = `
                                <li>
                                    <i class="color-box" style="background-color: rgba(255, 0, 0, 0.6);"></i>
                                    Jumlah Tunggakan Parah
                                </li>
                                <li>
                                    <i class="color-box" style="background-color: rgba(255, 255, 0, 0.6);"></i>
                                    Jumlah Tunggakan Sedang
                                </li>
                                <li>
                                    <i class="color-box" style="background-color: rgba(0, 255, 0, 0.6);"></i>
                                    Jumlah Tunggakan Biasa
                                </li>
                            `;
                        }

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
                                </tbody>
                            </table>
                        `);

                        // Load cluster chart data
                        loadClusterChartNop(props.kecamatan, props.kelurahan);
                    });

                    // Set total data
                    document.getElementById('total-jumlah-tunggakan').textContent = data.totalData
                        .total_jumlah_tunggakan;
                    document.getElementById('total-nominal-tunggakan').textContent =
                        `Rp ${data.totalData.total_nominal_tunggakan.toLocaleString()}`;
                    document.getElementById('total-jumlah-nop').textContent = data.totalData.total_jumlah_nop;
                });
        }
        $(document).ready(function() {
            loadMapData();
        });
    </script>


@endsection
