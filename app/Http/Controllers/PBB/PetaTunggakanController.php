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
            ->table('data.wilayah_ta')
            ->select('kecamatan', 'kelurahan', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
            ->get();

        $tunggakan = DB::connection('pgsql_pbb')
            ->table('data.detail_tunggakan')
            ->get();

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

        $formattedWilayah = $wilayah->map(function ($item) use ($kelurahanData) {
            $kecamatan = $item->kecamatan;
            $kelurahan = $item->kelurahan;
            $tunggakanData = $kelurahanData[$kecamatan][$kelurahan] ?? [
                'BERAT' => ['jumlah' => 0, 'nominal' => 0],
                'SEDANG' => ['jumlah' => 0, 'nominal' => 0],
                'RINGAN' => ['jumlah' => 0, 'nominal' => 0]
            ];
            $geometry = $item->geometry;

            $beratScore = $tunggakanData['BERAT']['jumlah'] * 0.5;
            $sedangScore = $tunggakanData['SEDANG']['jumlah'] * 0.3;
            $ringanScore = $tunggakanData['RINGAN']['jumlah'] * 0.2;
            $totalScore = $beratScore + $sedangScore + $ringanScore;

            return [
                'kecamatan' => $kecamatan,
                'kelurahan' => $kelurahan,
                'geometry' => $geometry,
                'tunggakanData' => $tunggakanData,
                'totalScore' => $totalScore,
                'jumlahTunggakan' => array_sum(array_column($tunggakanData, 'jumlah'))
            ];
        })->sortByDesc('totalScore');
        $formattedWilayah = $formattedWilayah->values();
        // dd($formattedWilayah);
        return view('admin.pbb.peta.peta', compact('formattedWilayah'));
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
