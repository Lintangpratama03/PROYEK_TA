<?php

namespace App\Http\Controllers\PBB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetaTunggakanController extends Controller
{
    public function index()
    {
        $wilayah = DB::connection('pgsql_pbb')
            ->table('data.wilayah')
            ->select('kecamatan', 'kelurahan', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
            ->get();

        $tunggakan = DB::connection('pgsql_pbb')
            ->table('data.detail_tunggakan')
            ->get();

        // Inisialisasi variabel $kelurahanData
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

        // Inisialisasi variabel $sortedData
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

        $formattedWilayah = $wilayah->map(function ($item) use ($kelurahanData) {
            $kecamatan = $item->kecamatan;
            $kelurahan = $item->kelurahan;
            $tunggakanData = $kelurahanData[$kecamatan][$kelurahan] ?? [
                'BERAT' => ['jumlah' => 0, 'nominal' => 0],
                'SEDANG' => ['jumlah' => 0, 'nominal' => 0],
                'RINGAN' => ['jumlah' => 0, 'nominal' => 0]
            ];
            $geometry = $item->geometry;

            return [
                'kecamatan' => $kecamatan,
                'kelurahan' => $kelurahan,
                'geometry' => $geometry,
                'tunggakanData' => $tunggakanData,
                'jumlahTunggakan' => array_sum(array_column($tunggakanData, 'jumlah')) // Total jumlah tunggakan
            ];
        });


        $sortedData = collect($sortedData);
        // dd($formattedWilayah);
        return view('admin.pbb.peta.peta', compact('formattedWilayah', 'sortedData'));
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
