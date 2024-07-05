<?php

namespace App\Http\Controllers\PBB;

use App\Http\Controllers\Controller;
use App\Models\WilayahTa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelolaPetaController extends Controller
{
    public function index()
    {
        // Ambil data dari tabel wilayah
        $wilayahs = DB::connection("pgsql_pbb")
            ->table("data.wilayah_ta")
            ->select('id', 'kecamatan', 'kelurahan', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
            ->get();

        // Konversi hasil query ke format yang sesuai dengan GeoJSON
        $geojsonFeatures = [];
        foreach ($wilayahs as $wilayah) {
            $feature = [
                'type' => 'Feature',
                'properties' => [
                    'id' => $wilayah->id,
                    'kecamatan' => $wilayah->kecamatan,
                    'kelurahan' => $wilayah->kelurahan,
                ],
                'geometry' => json_decode($wilayah->geometry),
            ];
            $geojsonFeatures[] = $feature;
        }

        // Buat GeoJSON lengkap
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => $geojsonFeatures,
        ];

        // Kembalikan sebagai view dengan data wilayah
        return view('admin.pbb.peta.index', compact('geojson'));
    }

    public function create()
    {
        return view('admin.pbb.peta.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kecamatan' => 'required|string|max:50',
            'kelurahan' => 'required|string|max:50',
            'geometry' => 'required', // You may need to adjust validation for geometry
        ]);

        WilayahTa::create($validated);

        return redirect()->route('kelola_peta.index')->with('success', 'Data created successfully.');
    }

    public function show($id)
    {
        // Fetch WilayahTa model by ID
        $wilayah = WilayahTa::findOrFail($id);
        dd($wilayah);
        // Assuming $wilayah->geometry is the EWKB string
        $ewkbHex = bin2hex($wilayah->geometry); // Convert binary data to hexadecimal string

        dd($ewkbHex);
        // Use PostGIS functions directly
        $wkt = DB::selectOne('SELECT ST_AsText(ST_GeomFromEWKB(decode(:ewkbHex, \'hex\'))) AS wkt', ['ewkbHex' => $ewkbHex])->wkt;
        // Now $wkt contains the geometry in WKT format
        return view('admin.pbb.peta.show', compact('wilayah', 'wkt'));
    }

    public function edit($id)
    {
        $wilayah = WilayahTa::findOrFail($id);
        return view('admin.pbb.peta.edit', compact('wilayah'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kecamatan' => 'required|string|max:50',
            'kelurahan' => 'required|string|max:50',
            'geometry' => 'required', // You may need to adjust validation for geometry
        ]);

        $wilayah = WilayahTa::findOrFail($id);
        $wilayah->update($validated);

        return redirect()->route('kelola_peta.index')->with('success', 'Data updated successfully.');
    }

    public function destroy($id)
    {
        $wilayah = WilayahTa::findOrFail($id);
        $wilayah->delete();

        return redirect()->route('kelola_peta.index')->with('success', 'Data deleted successfully.');
    }
}
